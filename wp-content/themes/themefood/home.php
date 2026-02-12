<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package themefood
 */
if ( function_exists( 'ot_get_option' ) ) {
  $background_header = ot_get_option( 'background_header' );
  $teks_header = ot_get_option( 'teks_header' );	
  $background_katalog = ot_get_option( 'background_katalog' );
  $tampilan_desktop = ot_get_option( 'tampilan_desktop' );
}
get_header();
?>

	<main id="primary" class="site-main">

		<?php if ( have_posts() ) : ?>

			<header style="<?=($background_header !== '' ? 'background:'.$background_header : '')?>" class="entry-header <?=($teks_header == 'white' ? 'white-text' : '')?>">
				<a href="<?=getTplPageURL('page-katalog.php')?>" class="back-katalog"><svg width="16px" height="16px" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"> <title>md-back-button-icon</title> <desc>Created with Sketch.</desc> <defs></defs> <g id="toolbar-back-button" stroke="none" stroke-width="1" fill-rule="evenodd"> <g id="android" transform="translate(-32.000000, -32.000000)" fill-rule="nonzero"> <polygon id="md-back-button-icon" points="48 39 35.83 39 41.42 33.41 40 32 32 40 40 48 41.41 46.59 35.83 41 48 41"></polygon> </g> </g> </svg></a>
				<?php the_archive_title( '<h1 class="page-title">', '</h1>' ); ?>
			</header><!-- .entry-header -->

			<?php
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'archive' );

			endwhile;

			the_posts_navigation();

		endif;
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
