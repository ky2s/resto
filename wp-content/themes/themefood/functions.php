<?php
/**
 * themefood functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package themefood
 */

require_once get_template_directory() . '/inc/LicenseRequest.php';

require_once get_template_directory() . '/inc/Client.php';

require_once get_template_directory() . '/inc/Api.php';

require_once get_template_directory() . '/inc/class-tgm-plugin-activation.php';

require_once get_template_directory() . '/inc/puc/plugin-update-checker.php';

use LicenseKeys\Utility\Api;

use LicenseKeys\Utility\Client;

use LicenseKeys\Utility\LicenseRequest;

if ( ! defined( 'THEMEFOOD_VERSION' ) ) {
	
	define( 'THEMEFOOD_VERSION', '1.3.3' );
	
	define( 'FOOD_SOURCE', 'https://themefood.id' );
	
	define( 'FOOD_STORAGE', 'themefood_storage' );
	
}

if ( ! function_exists( 'themefood_setup' ) ) :
	function themefood_setup() {
		global $pagenow;
		if ( 'themes.php' == $pagenow && is_admin() && isset( $_GET['activated'] ) ) {
			wp_redirect( esc_url_raw( add_query_arg( 'page', 'license_key', admin_url( 'admin.php' ) ) ) );
		}
		load_theme_textdomain( 'themefood', get_template_directory() . '/languages' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_image_size( 'foto-kategori', 200, 87, true );
		add_image_size( 'foto-produk', 555, 555, true );
		add_image_size( 'foto-slide', 950, 546, true );
		add_image_size( 'foto-cs', 112, 112, true );
		add_image_size( 'foto-info', 555, 9999 );

		register_nav_menus(
			array(
				'menu-1' => esc_html__( 'Top Menu', 'themefood' ),
			)
		);
		
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);

		add_theme_support( 'customize-selective-refresh-widgets' );

	}
endif;
add_action( 'after_setup_theme', 'themefood_setup' );

class Custom_Walker_Nav_Menu extends Walker_Nav_Menu {
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' );
		
		$display_depth = ( $depth + 1);
		
		$classes = array(
		
			'sub-menu',
			
			( $display_depth % 2  ? 'menu-odd' : 'menu-even' ),
			
			( $display_depth >=2 ? 'sub-sub-menu' : '' ),
			
			'menu-depth-' . $display_depth
			
			);
			
		$class_names = implode( ' ', $classes );

		$output .= "\n" . $indent . '<div class="expandable-content">' . "\n";
		
	}
	
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' );
		
		$output .= "\n" . $indent . '</div>' . "\n";
		
	}

	function start_el ( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		
		global $wp_query, $wpdb;
		
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		
		$class_names = ' class="' . esc_attr( $class_names ) . '"';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';

		$has_children = $wpdb->get_var("SELECT COUNT(meta_id)
								FROM wp_postmeta
								WHERE meta_key='_menu_item_menu_item_parent'
								AND meta_value='".$item->ID."'");

		if ( $depth == 0 && $has_children > 0  ) {

			$output .= $indent . '<ons-list-item tappable expandable>';

			$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
			
			$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
			
			$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			
			$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

			$item_output = $args->before;
			
			$item_output .= '<a'. $attributes .' class="center">';
			
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			
			$item_output .= '</a>';
			
			$item_output .= $args->after;

		} else {

			$output .= $indent . '<ons-list-item tappable>';

			$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
			
			$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
			
			$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
			
			$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

			$item_output = $args->before;
			
			$item_output .= '<a'. $attributes .' class="center">';
			
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			
			$item_output .= '</a>';
			
			$item_output .= $args->after;

		}

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args, $id );
	}
	
	function end_el( &$output, $item, $depth = 0, $args = array() ) {
		
		$indent = ( $depth > 0  ? str_repeat( "\t", $depth ) : '' ); 
		
		$output .= "\n" . $indent . '</ons-list-item>' . "\n";
		
	}
	
}

function food_validate( $bpom, $sku ) {
	$response = Api::activate(
		Client::instance(),
		function() use( $bpom, $sku ) {
			return LicenseRequest::create(
				FOOD_SOURCE . '/wp-admin/admin-ajax.php',
				'Q7ja4A7tK0FLTxS',
				$sku,
				$bpom,
				LicenseRequest::DAILY_FREQUENCY
			);
		},
		function( $bpom ) {
		   update_option( FOOD_STORAGE, $bpom, true );
		}
	);
	return $response;
}

function food_invalidate( $bpom ) {
	$response = Api::deactivate(
		Client::instance(),
		function() {
			return new LicenseRequest( get_option( FOOD_STORAGE ) );
		},
		function( $bpom ) {
		   if ($bpom === null)
			   update_option( FOOD_STORAGE, null );
		}
	);
	return $response->error === false;
}

$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(

	FOOD_SOURCE . '/dl/update.json',
	
	__FILE__,
	
	'themefood'
	
);

function themefood_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'themefood_content_width', 480 );
}
add_action( 'after_setup_theme', 'themefood_content_width', 0 );

function wp_jquery_manager_plugin_front_end_scripts() {
    $wp_admin = is_admin();
    $wp_customizer = is_customize_preview();
    if ( $wp_admin || $wp_customizer ) {
        return;
    }
    else {
        wp_deregister_script( 'jquery' );
        wp_deregister_script( 'jquery-core' );
        wp_deregister_script( 'jquery-migrate' );
        wp_register_script( 'jquery-core', get_template_directory_uri() . '/js/jquery.min.js', array(), null, true );
        wp_register_script( 'jquery', false, array( 'jquery-core' ), null, true );
        wp_enqueue_script( 'jquery' );
    }
}
add_action( 'wp_enqueue_scripts', 'wp_jquery_manager_plugin_front_end_scripts' );

function themefood_scripts() {
	if ( function_exists( 'ot_get_option' ) ) {
	  $mode_timeslot = ot_get_option( 'mode_timeslot' );
	}
	if (is_page_template('page-katalog.php')) { 
		wp_dequeue_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_enqueue_style( 'onsenui', get_template_directory_uri() . '/css/onsenui.min.css', array(), THEMEFOOD_VERSION );		
		wp_enqueue_style( 'placeholder', get_template_directory_uri() . '/css/placeholder-loading.min.css', array(), THEMEFOOD_VERSION );		
		wp_enqueue_style( 'leaflet', get_template_directory_uri() . '/css/leaflet.min.css', array(), THEMEFOOD_VERSION );		
		wp_enqueue_style( 'front', get_template_directory_uri() . '/css/front.min.css', array(), THEMEFOOD_VERSION );		
		if(preg_match('(date|datetime)', $mode_timeslot) === 1) {
			wp_enqueue_style( 'datetimepicker', get_template_directory_uri() . '/css/jquery.datetimepicker.min.css', array(), THEMEFOOD_VERSION );
			wp_enqueue_script( 'datetimepicker', get_template_directory_uri() . '/js/jquery.datetimepicker.full.min.js', array(), THEMEFOOD_VERSION, true );			
		}
		wp_enqueue_script( 'onsen', get_template_directory_uri() . '/js/onsenui.min.js', array(), THEMEFOOD_VERSION, true );
		wp_enqueue_script( 'lazyload', get_template_directory_uri() . '/js/lazyload.min.js', array(), THEMEFOOD_VERSION, true );
		wp_enqueue_script( 'readmore', get_template_directory_uri() . '/js/readmore.min.js', array(), THEMEFOOD_VERSION, true );
		wp_enqueue_script( 'savemyform', get_template_directory_uri() . '/js/saveMyForm.jquery.min.js', array(), THEMEFOOD_VERSION, true );
		wp_enqueue_script( 'jquery-ui', get_template_directory_uri() . '/js/jquery-ui.min.js', array(), THEMEFOOD_VERSION, true );
		wp_enqueue_script( 'confetti', get_template_directory_uri() . '/js/confetti.min.js', array(), THEMEFOOD_VERSION, true );
		wp_enqueue_script( 'themefood-cart', get_template_directory_uri() . '/js/cart.min.js', array(), THEMEFOOD_VERSION, true );
		wp_enqueue_script( 'themefood-script', get_template_directory_uri() . '/js/script.min.js', array(), THEMEFOOD_VERSION, true );
	} else {
		wp_enqueue_style( 'themefood-style', get_stylesheet_uri(), array(), THEMEFOOD_VERSION );
	}	
	if (is_singular('tf-produk')) {
		wp_enqueue_style( 'glider', get_template_directory_uri() . '/css/glide.core.min.css', array(), THEMEFOOD_VERSION );
		wp_enqueue_script( 'glider', get_template_directory_uri() . '/js/glide.min.js', array(), THEMEFOOD_VERSION, true );
	}
}
add_action( 'wp_enqueue_scripts', 'themefood_scripts' );

function is_valid_page() {
	return Api::validate(
		Client::instance(),
		function() {
			return new LicenseRequest( get_option( FOOD_STORAGE ) );
		},
		function( $license ) {
		   update_option( FOOD_STORAGE, $license );
		}
	);
}

function defer_parsing_of_js( $url ) {
    if ( is_user_logged_in() ) return $url;
    if ( FALSE === strpos( $url, '.js' ) ) return $url;
    if ( preg_match('/jquery.min.js|onsenui.min.js|glide.min.js/i', $url) ) return $url;
    return str_replace( ' src', ' defer src', $url );
}
add_filter( 'script_loader_tag', 'defer_parsing_of_js', 10 );

add_action( 'wp_head', 'inc_manifest_link' );
function inc_manifest_link() {   
	$site_name = get_bloginfo('name');
	if ( function_exists( 'ot_get_option' ) ) {
	  $theme_color = ot_get_option( 'theme_color' );
	  $background_color = ot_get_option( 'background_color' );
	  $icon_pwa = ot_get_option( 'icon_pwa' );
	}
	echo '<link rel="manifest" href="'.get_template_directory_uri().'/manifest.json.php?name='.rawurlencode($site_name).'&theme='.rawurlencode($theme_color).'&bg='.rawurlencode($background_color).'&icon='.rawurlencode($icon_pwa).'" />';
	echo '<link rel="preload" href="'.get_template_directory_uri().'/css/ionicons/css/ionicons.min.css" as="style" />';
	echo '<link rel="preload" href="'.get_template_directory_uri().'/css/ubuntu/Ubuntu-Regular.ttf" as="font" type="font/ttf" crossorigin />';
	echo '<link rel="preload" href="'.get_template_directory_uri().'/css/ionicons/fonts/ionicons.woff2?v=4.5.5" as="font" type="font/woff2" crossorigin />';
}

add_filter('language_attributes', 'add_opengraph_doctype');
function add_opengraph_doctype( $output ) {
	return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
}

function custom_excerpt_length( $length ) {
	return 20;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

add_action( 'wp_head', 'insert_fb_in_head', 5 ); 
function insert_fb_in_head() {
	if (is_page_template('page-katalog.php')) {
		if ( function_exists( 'ot_get_option' ) ) {
		  $nama_toko = ot_get_option( 'nama_toko' );
		  $slogan_toko = ot_get_option( 'slogan_toko' );
		  $social_thumbnail = ot_get_option( 'social_thumbnail' ) ?: get_template_directory_uri() . '/img/social-thumbnail.jpg';
		}
        echo '<meta property="og:title" content="' . $slogan_toko . ' | ' . $nama_toko . '"/>';
        echo '<meta property="og:type" content="website"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="' . $nama_toko . '"/>';
        echo '<meta property="og:image" content="' . $social_thumbnail . '"/>';
	}
}

add_filter('mime_types', 'webp_upload_mimes');
function webp_upload_mimes($existing_mimes) {
	$existing_mimes['webp'] = 'image/webp';
	return $existing_mimes;
}
add_filter('file_is_displayable_image', 'webp_is_displayable', 10, 2);
function webp_is_displayable($result, $path) {
	if ($result === false) {
		$displayable_image_types = array( IMAGETYPE_WEBP );
		$info = @getimagesize( $path );
		if (empty($info)) {
			$result = false;
		} elseif (!in_array($info[2], $displayable_image_types)) {
			$result = false;
		} else {
			$result = true;
		}
	}
	return $result;
}
	
add_action( 'admin_notices', 'tf_admin_notices' );
function tf_admin_notices() {	
	if ( isset( $_POST['tf_activate_license'] ) ) {
		if (food_validate( $_POST['tf_license'], $_POST['tf_license_type'] )->error == 1) {
			?>
			<div class="notice notice-error is-dismissible">		
				<p><?= food_validate( $_POST['tf_license'], $_POST['tf_license_type'] )->errors->license_key[0]; ?></p>			
			</div>		
			<?php
		} else { 
			?>
			<div class="notice notice-success is-dismissible">		
				<p><?php _e( 'ThemeFood berhasil diaktifasi! Refresh halaman untuk menampilkan perubahan.', 'themefood' ); ?></p>
			</div>		
			<script>
			location.reload();
			</script>
			<?php		        
		}
	}
	if ( isset( $_POST['tf_deactivate_license'] ) ) {
		if (food_invalidate( $_POST['tf_license'] )) {
			if (is_plugin_active('wp-rest-cache/wp-rest-cache.php')){
				\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/kategori_produk?per_page=100' );
			}
			?>
				<div class="notice notice-success is-dismissible">
					<p><?php _e( 'Lisensi berhasi direset!', 'themefood' ); ?></p>
				</div>
				<script>
				location.reload();
				</script>
			<?php		        
		} else {
			update_option( FOOD_STORAGE, null );
		}			
	}
	if ( !is_valid_page() ) {
	?>
		<div class="notice notice-error">
			<p><?php _e( 'Masukkan kode lisensi yang valid agar dapat ThemeFood dapat berjalan dengan baik. <a href="'.admin_url().'admin.php?page=license_key'.'">Klik disini</a> untuk memasukkan lisensi.', 'themefood' ); ?></p>
		</div>            
	<?php
	}
	if(get_theme_update_available(wp_get_theme())) {
		echo '<div class="notice notice-warning"><p>' . get_theme_update_available(wp_get_theme()) . '</p></div>';
	}
}

add_action( 'admin_enqueue_scripts', 'themefood_admin_scripts' );	
function themefood_admin_scripts($hook_suffix) {
	global $typenow;
	wp_enqueue_style( 'admin-style', get_template_directory_uri() . '/css/admin.min.css', false, THEMEFOOD_VERSION );
	if(($typenow == 'tf-produk')){
        wp_enqueue_media();
        wp_register_script( 'meta-image', get_template_directory_uri() . '/js/media-uploader.js', array( 'jquery' ), THEMEFOOD_VERSION, true );
        wp_localize_script( 'meta-image', 'meta_image',
            array(
                'title' => 'Upload Foto',
                'button' => 'Gunakan gambar ini',
            )
        );
        wp_enqueue_script( 'meta-image' );
    }
	if(($typenow == 'tf-slider')){
		wp_enqueue_style( 'select2', get_template_directory_uri() . '/css/select2.min.css', false, THEMEFOOD_VERSION );
		wp_enqueue_script( 'select2', get_template_directory_uri() . '/js/select2.min.js', array(), THEMEFOOD_VERSION, true );
	}
}  

add_action( 'wp_footer', 'themefood_onesignal_script', 999 );
add_action( 'admin_footer', 'themefood_onesignal_script', 999 );
function themefood_onesignal_script() {
	if ( function_exists( 'ot_get_option' ) ) {
	  $aktifkan_adminnotif = ot_get_option( 'aktifkan_adminnotif' );
	  $onesignal_appid = ot_get_option( 'onesignal_appid' );
	  $onesignal_apikey = ot_get_option( 'onesignal_apikey' );
	}
	if($aktifkan_adminnotif == 'on') {
		if($onesignal_appid !== '' && $onesignal_apikey !== '') {
?>
<script src='https://cdn.onesignal.com/sdks/OneSignalSDK.js' id='OneSignalSDK-js' async></script>		
<script>
  window.OneSignal = window.OneSignal || [];
  OneSignal.push(function() {
	OneSignal.init({
	  appId: "<?=$onesignal_appid?>",
	  autoResubscribe: true,
	  <?php if(is_admin()):?>
	  notifyButton: {
		enable: true
	  },
	  welcomeNotification: {
		"title": "Yeay! Notifikasi Order Aktif",
		"message": "Mulai sekarang anda akan menerima notifikasi order baru"
	  }
	  <?php endif; ?>
	});
	OneSignal.SERVICE_WORKER_PARAM = { scope: '/wp-content/themes/themefood/js/' };
	OneSignal.SERVICE_WORKER_PATH = 'wp-content/themes/themefood/js/OneSignalSDKWorker.js'
	OneSignal.SERVICE_WORKER_UPDATER_PATH = 'wp-content/themes/themefood/js/OneSignalSDKUpdaterWorker.js'
  });
</script>
<?php
		}
	}	
}

add_action( 'login_enqueue_scripts', 'disable_serviceWorker' );
add_action( 'admin_enqueue_scripts', 'disable_serviceWorker' );
add_action( 'wp_footer', 'disable_serviceWorker' );
function disable_serviceWorker() {
	if(!is_page_template('page-katalog.php')) {
    ?>
    <script>
	navigator.serviceWorker.getRegistrations().then(function(registrations) {
		for(let registration of registrations) {
			if(registration.active.scriptURL.indexOf('sw.js.php') !== -1) {
				registration.unregister()
			}
		}
	});
    </script>
<?php 
	}
}

add_action( 'login_enqueue_scripts', 'themefood_custom_login_ui' );
function themefood_custom_login_ui() {
	wp_enqueue_style(
		'themefood-login-style',
		get_template_directory_uri() . '/css/login-custom.css',
		array(),
		THEMEFOOD_VERSION
	);
}

add_filter( 'login_headerurl', 'themefood_custom_login_url' );
function themefood_custom_login_url() {
	return home_url( '/' );
}

add_filter( 'login_headertext', 'themefood_custom_login_title' );
function themefood_custom_login_title() {
	return get_bloginfo( 'name' );
}

add_action( 'wp_footer', 'user_script', 20, 1 );
function user_script() {
	if ( function_exists( 'ot_get_option' ) ) {
		$footer_katalog_produk = ot_get_option( 'footer_katalog_produk' );
		$tampilan_desktop = ot_get_option( 'tampilan_desktop' );
		$footer_order_redirect = ot_get_option( 'footer_order_redirect' );
    }
	if (is_page_template('page-katalog.php')) {	
		echo $footer_katalog_produk;
		if($tampilan_desktop == 'desktop') {
			?>
			<script>
			ons.ready(function () {				
				(function($) {
					if (window.innerWidth > 767) {
						$('body').removeClass('grid1').addClass('desktop-layout');
						$('body.desktop-layout .cartBtn-wrapper').removeClass('cart-default cart-floating cart-full').addClass('cart-mini');
					}
				})(jQuery);
			});	
			</script>				
			<?php
		}
	}
	if (is_page_template('page-order.php')) {
		echo $footer_order_redirect;
	}		
	if (is_singular('tf-produk')) {
		?>
<script>
new Glide('.galeri-produk').mount();
</script>		
		<?php
	}		
}

function disable_emojis() {
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
    remove_action( 'admin_print_styles', 'print_emoji_styles' );
    remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
    remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
    remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
    add_filter( 'tiny_mce_plugins', 'disable_emojis_tinymce' );
}
add_action( 'init', 'disable_emojis' );

function disable_emojis_tinymce( $plugins ) {
    if ( is_array( $plugins ) ) {
        return array_diff( $plugins, array( 'wpemoji' ) );
    } else {
        return array();
    }
}

function speed_stop_loading_wp_embed() {
    if (!is_admin()) {
        wp_deregister_script('wp-embed');
    }
}
add_action('init', 'speed_stop_loading_wp_embed');

function __default_local_avatar() {
    return get_bloginfo('template_directory') . '/img/default_avatar.png';
}
add_filter( 'pre_option_avatar_default', '__default_local_avatar' );

function maximum_api_filter($query_params) {
    $query_params['per_page']["maximum"] = 1000;
    return $query_params;
}
add_filter('rest_tf-produk_collection_params', 'maximum_api_filter');
add_filter('rest_tf-info_collection_params', 'maximum_api_filter');
add_filter('rest_tf-slider_collection_params', 'maximum_api_filter');
add_filter('rest_tf-kupon_collection_params', 'maximum_api_filter');
add_filter('rest_tf-cs_collection_params', 'maximum_api_filter');
add_filter('rest_tf-order_collection_params', 'maximum_api_filter');

add_filter('pre_get_document_title', 'change_the_title');
function change_the_title() {
	if ( function_exists( 'ot_get_option' ) ) {
	  $site_title = ot_get_option( 'nama_toko' );
	  $site_desc = ot_get_option( 'slogan_toko' );
	}
	$title_tag = $site_title . (($site_desc !== '') ? ' &#8211; ' . $site_desc : '');
	if (is_page_template('page-katalog.php') && $site_title !== '') return $title_tag;
}

add_filter('body_class', 'my_plugin_body_class');
function my_plugin_body_class($classes) {
	if(!is_valid_page()) {
		$classes[] = 'invalid';
	} else { 
		$classes[] = '';
	}
    return $classes;
}

add_action( 'wp_head', 'tf_favicon_link' );
function tf_favicon_link() {
	if ( function_exists( 'ot_get_option' ) ) {
	  $favicon_website = ot_get_option( 'favicon_website' );
	}
    if($favicon_website !== '') echo '<link rel="shortcut icon" type="image/x-icon" href="'.$favicon_website.'" />' . "\n";
}

function adjustBrightness($hex, $steps) {
    $steps = max(-255, min(255, $steps));
    $hex = str_replace('#', '', $hex);
    if (strlen($hex) == 3) {
        $hex = str_repeat(substr($hex,0,1), 2).str_repeat(substr($hex,1,1), 2).str_repeat(substr($hex,2,1), 2);
    }
    $color_parts = str_split($hex, 2);
    $return = '#';
    foreach ($color_parts as $color) {
        $color   = hexdec($color);
        $color   = max(0,min(255,$color + $steps));
        $return .= str_pad(dechex($color), 2, '0', STR_PAD_LEFT);
    }
    return $return;
}

add_action('wp_head', 'button_color_style', 100);
function button_color_style() {
	if ( function_exists( 'ot_get_option' ) ) {
		$background_header = ot_get_option( 'background_header' );
		$btn_color = ot_get_option( 'button_color' );	
		$subtitle_durianpay = ot_get_option( 'subtitle_durianpay' );
    }	
	?>
	<style>
	body.desktop-layout .toolbar--material:before { background: <?=$background_header?>; }
	.cartBtn-wrapper .cartBtn, body.grid1 .item-list .add-item { background: <?=$btn_color?>; }
	.cartBtn-wrapper .menu-cat { background: <?=adjustBrightness($btn_color,-10)?>; }
	.button--outline, .button--outline:hover, #sliderKat.tabbed #cat-list .cat-item .cat-wrapper.active, #sliderKat.tabbed #cat-list .cat-item .cat-wrapper.active a { color: <?=$btn_color?>; border-color: <?=$btn_color?>; }
	.item-list .item-qty ons-button, #extra .extra-cta .right .item-qty ons-button, #cart #my-cart-table tbody > tr td .my-product-qty ons-button, .item-list.loadmore .center, .share-button:hover .label, #detailItem .item-desc + [data-readmore-toggle] { color: <?=$btn_color?>; }
	#detailItem .add-item-popup, #itemNotes .add-notes-popup, #extra .extra-cta .add-item-extra, #cart .checkout-cta .checkout-btn, #delivTime .choose-delivery-time { background: <?=$btn_color?>; text-shadow: 1px 1px <?=adjustBrightness($btn_color,-10)?>, -1px -1px <?=adjustBrightness($btn_color,-10)?>; }
	<?=!empty($subtitle_durianpay) ? '#paymentMethod .action-sheet-button .list-item label.center[for=metode-durianpay]:after { content: "('.$subtitle_durianpay.')"; }' : ''?>
	</style>
	<?php
}

add_filter( 'admin_body_class', 'my_admin_body_class' );
function my_admin_body_class( $classes ) {
	$aktifkan_admintheme = 'off';
	if ( function_exists( 'ot_get_option' ) ) {
		$aktifkan_admintheme = ot_get_option( 'aktifkan_admintheme' );
	}
	if($aktifkan_admintheme == 'on') {
		return "$classes wpat wpat-spacing-off wpat-toolbar-on wpat-left-menu-width";
	}
}

add_action('wp_dashboard_setup', 'themefood_laporan_dashboard_widget');  
function themefood_laporan_dashboard_widget() {
	if ( function_exists( 'ot_get_option' ) ) {
		$aktifkan_adminwidget = ot_get_option( 'aktifkan_adminwidget' );
	}
	if($aktifkan_adminwidget == 'on') {
		global $wp_meta_boxes;
		unset($wp_meta_boxes['dashboard']);
		wp_add_dashboard_widget('laporan_widget', 'Laporan Orderan', 'laporan_page');
	} 
} 

function is_cdn($url) {
	if ( function_exists( 'ot_get_option' ) ) {
		$layanan_cdn = ot_get_option( 'layanan_cdn' );
		$bunny_hostname = ot_get_option( 'bunny_hostname' );
	}
	$image = $url;
	if($layanan_cdn == 'statically'){
		$image = 'https://cdn.statically.io/img/' . preg_replace('#^https?://#', '', $url) . '?quality=100&f=auto';		
	}
	if($layanan_cdn == 'bunny'){
		$image = 'https://' . $bunny_hostname . parse_url($url, PHP_URL_PATH);
	}
	return $image;
}

add_action('add_meta_boxes', 'my_remove_wp_seo_meta_box', 100);
function my_remove_wp_seo_meta_box() {
	if(class_exists('WPSEO_Options')){
		remove_meta_box('wpseo_meta', ['tf-cs','tf-slider','tf-kupon','tf-order'], 'normal');
	}
}

add_action( 'admin_menu', 'themefood_option_menu', 999 );
function themefood_option_menu() {
	add_menu_page(
		'themefood-options',
		__( 'ThemeFood Options', 'themefood' ),
		__( 'ThemeFood', 'themefood' ),
		'manage_options', 
		'themefood-options',
		'',
		get_template_directory_uri() . '/img/icon-themefood.svg',
		26
	);
	add_submenu_page(
		'themefood-options',
		__( 'Lisensi', 'themefood' ),
		__( 'Lisensi', 'themefood' ),
		'manage_options', 
		'license_key',
		'misc_page'
	);
	if(!is_valid_page()) remove_submenu_page( 'themefood-options', 'themefood-options' );
}

add_action( 'tgmpa_register', 'themefood_register_required_plugins' );
function themefood_register_required_plugins() {
	$plugins = array(
		array(
			'name'               => 'TF Supporter',
			'slug'               => 'tf-supporter',
			'source'             => 'https://themefood.id/dl/plugin/tf-supporter.zip',
			'external_url'       => 'https://themefood.id/',
			'version'            => '1.3.3',
			'required'           => true,
			'force_activation'   => true,
			'force_deactivation' => true
		),
		array(
			'name'               => 'Category Order and Taxonomy Terms Order',
			'slug'               => 'taxonomy-terms-order',
			'required'           => true
		),
		array(
			'name'               => 'One Click Demo Import',
			'slug'               => 'one-click-demo-import',
			'required'           => false
		),
		array(
			'name'               => 'Post Types Order',
			'slug'               => 'post-types-order',
			'required'           => true
		),
		array(
			'name'               => 'WP REST Cache',
			'slug'               => 'wp-rest-cache',
			'required'           => true
		),
		array(
			'name'               => 'WP Import Export Lite',
			'slug'               => 'wp-import-export-lite',
			'required'           => false
		)
	);
	$config = array(
		'id'           => 'themefood-' . THEMEFOOD_VERSION,
		'default_path' => '',
		'menu'         => 'tgmpa-install-plugins',
		'parent_slug'  => 'themes.php',
		'capability'   => 'edit_theme_options',
		'has_notices'  => true,
		'dismissable'  => false,
		'dismiss_msg'  => '',
		'is_automatic' => false,
		'message'      => ''
	);
	if(is_valid_page()) tgmpa( $plugins, $config ); 
}

add_filter( 'ocdi/plugin_intro_text', 'ocdi_plugin_intro_text' );
function ocdi_plugin_intro_text( $default_text ) {
	$default_text .= '<div class="ocdi__intro-text"><p>Klik tombol "Import Demo Data" untuk memulai import demo konten seperti yang tampil pada halaman demo ThemeFood.</p></div>';
	return $default_text;
}

add_filter( 'ocdi/import_files', 'ocdi_import_files' );
function ocdi_import_files() {
  return [
    [
      'import_file_name'           => 'Demo Katalog',
      'import_file_url'            => 'https://themefood.id/dl/demo-content.xml',
      'import_ot_url'              => 'https://themefood.id/dl/theme-options.txt',
      'import_preview_image_url'   => 'https://demo.themefood.id/wp-content/themes/themefood/img/demo-preview.jpg',
      'preview_url'                => 'https://demo.themefood.id',
    ]
  ];
}

add_action( 'ocdi/after_import', 'ocdi_after_import' );
function ocdi_after_import( $selected_import ) {
	global $wp_rewrite;
    $wp_rewrite->set_permalink_structure('/%postname%/');
    $wp_rewrite->flush_rules();
	$front_page_id = get_page_by_title( 'Home' );
	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	$file = $selected_import['import_ot_url'];
    $theme_options_txt = wp_remote_get($file);
    $options = unserialize(base64_decode($theme_options_txt['body']));
    $settings = get_option(ot_settings_id());
    foreach ($settings['settings'] as $setting) {
        if (isset($options[$setting['id']])) {
            $content = ot_stripslashes($options[$setting['id']]);
            $options[$setting['id']] = ot_validate_setting($content, $setting['type'], $setting['id']);
        }
    }
    update_option(ot_options_id(), $options);
}

add_filter( 'ocdi/regenerate_thumbnails_in_content_import', '__return_false' );

function obfuscate_email($email) {
    $em   = explode("@",$email);
    $name = implode('@', array_slice($em, 0, count($em)-1));
    $len  = floor(strlen($name)/2);
    return substr($name,0, $len) . str_repeat('*', $len) . "@" . end($em);   
}

function misc_page() {
	?>
	<div class="wrap" id="themefood-wrap">
	  <h1 class="wp-heading-inline">Lisensi ThemeFood</h1>
	  <div id="lisensi">
	  <form method="post">
	  <?php if ( is_valid_page() ):
		$misc_check = get_option( FOOD_STORAGE );	  
		$misc_check = json_decode( $misc_check, true );
	  ?>
		<p>Selamat! Lisensi yang anda masukkan <strong style="color: #03AA0E; letter-spacing: 1px;">VALID</strong>.</p>
		<strong>Status</strong>: <?php echo $misc_check['data']['status']; ?><br />
		<strong>Email</strong>: <?php echo obfuscate_email($misc_check['data']['email']); ?><br />
		<strong>Domain</strong>: <?php echo $misc_check['request']['domain']; ?><br />
		<strong>Lisensi</strong>: <?php echo str_repeat("*", strlen($misc_check['data']['the_key'])); ?><br />
		<strong>Tipe</strong>: <?php echo explode("-", $misc_check['request']['sku'])[1] . ' Website'; ?>
		<input type="hidden" id="tf-license" name="tf_license" value="<?php echo $misc_check['data']['the_key']; ?>" autocomplete="off"/>			
		<p class="submit tf-submit">
		  <input type="submit" name="tf_deactivate_license" class="button-primary"
				   value="<?php _e( 'Reset Lisensi', 'themefood' ); ?>">
		</p>
		<?php else: ?>
		<p>Anda bisa mendapatkan lisensi yang valid di <a href="https://themefood.id/akun/lisensi" target="_blank">halaman member ThemeFood</a>.</p>
		<label for="tf-license"><?php _e('Kode Lisensi:', 'themefood'); ?></label>
		<select id="tf-license-type" name="tf_license_type">
			<option value="skulickey-1">1 Website</option>
			<option value="skulickey-3">3 Website</option>
			<option value="skulickey-5">5 Website</option>
			<option value="skulickey-10">10 Website</option>
		</select>
		<input type="text" id="tf-license" name="tf_license" placeholder="XXXX-XXXX-XXXX-XXXX" autocomplete="off"/>			
		<p class="submit tf-submit">
		  <input type="submit" name="tf_activate_license" class="button-primary"
				   value="<?php _e( 'Aktifkan Lisensi', 'themefood' ); ?>">
		</p>
		<?php endif; ?>
		</form>
	  </div>
	</div>
	<?php
}
