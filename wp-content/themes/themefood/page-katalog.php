<?php
/*
Template Name: Katalog Produk
*/
get_header();
	
if ( function_exists( 'ot_get_option' ) ) {
  $favicon_website = ot_get_option( 'favicon_website' );
  if($favicon_website == '') $favicon_website = preg_replace('#^https?://#', '', get_template_directory_uri()) . '/img/icon-48.png';
  $logo_website = ot_get_option( 'logo_website' );
  $aktifkan_loader = ot_get_option( 'aktifkan_loader' );
  $style_loader = ot_get_option( 'style_loader' );
  $background_katalog = ot_get_option( 'background_katalog' );
  $background_header = ot_get_option( 'background_header' );
  $teks_header = ot_get_option( 'teks_header' );
  $mata_uang = ot_get_option( 'mata_uang' );
  $status_toko = ot_get_option( 'status_toko' );
  $pesan_toko = ot_get_option( 'pesan_toko' );
  $nama_toko = ot_get_option( 'nama_toko' ) ?: get_bloginfo( 'name' );
  $alamat_toko = ot_get_option( 'alamat_toko' );
  $tampilan_produk_choice = ot_get_option( 'tampilan_produk_choice' );
  $tampilan_produk_desktop = ot_get_option( 'tampilan_produk_desktop' );
  $jumlah_foto_slider = ot_get_option( 'jumlah_foto_slider' );
  $tampilan_slider = ot_get_option( 'tampilan_slider' );
  $aktifkan_autoslide = ot_get_option( 'aktifkan_autoslide' );
  $interval_autoslide = ot_get_option( 'interval_autoslide' );
  $tombol_cta = ot_get_option( 'tombol_cta', array() );
  $teks_tombol_chat = ot_get_option( 'teks_tombol_chat' );
  $judul_widget_chat = ot_get_option( 'judul_widget_chat' );
  $subjudul_widget_chat = ot_get_option( 'subjudul_widget_chat' );
  $judul_info = ot_get_option( 'judul_info' );
  $jumlah_info = ot_get_option( 'jumlah_info' );
  $tampilan_kategori = ot_get_option( 'tampilan_kategori' );
  $urutan_kategori = ot_get_option( 'urutan_kategori' );
  $tampilan_keranjang = ot_get_option( 'tampilan_keranjang' );
  $icon_kat = ot_get_option( 'icon_kat' ) ?: 'restaurant';
  $jumlah_produk = ot_get_option( 'jumlah_produk' );
  $placeholder_instruksi = ot_get_option( 'placeholder_instruksi' );
  $metode_pengiriman = ot_get_option( 'metode_pengiriman' );
  $aktifkan_switcher = ot_get_option( 'aktifkan_switcher' );
  $api_rajaongkir = ot_get_option( 'api_rajaongkir' );
  $kecamatan_id = ot_get_option( 'kecamatan_id' );
  $pilihan_kurir = ot_get_option( 'pilihan_kurir' );
  $nama_kurir = ot_get_option( 'nama_kurir' );
  $metode_tarif = ot_get_option( 'metode_tarif' );
  $aktifkan_tarifdasar = ot_get_option( 'aktifkan_tarifdasar' );
  $tarif_dasar = ot_get_option( 'tarif_dasar' );
  $jangkauan_dasar = ot_get_option( 'jangkauan_dasar' );
  $tarif_per_km = ot_get_option( 'tarif_per_km' );
  $max_jangkauan = ot_get_option( 'max_jangkauan' );
  $ojol_flat = ot_get_option( 'ojol_flat' );
  $tarif_flat = ot_get_option( 'tarif_flat' );
  $metode_checkout = ot_get_option( 'metode_checkout' );
  $jumlah_meja = ot_get_option( 'jumlah_meja' );
  $tampilkan_berat = ot_get_option( 'tampilkan_berat' );
  $tampilkan_email = ot_get_option( 'tampilkan_email' );
  $api_open_route = ot_get_option( 'api_open_route', array() );
  foreach ($api_open_route as $key => $subArr) { 
    unset($api_open_route[$key]['title']);      
  }
  $lokasi_toko = ot_get_option( 'lokasi_toko', array() );
  foreach ($lokasi_toko as $key => $subArr) { 
    unset($lokasi_toko[$key]['title']);      
  }
  $teks_copyright = ot_get_option( 'teks_copyright' );
  $aktifkan_pwa = ot_get_option( 'aktifkan_pwa' );  
  $aktifkan_salespop = ot_get_option( 'aktifkan_salespop' );  
  $interval_salespop = ot_get_option( 'interval_salespop' );  
  $format_order = ot_get_option( 'format_order' );
  $tampilkan_kodeunik = ot_get_option( 'tampilkan_kodeunik' );
  $nama_kodeunik = ot_get_option( 'nama_kodeunik' );
  $digit_kodeunik = ot_get_option( 'digit_kodeunik' );
  $metode_kodeunik = ot_get_option( 'metode_kodeunik' );
  $additional_fee = ot_get_option( 'additional_fee' );
  $label_fee = ot_get_option( 'label_fee' );
  $persen_fee = ot_get_option( 'persen_fee' );
  $nominal_fee = ot_get_option( 'nominal_fee' );
  $tampilan_desktop = ot_get_option( 'tampilan_desktop' );
  $layanan_cdn = ot_get_option( 'layanan_cdn' );
  $bunny_hostname = ot_get_option( 'bunny_hostname' );
  $diskon_ongkir = ot_get_option( 'diskon_ongkir' );
  $minimum_belanja = ot_get_option( 'minimum_belanja' );
  $nominal_diskon = ot_get_option( 'nominal_diskon' );
  $metode_pembayaran = ot_get_option( 'metode_pembayaran' );
  $data_bank = ot_get_option( 'data_bank', array() );
  $instruksi_transfer_bank = ot_get_option( 'instruksi_transfer_bank' );
  $instruksi_cod = ot_get_option( 'instruksi_cod' );
  $instruksi_bayar_di_kasir = ot_get_option( 'instruksi_bayar_di_kasir' );
  $instruksi_durianpay = ot_get_option( 'instruksi_durianpay' );
  $minimum_order = ot_get_option( 'minimum_order' );
  $aktifkan_timeslot = ot_get_option( 'aktifkan_timeslot' );
  $mode_timeslot = ot_get_option( 'mode_timeslot' );
  $label_timeslot = ot_get_option( 'label_timeslot' );
  $jam_timeslot = ot_get_option( 'jam_timeslot', array() );
  foreach ($jam_timeslot as $key => $subArr) { 
    unset($jam_timeslot[$key]['title']);      
  }  
  $hari_timeslot = ot_get_option( 'hari_timeslot' );
  $timeslot_weekend = ot_get_option( 'timeslot_weekend' );
  $timeslot_rentangjam = ot_get_option( 'timeslot_rentangjam' );
  $timeslot_stepmenit = ot_get_option( 'timeslot_stepmenit' );
  $timeslot_mulaijam = ot_get_option( 'timeslot_mulaijam' );
  $timeslot_hinggajam = ot_get_option( 'timeslot_hinggajam' );
}
?>
<img id="background" width="100vw" height="100%" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiPz48c3ZnIHdpZHRoPSI5OTk5OXB4IiBoZWlnaHQ9Ijk5OTk5cHgiIHZpZXdCb3g9IjAgMCA5OTk5OSA5OTk5OSIgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIj48ZyBzdHJva2U9Im5vbmUiIGZpbGw9Im5vbmUiIGZpbGwtb3BhY2l0eT0iMCI+PHJlY3QgeD0iMCIgeT0iMCIgd2lkdGg9Ijk5OTk5IiBoZWlnaHQ9Ijk5OTk5Ij48L3JlY3Q+IDwvZz4gPC9zdmc+">

<?php if($aktifkan_loader !== 'off' && !isset($_GET['form'])): ?>
<div id="splash">
	<div class="loader-wrapper">
		<div class="loader style<?=$style_loader?:'1'?>">Loading...</div>
	</div>
</div>
<?php endif; ?>

<?php
if (isset($_GET['form'])){
	echo '<ons-navigator id="site-nav" page="cart.html"></ons-navigator>';
} else {
	echo '<ons-navigator id="site-nav" page="home.html"></ons-navigator>';	
}
?>

<template id="home.html">
  <ons-page id="home">
    <ons-toolbar style="<?=($background_header !== '' ? 'background:'.$background_header : '')?>" class="<?=($teks_header == 'white' ? 'white-text' : '')?>">
      <div class="left">
        <ons-toolbar-button id="logo">
			<?=(($logo_website == '') ? '<img class="logo-icon" src="'.is_cdn($favicon_website).'" /> <span class="logo-title">'.$nama_toko.'</span>' : '<img class="logo-icon" src="'.is_cdn($logo_website).'" />')?>
		</ons-toolbar-button>
        <ons-input id="search" modifier="underbar" placeholder="Cari produk apa..."></ons-input>
		<ons-icon class="closeSearch" icon="ion-md-close"></ons-icon>
      </div>
      <div class="right">
        <ons-toolbar-button icon="ion-ios-search" class="searchBtn"></ons-toolbar-button>
        <ons-toolbar-button icon="ion-md-heart" class="bookmarked"></ons-toolbar-button>
        <ons-toolbar-button icon="ion-md-person" class="orderHistory"></ons-toolbar-button>
		<?php
		$has_menu_items = wp_nav_menu( array( 'theme_location' => 'menu-1', 'echo' => false )) !== false;
		$is_assigned = has_nav_menu('menu-1');
		if($has_menu_items && $is_assigned):
		?>
		<ons-toolbar-button icon="ion-md-more" onclick="showPopover(this,'site-menu')"></ons-toolbar-button>
		<?php endif; ?>
      </div>
    </ons-toolbar>
    
   <ons-pull-hook id="pull-hook">
      <?php echo __('Pull to refresh', 'themefood'); ?>
    </ons-pull-hook>

	<div id="banner-loader">
		<div class="ph-item">
			<div class="ph-col-10">
				<div class="ph-picture"></div>
			</div>
			<div class="ph-col-2">
				<div class="ph-picture"></div>
			</div>
		</div>	
	</div>
	
	<div id="sliderBanner"></div>
	
	<ons-row id="store-cta">
	<?php
	  if ( ! empty( $tombol_cta ) ) {
		foreach( $tombol_cta as $cta ) {
		  echo '
		  <ons-col>	
			<ons-button class="cta" modifier="quiet">
			  <a href="' . $cta['cta_link'] . '" target="_blank">
				<ons-icon icon="ion-' . $cta['cta_icon'] . '"></ons-icon>
				<span>' . $cta['cta_teks'] . '</span>
			  </a>
			</ons-button>
		  </ons-col>';
		}
	  }	
	?>
	  <ons-col>	
		<ons-button class="cta openChat" modifier="quiet">
		  <a>
		    <ons-icon icon="ion-logo-whatsapp"></ons-icon>
		    <span><?=($teks_tombol_chat !== '' ? $teks_tombol_chat : 'Chat CS')?></span>
		  </a>
		</ons-button>
	  </ons-col>
	</ons-row>

	<div id="info-loader">
		<div class="ph-item">
			<div class="ph-col-12">
			  <div class="ph-row">
				<div class="ph-col-6 big"></div>
				<div class="ph-col-6 empty big"></div>
				<div class="ph-col-12"></div>
				<div class="ph-col-12"></div>
				<div class="ph-col-12"></div>
			  </div>
			</div>
		</div>	
	</div>
	
	<div id="infoToko" class="hidden"></div>
	
	<div id="kat-loader">
		<div class="ph-item">
			<div class="ph-col-5">
				<div class="ph-picture"></div>
			</div>
			<div class="ph-col-5">
				<div class="ph-picture"></div>
			</div>
			<div class="ph-col-2">
				<div class="ph-picture"></div>
			</div>
		</div>	
	</div>
	
	<div id="sliderKat" class="hidden <?=$tampilan_kategori == 'grid' ? 'alternate' : ''?> <?=$tampilan_kategori?>"></div>    
	<div id="produk" class="cat-<?=$tampilan_kategori?>"></div>	
	<ons-popover direction="up" disable-auto-styling cancelable id="menu"><ons-list></ons-list></ons-popover>    
    <div class="cartBtn-wrapper cart-<?=$status_toko?> cart-<?=$tampilan_keranjang?>">
		<div class="cart-menu">
		  <ons-button ripple icon="ion-ios-<?=$icon_kat?>" class="menu-cat" onclick="showPopover(this,'menu')"><?php echo __('Kategori', 'themefood'); ?></ons-button>  
		  <ons-button ripple class="cartBtn">
			<ons-row>      
			  <ons-col class="right">
				<ons-row>      
				  <ons-col> 	 
					<?php
						if($status_toko == 'tutup') {
					?>
						<?php echo __('Status Toko', 'themefood'); ?>
						<span class="cart-status"><?php echo __('Tutup Sementara', 'themefood'); ?></span>					
					<?php 
						} else {
					?>
						<span class="cart-qty">0</span> pcs
						<span class="cart-total"><?=$mata_uang?>0</span>
					<?php
						}
					?>
				  </ons-col>  
				  <ons-col width="15"> 	 
					<ons-icon icon="ion-md-cart"></ons-icon>			  
				  </ons-col>  
  			    </ons-row>  
			  </ons-col>  
			</ons-row>      
		  </ons-button> 
		</div>  
    </div>
	
	<ons-popover direction="down" id="site-menu" cancelable>
      <ons-list id="menu-list">
			<?php
			wp_nav_menu(array(
				'items_wrap'=> '%3$s',
				'walker' => new Custom_Walker_Nav_Menu(),
				'depth' => 2,
				'container'=> false,
				'menu_class' => '',
				'theme_location'=> 'menu-1',
				'fallback_cb'=> false 
			));
			?>
		</ons-list>
    </ons-popover>
	
	<div id="footer" class="hidden">
		<ons-row>
			<ons-col class="footer-logo">
				<?=(($logo_website == '') ? '<img class="footer-logo-icon" src="'.is_cdn($favicon_website).'" /> <span class="footer-logo-title">'.$nama_toko.'</span>' : '<img class="footer-logo-icon" src="'.is_cdn($logo_website).'" />')?>			
			</ons-col>
		</ons-row>
		<ons-row>
			<ons-col class="footer-address">
				<p><?=$alamat_toko?></p>
				<small><?=$teks_copyright?></small>
				<?php if (!is_user_logged_in() && $aktifkan_pwa == 'on') { ?>
				<ons-button icon="ion-md-flash" class="install" modifier="outline"><?php echo __('Install App', 'themefood'); ?></ons-button>
				<?php } ?>
			</ons-col>
		</ons-row>
	</div>
			
	<div id="salesPopup" class="adjust-<?=$tampilan_keranjang?>"><div class="sales"></div></div>

  </ons-page>
</template>

<template id="detailItem.html">
  <ons-action-sheet id="detailItem" cancelable>
    <ons-icon class="closeSheet" icon="ion-md-close"></ons-icon>
    <ons-action-sheet-button>
      <div class="item-img-wrapper" data-default="<?=is_cdn(get_template_directory_uri() . '/img/placeholder.webp')?>"">
		<img class="item-img lazyload" src="<?=is_cdn(get_template_directory_uri() . '/img/placeholder.webp')?>" />
	  </div>
      <span class="item-title"></span>
      <div class="item-desc"></div>
      <small class="item-price"></small>
	  <small class="item-price-was"><span></span></small>
	  <div class="share-button">
		<span class="label"><i class="ion ion-md-share"></i></span>
		<div class="sites">
			<a href="#" target="_blank" class="facebook"><i class="ion ion-logo-facebook"></i></a>
			<a href="#" target="_blank" class="twitter"><i class="ion ion-logo-twitter"></i></a>
			<a href="#" target="_blank" class="telegram"><i class="ion ion-md-paper-plane"></i></a>
			<a href="#" target="_blank" class="whatsapp"><i class="ion ion-logo-whatsapp"></i></a>
		</div>
	  </div>
      <ons-button class="add-item-popup"><?php echo __('Tambah Pesanan', 'themefood'); ?></ons-button>
    </ons-action-sheet-button>
  </ons-action-sheet>
</template>
  
<template id="extra.html">
  <ons-page id="extra">
    <ons-toolbar style="<?=($background_header !== '' ? 'background:'.$background_header : '')?>" class="<?=($teks_header == 'white' ? 'white-text' : '')?>">
      <div class="left"><ons-back-button></ons-back-button></div>
      <div class="center"><?php echo __('Atur pesanan', 'themefood'); ?></div>
    </ons-toolbar>
	<ons-list class="extra-option">
		<ons-list-item class="extra-title">
		  <span class="left"></span>
		  <span class="right"></span>
		</ons-list-item>
	</ons-list>
	<div class="extra-notes">
		<strong><?php echo __('Instruksi Pemesanan', 'themefood'); ?></strong>
		<textarea class="item-notes" placeholder="<?=$placeholder_instruksi?>"></textarea>
	</div>
	<div class="extra-cta">
		<ons-row>
			<ons-col class="left" width="80"><?php echo __('Jumlah Pesanan', 'themefood'); ?></ons-col>
			<ons-col class="right">
				<ons-row class="item-qty qty-extra">
				  <ons-col width="33">
					<ons-button icon="ion-ios-remove-circle-outline" class="sub" modifier="quiet"></ons-button>
				  </ons-col>
				  <ons-col width="34">  
					<ons-input type="number" class="qty" modifier="underbar" min="1" value="1"></ons-input>
				  </ons-col>    
				  <ons-col width="33">
					<ons-button icon="ion-ios-add-circle-outline" class="add" modifier="quiet"></ons-button>
				  </ons-col>  
				</ons-row>			
			</ons-col>
		</ons-row>
		<ons-row>
			<ons-col>
				<ons-button class="add-item-extra add-to-cart"><?php echo __('Tambah Pesanan', 'themefood'); ?> - <span class="extra-total">0</span></ons-button>
			</ons-col>
		</ons-row>
	</div>
  </ons-page>
</template>  
  
<template id="itemNotes.html">
  <ons-action-sheet id="itemNotes" cancelable title="<?php echo __('Tambah Instruksi Pemesanan', 'themefood'); ?>">
    <ons-icon class="closeSheet" icon="ion-md-close"></ons-icon>
    <ons-action-sheet-button>
      <textarea class="item-notes" placeholder="<?=$placeholder_instruksi?>"></textarea>
      <ons-button class="add-notes-popup"><?php echo __('Simpan Instruksi', 'themefood'); ?></ons-button>
    </ons-action-sheet-button>
  </ons-action-sheet>
</template>
  
<template id="detailInfo.html">
  <ons-action-sheet id="detailInfo" cancelable>
    <ons-icon class="closeSheet" icon="ion-md-close"></ons-icon>
    <ons-action-sheet-button>
      <img class="info-img lazyload" src="<?=is_cdn(get_template_directory_uri() . '/img/placeholder.webp')?>" />
      <h4 class="info-title"></h4>
      <div class="info-desc"></div>
    </ons-action-sheet-button>
  </ons-action-sheet>
</template>

<template id="chat.html">
  <ons-action-sheet id="chat" cancelable>
    <ons-icon class="closeSheet" icon="ion-md-close"></ons-icon>
    <ons-action-sheet-button>
      <ons-row>
        <ons-col class="chatTitle">
          <h4><?=($judul_widget_chat !== '' ? $judul_widget_chat : 'Ada pertanyaan?<br>Chat aja...')?></h4>
          <span><?=($subjudul_widget_chat !== '' ? $subjudul_widget_chat : 'Biasa menjawab dalam 5 menit')?></span>
        </ons-col>
      </ons-row>		
    </ons-action-sheet-button>
  </ons-action-sheet>
</template>

<template id="paymentMethod.html">
  <ons-action-sheet id="paymentMethod" cancelable>
    <ons-icon class="closeSheet" icon="ion-md-close"></ons-icon>
	<?php 
	if(!empty($metode_pembayaran)) {
		foreach( $metode_pembayaran as $metode ) {
			if( in_array( 'transfer-bank', $metode_pembayaran ) ) {
				$checkDefault = '';
			} else {
				$checkDefault = 'checked';
			}
			if($metode == 'transfer-bank') {
			  if ( ! empty( $data_bank ) ) {
				foreach( $data_bank as $bank ) {
				  echo '
					<ons-action-sheet-button>
						<ons-list-item tappable>
							<label for="metode-' . str_replace(' ', '-', $bank['bank_name']) . '" data-no="' . $bank['bank_number'] . '" data-name="' . $bank['bank_owner'] . '" class="center">' . $bank['bank_name'] . '</label>
							<label class="right"><ons-radio name="metode-pembayaran" input-id="metode-' . str_replace(' ', '-', $bank['bank_name']) . '" value="metode-' . str_replace(' ', '-', $bank['bank_name']) . '" ' . ($data_bank[0]['bank_name'] == $bank['bank_name'] ? 'checked' : '') . '></ons-radio></label> 
						</ons-list-item>		
					</ons-action-sheet-button>';
				}
			  } else {
				  echo '
					<ons-action-sheet-button>
						<ons-list-item tappable>
							<label for="metode-' . $metode . '" class="center">' . ucwords(str_replace('-', ' ', $metode)) . '</label>
							<label class="right"><ons-radio name="metode-pembayaran" input-id="metode-' . $metode . '" value="metode-' . $metode . '" checked></ons-radio></label> 
						</ons-list-item>		
					</ons-action-sheet-button>';				  
			  }
			} else {
				  echo '
					<ons-action-sheet-button>
						<ons-list-item tappable>
							<label for="metode-' . $metode . '" class="center">' . ucwords(str_replace('-', ' ', $metode)) . '</label>
							<label class="right"><ons-radio name="metode-pembayaran" input-id="metode-' . $metode . '" value="metode-' . $metode . '" ' . (array_values($metode_pembayaran)[0] == $metode ? $checkDefault : '') . '></ons-radio></label> 
						</ons-list-item>		
					</ons-action-sheet-button>';				
			}
		}
	} else {
				  echo '
					<ons-action-sheet-button>
						<ons-list-item tappable>
							<label for="metode-transfer-bank" class="center">Transfer Bank</label>
							<label class="right"><ons-radio name="metode-pembayaran" input-id="metode-transfer-bank" value="metode-transfer-bank" checked></ons-radio></label> 
						</ons-list-item>		
					</ons-action-sheet-button>';		
	}
	?>
  </ons-action-sheet>
</template>

<template id="delivTime.html">
  <ons-action-sheet id="delivTime" cancelable>
    <ons-icon class="closeSheet" icon="ion-md-close"></ons-icon>
    <?php
    if( ($aktifkan_timeslot == 'on' && $metode_pengiriman == 'ojol') || ($aktifkan_timeslot == 'on' && $metode_pengiriman == 'kurir' && $aktifkan_switcher == 'on') ) {
		if($mode_timeslot == 'timeslot') {
			date_default_timezone_set('GMT');
			$curTime = strtotime('+' . get_option('gmt_offset') . ' hours');
			$date = date('Y-m-d', $curTime);
			$weekOfdays = array();
			$weekOfdays[] = date('l d/m', strtotime($date));
			for($i =1; $i <= 6; $i++){
				$weekOfdays[] = date('l d/m', strtotime("+$i day", strtotime($date)));
			}
			function harijam($hari,$class) {
				if ( function_exists( 'ot_get_option' ) ) {
				  $jam_timeslot = ot_get_option( 'jam_timeslot', array() );
				  foreach ($jam_timeslot as $key => $subArr) { 
					unset($jam_timeslot[$key]['title']);      
				  }  
				}
				$curTime = strtotime('+' . get_option('gmt_offset') . ' hours');
				$curHour = date('HH', $curTime);
				$isChecked = false;
				foreach($jam_timeslot as $jam) {
					$isDisabled = '';
					if($class == 'today' && $curHour > $jam['slot_mulai']) $isDisabled = 'disabled';
					echo '<ons-action-sheet-button class="'.$isDisabled.'">
							<ons-list-item tappable>
								<label for="deliv-'.str_replace(array("/", " "), "-", $hari).'-'.str_replace(":", "", $jam['slot_mulai']).'-'.str_replace(":", "", $jam['slot_hingga']).'" class="center">' . $hari . ' (' . $jam['slot_mulai'] . ' - ' . $jam['slot_hingga'] . ')</label>
								<label class="right '.$class.'"><ons-radio name="delivery-time" input-id="deliv-'.str_replace(array("/", " "), "-", $hari).'-'.str_replace(":", "", $jam['slot_mulai']).'-'.str_replace(":", "", $jam['slot_hingga']).'" value="deliv-'.str_replace(array("/", " "), "-", $hari).'-'.str_replace(":", "", $jam['slot_mulai']).'-'.str_replace(":", "", $jam['slot_hingga']).'" '.$isDisabled.'></ons-radio></label> 
							</ons-list-item>		
						</ons-action-sheet-button>';
				}
			}
			$seminggu = array();
			foreach($weekOfdays as $days) {
				if(strpos($days, 'Monday') !== false) $seminggu[1] = str_replace('Monday', 'Senin', $days);
				if(strpos($days, 'Tuesday') !== false) $seminggu[2] = str_replace('Tuesday', 'Selasa', $days);
				if(strpos($days, 'Wednesday') !== false) $seminggu[3] = str_replace('Wednesday', 'Rabu', $days);
				if(strpos($days, 'Thursday') !== false) $seminggu[4] = str_replace('Thursday', 'Kamis', $days);
				if(strpos($days, 'Friday') !== false) $seminggu[5] = str_replace('Friday', 'Jumat', $days);
				if(strpos($days, 'Saturday') !== false) $seminggu[6] = str_replace('Saturday', 'Sabtu', $days);
				if(strpos($days, 'Sunday') !== false) $seminggu[7] = str_replace('Sunday', 'Minggu', $days);
			}
			$todayIndex = date('N', $curTime);
			if(count($hari_timeslot) === 1) {
				if(in_array($todayIndex,$hari_timeslot)) {
					harijam($seminggu[$todayIndex],'today');
				} else {
					harijam($seminggu[reset($hari_timeslot)],'');
				}
			} else {
				if(in_array($todayIndex,$hari_timeslot)) {
					$keys = array_keys($hari_timeslot);
					$size = count($keys);
					$foundKey = array_search($todayIndex,$hari_timeslot);
					if($foundKey !== false) {
						$nextKey = array_search($foundKey,$keys)+1;
						if($nextKey < $size) {
							harijam($seminggu[$todayIndex],'today');
							harijam($seminggu[$hari_timeslot[$keys[$nextKey]]],'');
						} else {
							harijam($seminggu[$todayIndex],'today');
							harijam($seminggu[reset($hari_timeslot)],'');
						}
					}
				} else {
					$nearestIndex = array_filter($hari_timeslot, function($n) use($todayIndex){ return $n > $todayIndex; });
					harijam($seminggu[reset($nearestIndex)],'');
				}
			}
		}
		if(preg_match('(date|datetime)', $mode_timeslot) === 1) {
			echo '<ons-action-sheet-button class="calendar">
					<input id="deliv-calendar" name="delivery-time" type="text" value="" />
					<ons-button class="choose-delivery-time">' . __('Pilih Tanggal Ini', 'themefood') . '</ons-button>
				  </ons-action-sheet-button>';
		}
	}
  ?>
  </ons-action-sheet>
</template>

<template id="cart.html">
  <ons-page id="cart">
    <ons-toolbar style="<?=($background_header !== '' ? 'background:'.$background_header : '')?>" class="<?=($teks_header == 'white' ? 'white-text' : '')?>">
      <div class="left"><ons-back-button></ons-back-button></div>
      <div class="center"><?php echo __('Keranjang Belanja', 'themefood'); ?></div>
    </ons-toolbar>
	<div id="cart-wrapper">
    <div id="table-cart">
		<table class="table table-hover table-responsive" id="my-cart-table"></table>
	</div>
	<ons-list class="form-list">
	<form id="checkout-form" action="<?=getTplPageURL('page-order.php')?>" method="POST" target="_blank">
		<ons-list-item class="cust-checkout">
			<ons-row>
				<ons-col>
					<div class="switch-field">
					  <input type="radio" id="checkout-delivery" name="checkout-method" value="Delivery" checked/>
					  <label for="checkout-delivery"><ons-icon icon="ion-md-car"></ons-icon> Delivery</label>
					  <input type="radio" id="checkout-dinein" name="checkout-method" value="Dine In" />
					  <label for="checkout-dinein"><ons-icon icon="ion-md-restaurant"></ons-icon> Dine in</label>
					</div>				
				</ons-col>
			</ons-row>
		</ons-list-item>	
		<ons-list-item>
			<ons-list-title><?php echo __('Form Pemesanan', 'themefood'); ?></ons-list-title>		
		</ons-list-item>	
		<ons-list-item class="cust-name-wrapper">
			<ons-input type="text" input-id="cust-name" name="cust-name" modifier="underbar" placeholder="<?php echo __('Nama Lengkap', 'themefood'); ?>" required float></ons-input>		
		</ons-list-item>	
		<ons-list-item class="cust-nowa-wrapper">
			<ons-input type="number" input-id="cust-nowa" name="cust-nowa" modifier="underbar" placeholder="<?php echo __('No WhatsApp', 'themefood'); ?>" required float></ons-input>		
		</ons-list-item>	
		<ons-list-item class="cust-email-wrapper">
			<ons-input type="email" input-id="cust-email" name="cust-email" modifier="underbar" placeholder="<?php echo __('Alamat Email', 'themefood'); ?>" required float></ons-input>		
		</ons-list-item>	
		<ons-list-item class="cust-alamat-wrapper">
			<textarea id="cust-alamat" name="cust-alamat" class="textarea" rows="3" placeholder="<?php echo __('Alamat Lengkap', 'themefood'); ?>"></textarea>			
		</ons-list-item>
		<?php
			if($aktifkan_switcher == 'on' && preg_match('(kurir|ojol)', $metode_pengiriman) === 1 ):
			?>
			<div id="shipping-switcher">
				<span class="text-input__label text-input--material__label text-input--underbar__label text-input--material__label--active">Metode Pengiriman</span>
				<?php
					if($metode_pengiriman == 'kurir'):
				?>
					<ons-list-item tappable>
						<label class="left"><ons-radio name="switch-kurir" input-id="switch-kurir" value="switch-kurir" checked></ons-radio></label> 
						<label for="switch-kurir" class="center">Kurir</label>
					</ons-list-item>
					<ons-list-item tappable>
						<label class="left"><ons-radio name="switch-kurir" input-id="switch-ojol" value="switch-ojol"></ons-radio></label> 
						<label for="switch-ojol" class="center"><?=$nama_kurir?></label>
					</ons-list-item>
				<?php
					 else:
				?>	
					<ons-list-item tappable>
						<label class="left"><ons-radio name="switch-kurir" input-id="switch-ojol" value="switch-ojol" checked></ons-radio></label> 
						<label for="switch-ojol" class="center"><?=$nama_kurir?></label>
					</ons-list-item>
					<ons-list-item tappable>
						<label class="left"><ons-radio name="switch-kurir" input-id="switch-kurir" value="switch-kurir"></ons-radio></label> 
						<label for="switch-kurir" class="center">Kurir</label>
					</ons-list-item>
				<?php
					 endif;
				?>	
			</div>
		<?php
			endif;
			?>
		<ons-list-item class="cust-kec-wrapper">
			<ons-input type="text" input-id="cust-kec" name="cust-kec" modifier="underbar" placeholder="<?php echo __('Kecamatan', 'themefood'); ?>" float></ons-input>	
			<span class="cust-hint"></span>
			<input type="hidden" id="origin" value="">
		</ons-list-item>
		<ons-list-item class="cust-loc-wrapper">
			<ons-input type="text" input-id="cust-loc" name="cust-loc" modifier="underbar" placeholder="<?php echo __('Koordinat Lokasi', 'themefood'); ?>" readonly float></ons-input>					
			<ons-icon icon="ion-md-locate"></ons-icon>
			<div id="map-wrapper"></div>
		</ons-list-item>	
		<ons-list-item class="cust-table-wrapper">
			<select id="cust-table" name="cust-table" class="select-input select-input--underbar">
			  <option value=""><?php echo __('Pilih Meja', 'themefood'); ?></option>
			  <?php
			  if($jumlah_meja !== '') {
				  for ($i = 1; $i <= $jumlah_meja; $i++){
					echo '<option value="'.$i.'">Meja '.$i.'</option>';
				  }
			  }
			  ?>
			</select>
			<span class="text-input__label text-input--material__label text-input--underbar__label text-input--material__label--active">No Meja</span>
		</ons-list-item>			
		<input type="hidden" id="cust-sub" name="cust-sub" value="" />
		<input type="hidden" id="cust-ongkir" name="cust-ongkir" value="" />
		<?php
			if($tampilkan_kodeunik == 'yes'):
			?>
			<input type="hidden" id="cust-unik" name="cust-unik" value="" />
			<?php
			endif;
			if($additional_fee == 'persen' || $additional_fee == 'nominal'):
			?>
			<input type="hidden" id="cust-fee" name="cust-fee" value="" />
			<?php
			endif;
			if( ($aktifkan_timeslot == 'on' && $metode_pengiriman == 'ojol') || ($aktifkan_timeslot == 'on' && $metode_pengiriman == 'kurir' && $aktifkan_switcher == 'on') ):
			?>
			<input type="hidden" id="cust-delivtime" name="cust-delivtime" value="" />
			<?php
			endif;
		?>
		<input type="hidden" id="cust-disc" name="cust-disc" value="" />
		<input type="hidden" id="cust-code" name="cust-code" value="" />
		<input type="hidden" id="cust-total" name="cust-total" value="" />
		<input type="hidden" id="cust-produk" name="cust-produk" value="" />
		<input type="hidden" id="cust-chat" name="cust-chat" value="" />
		<input type="hidden" id="cust-payment" name="cust-payment" value="" />
		<input type="submit" value="Submit">
	</form>
	</ons-list>
	<ons-row class="checkout-summary">
		<ons-col width="100">
			<ons-list-title><?php echo __('Ringkasan Pemesanan', 'themefood'); ?></ons-list-title>		
			<?php
				if( ($aktifkan_timeslot == 'on' && $metode_pengiriman == 'ojol') || ($aktifkan_timeslot == 'on' && $metode_pengiriman == 'kurir' && $aktifkan_switcher == 'on') ):
			?>
			<ons-list-item class="checkout-delivtime-wrapper">
				<ons-row>
					<ons-col><?php echo __('Delivery Time', 'themefood'); ?></ons-col>
					<ons-col class="text-right"><ons-icon icon="ion-md-calendar"></ons-icon> <span class="checkout-delivtime" data-id="">Pilih Tanggal</span></ons-col>
				</ons-row>
			</ons-list-item>	
			<?php
				endif;
			?>
				<ons-list-item>
				<ons-row>
					<ons-col><?php echo __('Subtotal', 'themefood'); ?></ons-col>
					<ons-col class="text-right checkout-subtotal">0</ons-col>
				</ons-row>
			</ons-list-item>	
			<ons-list-item class="checkout-shipping-wrapper">
				<ons-row>
					<ons-col width="30"><?php echo __('Ongkir', 'themefood'); ?></ons-col>
					<ons-col class="text-right"><div class="checkout-shipping"><span class="checkout-shipping-val"><?=$mata_uang?>0</span><div class="checkout-shipping-render"></div></div></ons-col>
				</ons-row>
			</ons-list-item>	
			<ons-list-item>
				<ons-row>
					<ons-col><?php echo __('Diskon', 'themefood'); ?></ons-col>
					<ons-col class="text-right checkout-disc"><?=$mata_uang?>0</ons-col>
				</ons-row>
			</ons-list-item>	
			<?php
				if($tampilkan_kodeunik == 'yes'):
				?>
			<ons-list-item>
				<ons-row>
					<ons-col><?=$nama_kodeunik;?></ons-col>
					<ons-col class="text-right checkout-unik"><?=$mata_uang?>0</ons-col>
				</ons-row>
			</ons-list-item>	
			<?php
				endif;
				if($additional_fee == 'persen' || $additional_fee == 'nominal'):
				?>
			<ons-list-item>
				<ons-row>
					<ons-col><?=$label_fee;?></ons-col>
					<ons-col class="text-right checkout-fee"><?=$mata_uang?>0</ons-col>
				</ons-row>
			</ons-list-item>	
			<?php
				endif;
				?>
			<ons-list-item class="checkout-total-wrapper">
				<ons-row>
					<ons-col><?php echo __('Total Pembayaran', 'themefood'); ?></ons-col>
					<ons-col class="text-right checkout-total"><?=$mata_uang?>0</ons-col>
				</ons-row>
			</ons-list-item>	
		</ons-col>
		<ons-col width="100">
			<ons-list-item class="checkout-payment-wrapper">
				<ons-row>
					<ons-col><?php echo __('Metode Pembayaran', 'themefood'); ?></ons-col>
					<ons-col class="text-right"><span class="checkout-payment" data-id=""></span> <ons-icon icon="ion-ios-arrow-down"></ons-icon></ons-col>
				</ons-row>
				<ons-row>
					<ons-col><span class="checkout-payment-instruction"></span></ons-col>
				</ons-row>
			</ons-list-item>	
		</ons-col>		
	</ons-row>	
	<ons-row class="checkout-cta">
		<ons-col>
			<ons-button class="checkout-btn"><?php echo __('Pesan Sekarang', 'themefood'); ?><span class="checkout-total">0</span></ons-button>
			<span class="checkout-info"><ons-icon icon="ion-logo-whatsapp"></ons-icon> <?php echo __('Anda akan diarahkan ke WhatsApp untuk pemesanan', 'themefood'); ?></span>
		</ons-col>
	</ons-row>
	<ons-modal direction="up">
		<div class="loader-wrapper">
			<div class="spinner">
			  <div class="bounce1"></div>
			  <div class="bounce2"></div>
			  <div class="bounce3"></div>
			</div>
			<?php echo __('Memuat...', 'themefood'); ?>
		</div>
	</ons-modal>
	</div>
  </ons-page>  
</template>

<template id="history.html">
  <ons-page id="history">
    <ons-toolbar style="<?=($background_header !== '' ? 'background:'.$background_header : '')?>" class="<?=($teks_header == 'white' ? 'white-text' : '')?>">
      <div class="left"><ons-back-button></ons-back-button></div>
      <div class="center"><?php echo __('Histori Pemesanan', 'themefood'); ?></div>
    </ons-toolbar>
	<div id="orderan"></div>
	<ons-modal direction="up">
		<div class="loader-wrapper">
			<div class="spinner">
			  <div class="bounce1"></div>
			  <div class="bounce2"></div>
			  <div class="bounce3"></div>
			</div>
			<?php echo __('Memuat...', 'themefood'); ?>
		</div>
	</ons-modal>
  </ons-page>  
</template>

<template id="success.html">
  <ons-page id="success">
	<div class="notifikasi-sukses">
		<img class="lazyload" data-src="<?=is_cdn(get_template_directory_uri() . '/img/icon-success.gif')?>" />
		<span><?php echo __('Terima Kasih', 'themefood'); ?></span>
		<small><?php echo __('Orderan anda telah kami terima.', 'themefood'); ?></small>
		<small id="text-payment"></small>
	</div>
	<div class="detail-sukses">
		<div class="btn-sukses">
			<ons-button class="btn-struk">
				<a href="#" target="_blank">
					<ons-icon icon="ion-md-list-box"></ons-icon>
					<span><?php echo __('Lihat Struk', 'themefood'); ?></span>
				</a>
			</ons-button>
			<ons-button class="btn-back-home" modifier="quiet">
				<span><?php echo __('Kembali Ke Katalog', 'themefood'); ?></span>
			</ons-button>
		</div>
		<div class="ringkasan-order">
			<span><?php echo __('Ringkasan Pemesanan', 'themefood'); ?></span>
			<table>
				<tbody>
					<tr>
						<td><?php echo __('Order ID', 'themefood'); ?></td>
						<td class="ringkasan-id">-</td>
					</tr>
					<tr>
						<td><?php echo __('Tanggal', 'themefood'); ?></td>
						<td class="ringkasan-date">-</td>
					</tr>
					<tr>
						<td><?php echo __('Status Order', 'themefood'); ?></td>
						<td class="ringkasan-status">-</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	<ons-modal direction="up">
		<div class="loader-wrapper">
			<div class="spinner">
			  <div class="bounce1"></div>
			  <div class="bounce2"></div>
			  <div class="bounce3"></div>
			</div>
			<?php echo __('Memuat...', 'themefood'); ?>
		</div>
	</ons-modal>
  </ons-page>  
</template>

<script>
var tfSite = '<?=get_site_url()?>',
	tfGmt = '<?=get_option("gmt_offset")?>',
	tfCurrency = '<?=($mata_uang !== "" ? $mata_uang : "Rp")?>',
	tfStatus = '<?=$status_toko?>',
	tfDesktop = '<?=$tampilan_desktop?>',
	tfPesantoko = '<?=($pesan_toko !== "" ? $pesan_toko : "Mohon maaf, toko sedang tutup. Silahkan kunjungi kembali nanti.")?>',
	tfInitialitem = '<?=$jumlah_produk?>',
	tfInitialinfo = '<?=$jumlah_info?>',
	tfInfotitle = '<?=($judul_info !== "" ? $judul_info : "Info Artikel")?>',
	tfInitialslider = '<?=$jumlah_foto_slider?>',
	tfSliderstyle = '<?=$tampilan_slider?>',
	tfSliderauto = '<?=($aktifkan_autoslide == 'on' ? 'yes' : 'no')?>',
	tfSliderinterval = '<?=$interval_autoslide?>',
	tfKatsort = '<?=$urutan_kategori?>',
	tfKatstyle = '<?=$tampilan_kategori?>',
	tfShipping = '<?=$metode_pengiriman?>',
	tfCourier = ['<?=($pilihan_kurir !== "" ? implode("','", $pilihan_kurir) : "")?>'],
	tfFlat = '<?=$tarif_flat?>',
	tfOrigin = '<?=$kecamatan_id?>',
	tfCheckout = '<?=$metode_checkout?>',
	tfShowweight = '<?=$tampilkan_berat?>',
	tfShowemail = '<?=$tampilkan_email?>',
	tfShoplocation = <?=(!empty($lokasi_toko) ? json_encode(array_values($lokasi_toko)) : "''")?>,
	tfOjolmetode = '<?=$metode_tarif?>',
	tfOjolbasic = '<?=$aktifkan_tarifdasar?>',
	tfOjolbasicfee = '<?=$tarif_dasar?>',
	tfOjolbasicrange = '<?=$jangkauan_dasar?>',
	tfOjolfee = '<?=$tarif_per_km?>',
	tfOjolrange = '<?=$max_jangkauan?>',
	tfOjoltext = '<?=$nama_kurir?>',
	tfOjolflat = '<?=$ojol_flat?>',
	tfDiscongkir = '<?=$diskon_ongkir?>',
	tfMinbelanja = '<?=$minimum_belanja?>',
	tfDiscnominal = '<?=$nominal_diskon?>',
	tfSwitcher = '<?=($aktifkan_switcher == 'on' && preg_match('(kurir|ojol)', $metode_pengiriman) === 1 ? 'yes' : 'no')?>',
	tfSalespop = '<?=($aktifkan_salespop == 'on' ? 'yes' : 'no')?>',
	tfSalespopinterval = '<?=$interval_salespop?>',
	tfApior = <?=(!empty($api_open_route) ? json_encode(array_values($api_open_route)) : "''")?>,
	tfApiro = '<?=($api_rajaongkir !== '' ? $api_rajaongkir : 'null')?>',
	tfUnik = '<?=$tampilkan_kodeunik?>',
	tfDigitunik = '<?=$digit_kodeunik?>',
	tfSignunik = '<?=$metode_kodeunik?>',
	tfFee = '<?=$additional_fee?>',
	tfLabelfee = '<?=$label_fee?>',
	tfPersenfee = '<?=$persen_fee?>',
	tfNominalfee = '<?=$nominal_fee?>',
	tfInstransfer = <?=json_encode($instruksi_transfer_bank)?>,
	tfInscod = <?=json_encode($instruksi_cod)?>,
	tfInskasir = <?=json_encode($instruksi_bayar_di_kasir)?>,
	tfInsdurianpay = <?=json_encode($instruksi_durianpay)?>,
	tfMinorder = '<?=$minimum_order?>',
	tfCdn = '<?=$layanan_cdn?>',
	tfCdnbunny = '<?=$bunny_hostname?>',
	tfTimeslot = '<?=($aktifkan_timeslot == 'on' ? 'yes' : 'no')?>',
	tfTimeslotmode = '<?=$mode_timeslot?>',
	tfTimeslotlabel = '<?=$label_timeslot?>',
	tfTimeslotjam = <?=(!empty($jam_timeslot) ? json_encode(array_values($jam_timeslot)) : "''")?>,
	tfTimeslothari = ['<?=($hari_timeslot !== "" ? implode("','", $hari_timeslot) : "")?>'],	
	tfTimeslotweekend = '<?=$timeslot_weekend?>',
	tfTimeslotstep = '<?=$timeslot_stepmenit?>',
	tfTimeslotrentang = '<?=$timeslot_rentangjam?>',
	tfTimeslotrentangmulai = '<?=$timeslot_mulaijam?>',
	tfTimeslotrentanghingga = '<?=$timeslot_hinggajam?>',	
	tfFormat = <?=json_encode($format_order)?>;
<?php if($tampilan_desktop == 'mobile' && !isset($_GET['form'])) { ?> 
	if (window.innerWidth > 480) {
		document.body.setAttribute('style','background-image: url(<?=is_cdn($background_katalog)?>)!important; background-attachment: fixed; background-size: cover;');
	}
<?php } ?>
<?php if($tampilan_produk_choice == 'beda') { ?> 
	if (window.innerWidth > 767) {
		const classes = ["default","compact","grid2","grid1"];
		classes.forEach(c => {
		  if (document.body.classList.contains(c)) {
			 document.body.classList.remove(c);
		  }
		});
		document.body.classList.add("<?=$tampilan_produk_desktop?>");
	}
<?php } ?>
<?php if (!is_user_logged_in()) { 
  if($aktifkan_pwa == 'on') { ?>
	if('serviceWorker' in navigator) {
		navigator.serviceWorker.register('<?=get_template_directory_uri()?>/js/sw.js.php', { scope: "/" }).then(registration => {
			console.log('ServiceWorker registration successful with scope: ', registration.scope);
			}).catch(err => {
			console.log('ServiceWorker registration failed: ', err);
		});
	}
	document.addEventListener('init', function(event) {
		let installPromptEvent;
		window.addEventListener('beforeinstallprompt', (event) => {
		  event.preventDefault();
		  installPromptEvent = event;
		});
		document.querySelector('.install').addEventListener('click', () => {
		  installPromptEvent.prompt();
		  installPromptEvent.userChoice.then((choice) => {
			if (choice.outcome === 'accepted') {
			  console.log('User accepted the A2HS prompt');
			  document.querySelector('.install').setAttribute('style','display:none');
			} else {
			  console.log('User dismissed the A2HS prompt');
			}
			installPromptEvent = null;
		  });
		});
		window.addEventListener('appinstalled', (event) => {
		  document.querySelector('.install').setAttribute('style','display:none');
		});
		async function getInstalledApps() {
		  const installedApps = await navigator.getInstalledRelatedApps();
		  if(installedApps.length !== 0) {
			document.querySelector('.install').setAttribute('style','display:none');		  
		  }
		}
		if ('getInstalledRelatedApps' in navigator) {
		  getInstalledApps();
		}
	});
<?php }
  } else { ?>
	navigator.serviceWorker.getRegistrations().then(function(registrations) {
		for(let registration of registrations) {
			if(registration.active.scriptURL.indexOf('sw.js.php') !== -1) {
				registration.unregister()
			} 
		}
	});
<?php } ?>
</script>
<?php
get_footer();
?>