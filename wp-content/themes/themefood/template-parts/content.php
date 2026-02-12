<?php
/**
 * Template part for displaying post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package themefood
 */
if ( function_exists( 'ot_get_option' ) ) {
  $background_header = ot_get_option( 'background_header' );
  $teks_header = ot_get_option( 'teks_header' );
  $nama_toko = ot_get_option( 'nama_toko' );
  if($nama_toko == '') $nama_toko = get_bloginfo( 'name' );
  $mata_uang = ot_get_option( 'mata_uang' );
}
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header style="<?=($background_header !== '' ? 'background:'.$background_header : '')?>" class="entry-header <?=($teks_header == 'white' ? 'white-text' : '')?>">
		<a href="<?=getTplPageURL('page-katalog.php')?>" class="back-katalog"><svg width="16px" height="16px" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>md-back-button-icon</title> <desc>Created with Sketch.</desc> <defs></defs> <g id="toolbar-back-button" stroke="none" stroke-width="1" fill-rule="evenodd"> <g id="android" transform="translate(-32.000000, -32.000000)" fill-rule="nonzero"> <polygon id="md-back-button-icon" points="48 39 35.83 39 41.42 33.41 40 32 32 40 40 48 41.41 46.59 35.83 41 48 41"></polygon> </g> </g> </svg></a>
		<?php the_title( '<h1 class="entry-title">', '</h1>' ) ?>
	</header><!-- .entry-header -->

	<div class="entry-content">
		<?php
		switch ( get_post_type() ) {
			case 'tf-produk' :
				$image_size = 'foto-produk';
				break;				
			case 'foto-info' :
				$image_size = 'foto-info';
				break;
			default :
				$image_size = 'full';
				break;
		}
		if ( has_post_thumbnail() ) {
			if ( 'tf-produk' === get_post_type() ) {
				$image_ids = get_post_meta( get_the_ID(), 'galeri_produk' );
				if ( ! empty( $image_ids ) && $image_ids[0] !== '') {
					echo '<div class="galeri-produk">
							<div class="glide__track" data-glide-el="track">
							<ul class="glide__slides">
							<li class="glide__slide">
								<img src="'.is_cdn(get_the_post_thumbnail_url(get_the_ID(), $image_size)).'" class="item-thumbnail" />
							</li>';
					$image_ids = explode( ',', $image_ids[0] );
					foreach($image_ids as $image_id) {
						$image_url = wp_get_attachment_url($image_id); ?>
							<li class="glide__slide">
								<img src="<?php echo $image_url; ?>" class="item-thumbnail" />
							</li>
						<?php
					}
					echo '</ul>
						  </div>
						  <div class="glide__arrows" data-glide-el="controls">
							<button class="glide__arrow glide__arrow--left" data-glide-dir="<"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512"><path d="M217.9 256L345 129c9.4-9.4 9.4-24.6 0-33.9-9.4-9.4-24.6-9.3-34 0L167 239c-9.1 9.1-9.3 23.7-.7 33.1L310.9 417c4.7 4.7 10.9 7 17 7s12.3-2.3 17-7c9.4-9.4 9.4-24.6 0-33.9L217.9 256z" fill="white"/><rect x="0" y="0" width="512" height="512" fill="rgba(0, 0, 0, 0)" /></svg></button>
							<button class="glide__arrow glide__arrow--right" data-glide-dir=">"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 512 512"><path d="M294.1 256L167 129c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.3 34 0L345 239c9.1 9.1 9.3 23.7.7 33.1L201.1 417c-4.7 4.7-10.9 7-17 7s-12.3-2.3-17-7c-9.4-9.4-9.4-24.6 0-33.9l127-127.1z" fill="white"/><rect x="0" y="0" width="512" height="512" fill="rgba(0, 0, 0, 0)" /></svg></button>
						  </div>
						  </div>';	
				} else {
					echo '<img src="'.is_cdn(get_the_post_thumbnail_url(get_the_ID(), $image_size)).'" class="item-thumbnail" />';									
				}
			} else {
				echo '<img src="'.is_cdn(get_the_post_thumbnail_url(get_the_ID(), $image_size)).'" class="item-thumbnail" />';				
			}
		} else {
			if ( 'tf-produk' === get_post_type() ) {
				echo '<img src="'.is_cdn(get_template_directory_uri() . '/img/placeholder.webp').'" class="item-thumbnail" />';
			}		
		}		
		the_content();
		if ( 'tf-produk' === get_post_type() ) :
			?>
			<div class="entry-meta entry-produk">
				<?php
				$harga_produk = get_post_meta( get_the_ID(), 'harga_produk' , true );
				$harga_diskon = get_post_meta( get_the_ID(), 'harga_diskon' , true );
				if ( $harga_diskon == '' || $harga_diskon == 0 ) :
				?>
					<small class="item-price"><?=$mata_uang?><?=number_format( $harga_produk, 0 , ',' , '.' )?></small>
				<?php else: ?>	
					<small class="item-price"><?=$mata_uang?><?=number_format( $harga_diskon, 0 , ',' , '.' )?></small> <small class="item-price-was"><span><?=$mata_uang?><?=number_format( $harga_produk, 0 , ',' , '.' )?></span></small>				
				<?php endif; ?>
			</div><!-- .entry-meta --> 
			<div class="view-product-wrapper">
				<a class="view-product" href="<?=get_site_url().'/#produk-'.get_the_ID()?>"><?php echo __('Pesan Produk', 'themefood'); ?></a>
			</div>
		<?php endif; ?>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
