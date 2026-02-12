<?php
/*
Template Name: Order Redirect
*/
require_once('inc/durianpay.php');
get_header();
if ( function_exists( 'ot_get_option' ) ) {
  $background_struk = ot_get_option( 'background_struk' );
  $aktifkan_adminnotif = ot_get_option( 'aktifkan_adminnotif' );
  $onesignal_appid = ot_get_option( 'onesignal_appid' );
  $onesignal_apikey = ot_get_option( 'onesignal_apikey' );
}
?>
<style>
body.page-template-page-order { background-color: <?=$background_struk['background-color']?>; background-repeat: <?=$background_struk['background-repeat']?>; background-attachment: <?=$background_struk['background-attachment']?>; background-position: <?=$background_struk['background-position']?>; background-size: <?=$background_struk['background-size']?>; background-image: url(<?=($background_struk['background-image'] ?: get_template_directory_uri().'/img/bg-pattern.jpg')?>); }
</style>
<?php
if (empty($_GET['sent'])) {
	global $wpdb;
	$json = url_get_contents(get_bloginfo('url') . '/wp-json/wp/v2/tf-order?per_page=10');
	$obj = json_decode($json);
	$cs_json = url_get_contents(get_bloginfo('url') . '/wp-json/wp/v2/tf-cs?per_page=1000');
	$cs_list = json_decode($cs_json, true);
	$cs_active = array();
	$today_index = date('w', current_time( 'timestamp' ));
	foreach ($cs_list as $list) {
		if (isset($list['followup_order']) && $list['followup_order'] == 'yes' && in_array( $today_index ,$list['jadwal_cs'] ) ) {
			$cs_active[] = $list['no_cs'];			
		}
	}
	if(isset($obj[0]->order_cs)) {
		$cs_prev = $obj[0]->order_cs;		
	} else {
		$cs_prev = $cs_active[0];
	}
	$cs_selected = $cs_active[0];
	foreach ($cs_active as $cs_now) {
		if ($cs_now == $cs_prev) {
			continue;
		}
		$cs_selected = $cs_now;
	}
	$chat = $_POST['cust-chat'] ?: '';
	$nama = $_POST['cust-name'] ?: '';
	$nowa = $_POST['cust-nowa'] ?: '';
	$email = $_POST['cust-email'] ?: '';
	$alamat = $_POST['cust-alamat'] ?: '';
	$method = $_POST['checkout-method'] ?: '';
	$kec = $_POST['cust-kec'] ?: '';
	$koor = $_POST['cust-loc'] ?: '';
	$meja = $_POST['cust-table'] ?: '';
	$subtotal = $_POST['cust-sub'] ?: '';
	$ongkir = $_POST['cust-ongkir'] ?: '';
	$disc = $_POST['cust-disc'] ?: '';
	if(isset($_POST['cust-unik'])) {
		$unik = $_POST['cust-unik'] ?: '';
	}
	if(isset($_POST['cust-fee'])) {
		$fee = $_POST['cust-fee'] ?: '';
	}
	if(isset($_POST['cust-delivtime'])) {
		$delivtime = $_POST['cust-delivtime'] ?: '';
	}
	$code = $_POST['cust-code'] ?: '';
	$total = $_POST['cust-total'] ?: '';
	$payment = $_POST['cust-payment'] ?: '';
	$produk = $_POST['cust-produk'] ?: '';
	$new_order = array(
	  'post_title' => 'Orderan',
	  'post_status'   => 'publish',
	  'post_type' => 'tf-order',
	  'comment_status' =>'closed'
	);
	if(!empty($produk)) {
		$id = wp_insert_post( $new_order );
		$term = get_term_by('slug', 'diterima', 'order_status');
		$wpdb->update( $wpdb->posts, array( 'post_title' =>  'Orderan #' . $id ), array( 'ID' => $id ) ); 
		$wpdb->update( $wpdb->posts, array( 'post_name' =>  $id ), array( 'ID' => $id ) );
		wp_set_object_terms( $id, $term->term_id, 'order_status' );
		update_post_meta($id, "order_nama", $nama, true);	
		update_post_meta($id, "order_nowa", $nowa, true);	
		update_post_meta($id, "order_email", $email, true);	
		update_post_meta($id, "order_alamat", $alamat, true);	
		update_post_meta($id, "order_method", $method, true);	
		update_post_meta($id, "order_kec", $kec, true);	
		update_post_meta($id, "order_koordinat", $koor, true);	
		update_post_meta($id, "order_meja", $meja, true);	
		update_post_meta($id, "order_subtotal", $subtotal, true);	
		update_post_meta($id, "order_ongkir", $ongkir, true);
		if(isset($_POST['cust-unik'])) {
			update_post_meta($id, "order_unik", $unik, true);	
		}
		if(isset($_POST['cust-fee'])) {
			update_post_meta($id, "order_fee", $fee, true);	
		}
		if(isset($_POST['cust-delivtime'])) {
			update_post_meta($id, "order_delivtime", $delivtime, true);	
		}
		update_post_meta($id, "order_nominaldiskon", $disc, true);	
		update_post_meta($id, "order_kodediskon", $code, true);	
		update_post_meta($id, "order_total", $total, true);	
		update_post_meta($id, "order_payment", $payment, true);	
		update_post_meta($id, "order_produk", $produk, true);
		update_post_meta($id, "order_cs", $cs_selected, true);
		$struk = get_site_url() . '/orderan/' . $id . '?view=' . strtotime(get_the_time('c', $id));								
		if(!empty($email)) {
			$to_email = $email;
			$email_content = wp_remote_get($struk);
			$headers = array('Content-Type: text/html; charset=UTF-8');
			$mail = wp_mail($to_email, sprintf( __( 'Orderan #%s telah diterima', 'themefood' ), $id ), $email_content['body'], $headers);
		}
		if($aktifkan_adminnotif == 'on' && $onesignal_appid !== '' && $onesignal_apikey !== '') {
			$fields = array(
				'app_id' => $onesignal_appid,
				'included_segments' => array(
					'Subscribed Users'
				),
				'contents' => array(
					"en" => 'Yeay! Ada order baru dari ' . $nama . ' (#' . $id . ')'
				),
				'web_buttons' => array(
					array(
						"id" => "view-order",
						"text" => "Lihat Orderan",
						"url" => admin_url("post.php?post=" . $id . "&action=edit")
					)
				)
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json; charset=utf-8',
				'Authorization: Basic ' . $onesignal_apikey
			));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_HEADER, FALSE);
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    
			curl_exec($ch);
			curl_close($ch);    
		}
		$paymentLink = '';
		if(strtolower($payment) == 'durianpay') {
			if ( function_exists( 'ot_get_option' ) ) {
				$api_durianpay = ot_get_option( 'api_durianpay' );
				$nama_kodeunik = ot_get_option( 'nama_kodeunik' );
				$label_fee = ot_get_option( 'label_fee' );
			}
			$productList = json_decode(stripslashes($produk), true);
			$itemList = array();
			foreach($productList as $product) {
				$item_produk = $product['name'];
				$item_qty = $product['quantity'];
				$item_price = $product['quantity'] * $product['price'];
				$item_img = $product['image'];
				$itemList[] = array(
								   "name" => $item_produk,
								   "qty" => $item_qty,
								   "price" => number_format(round($item_price), 2),
								   "logo" => $item_img,
								);
			}
			if(!empty($ongkir)) {
				preg_match('#\((.*?)\)#', $ongkir, $ongkirPrice);
				$ongkirPrice = preg_replace('/\D/','',html_entity_decode($ongkirPrice[1]));
				$itemList[] = array(
								   "name" => strtok($ongkir, '('),
								   "qty" => 1,
								   "price" => number_format(round($ongkirPrice), 2),
								   "logo" => '',
								);
			}
			if(!empty($unik)) {
				$unikPrice = preg_replace('/\D/','',html_entity_decode($unik));
				$itemList[] = array(
								   "name" => $nama_kodeunik,
								   "qty" => 1,
								   "price" => number_format(round($unikPrice), 2),
								   "logo" => '',
								);
			}
			if(!empty($fee)) {
				$feePrice = preg_replace('/\D/','',html_entity_decode($fee));
				$itemList[] = array(
								   "name" => $label_fee,
								   "qty" => 1,
								   "price" => number_format(round($feePrice), 2),
								   "logo" => '',
								);
			}
			
			$dpay = Durianpay::create($api_durianpay);
			$order = new DCreateOrderRequest();
			$order->amount = number_format(round( preg_replace('/\D/','',html_entity_decode($total)) ), 2);
			$order->customer = new StdClass;
			$order->customer->given_name = $nama;
			$order->customer->address_line_1 = $alamat;
			$order->customer->address_line_2 = $kec;
			$order->customer->email = $email ?: get_option('admin_email');
			$order->customer->mobile = $nowa;
			$order->order_ref_id = strval($id);
			$order->items = $itemList;
			$order->is_payment_link = true;
			$response = $dpay->createOrder($order);
			$responseObj = json_decode($response);
			$paymentID = $responseObj->data->id;
			$paymentLinkUrl = $responseObj->data->payment_link_url;
			$paymentLink = '%0A%0A••••••••••%0A%0A*' . __( 'Pembayaran dilakukan melalui link berikut:' , 'themefood' ) . '* https://links.durianpay.id/payment/' . $paymentLinkUrl . '%0A%0A••••••••••';
			update_post_meta($id, "order_payment_link", $paymentLinkUrl, true);
			update_post_meta($id, "order_payment_id", $paymentID, true);
		}
		echo('<script>location.href = "http'.(empty($_SERVER['HTTPS'])?'':'s').'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?text='. rawurlencode($chat) . $paymentLink . '%0A%0A' . $struk.'&phone='.$cs_selected.'&sent=1";</script>');
	} else {
		echo('<script>location.href = "http'.(empty($_SERVER['HTTPS'])?'':'s').'://' . $_SERVER['HTTP_HOST'] . '";</script>');
	}
} else {
?>	
	<div class="container">
		<div class="row"> 
			<div class="loader-wrapper">
				<div class="spinner">
				  <div class="bounce1"></div>
				  <div class="bounce2"></div>
				  <div class="bounce3"></div>
				</div>
				Membuka aplikasi WhatsApp dalam <span>3</span> 
			</div>
			<small class="click-here">Tekan <a href="#">disini</a> jika aplikasi tidak terbuka</small>
		</div>
	</div>
	<script>
	var count = 3,
		url = 'https://web.whatsapp.com/send',
		text = encodeURIComponent(<?=json_encode($_GET['text'])?>);
	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
		url = 'whatsapp://send';
	};
	var redirect = url + '?text=' + text.replace(/(?:\r\n|\r|\n)/g, '%0A').replace(/%250A/g, '%0A') + '&phone=<?=$_GET['phone']?>';
	document.querySelector('.click-here a').href = redirect;
	var counter = setInterval(function(){
	  count=count-1;
	  if (count <= 0) {
		 clearInterval(counter);
		 window.location.replace(redirect);
		 return;
	  }
	  document.querySelector('.loader-wrapper span').innerHTML = count;
	}, 1000);
	</script>
<?php }
get_footer();
?>