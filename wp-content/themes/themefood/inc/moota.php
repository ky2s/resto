<?php
require_once('../../../../wp-load.php');
$notifications = file_get_contents('php://input');
$notify = json_decode($notifications, true);
if ( function_exists( 'ot_get_option' ) ) {
  $moota_secret = ot_get_option( 'moota_secret' );
  $moota_status = ot_get_option( 'moota_status' );
  $moota_cekhari = ot_get_option( 'moota_cekhari' );
}
$secret = $moota_secret;
$signature = hash_hmac('sha256', $notifications, $secret);
if($signature === $_SERVER['HTTP_SIGNATURE']){
	if($notifications){
		foreach($notify as $notif){
			$nominal = 'Rp'.number_format( $notif['amount'], 0 , ',' , '.' );
			$args = array(  
				'post_type' => 'tf-order',
				'posts_per_page' => -1,
				'meta_key' => 'order_total',
				'meta_query' => array(
				   array(
					   'key' => 'order_total',
					   'value' => $nominal,
					   'compare' => '=',
				   )
				),
				'date_query'    => array(
					'column'  => 'post_date',
					'after'   => '- '.$moota_cekhari.' days'
				),
				'tax_query' => array(
					array(
					   'taxonomy' => 'order_status',
					   'field' => 'slug',
					   'terms' => 'diterima',
					)
				)
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					echo get_the_ID();
					$log = file_put_contents('logs.txt', get_the_ID().PHP_EOL , FILE_APPEND | LOCK_EX);
					if(has_term( 'diterima', 'order_status', get_the_ID() )){
						wp_set_object_terms( get_the_ID(), $moota_status, 'order_status' );
						update_post_meta(get_the_ID(), 'order_payment_proof', 'Pembayaran Melalui Bank : ' . strtoupper($notif['bank_type']) . ' - Moota (' . $notif['date'] . ')', true);
						themefood_send_whatsapp_bot(get_the_ID());
					}
				}
			}
			wp_reset_postdata();		
		}
	}
}
?>