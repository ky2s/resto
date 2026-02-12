<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package themefood
 */
if ( function_exists( 'ot_get_option' ) ) {
  $background_katalog = ot_get_option( 'background_katalog' );
  $tampilan_desktop = ot_get_option( 'tampilan_desktop' );
}
get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', '' );

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
	
<script>
<?php if($tampilan_desktop == 'mobile') { ?> 
	if (window.innerWidth > 480) {
		document.body.setAttribute('style','background-image: url(<?=is_cdn($background_katalog)?>)!important; background-attachment: fixed; background-size: cover;');
	}
<?php } ?>
</script>
	
<?php
get_footer();
