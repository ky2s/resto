<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package themefood
 */

if ( function_exists( 'ot_get_option' ) ) {
  $background_katalog = ot_get_option( 'background_katalog' );	
}
get_header();
?>

	<main id="primary" class="site-main">

		<?php
		while ( have_posts() ) :
			the_post();

			get_template_part( 'template-parts/content', 'page' );

		endwhile; // End of the loop.
		?>

	</main><!-- #main -->
	
	<script>
	if (window.innerWidth > 480) {
		document.body.setAttribute('style','background-image: url(<?=$background_katalog?>)!important; background-attachment: fixed; background-size: cover;');
	}	
	</script>

<?php
get_footer();
