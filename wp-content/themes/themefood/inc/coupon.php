<?php
require_once("../../../../wp-load.php"); 
$coupon = file_get_contents(get_bloginfo('url') . '/wp-json/wp/v2/tf-kupon?per_page=1000');
$json = json_decode($coupon);
foreach ($json as $item) {
	$code = openssl_decrypt($item->ids,"AES-128-ECB","ThemeFood");
	$date_now = date("Y-m-d");
	if(isset($_POST['code'])) {
		if (strtolower($code) == strtolower($_POST['code'])) {
			if (!empty($item->berlaku_dari) && empty($item->berlaku_hingga)) {
				if ($date_now >= $item->berlaku_dari) {
					echo $item->tipe_kupon.','.$item->nilai_kupon;		
				}		
			} else if (empty($item->berlaku_dari) && !empty($item->berlaku_hingga)) {
				if ($date_now <= $item->berlaku_hingga) {
					echo $item->tipe_kupon.','.$item->nilai_kupon;		
				}		
			} else if (!empty($item->berlaku_dari) && !empty($item->berlaku_hingga)) {
				if ($date_now >= $item->berlaku_dari && $date_now <= $item->berlaku_hingga) {
					echo $item->tipe_kupon.','.$item->nilai_kupon;		
				}		
			} else if (empty($item->berlaku_dari) && empty($item->berlaku_hingga)) {
					echo $item->tipe_kupon.','.$item->nilai_kupon;		
			}
		}
    }
}
?>