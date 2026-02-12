<?php
/**
 * Template part for displaying post
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package themefood
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<div class="entry-content">
		<a href="<?php echo get_the_permalink(); ?>">
		<?php
		if ( has_post_thumbnail() ) : ?>
			<img src="<?=is_cdn(get_the_post_thumbnail_url(get_the_ID(), 'full'))?>" class="item-thumbnail" />
		<?php
			endif;		
			the_title( '<h1 class="entry-title">', '</h1>' );
			the_excerpt();
		?>
		</a>
	</div><!-- .entry-content -->

</article><!-- #post-<?php the_ID(); ?> -->
