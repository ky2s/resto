<?php
if($_GET['view'] != strtotime(get_the_time('c'))) {
	header('Location: ' . getTplPageURL('page-katalog.php'));
}
get_header();
if ( function_exists( 'ot_get_option' ) ) {
  $favicon_website = ot_get_option( 'favicon_website' );
  if($favicon_website == '') $favicon_website = get_template_directory_uri() . '/img/icon-48.png';
  $logo_website = ot_get_option( 'logo_website' );
  $nama_toko = ot_get_option( 'nama_toko' );
  if($nama_toko == '') $nama_toko = get_bloginfo( 'name' );
  $alamat_toko = ot_get_option( 'alamat_toko' );  
  $background_struk = ot_get_option( 'background_struk' );  
  $nama_kodeunik = ot_get_option( 'nama_kodeunik' );
  $label_fee = ot_get_option( 'label_fee' );
  $currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';
  $metode_pembayaran = ot_get_option( 'metode_pembayaran' );
  $instruksi_transfer_bank = ot_get_option( 'instruksi_transfer_bank' );
  $instruksi_cod = ot_get_option( 'instruksi_cod' );
  $instruksi_bayar_di_kasir = ot_get_option( 'instruksi_bayar_di_kasir' );
  $instruksi_durianpay = ot_get_option( 'instruksi_durianpay' );
  $label_timeslot = ot_get_option( 'label_timeslot' );
}
$email_bg_color = $background_struk['background-color'] ? 'background-color:'.$background_struk['background-color'].';' : '';
$email_bg_repeat = $background_struk['background-repeat'] ? 'background-repeat:'.$background_struk['background-repeat'].';' : '';
$email_bg_attachment = $background_struk['background-attachment'] ? 'background-attachment:'.$background_struk['background-attachment'].';' : '';
$email_bg_position = $background_struk['background-position'] ? 'background-position:'.$background_struk['background-position'].';' : '';
$email_bg_size = $background_struk['background-size'] ? 'background-size:'.$background_struk['background-size'].';' : '';
$email_bg_image = $background_struk['background-image'] ? 'background-image:url('.$background_struk['background-image'].');' : 'background-image:url('.get_template_directory_uri().'/img/bg-pattern.jpg'.');';
?>
<style>
body.single-tf-order .cetak:before {
  content: '';
  position: absolute;
  display: block;
  width: calc(100% + 30px);
  height: 30px;
  background: #444;
  box-shadow: inset 0 2px 10px rgba(0,0,0,.3);
  top: -15px;
  left: -15px;
  border-radius: 30px;
}
body.single-tf-order .cetak:after {
  content: '';
  position: absolute;
  display: block;
  width: 100%;
  height: 10px;
  bottom: -10px;
  left: 0;
  background-image: linear-gradient(45deg, rgba(0, 0, 0, 0) 33.333%, #fff 33.333%, #fff 66.667%, rgba(0, 0, 0, 0) 66.667%), linear-gradient(-45deg, rgba(0, 0, 0, 0) 33.333%, #fff 33.333%, #fff 66.667%, rgba(0, 0, 0, 0) 66.667%);
  background-size: 20px 40px;
  background-position: 50% -30px;
  background-repeat: repeat-x;
  z-index: 1;
}
body.single-tf-order .col > div:first-child {
  border-left: none!important;
  padding-right: 10px!important;
}
body.single-tf-order .col > div:last-child {
  padding-left: 10px!important;
}
body.single-tf-order #status-order.Diproses span {
	color: #e2bc00!important;
}
body.single-tf-order #status-order.Diantar span {
	color: #9913ff!important;
}
body.single-tf-order #status-order.Selesai span {
	color: #03AA0E!important;
}
body.single-tf-order #status-order.Dibatalkan span {
	color: red!important;
}
body.single-tf-order .barcode-wrapper #barcode {
  width: 100%!important;
}
@media print {
  @page {
    margin:0!important;
  }
  body.single-tf-order #receipt-wrapper {
	font-family: monospace;	  
  }
  body.single-tf-order .container {
    padding: 0!important;
  }
  body.single-tf-order .cetak {
    box-shadow: none!important;
    padding: 20px!important;
  }
  body.single-tf-order #header {
    box-shadow: none!important;	  
  }
  body.single-tf-order .cetak:before, body.single-tf-order .cetak:after, body.single-tf-order #status-order, body.single-tf-order .col-metode-pembayaran, body.single-tf-order .col-instruksi-pembayaran, body.single-tf-order #btn-pay {
    display: none!important;
  }
  body.single-tf-order .row {
    width: 100%!important;
    max-width: 100mm!important;
  }
  body.single-tf-order #table {
    border-bottom: 1px dashed #000;
    margin: 10px 0;
  }
}
</style>
<div id="receipt-wrapper" style="<?=$email_bg_color.' '.$email_bg_repeat.' '.$email_bg_attachment.' '.$email_bg_position.' '.$email_bg_size.' '.$email_bg_image;?> margin: 0; font-size: 3.4mm; position: relative; overflow: visible; width: 100%; min-height: 100vh; padding-bottom: 30px; line-height: 1.5em;">
	<div class="container" style="min-height: 100%; width: 100%; padding: 40px 0 0; display: block;">
		<div class="row" style="width: 90%; max-width: 360px; margin: 0 auto;">
			<div id="struk">
				<div class="cetak" style="background: #fff; width: 100%; padding: 20px; box-sizing: border-box; position: relative; box-shadow: 0 3px 15px rgba(0,0,0,.2);">
					<div id="header" style="border-bottom: 1px dashed #000; text-align: center; box-shadow: inset 0 15px 10px -10px rgb(0,0,0,0.5); margin: -20px -20px 10px; padding: 20px 15px 10px; z-index: 1; position: relative; background: #fff;">
						<div class="logo" style="padding-bottom: 5px;">
						<?=(($logo_website == '') ? '<img class="logo-icon" src="'.$favicon_website.'" style="height: 32px; width: auto; max-width: 100%; vertical-align: middle;"/> <span class="logo-title" style="vertical-align: middle; font-family: Ubuntu, sans-serif; font-size: 6mm; font-weight: 700; margin-left: 2mm;">'.$nama_toko.'</span>' : '<img class="logo-icon" src="'.$logo_website.'" style="height: 32px; width: auto; max-width: 100%; vertical-align: middle;"/>')?>
						</div>
						<p style="margin-bottom: 10px;"><?=$alamat_toko?></p>
					</div>
					<a id="status-order" class="<?=get_the_terms( get_the_ID(), 'order_status' )[0]->name;?>" href="<?=((strpos(get_post_meta(get_the_ID(), 'catatan_order', true), 'glympse.com') !== false) ? get_post_meta(get_the_ID(), 'catatan_order', true) : get_permalink( get_the_ID() ).'?view='.strtotime(get_the_time('c', get_the_ID())))?>" style="display: block; margin: -10px -20px 10px; border-bottom: 1px dashed #000; padding: 10px 0;"><span style="text-align: center; color: #000; display: block; text-transform: uppercase; font-weight: 700;" class="<?=get_the_terms( get_the_ID(), 'order_status' )[0]->name;?>">Pesanan <?=get_the_terms( get_the_ID(), 'order_status' )[0]->name;?></span></a>
					<div class="col" style="display: flex; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px;">
						<div class="order-id" style="flex: 1; <?=((get_post_meta( get_the_ID(), 'order_method', true ) == 'Delivery') ? 'width: 50%;' : 'width: 30%;')?>">
							<small style="font-size: 3mm;">Order ID</small>
							<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;">#<?=get_the_ID()?></span>
						</div>
						<div class="order-date" style="flex: 1; <?=((get_post_meta( get_the_ID(), 'order_method', true ) == 'Delivery') ? 'width: 50%; text-align: right;' : 'width: 30%; text-align: center;')?> border-left: 1px dashed #000; padding: 0 10px;">
							<small style="font-size: 3mm;">Tgl. Order</small>
							<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;"><?=the_time('d/m/y');?></span>
						</div>
						<?php if(get_post_meta( get_the_ID(), 'order_method', true ) == 'Dine In') { ?>
						<div class="order-meja" style="flex: 1; width: 30%; border-left: 1px dashed #000; text-align: right;">
							<small style="font-size: 3mm;">No Meja</small>
							<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;"><?=get_post_meta(get_the_ID(), 'order_meja', true)?></span>
						</div>
						<?php } else { ?>
						<style>.col > .order-date { text-align: right; padding: 0 0 0 10px!important; }</style>
						<?php } ?>
					</div>
					<div class="col" style="display: flex; border-bottom: 1px dashed #000; padding-bottom: 10px; margin-bottom: 10px;">
						<div class="order-nama" style="flex: 1; width: 50%;">
							<small style="font-size: 3mm;">Nama Lengkap</small>
							<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;"><?=get_post_meta(get_the_ID(), 'order_nama', true);?></span>
						</div>
						<div class="order-nowa" style="flex: 1; width: 50%; border-left: 1px dashed #000; text-align: right;">
							<small style="font-size: 3mm;">No WhatsApp</small>
							<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;"><?=get_post_meta(get_the_ID(), 'order_nowa', true);?></span>
						</div>
					</div>
					<?php if(!empty(get_post_meta(get_the_ID(), 'order_alamat', true))) { ?>
					<div class="col col-alamat" style="display: flex; padding-bottom: 10px; margin-bottom: 0px;">
						<div class="order-alamat" style="flex: 1; padding-left: 0!important; padding-right: 0!important;">
							<small style="font-size: 3mm;">Alamat</small>
							<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;"><?=get_post_meta(get_the_ID(), 'order_alamat', true)?><?php echo !empty(get_post_meta(get_the_ID(), 'order_kec', true)) ? '<br>' . get_post_meta(get_the_ID(), 'order_kec', true) : ''; ?></span>
						</div>
					</div>
					<?php } ?>
					<div id="table">
						<table style="width: 100%; border-collapse: collapse; margin-bottom: 10px;">
							<tr class="tabletitle" style="background: #EEE; text-transform: uppercase; border-bottom: 2px solid #000; height: 10mm; font-weight: 700; font-size: 3.6mm;">
								<td style="width: 40%;">Item</td>
								<td>Qty</td>
								<td>Subtotal</td>
							</tr>
							<?php
							$json = get_post_meta(get_the_ID(), 'order_produk', true);
							$json = json_decode($json, true);
							foreach($json as $item) {
							?>
							<tr class="item" style="border-bottom: 1px solid #ccc;">
								<td class="tableitem" style="padding: 10px 0;"><strong style="display: block;"><?=$item['name'];?></strong><?=!empty($item['summary']) ? 'Catatan: ' . $item['summary'] : '';?></td>
								<td class="tableitem" style="padding: 10px 0;"><?=$item['quantity'];?></td>
								<td class="tableitem" style="padding: 10px 0;"><?=$currency_sym?><?=number_format( $item['price'] * $item['quantity'], 0 , ',' , '.' );?></td>
							</tr>
							<?php } ?>
							<tr class="tabletitle subtotal" style="background: #EEE; height: 6mm; border-top: 2px solid #000;">
								<td></td>
								<td>Subtotal</td>
								<td><?=get_post_meta(get_the_ID(), 'order_subtotal', true);?></td>
							</tr>
							<?php if(!empty(get_post_meta(get_the_ID(), 'order_ongkir', true))) { ?>
							<tr class="tabletitle" style="background: #EEE; height: 6mm;">
								<td></td>
								<td>Ongkir</td>
								<td><?=get_post_meta(get_the_ID(), 'order_ongkir', true);?></td>
							</tr>
							<?php } ?>
							<tr class="tabletitle" style="background: #EEE; height: 6mm;">
								<td></td>
								<td>Diskon</td>
								<td><?= !empty(get_post_meta(get_the_ID(), 'order_nominaldiskon', true)) ? get_post_meta(get_the_ID(), 'order_nominaldiskon', true) : '-';?></td>
							</tr>
							<?php if(!empty(get_post_meta(get_the_ID(), 'order_unik', true))) { ?>
							<tr class="tabletitle" style="background: #EEE; height: 6mm;">
								<td></td>
								<td><?=$nama_kodeunik;?></td>
								<td><?=get_post_meta(get_the_ID(), 'order_unik', true);?></td>
							</tr>
							<?php } ?>
							<?php if(!empty(get_post_meta(get_the_ID(), 'order_fee', true))) { ?>
							<tr class="tabletitle" style="background: #EEE; height: 6mm;">
								<td></td>
								<td><?=$label_fee;?></td>
								<td><?=get_post_meta(get_the_ID(), 'order_fee', true);?></td>
							</tr>
							<?php } ?>
							<tr class="tabletitle total" style="background: #EEE; height: 6mm; font-weight: 700;">
								<td></td>
								<td>Total</td>
								<td><?=get_post_meta(get_the_ID(), 'order_total', true);?></td>
							</tr>
						</table>
					</div>
						<?php if(get_the_terms( get_the_ID(), 'order_status' )[0]->name == 'Diterima') { ?>
						<div class="col col-metode-pembayaran" style="display: flex; border-bottom: 1px dashed #000; margin-bottom: 10px; padding-bottom: 10px;">
							<div class="order-metode-pembayaran" style="flex: 1; padding-left: 0!important; padding-right: 0!important;">
								<small style="font-size: 3mm;">Metode Pembayaran</small>
								<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;"><?=get_post_meta(get_the_ID(), 'order_payment', true);?></span>
							</div>
						</div>
						<div class="col col-instruksi-pembayaran" style="display: flex; border-bottom: 1px dashed #000; margin-bottom: 10px; padding-bottom: 10px;">
							<div class="order-instruksi-pembayaran" style="flex: 1; padding-left: 0!important; padding-right: 0!important;">
								<small style="font-size: 3mm;">Instruksi Pembayaran</small>
								<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;">
								<?php								
									$arrMetode = array('cod','bayar di kasir','durianpay');
									if ( in_array(strtolower(get_post_meta(get_the_ID(), 'order_payment', true)), $arrMetode) ) {									
										if(strtolower(get_post_meta(get_the_ID(), 'order_payment', true)) == 'cod') echo $instruksi_cod; 
										if(strtolower(get_post_meta(get_the_ID(), 'order_payment', true)) == 'bayar di kasir') echo $instruksi_bayar_di_kasir; 
										if(strtolower(get_post_meta(get_the_ID(), 'order_payment', true)) == 'durianpay') {
											echo $instruksi_durianpay;
											if(!empty(get_post_meta(get_the_ID(), 'order_payment_link', true))) {
												echo '<a id="btn-pay" target="_blank" href="https://links.durianpay.id/payment/' . get_post_meta(get_the_ID(), 'order_payment_link', true) . '" style="display: block; text-align: center; background: #03aa0e; color: #fff; border-radius: 6px; border-bottom: 5px solid rgba(0,0,0,.2); padding: 12px 6px; margin-top: 10px;">Bayar Sekarang</a>';
											}
										}
									} else {
										echo $instruksi_transfer_bank;
									}								
								?>								
								</span>
							</div>
						</div>
						<?php } ?>
						<?php if(!empty(get_post_meta(get_the_ID(), 'order_koordinat', true))) { ?>
						<div class="col col-koordinat" style="display: flex; border-bottom: 1px dashed #000; margin-bottom: 10px; padding-bottom: 10px;">
							<div class="order-koordinat" style="flex: 1; padding-left: 0!important; padding-right: 0!important;">
								<small style="font-size: 3mm;">Koordinat Alamat</small>
								<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;"><a href="https://www.google.com/maps?daddr=<?=get_post_meta(get_the_ID(), 'order_koordinat', true);?>">https://www.google.com/maps?daddr=<?=get_post_meta(get_the_ID(), 'order_koordinat', true);?></a></span>
							</div>
						</div>
						<?php } ?>
						<?php if(!empty(get_post_meta(get_the_ID(), 'order_delivtime', true))) { ?>
						<div class="col col-koordinat" style="display: flex; border-bottom: 1px dashed #000; margin-bottom: 0px; padding-bottom: 10px;">
							<div class="order-koordinat" style="flex: 1; padding-left: 0!important; padding-right: 0!important;">
								<small style="font-size: 3mm;"><?=$label_timeslot?></small>
								<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;"><?=get_post_meta(get_the_ID(), 'order_delivtime', true);?></span>
							</div>
						</div>
						<?php } ?>
						<?php if(!empty(get_post_meta(get_the_ID(), 'catatan_order', true))) { ?>
						<div class="col col-catatan" style="display: flex; margin-bottom: 0; padding-top: 10px;">
							<div class="order-catatan" style="flex: 1; padding-left: 0!important; padding-right: 0!important; margin: 0 -20px; overflow:hidden;">
								<?php if (strpos(get_post_meta(get_the_ID(), 'catatan_order', true), 'glympse.com') !== false) { ?>
									<iframe src="<?=get_post_meta(get_the_ID(), 'catatan_order', true);?>" style="width:100%;border:0;min-height:550px;margin-top: -51px;"></iframe>
								<?php } else { ?>
									<small style="font-size: 3mm;">Catatan Orderan</small>
									<span style="display: block; font-size: 3.6mm; font-weight: 700; word-break: break-word;"><?=get_post_meta(get_the_ID(), 'catatan_order', true);?></span>									
								<?php } ?>
							</div>
						</div>
						<?php } ?>
						<div class="barcode-wrapper" style="text-align: center;">
							<svg id="barcode"></svg>
						</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.3/dist/barcodes/JsBarcode.code128.min.js"></script>
<script>
JsBarcode("#barcode", "<?=get_the_title()?>", {
  format: "code128",
  fontOptions: "bold",
  fontSize: 14,
  height: 40
});
if (window.location.href.indexOf("print") != -1) {
	window.print();
}
</script>
<?php
get_footer();
?>