<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package themefood
 */
if ( function_exists( 'ot_get_option' ) ) {
	$class = ot_get_option( 'tampilan_produk' );
	if($class == 'grid1') $class = 'grid2 grid1';
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1.0 maximum-scale=1.0" />
	<link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class($class); ?>>
<?php wp_body_open(); ?>