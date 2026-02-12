<?php
/*
Plugin Name: ThemeFood Supporter Plugin
Plugin URI: https://themefood.id
Author: ThemeFood Team
Author URI: https://themefood.id
Description: Plugin pelengkap tema ThemeFood.
Version: 1.3.3
Text Domain: themefood
*/

if( 'themefood' !== get_option( 'template' ) ) return;

require get_template_directory() . '/inc/theme-options.php';

use LicenseKeys\Utility\Api;

use LicenseKeys\Utility\Client;

use LicenseKeys\Utility\LicenseRequest;

if ( class_exists( 'OT_Loader' ) && defined( 'OT_PLUGIN_MODE' ) && true === OT_PLUGIN_MODE && defined( 'ABSPATH' ) ) {

	add_filter( 'ot_theme_mode', '__return_false', 999 );

	/**
	 * Forces Plugin Mode when OptionTree is already loaded and displays an admin notice.
	 */
	function ot_conflict_notice() {
		echo '<div class="error"><p>' . esc_html__( 'OptionTree is installed as a plugin and also embedded in your current theme. Please deactivate the plugin to load the theme dependent version of OptionTree, and remove this warning.', 'option-tree' ) . '</p></div>';
	}

	add_action( 'admin_notices', 'ot_conflict_notice' );
}

if ( ! class_exists( 'OT_Loader' ) && defined( 'ABSPATH' ) ) {

	/**
	 * OptionTree loader class.
	 */
	class OT_Loader {

		/**
		 * Class constructor.
		 *
		 * This method loads other methods of the class.
		 *
		 * @access public
		 * @since  2.0
		 */
		public function __construct() {

			// Load OptionTree.
			add_action( 'after_setup_theme', array( $this, 'load_option_tree' ), 1 );
		}

		/**
		 * OptionTree loads on the 'after_setup_theme' action.
		 *
		 * @todo Load immediately.
		 *
		 * @access public
		 * @since 2.1.2
		 */
		public function load_option_tree() {

			// Setup the constants.
			$this->constants();

			// Include the required admin files.
			$this->admin_includes();

			// Include the required files.
			$this->includes();

			// Hook into WordPress.
			$this->hooks();
		}

		/**
		 * Constants.
		 *
		 * Defines the constants for use within OptionTree. Constants
		 * are prefixed with 'OT_' to avoid any naming collisions.
		 *
		 * @access private
		 * @since  2.0
		 */
		private function constants() {

			/**
			 * Current Version number.
			 */
			define( 'OT_VERSION', '2.7.3' );

			/**
			 * For developers: Theme mode.
			 *
			 * Run a filter and set to true to enable OptionTree theme mode.
			 * You must have this files parent directory inside of
			 * your themes root directory. As well, you must include
			 * a reference to this file in your themes functions.php.
			 *
			 * @since 2.0
			 */
			define( 'OT_THEME_MODE', apply_filters( 'ot_theme_mode', false ) );

			/**
			 * For developers: Child Theme mode. TODO document
			 *
			 * Run a filter and set to true to enable OptionTree child theme mode.
			 * You must have this files parent directory inside of
			 * your themes root directory. As well, you must include
			 * a reference to this file in your themes functions.php.
			 *
			 * @since 2.0.15
			 */
			define( 'OT_CHILD_THEME_MODE', apply_filters( 'ot_child_theme_mode', false ) );

			/**
			 * For developers: Show Pages.
			 *
			 * Run a filter and set to false if you don't want to load the
			 * settings & documentation pages in the admin area of WordPress.
			 *
			 * @since 2.0
			 */
			define( 'OT_SHOW_PAGES', apply_filters( 'ot_show_pages', false ) );

			/**
			 * For developers: Show Theme Options UI Builder
			 *
			 * Run a filter and set to false if you want to hide the
			 * Theme Options UI page in the admin area of WordPress.
			 *
			 * @since 2.1
			 */
			define( 'OT_SHOW_OPTIONS_UI', apply_filters( 'ot_show_options_ui', false ) );

			/**
			 * For developers: Show Settings Import
			 *
			 * Run a filter and set to false if you want to hide the
			 * Settings Import options on the Import page.
			 *
			 * @since 2.1
			 */
			define( 'OT_SHOW_SETTINGS_IMPORT', apply_filters( 'ot_show_settings_import', true ) );

			/**
			 * For developers: Show Settings Export
			 *
			 * Run a filter and set to false if you want to hide the
			 * Settings Import options on the Import page.
			 *
			 * @since 2.1
			 */
			define( 'OT_SHOW_SETTINGS_EXPORT', apply_filters( 'ot_show_settings_export', true ) );

			/**
			 * For developers: Show New Layout.
			 *
			 * Run a filter and set to false if you don't want to show the
			 * "New Layout" section at the top of the theme options page.
			 *
			 * @since 2.0.10
			 */
			define( 'OT_SHOW_NEW_LAYOUT', apply_filters( 'ot_show_new_layout', false ) );

			/**
			 * For developers: Show Documentation
			 *
			 * Run a filter and set to false if you want to hide the Documentation.
			 *
			 * @since 2.1
			 */
			define( 'OT_SHOW_DOCS', apply_filters( 'ot_show_docs', false ) );

			/**
			 * For developers: Custom Theme Option page
			 *
			 * Run a filter and set to false if you want to hide the OptionTree
			 * Theme Option page and build your own.
			 *
			 * @since 2.1
			 */
			define( 'OT_USE_THEME_OPTIONS', apply_filters( 'ot_use_theme_options', true ) );

			/**
			 * For developers: Meta Boxes.
			 *
			 * Run a filter and set to false to keep OptionTree from
			 * loading the meta box resources.
			 *
			 * @since 2.0
			 */
			define( 'OT_META_BOXES', apply_filters( 'ot_meta_boxes', false ) );

			/**
			 * For developers: Allow Unfiltered HTML in all the textareas.
			 *
			 * Run a filter and set to true if you want all the users to be
			 * able to add script, style, and iframe tags in the textareas.
			 * WARNING: This opens a security hole for low level users
			 * to be able to post malicious scripts, you've been warned.
			 *
			 * If a user can already post `unfiltered_html` then the tags
			 * above will be available to them without setting this to `true`.
			 *
			 * @since 2.0
			 */
			define( 'OT_ALLOW_UNFILTERED_HTML', apply_filters( 'ot_allow_unfiltered_html', true ) );

			/**
			 * For developers: Post Formats.
			 *
			 * Run a filter and set to true if you want OptionTree
			 * to load meta boxes for post formats.
			 *
			 * @since 2.4.0
			 */
			define( 'OT_POST_FORMATS', apply_filters( 'ot_post_formats', false ) );

			/**
			 * Check if in theme mode.
			 *
			 * If OT_THEME_MODE and OT_CHILD_THEME_MODE is false, set the
			 * directory path & URL like any other plugin. Otherwise, use
			 * the parent or child themes root directory.
			 *
			 * @since 2.0
			 */
			if ( false === OT_THEME_MODE && false === OT_CHILD_THEME_MODE ) {
				define( 'OT_DIR', plugin_dir_path( __FILE__ ) );
				define( 'OT_URL', plugin_dir_url( __FILE__ ) );
			} else {
				if ( true === OT_CHILD_THEME_MODE ) {
					$temp_path = explode( get_stylesheet(), str_replace( '\\', '/', dirname( __FILE__ ) ) );
					$path      = ltrim( end( $temp_path ), '/' );
					define( 'OT_DIR', trailingslashit( trailingslashit( get_stylesheet_directory() ) . $path ) );
					define( 'OT_URL', trailingslashit( trailingslashit( get_stylesheet_directory_uri() ) . $path ) );
				} else {
					$temp_path = explode( get_template(), str_replace( '\\', '/', dirname( __FILE__ ) ) );
					$path      = ltrim( end( $temp_path ), '/' );
					define( 'OT_DIR', trailingslashit( trailingslashit( get_template_directory() ) . $path ) );
					define( 'OT_URL', trailingslashit( trailingslashit( get_template_directory_uri() ) . $path ) );
				}
			}

			/**
			 * Template directory URI for the current theme.
			 *
			 * @since 2.1
			 */
			if ( true === OT_CHILD_THEME_MODE ) {
				define( 'OT_THEME_URL', get_stylesheet_directory_uri() );
			} else {
				define( 'OT_THEME_URL', get_template_directory_uri() );
			}
		}

		/**
		 * Include admin files.
		 *
		 * These functions are included on admin pages only.
		 *
		 * @access private
		 * @since  2.0
		 */
		private function admin_includes() {

			// Exit early if we're not on an admin page.
			if ( ! is_admin() ) {
				return false;
			}

			// Global include files.
			$files = array(
				'ot-functions-admin',
				'ot-functions-option-types',
				'ot-functions-compat',
				'class-ot-settings',
			);

			// Include the meta box api.
			if ( true === OT_META_BOXES ) {
				$files[] = 'class-ot-meta-box';
			}

			// Include the post formats api.
			if ( true === OT_META_BOXES && true === OT_POST_FORMATS ) {
				$files[] = 'class-ot-post-formats';
			}

			// Include the settings & docs pages.
			if ( true === OT_SHOW_PAGES ) {
				$files[] = 'ot-functions-settings-page';
				$files[] = 'ot-functions-docs-page';
			}

			// Include the cleanup api.
			$files[] = 'class-ot-cleanup';

			// Require the files.
			foreach ( $files as $file ) {
				$this->load_file( OT_DIR . 'includes' . DIRECTORY_SEPARATOR . "{$file}.php" );
			}

			// Registers the Theme Option page.
			add_action( 'init', 'ot_register_theme_options_page' );

			// Registers the Settings page.
			if ( true === OT_SHOW_PAGES ) {
				add_action( 'init', 'ot_register_settings_page' );

				// Global CSS.
				add_action( 'admin_head', array( $this, 'global_admin_css' ) );
			}
		}

		/**
		 * Include front-end files.
		 *
		 * These functions are included on every page load
		 * incase other plugins need to access them.
		 *
		 * @access private
		 * @since  2.0
		 */
		private function includes() {

			$files = array(
				'ot-functions',
				'ot-functions-deprecated',
			);

			// Require the files.
			foreach ( $files as $file ) {
				$this->load_file( OT_DIR . 'includes' . DIRECTORY_SEPARATOR . "{$file}.php" );
			}
		}

		/**
		 * Execute the WordPress Hooks.
		 *
		 * @access public
		 * @since 2.0
		 */
		private function hooks() {

			// Attempt to migrate the settings.
			if ( function_exists( 'ot_maybe_migrate_settings' ) ) {
				add_action( 'init', 'ot_maybe_migrate_settings', 1 );
			}

			// Attempt to migrate the Options.
			if ( function_exists( 'ot_maybe_migrate_options' ) ) {
				add_action( 'init', 'ot_maybe_migrate_options', 1 );
			}

			// Attempt to migrate the Layouts.
			if ( function_exists( 'ot_maybe_migrate_layouts' ) ) {
				add_action( 'init', 'ot_maybe_migrate_layouts', 1 );
			}

			// Load the Meta Box assets.
			if ( true === OT_META_BOXES ) {

				// Add scripts for metaboxes to post-new.php & post.php.
				add_action( 'admin_print_scripts-post-new.php', 'ot_admin_scripts', 11 );
				add_action( 'admin_print_scripts-post.php', 'ot_admin_scripts', 11 );

				// Add styles for metaboxes to post-new.php & post.php.
				add_action( 'admin_print_styles-post-new.php', 'ot_admin_styles', 11 );
				add_action( 'admin_print_styles-post.php', 'ot_admin_styles', 11 );

			}

			// Adds the Theme Option page to the admin bar.
			add_action( 'admin_bar_menu', 'ot_register_theme_options_admin_bar_menu', 999 );

			// Prepares the after save do_action.
			add_action( 'admin_init', 'ot_after_theme_options_save', 1 );

			// default settings.
			add_action( 'admin_init', 'ot_default_settings', 2 );

			// Import.
			add_action( 'admin_init', 'ot_import', 4 );

			// Export.
			add_action( 'admin_init', 'ot_export', 5 );

			// Save settings.
			add_action( 'admin_init', 'ot_save_settings', 6 );

			// Save layouts.
			add_action( 'admin_init', 'ot_modify_layouts', 7 );

			// Create media post.
			add_action( 'admin_init', 'ot_create_media_post', 8 );

			// Google Fonts front-end CSS.
			// add_action( 'wp_enqueue_scripts', 'ot_load_google_fonts_css', 1 );

			// Dynamic front-end CSS.
			add_action( 'wp_enqueue_scripts', 'ot_load_dynamic_css', 999 );

			// Insert theme CSS dynamically.
			add_action( 'ot_after_theme_options_save', 'ot_save_css' );

			// AJAX call to create a new section.
			add_action( 'wp_ajax_add_section', array( $this, 'add_section' ) );

			// AJAX call to create a new setting.
			add_action( 'wp_ajax_add_setting', array( $this, 'add_setting' ) );

			// AJAX call to create a new contextual help.
			add_action( 'wp_ajax_add_the_contextual_help', array( $this, 'add_the_contextual_help' ) );

			// AJAX call to create a new choice.
			add_action( 'wp_ajax_add_choice', array( $this, 'add_choice' ) );

			// AJAX call to create a new list item setting.
			add_action( 'wp_ajax_add_list_item_setting', array( $this, 'add_list_item_setting' ) );

			// AJAX call to create a new layout.
			add_action( 'wp_ajax_add_layout', array( $this, 'add_layout' ) );

			// AJAX call to create a new list item.
			add_action( 'wp_ajax_add_list_item', array( $this, 'add_list_item' ) );

			// AJAX call to create a new social link.
			add_action( 'wp_ajax_add_social_links', array( $this, 'add_social_links' ) );

			// AJAX call to retrieve Google Font data.
			add_action( 'wp_ajax_ot_google_font', array( $this, 'retrieve_google_font' ) );

			// Adds the temporary hacktastic shortcode.
			add_filter( 'media_view_settings', array( $this, 'shortcode' ), 10, 2 );

			// AJAX update.
			add_action( 'wp_ajax_gallery_update', array( $this, 'ajax_gallery_update' ) );

			// Modify the media uploader button.
			add_filter( 'gettext', array( $this, 'change_image_button' ), 10, 3 );
		}

		/**
		 * Load a file.
		 *
		 * @access private
		 * @since  2.0.15
		 *
		 * @param string $file Path to the file being included.
		 */
		private function load_file( $file ) {
			include_once $file;
		}

		/**
		 * Adds CSS for the menu icon.
		 */
		public function global_admin_css() {
			?>
<style>
	@font-face {
		font-family: "option-tree-font";
		src:url("<?php echo esc_url_raw( OT_URL ); ?>assets/fonts/option-tree-font.eot");
		src:url("<?php echo esc_url_raw( OT_URL ); ?>assets/fonts/option-tree-font.eot?#iefix") format("embedded-opentype"),
			url("<?php echo esc_url_raw( OT_URL ); ?>assets/fonts/option-tree-font.woff") format("woff"),
			url("<?php echo esc_url_raw( OT_URL ); ?>assets/fonts/option-tree-font.ttf") format("truetype"),
			url("<?php echo esc_url_raw( OT_URL ); ?>assets/fonts/option-tree-font.svg#option-tree-font") format("svg");
		font-weight: normal;
		font-style: normal;
	}
	#adminmenu #toplevel_page_ot-settings .menu-icon-generic div.wp-menu-image:before {
		font: normal 20px/1 "option-tree-font" !important;
		speak: none;
		padding: 6px 0;
		height: 34px;
		width: 20px;
		display: inline-block;
		-webkit-font-smoothing: antialiased;
		-moz-osx-font-smoothing: grayscale;
		-webkit-transition: all .1s ease-in-out;
		-moz-transition:    all .1s ease-in-out;
		transition:         all .1s ease-in-out;
	}
	#adminmenu #toplevel_page_ot-settings .menu-icon-generic div.wp-menu-image:before {
		content: "\e785";
	}
</style>
			<?php
		}

		/**
		 * AJAX utility function for adding a new section.
		 */
		public function add_section() {
			check_ajax_referer( 'option_tree', 'nonce' );

			$count  = isset( $_REQUEST['count'] ) ? absint( $_REQUEST['count'] ) : 0;
			$output = ot_sections_view( ot_settings_id() . '[sections]', $count );

			echo $output; // phpcs:ignore
			wp_die();
		}

		/**
		 * AJAX utility function for adding a new setting.
		 */
		public function add_setting() {
			check_ajax_referer( 'option_tree', 'nonce' );

			$name   = isset( $_REQUEST['name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['name'] ) ) : '';
			$count  = isset( $_REQUEST['count'] ) ? absint( $_REQUEST['count'] ) : 0;
			$output = ot_settings_view( $name, $count );

			echo $output; // phpcs:ignore
			wp_die();
		}

		/**
		 * AJAX utility function for adding a new list item setting.
		 */
		public function add_list_item_setting() {
			check_ajax_referer( 'option_tree', 'nonce' );

			$name   = isset( $_REQUEST['name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['name'] ) ) : '';
			$count  = isset( $_REQUEST['count'] ) ? absint( $_REQUEST['count'] ) : 0;
			$output = ot_settings_view( $name . '[settings]', $count );

			echo $output; // phpcs:ignore
			wp_die();
		}

		/**
		 * AJAX utility function for adding new contextual help content.
		 */
		public function add_the_contextual_help() {
			check_ajax_referer( 'option_tree', 'nonce' );

			$name   = isset( $_REQUEST['name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['name'] ) ) : '';
			$count  = isset( $_REQUEST['count'] ) ? absint( $_REQUEST['count'] ) : 0;
			$output = ot_contextual_help_view( $name, $count );

			echo $output; // phpcs:ignore
			wp_die();
		}

		/**
		 * AJAX utility function for adding a new choice.
		 */
		public function add_choice() {
			check_ajax_referer( 'option_tree', 'nonce' );

			$name   = isset( $_REQUEST['name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['name'] ) ) : '';
			$count  = isset( $_REQUEST['count'] ) ? absint( $_REQUEST['count'] ) : 0;
			$output = ot_choices_view( $name, $count );

			echo $output; // phpcs:ignore
			wp_die();
		}

		/**
		 * AJAX utility function for adding a new layout.
		 */
		public function add_layout() {
			check_ajax_referer( 'option_tree', 'nonce' );

			$count  = isset( $_REQUEST['count'] ) ? absint( $_REQUEST['count'] ) : 0;
			$output = ot_layout_view( $count );

			echo $output; // phpcs:ignore
			wp_die();
		}

		/**
		 * AJAX utility function for adding a new list item.
		 */
		public function add_list_item() {
			check_ajax_referer( 'option_tree', 'nonce' );

			$name       = isset( $_REQUEST['name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['name'] ) ) : '';
			$count      = isset( $_REQUEST['count'] ) ? absint( $_REQUEST['count'] ) : 0;
			$post_id    = isset( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : 0;
			$get_option = isset( $_REQUEST['get_option'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['get_option'] ) ) : '';
			$type       = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';
			$settings   = isset( $_REQUEST['settings'] ) ? ot_decode( sanitize_text_field( wp_unslash( $_REQUEST['settings'] ) ) ) : array();

			ot_list_item_view( $name, $count, array(), $post_id, $get_option, $settings, $type );
			wp_die();
		}

		/**
		 * AJAX utility function for adding a new social link.
		 */
		public function add_social_links() {
			check_ajax_referer( 'option_tree', 'nonce' );

			$name       = isset( $_REQUEST['name'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['name'] ) ) : '';
			$count      = isset( $_REQUEST['count'] ) ? absint( $_REQUEST['count'] ) : 0;
			$post_id    = isset( $_REQUEST['post_id'] ) ? absint( $_REQUEST['post_id'] ) : 0;
			$get_option = isset( $_REQUEST['get_option'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['get_option'] ) ) : '';
			$type       = isset( $_REQUEST['type'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['type'] ) ) : '';
			$settings   = isset( $_REQUEST['settings'] ) ? ot_decode( sanitize_text_field( wp_unslash( $_REQUEST['settings'] ) ) ) : array();

			ot_social_links_view( $name, $count, array(), $post_id, $get_option, $settings, $type );
			wp_die();
		}

		/**
		 * Fake the gallery shortcode.
		 *
		 * The JS takes over and creates the actual shortcode with
		 * the real attachment IDs on the fly. Here we just need to
		 * pass in the post ID to get the ball rolling.
		 *
		 * @access public
		 * @since  2.2.0
		 *
		 * @param  array  $settings The current settings.
		 * @param  object $post     The post object.
		 * @return array
		 */
		public function shortcode( $settings, $post ) {
			global $pagenow;

			if ( in_array( $pagenow, array( 'upload.php', 'customize.php' ), true ) ) {
				return $settings;
			}

			// Set the OptionTree post ID.
			if ( ! is_object( $post ) ) {
				$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : ( isset( $_GET['post_ID'] ) ? absint( $_GET['post_ID'] ) : 0 ); // phpcs:ignore
				if ( 0 >= $post_id && function_exists( 'ot_get_media_post_ID' ) ) {
					$post_id = ot_get_media_post_ID();
				}
				$settings['post']['id'] = $post_id;
			}

			// No ID return settings.
			if ( 0 >= $settings['post']['id'] ) {
				return $settings;
			}

			// Set the fake shortcode.
			$settings['ot_gallery'] = array( 'shortcode' => "[gallery id='{$settings['post']['id']}']" );

			// Return settings.
			return $settings;
		}

		/**
		 * AJAX to generate HTML for a list of gallery images.
		 *
		 * @access public
		 * @since  2.2.0
		 */
		public function ajax_gallery_update() {
			check_ajax_referer( 'option_tree', 'nonce' );

			if ( ! empty( $_POST['ids'] ) && is_array( $_POST['ids'] ) ) {

				$html = '';
				$ids  = array_filter( $_POST['ids'], 'absint' ); // phpcs:ignore

				foreach ( $ids as $id ) {

					$thumbnail = wp_get_attachment_image_src( $id, 'thumbnail' );

					$html .= '<li><img  src="' . esc_url_raw( $thumbnail[0] ) . '" width="75" height="75" /></li>';
				}

				echo $html; // phpcs:ignore
			}

			wp_die();
		}

		/**
		 * The JSON encoded Google fonts data, or false if it cannot be encoded.
		 *
		 * @access public
		 * @since  2.5.0
		 */
		public function retrieve_google_font() {
			check_ajax_referer( 'option_tree', 'nonce' );

			if ( isset( $_POST['field_id'], $_POST['family'] ) ) {

				ot_fetch_google_fonts();

				$field_id = isset( $_POST['field_id'] ) ? sanitize_text_field( wp_unslash( $_POST['field_id'] ) ) : '';
				$family   = isset( $_POST['family'] ) ? sanitize_text_field( wp_unslash( $_POST['family'] ) ) : '';
				$html     = wp_json_encode(
					array(
						'variants' => ot_recognized_google_font_variants( $field_id, $family ),
						'subsets'  => ot_recognized_google_font_subsets( $field_id, $family ),
					)
				);

				echo $html; // phpcs:ignore
			}

			wp_die();
		}

		/**
		 * Filters the media uploader button.
		 *
		 * @access public
		 * @since  2.1
		 *
		 * @param string $translation Translated text.
		 * @param string $text        Text to translate.
		 * @param string $domain      Text domain. Unique identifier for retrieving translated strings.
		 *
		 * @return string
		 */
		public function change_image_button( $translation, $text, $domain ) {
			global $pagenow;

			if ( apply_filters( 'ot_theme_options_parent_slug', 'themes.php' ) === $pagenow && 'default' === $domain && 'Insert into post' === $text ) {

				// Once is enough.
				remove_filter( 'gettext', array( $this, 'ot_change_image_button' ) );
				return apply_filters( 'ot_upload_text', esc_html__( 'Pilih Gambar', 'option-tree' ) );

			}

			return $translation;
		}
	}

	/**
	 * Instantiate the OptionTree loader class.
	 *
	 * @since 2.0
	 */
	new OT_Loader();
}

function themefood_send_wa_msg($phone,$key,$msg,$api) {
	$data = array(
		"phone_no" => '+'.$phone,
		"key" => $key,
		"message" => html_entity_decode($msg, ENT_QUOTES, "UTF-8")
		);
	$data_string = json_encode($data);
	$ch = curl_init($api);
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST"); 
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    	
	curl_setopt($ch, CURLOPT_VERBOSE, 0);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
	curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		'Content-Type: application/json',
		'Content-Length: ' . strlen($data_string)
		) 
	); 
	$result = curl_exec($ch);		
}
				
// Custom Post Type & Taxonomy
add_action( 'init', 'themefood_register_post_type' );
function themefood_register_post_type() {
	if(is_valid_page()):
    $produk = array(
        'labels' => array(  
			'name' => __( 'Katalog Produk' , 'themefood' ),
			'singular_name' => __( 'Katalog Produk' , 'themefood' ), 
			'add_new' => __( 'Tambah Produk' , 'themefood' ),
			'add_new_item' => __( 'Tambah Produk Baru' , 'themefood' ), 
			'edit_item' => __( 'Edit Produk' , 'themefood' ),
			'new_item' => __( 'Tambah Produk' , 'themefood' ),
			'view_item' => __( 'Lihat Produk' , 'themefood' ),
			'search_items' => __( 'Cari Produk' , 'themefood' ),
			'not_found' =>  __( 'Produk tidak ditemukan' , 'themefood' ),
			'not_found_in_trash' => __( 'Tidak ada katalog produk di tempat sampah' , 'themefood' ),
		),
        'has_archive' => true,
        'public' => true,
        'hierarchical' => false,
		'menu_position' => 29,
		'menu_icon' => 'dashicons-food',
		'taxonomies' => array( 'kategori_produk'),
        'supports' => array(
            'title', 
            'editor', 
            'custom-fields', 
            'thumbnail',
        ),
        'rewrite'   => array( 'slug' => 'produk' ),
        'show_in_rest' => true
    );
	endif;
    $info = array(
        'labels' => array(  
			'name' => __( 'Info Toko' , 'themefood' ),
			'singular_name' => __( 'Info Toko' , 'themefood' ), 
			'add_new' => __( 'Tambah Info' , 'themefood' ),
			'add_new_item' => __( 'Tambah Info Baru' , 'themefood' ), 
			'edit_item' => __( 'Edit Info' , 'themefood' ),
			'new_item' => __( 'Tambah Info' , 'themefood' ),
			'view_item' => __( 'Lihat Info' , 'themefood' ),
			'search_items' => __( 'Cari Info' , 'themefood' ),
			'not_found' =>  __( 'Info tidak ditemukan' , 'themefood' ),
			'not_found_in_trash' => __( 'Tidak ada info toko di tempat sampah' , 'themefood' ),
		),
        'has_archive' => true,
        'public' => true,
        'hierarchical' => false,
		'menu_position' => 29,
		'menu_icon' => 'dashicons-megaphone',
        'supports' => array(
            'title', 
            'editor', 
            'thumbnail',
			'author'
        ),
        'rewrite'   => array( 'slug' => 'info' ),
        'show_in_rest' => true
    );
    $slider = array(
        'labels' => array(  
			'name' => __( 'Slider Promo' , 'themefood' ),
			'singular_name' => __( 'Slider Promo' , 'themefood' ), 
			'add_new' => __( 'Tambah Slider' , 'themefood' ),
			'add_new_item' => __( 'Tambah Slider Baru' , 'themefood' ), 
			'edit_item' => __( 'Edit Slider' , 'themefood' ),
			'new_item' => __( 'Tambah Slider' , 'themefood' ),
			'view_item' => __( 'Lihat Slider' , 'themefood' ),
			'search_items' => __( 'Cari Slider' , 'themefood' ),
			'not_found' =>  __( 'Slider tidak ditemukan' , 'themefood' ),
			'not_found_in_trash' => __( 'Tidak ada slider di tempat sampah' , 'themefood' ),
		),
        'has_archive' => true,
        'public' => true,
        'hierarchical' => false,
		'menu_position' => 29,
		'menu_icon' => 'dashicons-format-gallery',
        'supports' => array(
            'title', 
            'thumbnail',
        ),
        'rewrite'   => array( 'slug' => 'slider' ),
        'show_in_rest' => true
    );
    $kupon = array(
        'labels' => array(  
			'name' => __( 'Kupon Diskon' , 'themefood' ),
			'singular_name' => __( 'Kupon Diskon' , 'themefood' ), 
			'add_new' => __( 'Tambah Kupon' , 'themefood' ),
			'add_new_item' => __( 'Tambah Kupon Baru' , 'themefood' ), 
			'edit_item' => __( 'Edit Kupon' , 'themefood' ),
			'new_item' => __( 'Tambah Kupon' , 'themefood' ),
			'view_item' => __( 'Lihat Kupon' , 'themefood' ),
			'search_items' => __( 'Cari Kupon' , 'themefood' ),
			'not_found' =>  __( 'Kupon tidak ditemukan' , 'themefood' ),
			'not_found_in_trash' => __( 'Tidak ada kupon diskon di tempat sampah' , 'themefood' ),
		),
        'has_archive' => false,
        'public' => true,
        'hierarchical' => false,
		'menu_position' => 29,
		'menu_icon' => 'dashicons-tag',
        'supports' => array(
            'title'
        ),
        'rewrite'   => array( 'slug' => 'kupon' ),
        'show_in_rest' => true
    );
    $cs = array(
        'labels' => array(  
			'name' => __( 'CS WhatsApp' , 'themefood' ),
			'singular_name' => __( 'CS WhatsApp' , 'themefood' ), 
			'add_new' => __( 'Tambah CS' , 'themefood' ),
			'add_new_item' => __( 'Tambah CS Baru' , 'themefood' ), 
			'edit_item' => __( 'Edit CS' , 'themefood' ),
			'new_item' => __( 'Tambah CS' , 'themefood' ),
			'view_item' => __( 'Lihat CS' , 'themefood' ),
			'search_items' => __( 'Cari CS' , 'themefood' ),
			'not_found' =>  __( 'CS tidak ditemukan' , 'themefood' ),
			'not_found_in_trash' => __( 'Tidak ada cs whatsapp di tempat sampah' , 'themefood' ),
		),
        'has_archive' => false,
        'public' => true,
        'hierarchical' => false,
		'menu_position' => 29,
		'menu_icon' => 'dashicons-whatsapp',
        'supports' => array(
            'title',
            'thumbnail',
        ),
        'rewrite'   => array( 'slug' => 'cs' ),
        'show_in_rest' => true
    );
    $order = array(
        'labels' => array(  
			'name' => __( 'Orderan' , 'themefood' ),
			'singular_name' => __( 'Orderan' , 'themefood' ), 
			'add_new' => __( 'Orderan Baru' , 'themefood' ),
			'add_new_item' => __( 'Tambah Orderan Baru' , 'themefood' ), 
			'edit_item' => __( 'Edit Orderan' , 'themefood' ),
			'new_item' => __( 'Orderan Baru' , 'themefood' ),
			'view_item' => __( 'Lihat Orderan' , 'themefood' ),
			'search_items' => __( 'Cari Orderan' , 'themefood' ),
			'not_found' =>  __( 'Orderan tidak ditemukan' , 'themefood' ),
			'not_found_in_trash' => __( 'Tidak ada orderan di tempat sampah' , 'themefood' ),
		),
        'has_archive' => true,
        'public' => true,
        'hierarchical' => false,
		'menu_position' => 29,
		'menu_icon' => 'dashicons-store',
        'supports' => array(
            'title'
        ),
		'capabilities' => array(
			'create_posts' => 'do_not_allow',
		),
		'map_meta_cap' => true,
        'rewrite'   => array( 'slug' => 'orderan' ),
        'show_in_rest' => true
    );
    $kat_produk = array(
        'labels' => array(  
			'name' => __( 'Kategori Produk' , 'themefood' ),
			'singular_name' => __( 'Kategori Produk' , 'themefood' ), 
			'add_new' => __( 'Kategori Produk Baru' , 'themefood' ),
			'add_new_item' => __( 'Tambah Kategori Baru' , 'themefood' ), 
			'edit_item' => __( 'Edit Kategori Produk' , 'themefood' ),
			'new_item' => __( 'Kategori Produk Baru' , 'themefood' ),
			'view_item' => __( 'Lihat Kategori Produk' , 'themefood' ),
			'search_items' => __( 'Cari Kategori Produk' , 'themefood' ),
			'not_found' =>  __( 'Kategori Produk tidak ditemukan' , 'themefood' ),
			'not_found_in_trash' => __( 'Tidak ada kategori produk di tempat sampah' , 'themefood' ),
		),
        'has_archive' => true,
        'public' => true,
        'hierarchical' => true,
        'rewrite'   => array( 'slug' => 'katalog' ),
        'show_in_rest' => true
    );
    $order_status = array(
        'labels' => array(  
			'name' => __( 'Status Order' , 'themefood' ),
			'singular_name' => __( 'Status Order' , 'themefood' ), 
			'add_new' => __( 'Status Order Baru' , 'themefood' ),
			'add_new_item' => __( 'Tambah Status Baru' , 'themefood' ), 
			'edit_item' => __( 'Edit Status Order' , 'themefood' ),
			'new_item' => __( 'Status Order Baru' , 'themefood' ),
			'view_item' => __( 'Lihat Status Order' , 'themefood' ),
			'search_items' => __( 'Cari Status Order' , 'themefood' ),
			'not_found' =>  __( 'Status Order tidak ditemukan' , 'themefood' ),
			'not_found_in_trash' => __( 'Tidak ada status order di tempat sampah' , 'themefood' ),
		),
        'public' => true,
        'hierarchical' => true,
		'show_in_menu' => false,
		'meta_box_cb' => 'themefood_status_meta_box',
        'show_in_rest' => true
    );
	if(is_valid_page()):	
	register_post_type( 'tf-produk', $produk );
	register_post_type( 'tf-info', $info );
	register_post_type( 'tf-slider', $slider );
	register_post_type( 'tf-kupon', $kupon );
	register_post_type( 'tf-cs', $cs );
	register_post_type( 'tf-order', $order );
	register_taxonomy( 'kategori_produk', 'tf-produk', $kat_produk );
	register_taxonomy( 'order_status', 'tf-order', $order_status );	
	// Default Order Status 
	$parent_term = term_exists( 'order_status', 'order_status' );
	wp_insert_term( 'Diterima', 'order_status', array( 'slug' => 'diterima' ));
	wp_insert_term( 'Diproses', 'order_status', array( 'slug' => 'diproses' ));
	wp_insert_term( 'Diantar', 'order_status', array( 'slug' => 'diantar' ));
	wp_insert_term( 'Selesai', 'order_status', array( 'slug' => 'selesai' ));
	wp_insert_term( 'Dibatalkan', 'order_status', array( 'slug' => 'dibatalkan' ));
	endif;
}

// Disable Gutenberg
add_filter('gutenberg_can_edit_post_type', 'prefix_disable_gutenberg', 10, 2);
add_filter('use_block_editor_for_post_type', 'prefix_disable_gutenberg', 10, 2);
function prefix_disable_gutenberg($current_status, $post_type)
{
    if (in_array($post_type, ['tf-produk','tf-info','tf-slider','tf-kupon','tf-cs','tf-order'], true )) return false;
    return $current_status;
}

// Meta Box CPT
add_action( 'admin_init', 'themefood_metabox' );
function themefood_metabox() {
    add_meta_box( 'themefood_produk_meta_box', 'Detail Produk', 'themefood_produk_meta_box', 'tf-produk', 'normal', 'high' );
	add_meta_box( 'themefood_extra_meta_box', 'Variasi Produk', 'themefood_extra_meta_box', 'tf-produk', 'normal', 'default');
	add_meta_box( 'themefood_gallery_meta_box', 'Galeri Produk', 'themefood_gallery_meta_box', 'tf-produk', 'side', 'default');
	add_meta_box( 'themefood_slider_meta_box', 'Target Slide', 'themefood_slider_meta_box', 'tf-slider', 'normal', 'default');
	add_meta_box( 'themefood_kupon_meta_box', 'Kupon Diskon', 'themefood_kupon_meta_box', 'tf-kupon', 'normal', 'default');
	add_meta_box( 'themefood_cs_meta_box', 'Profil CS', 'themefood_cs_meta_box', 'tf-cs', 'normal', 'default');
	add_meta_box( 'themefood_jadwalcs_meta_box', 'Jadwal CS', 'themefood_jadwalcs_meta_box', 'tf-cs', 'normal', 'default');
	add_meta_box( 'themefood_order_meta_box', 'Detail Orderan', 'themefood_order_meta_box', 'tf-order', 'normal', 'default');
	add_meta_box( 'themefood_order_produk_meta_box', 'Daftar Pesanan', 'themefood_order_produk_meta_box', 'tf-order', 'normal', 'default');
	add_meta_box( 'themefood_order_cs_meta_box', 'Follow-Up Oleh', 'themefood_order_cs_meta_box', 'tf-order', 'side', 'default');
    add_meta_box( 'themefood_emoji_meta_box', 'Copy Emoji', 'themefood_emoji_meta_box', ['tf-produk','tf-info'], 'side', 'default' );
}
add_action( 'save_post', 'add_cpt_post_fields', 10, 2 );
function add_cpt_post_fields( $post_id, $post ) {
    if ( in_array($post->post_type, ['tf-produk','tf-slider','tf-kupon','tf-cs','tf-order'], true ) ) {
        if ( isset( $_POST['meta'] ) ) {
            foreach( $_POST['meta'] as $key => $value ){
                update_post_meta( $post_id, $key, $value );
            }
        }
    }
	if (is_plugin_active('wp-rest-cache/wp-rest-cache.php')){
		if ( $post->post_type == 'tf-produk' ) {
			\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-produk?per_page=1000' );		
			\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/kategori_produk?per_page=100' );
		}
		if ( $post->post_type == 'tf-slider' ) \WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-slider?per_page=1000' );
		if ( $post->post_type == 'tf-kupon' ) \WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-kupon?per_page=1000' );
		if ( $post->post_type == 'tf-info' ) \WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-info?per_page=1000' );
		if ( $post->post_type == 'tf-cs' ) \WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-cs?per_page=1000' );
		if ( $post->post_type == 'tf-order' ) \WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-order?per_page=10' );
    }
}

function themefood_produk_meta_box($post) {
	$stok_produk = get_post_meta($post->ID, 'stok_produk', true);
	if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
	}
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function( $ ){	
		$('[name="meta[harga_produk]"], [name="meta[harga_diskon]"]').on('keyup input', function (e) {
			if($('[name="meta[harga_produk]"]').val() !== '' && $('[name="meta[harga_diskon]"]').val() !== '') {
				if (parseInt($('[name="meta[harga_produk]"]').val()) > parseInt($('[name="meta[harga_diskon]"]').val())) {
					$('[name="meta[harga_diskon]"]').css("border-color","#7e8993");
					$('[name="meta[harga_diskon]"] + small').css("color","#444");
					$('#publish').attr("disabled",false);
				} else {
					$('[name="meta[harga_diskon]"]').css("border-color","red");
					$('[name="meta[harga_diskon]"] + small').css("color","red");
					$('#publish').attr("disabled",true);
				}
			} else {
				$('[name="meta[harga_diskon]"]').css("border-color","#7e8993");
				$('[name="meta[harga_diskon]"] + small').css("color","#444");
				$('#publish').attr("disabled",false);
			}
		});	
    });
  </script>
    <table>
        <tr>
            <td style="width: 25%">Stok Produk</td>
            <td>
				<select name="meta[stok_produk]">
				  <option value="readystock" <?php selected( $stok_produk, 'readystock' ); ?>>Stok Tersedia</option>
				  <option value="outofstock" <?php selected( $stok_produk, 'outofstock' ); ?>>Stok Habis</option>
				</select>
				<small>Status stok produk</small>
            </td>
        </tr>
        <tr>
            <td style="width: 25%">Harga Produk (<?=$currency_sym?>)</td>
            <td>
				<input type="number" name="meta[harga_produk]" placeholder="0" value="<?php echo esc_html( get_post_meta( $post->ID, 'harga_produk', true ) );?>" />
				<small>Harga produk dalam <?=$currency_sym?></small>
            </td>
        </tr>
        <tr>
            <td>Harga Diskon (<?=$currency_sym?>)</td>
            <td>
				<input type="number" name="meta[harga_diskon]" placeholder="0" value="<?php echo esc_html( get_post_meta( $post->ID, 'harga_diskon', true ) );?>" />
				<small>Harus lebih murah dari harga produk</small>
            </td>
        </tr>
        <tr>
            <td>Berat Produk (g)</td>
            <td>
				<input type="number" name="meta[berat_produk]" placeholder="1000" value="<?php echo esc_html( get_post_meta( $post->ID, 'berat_produk', true ) );?>" />
				<small>Opsional. Berat default 1000g.</small>
            </td>
        </tr>
    </table>
<?php 
}

// Extra Variasi Produk
function themefood_extra_meta_box($post) {
    $variasi_group = get_post_meta($post->ID, 'variasi_group', true);
     wp_nonce_field( 'variasi_repeatable_meta_box_nonce', 'variasi_repeatable_meta_box_nonce' );	 
	if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
	}
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function( $ ){
        $( '#add-row' ).on('click', function() {
            var row = $( '.empty-row.screen-reader-text' ).clone(true);
            row.removeClass( 'empty-row screen-reader-text' );
            row.insertBefore( '#variasi-fieldset-one tbody>tr:last' );
            return false;
        });
        $( '.remove-row' ).on('click', function() {
            $(this).parents('tr').remove();
            return false;
        });
    });
  </script>
  <table id="variasi-fieldset-one">
  <tbody>
    <?php
     if ( $variasi_group ) :
      foreach ( $variasi_group as $field ) {
    ?>
    <tr>
      <td>
		<label>Nama Variasi</label>
        <input type="text" placeholder="Nama Variasi" name="nama_variasi[]" value="<?php if($field['nama_variasi'] != '') echo esc_attr( $field['nama_variasi'] ); ?>" />
	  </td> 
      <td>
		<label>Harga Variasi (<?=$currency_sym?>)</label>
		<input type="number" placeholder="0" name="harga_variasi[]" value="<?php if ($field['harga_variasi'] != '') echo esc_attr( $field['harga_variasi'] ); ?>" />
	  </td>
      <td>
		<label></label>
		<a class="button remove-row" href="#">Hapus</a>
	  </td>
    </tr>
    <?php
    }
    else :
    ?>
    <tr>
      <td> 
		<label>Nama Variasi</label>
        <input type="text" placeholder="Nama Variasi" title="Title" name="nama_variasi[]" /></td>
      <td> 
		<label>Harga Variasi (<?=$currency_sym?>)</label>
		<input type="number" placeholder="0" name="harga_variasi[]" />  
	  </td>
      <td>
		<label></label>
		<a class="button cmb-remove-row-button button-disabled">Hapus</a>
	  </td>
    </tr>
    <?php endif; ?>
    <tr class="empty-row screen-reader-text">
      <td>
		<label>Nama Variasi</label>
        <input type="text" placeholder="Nama Variasi" name="nama_variasi[]"/></td>
      <td>
		<label>Harga Variasi (<?=$currency_sym?>)</label>
		<input type="number" placeholder="0" name="harga_variasi[]"/>
	  </td>
      <td>
		<label></label>
		<a class="button remove-row" href="#">Hapus</a>
	  </td>
    </tr>
  </tbody>
</table>
<p><a id="add-row" class="button" href="#">Tambah variasi lain</a></p>
<?php
}
add_action('save_post', 'variasi_meta_box_save');
function variasi_meta_box_save($post_id) {
    if ( ! isset( $_POST['variasi_repeatable_meta_box_nonce'] ) ||
    ! wp_verify_nonce( $_POST['variasi_repeatable_meta_box_nonce'], 'variasi_repeatable_meta_box_nonce' ) )
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    $old = get_post_meta($post_id, 'variasi_group', true);
    $new = array();
    $nama_variasi = $_POST['nama_variasi'];
    $harga_variasi = $_POST['harga_variasi'];
     $count = count( $nama_variasi );
     for ( $i = 0; $i < $count; $i++ ) {
        if ( $nama_variasi[$i] != '' ) :
            $new[$i]['nama_variasi'] = stripslashes( strip_tags( $nama_variasi[$i] ) );
            $new[$i]['harga_variasi'] = stripslashes( $harga_variasi[$i] );
        endif;
    }
    if ( !empty( $new ) && $new != $old )
        update_post_meta( $post_id, 'variasi_group', $new );
    elseif ( empty($new) && $old )
        delete_post_meta( $post_id, 'variasi_group', $old );
}

function themefood_gallery_uploader_field( $name, $value = '' ) {
    $image = 'Upload Image';
    $button = 'button';
    $image_size = 'full';
    $display = 'none';     
    ?>     
    <p><?php esc_html_e( 'Tambahkan gambar galeri produk', 'themefood' );?></p>     
    <label>
        <div class="gallery-screenshot clearfix">
            <?php
            {
                $ids = explode(',', $value);
                foreach ($ids as $attachment_id) {
                    $img = wp_get_attachment_image_src($attachment_id, 'thumbnail');
                    echo '<div class="screen-thumb"><img src="' . esc_url($img[0]) . '" /></div>';
                }
            }
            ?>
        </div>         
        <input id="edit-gallery" class="button upload_gallery_button" type="button"
               value="<?php esc_html_e('Tambah/Edit', 'themefood') ?>"/>
        <input id="clear-gallery" class="button upload_gallery_button" type="button"
               value="<?php esc_html_e('Reset Galeri', 'themefood') ?>"/>
        <input type="hidden" name="<?php echo esc_attr($name); ?>" id="<?php echo esc_attr($name); ?>" class="gallery_values" value="<?php echo esc_attr($value); ?>">
    </label>
<?php   
}

function themefood_gallery_meta_box($post) {
	wp_nonce_field( 'save_feat_gallery', 'themefood_feat_gallery_nonce' );     
    $meta_key = 'galeri_produk';
    echo themefood_gallery_uploader_field( $meta_key, get_post_meta($post->ID, $meta_key, true) );
}

add_action('save_post', 'themefood_img_gallery_save');
function themefood_img_gallery_save($post_id) {     
    if ( !isset( $_POST['themefood_feat_gallery_nonce'] ) ) {
        return $post_id;
    }     
    if ( !wp_verify_nonce( $_POST['themefood_feat_gallery_nonce'], 'save_feat_gallery') ) {
        return $post_id;
    }      
    if ( isset( $_POST[ 'galeri_produk' ] ) ) {
        update_post_meta( $post_id, 'galeri_produk', esc_attr($_POST['galeri_produk']) );
    } else {
        update_post_meta( $post_id, 'galeri_produk', '' );
    }     
}

function themefood_slider_meta_box($post) {
	$items = get_posts( array (  
		'post_type' => ['tf-produk','tf-info'],  
		'posts_per_page' => -1,
		'post_status' => 'publish' 
	)); 
	$terms = get_terms([
		'taxonomy' => 'kategori_produk',
		'hide_empty' => true,
	]);
	$slider_target = get_post_meta($post->ID, 'slider_target', true);
    ?>
	<script type="text/javascript">
		jQuery(document).ready(function($){	
			$('#slider_target').select2();
		});
	</script>
    <table>
        <tr>
            <td>
				<select name='meta[slider_target]' id='slider_target'>
					<option value="">Tidak ada target</option>
					<?php   
					foreach($items as $item) {  
						echo '<option value="'.$item->post_type.'-'.$item->ID.'" '.selected( $slider_target, $item->post_type.'-'.$item->ID ).'>'.(($item->post_type === 'tf-produk') ? "PRODUK" : "INFO").' - '.$item->post_title.'</option>';  
					}
					foreach($terms as $term) {  
						echo '<option value="tf-kategori-'.$term->slug.'" '.selected( $slider_target, 'tf-kategori-'.$term->slug ).'>KATEGORI - '.$term->name.'</option>';  
					}
					?> 
				</select>
            </td>
        </tr>
    </table>
<?php 
}

function themefood_kupon_meta_box($post) {
	$tipe_kupon = get_post_meta($post->ID, 'tipe_kupon', true);
    ?>  
  <div id="kode_kupon">
	<input type="text" name="meta[kode_kupon]" placeholder="KODE KUPON" value="<?php echo esc_html( get_post_meta( $post->ID, 'kode_kupon', true ) );?>" />
	<span class="scissors">?</span>
	<small>Masukkan kode kupon tanpa spasi</small>
  </div>
    <table>
        <tr>
            <td>Tipe Kupon</td>
            <td>
				<select name="meta[tipe_kupon]">
				  <option value="persen" <?php selected( $tipe_kupon, 'persen' ); ?>>Diskon Persentase</option>
				  <option value="nominal" <?php selected( $tipe_kupon, 'nominal' ); ?>>Diskon Nominal</option>
				</select>
				<small>Tipe diskon kupon</small>
            </td>
        </tr>		
        <tr>
            <td>Nilai Kupon</td>
            <td>
				<input type="number" name="meta[nilai_kupon]" placeholder="0" value="<?php echo esc_html( get_post_meta( $post->ID, 'nilai_kupon', true ) );?>" />
				<small>Nilai diskon kupon berdasarkan tipe kupon</small>
            </td>
        </tr>
        <tr>
            <td>Berlaku Dari</td>
            <td>
				<input type="date" name="meta[berlaku_dari]" value="<?php echo esc_html( get_post_meta( $post->ID, 'berlaku_dari', true ) );?>" />
				<small>Opsional. Masa berlaku kupon.</small>
            </td>
        </tr>
        <tr>
            <td>Berlaku Hingga</td>
            <td>
				<input type="date" name="meta[berlaku_hingga]" value="<?php echo esc_html( get_post_meta( $post->ID, 'berlaku_hingga', true ) );?>" />
				<small>Opsional. Masa berakhir kupon.</small>
            </td>
        </tr>
    </table>
<?php 
}

function themefood_cs_meta_box($post) {
	$followup_order = get_post_meta($post->ID, 'followup_order', true);
    ?>
    <script type="text/javascript">
    jQuery(document).ready(function($){	
		$('[name="meta[no_cs]"]').blur(function(){
			$(this).val($(this).val().replace(/^0+/, '62'));
		})
    });
  </script>
    <table>
        <tr>
            <td>Follow-Up Order?</td>
            <td>			
				<div class="switch-field">
					<input type="radio" id="fu-yes" name="meta[followup_order]" value="yes" <?=($followup_order == 'yes' ? 'checked' : ''); ?> <?php if($followup_order == '') echo 'checked'; ?>/>
					<label for="fu-yes">Yes</label>
					<input type="radio" id="fu-no" name="meta[followup_order]" value="no"<?=($followup_order == 'no' ? 'checked' : ''); ?> />
					<label for="fu-no">No</label>
				</div>			
				<small>Yes jika No WA ingin menerima orderan</small>
            </td>
        </tr>
        <tr>
            <td>Nama CS</td>
            <td>
				<input type="text" name="meta[nama_cs]" value="<?php echo esc_html( get_post_meta( $post->ID, 'nama_cs', true ) );?>" />
				<small>Nama lengkap atau panggilan</small>
            </td>
        </tr>
        <tr>
            <td>No WhatsApp</td>
            <td>
				<input type="text" name="meta[no_cs]" placeholder="62" value="<?php echo esc_html( get_post_meta( $post->ID, 'no_cs', true ) );?>" />
				<small>Gunakan kode negara 62</small>
            </td>
        </tr>
        <tr>
            <td>Posisi CS</td>
            <td>
				<input type="text" name="meta[posisi_cs]" value="<?php echo esc_html( get_post_meta( $post->ID, 'posisi_cs', true ) );?>" />
				<small>Misal: Aftersales Support</small>
            </td>
        </tr>
        <tr>
            <td>Preset Text</td>
            <td>
				<input type="text" name="meta[text_cs]" placeholder="Halo, saya mau tanya..." value="<?php echo esc_html( get_post_meta( $post->ID, 'text_cs', true ) );?>" />
				<small>Teks preset yang tampil di WA saat klik CS di Chat Widget.</small>
            </td>
        </tr>
    </table>
<?php 
}

function themefood_jadwalcs_meta_box( $post ) {
    wp_nonce_field( basename(__FILE__), 'jadwalcs_nonce' );
    $postmeta = maybe_unserialize( get_post_meta( $post->ID, 'jadwal_cs', true ) );
    $elements = array(
        '1'  => 'Senin',
        '2'  => 'Selasa',
        '3'  => 'Rabu',
        '4'  => 'Kamis',
        '5'  => 'Jumat',
        '6'  => 'Sabtu',
        '0'  => 'Minggu',
    );
	?>
	<p>Jadwal Online / Offline CS</p>
	<?php
    foreach ( $elements as $id => $element) {
        if ( is_array( $postmeta ) && in_array( $id, $postmeta ) ) {
            $checked = 'checked="checked"';
        } else {
            $checked = null;
        }
        ?>
        <p>
            <input  type="checkbox" name="multval[]" value="<?php echo $id;?>" <?php echo $checked; ?> />
            <?php echo $element;?>
        </p>
        <?php
    }
}

add_action( 'save_post', function( $post_id ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'jadwalcs_nonce' ] ) && wp_verify_nonce( $_POST[ 'jadwalcs_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }
    if ( ! empty( $_POST['multval'] ) ) {
        update_post_meta( $post_id, 'jadwal_cs', $_POST['multval'] );
    } else {
        delete_post_meta( $post_id, 'jadwal_cs' );
    }
});

function themefood_order_meta_box($post) {
	if ( function_exists( 'ot_get_option' ) ) {	
		$label_timeslot = ot_get_option( 'label_timeslot' );
	}
    ?>
  <div class="order-header">
	<h2>Detail Orderan #<?=get_the_ID();?><a target="_blank" href="<?=get_permalink( get_the_ID() )?>?view=<?=strtotime(get_the_time('c'))?>"><span class="dashicons dashicons-external"></span></a></h2>
	<p>Dibuat pada <?=get_the_date('d F Y')?> pada <?=get_the_time('H:i')?> <?=(get_post_meta( $post->ID, 'order_payment', true ) !== '') ? 'dengan metode pembayaran via <strong>' . get_post_meta( $post->ID , 'order_payment' , true ) . '</strong>' : ''?></p>
	<span class="tipe-order <?=get_post_meta( $post->ID, 'order_method', true )?>">Tipe Order : <?=get_post_meta( $post->ID, 'order_method', true )?></span><a href="<?=get_permalink( get_the_ID() )?>?view=<?=strtotime(get_the_time('c'))?>&print=1" class="view-order" target="_blank"><span class="dashicons dashicons-printer"></span> Cetak Struk</a>
	<?php if(get_post_meta( $post->ID, 'order_method', true ) == 'Dine In') {?>
		<div class="view-meja"><span>No Meja</span><?php echo esc_html( get_post_meta( $post->ID, 'order_meja', true ) );?></div>
	<?php } ?>
  </div>
    <table>
        <tr>
            <td>Nama Customer</td>
            <td>
				<input type="text" name="meta[order_nama]" value="<?php echo esc_html( get_post_meta( $post->ID, 'order_nama', true ) );?>" />
            </td>
        </tr>
        <tr>
            <td>No WhatsApp</td>
            <td>
				<input type="text" name="meta[order_nowa]" value="<?php echo esc_html( get_post_meta( $post->ID, 'order_nowa', true ) );?>" />
            </td>
        </tr>
        <tr>
            <td>Email</td>
            <td>
				<input type="text" name="meta[order_email]" value="<?php echo esc_html( get_post_meta( $post->ID, 'order_email', true ) );?>" />
            </td>
        </tr>
		<?php if(!empty( get_post_meta( $post->ID, 'order_alamat', true ) )) {?>
        <tr>
            <td>Alamat</td>
            <td>
				<textarea rows="5" name="meta[order_alamat]"><?php echo esc_html( get_post_meta( $post->ID, 'order_alamat', true ) );?></textarea>
            </td>
        </tr>
		<?php } ?>
		<?php if(!empty( get_post_meta( $post->ID, 'order_kec', true ) )) {?>
        <tr>
            <td>Kecamatan</td>
            <td>
				<input type="text" name="meta[order_kec]" value="<?php echo esc_html( get_post_meta( $post->ID, 'order_kec', true ) );?>" />
            </td>
        </tr>
		<?php } ?>
		<?php if(!empty( get_post_meta( $post->ID, 'order_koordinat', true ) )) {?>
        <tr>
            <td>Koordinat</td>
            <td>
				<input type="text" name="meta[order_koordinat]" value="<?php echo esc_html( get_post_meta( $post->ID, 'order_koordinat', true ) );?>" />
				<iframe src="https://maps.google.com/maps?q=<?php echo esc_html( get_post_meta( $post->ID, 'order_koordinat', true ) );?>&z=15&output=embed" width="800" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
				<a class="button get-direction" href="https://www.google.com/maps?daddr=<?php echo esc_html( get_post_meta( $post->ID, 'order_koordinat', true ) );?>" target="_blank">Arah Menuju Lokasi</a>
            </td>
        </tr>
		<?php } ?>
		<?php if(!empty( get_post_meta( $post->ID, 'order_delivtime', true ) )) {?>
        <tr>
            <td><?=$label_timeslot?></td>
            <td>
				<input type="text" name="meta[order_delivtime]" value="<?php echo esc_html( get_post_meta( $post->ID, 'order_delivtime', true ) );?>" />
            </td>
        </tr>
		<?php } ?>
    </table>
<?php 
}

function themefood_order_produk_meta_box($post) {
	if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
		$nama_kodeunik = ot_get_option( 'nama_kodeunik' );
		$label_fee = ot_get_option( 'label_fee' );
	}
    ?>
	<table id="list-produk">
		<tr class="tabletitle">
			<td>Item</td>
			<td></td>
			<td>Qty</td>
			<td>Subtotal</td>
		</tr>
		<?php
		$json = get_post_meta(get_the_ID(), 'order_produk', true);
		$json = json_decode($json, true);
		foreach($json as $item) {
		?>
		<tr class="item">
			<td class="tableitem"><img src="<?=$item['image'];?>" /></td>
			<td class="tableitem"><strong><a href="<?=admin_url()?>post.php?post=<?=strtok($item['id'], '-')?>&action=edit"><?=$item['name'];?></a></strong><?=!empty($item['summary']) ? 'Catatan: ' . $item['summary'] : '';?></td>
			<td class="tableitem"><?=$item['quantity'];?></td>
			<td class="tableitem"><?=$currency_sym?><?=number_format( $item['price'] * $item['quantity'], 0 , ',' , '.' );?></td>
		</tr>
		<?php } ?>
		<tr class="tabletitle subtotal">
			<td></td>
			<td></td>
			<td>Subtotal</td>
			<td><?=get_post_meta(get_the_ID(), 'order_subtotal', true);?></td>
		</tr>
		<?php if(!empty(get_post_meta(get_the_ID(), 'order_ongkir', true))) { ?>
		<tr class="tabletitle">
			<td></td>
			<td></td>
			<td>Ongkir</td>
			<td><?=get_post_meta(get_the_ID(), 'order_ongkir', true);?></td>
		</tr>
		<?php } ?>
		<tr class="tabletitle">
			<td></td>
			<td></td>
			<td>Diskon</td>
			<td><?= (get_post_meta(get_the_ID(), 'order_nominaldiskon', true) !== $currency_sym.'0') ? get_post_meta(get_the_ID(), 'order_nominaldiskon', true) .' ('. get_post_meta(get_the_ID(), 'order_kodediskon', true) .')' : '-';?></td>
		</tr>
		<?php if(!empty(get_post_meta(get_the_ID(), 'order_unik', true))) { ?>
		<tr class="tabletitle">
			<td></td>
			<td></td>
			<td><?=$nama_kodeunik;?></td>
			<td><?=get_post_meta(get_the_ID(), 'order_unik', true);?></td>
		</tr>
		<?php } ?>
		<?php if(!empty(get_post_meta(get_the_ID(), 'order_fee', true))) { ?>
		<tr class="tabletitle">
			<td></td>
			<td></td>
			<td><?=$label_fee;?></td>
			<td><?=get_post_meta(get_the_ID(), 'order_fee', true);?></td>
		</tr>
		<?php } ?>
		<tr class="tabletitle total">
			<td></td>
			<td></td>
			<td>Total</td>
			<td><?=get_post_meta(get_the_ID(), 'order_total', true);?></td>
		</tr>
	</table>
<?php 
}

function url_get_contents($Url) {
    if (!function_exists('curl_init')){ 
        die('CURL is not installed!');
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $Url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

function themefood_order_cs_meta_box($post) {
	$json = url_get_contents(get_bloginfo('url') . '/wp-json/wp/v2/tf-cs?per_page=1000');
	$obj = json_decode($json, true);
	$cs = array_filter($obj, function ($var) {
		return ($var['no_cs'] == get_post_meta(get_the_ID(), 'order_cs', true));
	});
	$cs = reset($cs);
	if($cs) {
    ?>
		<img src="<?=$cs['foto_cs']?>" />
		<h4><?=$cs['nama_cs']?></h4>
		<span><a href="https://wa.me/<?=$cs['no_cs']?>" target="_blank"><?=$cs['no_cs']?></a></span>
	<?php 
	} else {
    ?>
		<span>Difollow-up oleh CS dengan nomor <a href="https://wa.me/<?=get_post_meta(get_the_ID(), 'order_cs', true);?>" target="_blank"><?=get_post_meta(get_the_ID(), 'order_cs', true);?></a> (CS sudah tidak ada di list CS)</span>
	<?php 
	}
}

function themefood_emoji_meta_box($post) {
    ?>
	<link rel="stylesheet" href="<?=get_template_directory_uri() . '/css/emojionearea.min.css';?>" />  
	<p>Emoji membuat konten semakin menarik. Scroll &amp; klik untuk copy.</p>
    <div id="emoji-box">
		<div class="emojionearea"></div>
	</div>
	<script src="<?=get_template_directory_uri() . '/js/emojionearea.min.js';?>"></script>  
	<script>
	(function ($) {	
		function copyEmoji(element) {
			var $temp = $("<input>");
			$("body").append($temp);
			$temp.val(element).select();
			document.execCommand("copy");
			$temp.remove();
		}
		$(".emojionearea").emojioneArea({
			standalone: true,
			hidePickerOnBlur: false,
			tones: false,
			search: false,
			searchPosition: "bottom",
			pickerPosition: "bottom",
			searchPlaceholder: "Cari emoji disini...",
			buttonTitle: "Klik untuk copy emoji",
			filters: {
				symbols: false,
				flags : false
			},
			events: {
			  emojibtn_click: function (button, event) {
				var chosenEmoji = $(".emojionearea")[0].emojioneArea.getText();
				copyEmoji(chosenEmoji);
				$('.emojionearea-editor').append('<span>Emoji berhasil dicopy!</span>');
				setTimeout(function() {
				  $('.emojionearea-editor span').remove();
				}, 5000);
			  }
		  }
		});
	})(jQuery);
	</script>
<?php 
}

// Kategori Produk Taxonomy as Radio Button
function kategori_produk_radio( $args ) {
    if ( ! empty( $args['taxonomy'] ) && $args['taxonomy'] === 'kategori_produk' ) {
        if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) {
            if ( ! class_exists( 'WPSE_139269_Walker_Category_Radio_Checklist' ) ) {
                class WPSE_139269_Walker_Category_Radio_Checklist extends Walker_Category_Checklist {
                    function walk( $elements, $max_depth, ...$args ) {
                        $output = parent::walk( $elements, $max_depth, ...$args );
                        $output = str_replace(
                            array( 'type="checkbox"', "type='checkbox'" ),
                            array( 'type="radio"', "type='radio'" ),
                            $output
                        );
                        return $output;
                    }
                }
            }
            $args['walker'] = new WPSE_139269_Walker_Category_Radio_Checklist;
        }
    }
    return $args;
}
add_filter( 'wp_terms_checklist_args', 'kategori_produk_radio' );

// Kategori Produk Image
function sanitize_image( $input ){
    $output = '';
    $filetype = wp_check_filetype( $input );
    $mime_type = $filetype['type'];
    if ( strpos( $mime_type, 'image' ) !== false ){
        $output = $input;
    } 
    return $output;
}
add_action('kategori_produk_add_form_fields', 'add_foto_kategori', 10, 2);
function add_foto_kategori($taxonomy){
    ?>
    <div class="form-field">
        <label for="">Foto Kategori</label>
        <input type="text" name="foto_kategori" id="foto_kategori" value="" style="width: 77%">
        <input type="button" id="upload_image_btn" class="button" value="Upload Foto" />
		<p>Rekomendasi ukuran: 160x70 px.</p>
    </div>
    <?php
}
add_action('created_kategori_produk', 'save_foto_kategori', 10, 2);
function save_foto_kategori($term_id, $tt_id) {
    if (isset($_POST['foto_kategori']) && '' !== $_POST['foto_kategori']){
        $image = esc_url_raw( sanitize_image($_POST['foto_kategori']) );
        add_term_meta($term_id, 'foto_kategori', $image, true);
    }
	if (is_plugin_active('wp-rest-cache/wp-rest-cache.php')){
		\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/kategori_produk?per_page=100' );
	}
}
add_action('kategori_produk_edit_form_fields', 'edit_foto_kategori', 10, 2);
function edit_foto_kategori($term, $taxonomy) {
    $foto_kategori = get_term_meta($term->term_id, 'foto_kategori', true);
?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="foto_kategori"><?php _e( 'Foto Kategori', 'themefood' ); ?></label></th>
		<td>
			<input type="text" name="foto_kategori" id="foto_kategori" value="<?php echo $foto_kategori ?>" style="width: 77%">
			<input type="button" id="upload_image_btn" class="button" value="Upload Foto" />
			<p class="description">Rekomendasi ukuran: 160x70 px.</p>
		</td>
    </div>
<?php
}
add_action('edited_kategori_produk', 'update_foto_kategori', 10, 2);
function update_foto_kategori($term_id, $tt_id) {
    if (isset($_POST['foto_kategori']) && '' !== $_POST['foto_kategori']){
        $image = esc_url_raw( sanitize_image($_POST['foto_kategori']) );
        update_term_meta($term_id, 'foto_kategori', $image);
    }
	if (is_plugin_active('wp-rest-cache/wp-rest-cache.php')){
		\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/kategori_produk?per_page=100' );
	}
}

// Order Status
function themefood_status_meta_box( $post, $box ) {
	$defaults = array('taxonomy' => 'order_status');
	if ( !isset($box['args']) || !is_array($box['args']) )
		$args = array();
	else
		$args = $box['args'];
	extract( wp_parse_args($args, $defaults), EXTR_SKIP );
	$tax = get_taxonomy($taxonomy);
	$term_obj = wp_get_object_terms($post->ID, $taxonomy );	
	if ( function_exists( 'ot_get_option' ) ) {
		$order_diterima = ot_get_option( 'order_diterima' );
		$order_diterima_1 = ot_get_option( 'order_diterima_1' );
		$order_diterima_2 = ot_get_option( 'order_diterima_2' );
		$order_diterima_3 = ot_get_option( 'order_diterima_3' );
		$order_diproses = ot_get_option( 'order_diproses' );
		$order_diantar = ot_get_option( 'order_diantar' );
		$order_selesai = ot_get_option( 'order_selesai' );
		$order_dibatalkan = ot_get_option( 'order_dibatalkan' );
		$link_followup = ot_get_option( 'link_followup' ) ?: 'web';
	}
	$url = 'https://'.$link_followup.'.whatsapp.com/send';
	if(isMobile()){
		$url = 'whatsapp://send';
	}
	$msg = themefood_process_variables(${"order_" . strtolower($term_obj[0]->slug)}, get_the_ID(), '');
	$msg_fup1 = themefood_process_variables($order_diterima_1, get_the_ID(), '');
	$msg_fup2 = themefood_process_variables($order_diterima_2, get_the_ID(), '');
	$msg_fup3 = themefood_process_variables($order_diterima_3, get_the_ID(), '');
	$link = '';
	if($term_obj[0]->slug == 'diterima') {
		$link1 = '';
		$link2 = '';
		$link3 = '';
		if(!empty($msg_fup1)){
			$link1 = '<a href="'.$url.'?text='.str_replace('%5Cn', '%0A', rawurlencode($msg_fup1)).'&phone='.get_post_meta( get_the_ID() , 'order_nowa' , true ).'" target="_blank" title="Follow-Up 1: '.ucwords($term_obj[0]->slug).'" class="button fu-btn"><span class="dashicons dashicons-whatsapp"></span> FU 1 : <strong>'.ucwords($term_obj[0]->slug).'</strong></a>';				
		}				
		if(!empty($msg_fup2)){
			$link2 = '<a href="'.$url.'?text='.str_replace('%5Cn', '%0A', rawurlencode($msg_fup2)).'&phone='.get_post_meta( get_the_ID() , 'order_nowa' , true ).'" target="_blank" title="Follow-Up 2: '.ucwords($term_obj[0]->slug).'" class="button fu-btn"><span class="dashicons dashicons-whatsapp"></span> FU 2 : <strong>'.ucwords($term_obj[0]->slug).'</strong></a>';				
		}				
		if(!empty($msg_fup3)){
			$link3 = '<a href="'.$url.'?text='.str_replace('%5Cn', '%0A', rawurlencode($msg_fup3)).'&phone='.get_post_meta( get_the_ID() , 'order_nowa' , true ).'" target="_blank" title="Follow-Up 3: '.ucwords($term_obj[0]->slug).'" class="button fu-btn"><span class="dashicons dashicons-whatsapp"></span> FU 3 : <strong>'.ucwords($term_obj[0]->slug).'</strong></a>';				
		}
		$link = $link1 . $link2 . $link3;
	} else {
		if(!empty($msg)){
			$link = '<a href="'.$url.'?text='.str_replace('%5Cn', '%0A', rawurlencode($msg)).'&phone='.get_post_meta( get_the_ID() , 'order_nowa' , true ).'" target="_blank" title="Follow-Up : '.ucwords($term_obj[0]->slug).'" class="button fu-btn"><span class="dashicons dashicons-whatsapp"></span> FU : <strong>'.ucwords($term_obj[0]->slug).'</strong></a>';				
		}
	}
?>
<script type="text/javascript">
jQuery(document).ready(function($){	
	$('.<?=$term_obj[0]->slug?>').addClass('active');
});
</script>
<div id="taxonomy-<?php echo $taxonomy; ?>" class="categorydiv">
	<input type='hidden' name='tax_input[<?=$taxonomy?>][]' value='0' />
	<?php wp_dropdown_categories( array( 'taxonomy' => $taxonomy, 'hide_empty' => 0, 'name' => "tax_input[" . $taxonomy . "][]", 'selected' => $term_obj[0]->term_id, 'orderby' => 'name', 'hierarchical' => 0 ) ); ?>
</div>
<ul class="status-timeline">
	<li class="diterima">Order Diterima</li>
	<li class="diproses">Order Diproses</li>
	<li class="diantar">Order Diantar</li>
	<li class="selesai">Order Selesai</li>
</ul>
<ul>
	<li class="dibatalkan">Order Dibatalkan</li>
</ul>
<?php
	echo $link;
	if(get_post_meta(get_the_ID(), 'order_payment_proof', true)){
	?>
		<div class="status-payment">
			<p><?=get_post_meta(get_the_ID(), 'order_payment_proof', true);?></p>
		</div>
	<?php
	}
}

// Move Meta Box
add_action('do_meta_boxes', 'move_meta_boxes');
function move_meta_boxes() {
    remove_meta_box( 'postimagediv', ['tf-produk','tf-slider','tf-cs'], 'side' );
    remove_meta_box( 'kategori_produkdiv', 'tf-produk', 'side' );
    add_meta_box('postimagediv', __('Foto Produk'), 'post_thumbnail_meta_box', 'tf-produk', 'normal', 'high');
    add_meta_box('postimagediv', __('Foto Slide'), 'post_thumbnail_meta_box', 'tf-slider', 'normal', 'high');
    add_meta_box('postimagediv', __('Foto CS'), 'post_thumbnail_meta_box', 'tf-cs', 'normal', 'high');
    add_meta_box('kategori_produkdiv', __('Kategori Produk'), 'post_categories_meta_box', 'tf-produk', 'normal', 'high', array('taxonomy' => 'kategori_produk'));
}

// Customize Rest API
add_filter( 'rest_prepare_tf-produk', 'clean_rest_api', 10, 3 );
add_filter( 'rest_prepare_tf-info', 'clean_rest_api', 10, 3 );
add_filter( 'rest_prepare_tf-slider', 'clean_rest_api', 10, 3 );
add_filter( 'rest_prepare_tf-kupon', 'clean_rest_api', 10, 3 );
add_filter( 'rest_prepare_tf-cs', 'clean_rest_api', 10, 3 );
add_filter( 'rest_prepare_tf-order', 'clean_rest_api', 10, 3 );
add_filter( 'rest_prepare_kategori_produk', 'clean_rest_api', 10, 3 );
function clean_rest_api($data, $post, $context) {
	unset ( $data->data ['modified_gmt']);
	unset ( $data->data ['status']);
	unset ( $data->data ['slug']);
	unset ( $data->data ['link']);
	unset ( $data->data ['guid']);
	unset ( $data->data ['type']);
	unset ( $data->data ['featured_media']);
	unset ( $data->data ['template']);
	unset ( $data->data ['meta']);
    $data->remove_link( 'collection' );
    $data->remove_link( 'self' );
    $data->remove_link( 'about' );
    $data->remove_link( 'author' );
    $data->remove_link( 'replies' );
    $data->remove_link( 'version-history' );
    $data->remove_link( 'https://api.w.org/featuredmedia' );
    $data->remove_link( 'https://api.w.org/attachment' );
    $data->remove_link( 'https://api.w.org/term' );
    $data->remove_link( 'curies' );
	if(is_valid_page()) return $data;
}
function url_cdn($url) {
	if ( function_exists( 'ot_get_option' ) ) {
		$layanan_cdn = ot_get_option( 'layanan_cdn' );
		$bunny_hostname = ot_get_option( 'bunny_hostname' );
	}
	$image = (($url) ?: get_template_directory_uri() . '/img/placeholder.webp');
	if($layanan_cdn == 'statically'){
		$image = (($url) ? 'https://cdn.statically.io/img/' . preg_replace('#^https?://#', '', $url) . '?quality=70&f=auto' : 'https://cdn.statically.io/img/' . preg_replace('#^https?://#', '', get_template_directory_uri()) . '/img/placeholder.webp?quality=70&f=auto');		
	}
	if($layanan_cdn == 'bunny'){
		$image = (($url) ? 'https://' . $bunny_hostname . parse_url($url, PHP_URL_PATH) : 'https://' . $bunny_hostname . parse_url(get_template_directory_uri(), PHP_URL_PATH) . '/img/placeholder.webp');		
	}
	return $image;
}
add_filter( 'rest_prepare_tf-produk', 'tfproduk_prepare_post', 10, 3 );
function tfproduk_prepare_post( $data, $post, $request ) {
  unset ( $data->data ['date_gmt']);
  $_data = $data->data;
  $fields = ['stok_produk', 'harga_produk', 'harga_diskon', 'berat_produk', 'variasi_group'];
  foreach ( $fields as $field ) {
    $_data[$field] = get_post_meta( $post->ID, $field, true );
  }
  $thumbnail_id = get_post_thumbnail_id( $post->ID );
  $featured_media_url = wp_get_attachment_image_src( $thumbnail_id, 'foto-produk' );
  $kategori_produk = wp_get_post_terms( $post->ID, 'kategori_produk' , array("fields" => "all") );
  $kats = array();
  foreach($kategori_produk as $kat){
	$kats[] = $kat->name;
  }
  $_data['foto_produk'] = url_cdn($featured_media_url[0]);
  $_data['kategori_produk'] = $kats;
  $_data['link_produk'] = get_permalink($post->ID);
  $image_ids = get_post_meta( $post->ID, 'galeri_produk' );
  $galeri = array();
  if ( ! empty( $image_ids ) && $image_ids[0] !== '') {
	$image_ids = explode( ',', $image_ids[0] );
	foreach($image_ids as $image_id) {
	  $galeri[] = url_cdn(wp_get_attachment_url($image_id));
	}
  }
  $_data['galeri_produk'] = $galeri;
  $data->data = $_data;
  if(is_valid_page()) return $data;
}
add_filter( 'rest_prepare_tf-info', 'tfinfo_prepare_post', 10, 3 );
function tfinfo_prepare_post( $data, $post, $request ) {
  unset ( $data->data ['date']);
  unset ( $data->data ['date_gmt']);
  unset ( $data->data ['modified']);
  $_data = $data->data;
  $thumbnail_id = get_post_thumbnail_id( $post->ID );
  $featured_media_url = wp_get_attachment_image_src( $thumbnail_id, 'foto-info' );
  $_data['featured_image'] = url_cdn($featured_media_url[0]);  
  $data->data = $_data;
  if(is_valid_page()) return $data;
}
add_filter( 'rest_prepare_tf-slider', 'tfslider_prepare_post', 10, 3 );
function tfslider_prepare_post( $data, $post, $request ) {
  unset ( $data->data ['id']);
  unset ( $data->data ['date']);
  unset ( $data->data ['date_gmt']);
  unset ( $data->data ['modified']);
  unset ( $data->data ['title']);
  $_data = $data->data;
  $thumbnail_id = get_post_thumbnail_id( $post->ID );
  $featured_media_url = wp_get_attachment_image_src( $thumbnail_id, 'foto-slide' );
  $_data['foto_slide'] = url_cdn($featured_media_url[0]);  
  $_data['slider_target'] = get_post_meta( $post->ID, 'slider_target', true );
  $data->data = $_data;
  if(is_valid_page()) return $data;
}
add_filter( 'rest_prepare_tf-kupon', 'tfkupon_prepare_post', 10, 3 );
function tfkupon_prepare_post( $data, $post, $request ) {
  unset ( $data->data ['id']);
  unset ( $data->data ['date']);
  unset ( $data->data ['date_gmt']);
  unset ( $data->data ['modified']);
  unset ( $data->data ['title']);
  $_data = $data->data;
  $fields = ['tipe_kupon', 'nilai_kupon', 'berlaku_dari', 'berlaku_hingga'];
  foreach ( $fields as $field ) {
    $_data[$field] = get_post_meta( $post->ID, $field, true );
  }
  $_data['ids'] = openssl_encrypt(get_post_meta( $post->ID, 'kode_kupon', true ),"AES-128-ECB","ThemeFood");
  $data->data = $_data;
  if(is_valid_page()) return $data;
}
add_filter( 'rest_prepare_tf-cs', 'tfcs_prepare_post', 10, 3 );
function tfcs_prepare_post( $data, $post, $request ) {
  unset ( $data->data ['id']);
  unset ( $data->data ['date']);
  unset ( $data->data ['date_gmt']);
  unset ( $data->data ['modified']);
  unset ( $data->data ['title']);
  $_data = $data->data;
  $fields = ['followup_order', 'nama_cs', 'no_cs', 'posisi_cs', 'text_cs', 'jadwal_cs'];
  foreach ( $fields as $field ) {
    $_data[$field] = get_post_meta( $post->ID, $field, true );
  }
  $thumbnail_id = get_post_thumbnail_id( $post->ID );
  $featured_media_url = wp_get_attachment_image_src( $thumbnail_id, 'foto-cs' );
  $_data['foto_cs'] = url_cdn($featured_media_url[0]);  
  $data->data = $_data;
  if(is_valid_page()) return $data;
}
function split_name($name) {
    $name = trim($name);
    $last_name = (strpos($name, ' ') === false) ? '' : preg_replace('#.*\s([\w-]*)$#', '$1', $name);
    $first_name = trim( preg_replace('#'.preg_quote($last_name,'#').'#', '', $name ) );
    return array($first_name, $last_name);
}
function get_starred($str) {
    $len = strlen($str);
    return substr($str, 0, 1).str_repeat('*', $len - 2).substr($str, $len - 1, 1);
}
add_filter( 'rest_prepare_tf-order', 'tforder_prepare_post', 10, 3 );
function tforder_prepare_post( $data, $post, $request ) {
  unset ( $data->data ['modified']);
  unset ( $data->data ['title']);
  unset ( $data->data ['order_status']);
  $_data = $data->data;
  $status_order = wp_get_post_terms( $post->ID, 'order_status' , array("fields" => "all") );
  $_data['status_order'] = $status_order[0]->name;
  $fields = ['order_produk','order_total','order_cs','catatan_order','order_payment','order_payment_link'];
  foreach ( $fields as $field ) {
    $_data[$field] = get_post_meta( $post->ID, $field, true );
  }
  $full = get_post_meta( $post->ID, 'order_nama', true );
  $name = split_name($full);
  if($name[1]){
	  $first = $name[0];
	  if(!empty($last)) { 
		$last = get_starred($name[1]);
	  } else {
		$last = '';
	  }
	  $_data['order_nama'] = $first.' '.$last;	  	  
  } else {
	  $_data['order_nama'] = $name[0];	  	  
  }
  $_data['order_nowa'] = substr(get_post_meta( $post->ID, 'order_nowa', true ), 0, -3) . '***';
  $data->data = $_data;
  if(is_valid_page()) return $data;
}
add_filter( 'rest_prepare_kategori_produk', 'kategori_produk_prepare_post', 10, 3 );
function kategori_produk_prepare_post( $data, $post, $request ) {
  if ( function_exists( 'ot_get_option' ) ) {
	$layanan_cdn = ot_get_option( 'layanan_cdn' );
	$bunny_hostname = ot_get_option( 'bunny_hostname' );
  }
  $image = ((get_term_meta( $post->term_id, 'foto_kategori', true )) ?: '');		
  if($layanan_cdn == 'statically'){
	$image = ((get_term_meta( $post->term_id, 'foto_kategori', true )) ? 'https://cdn.statically.io/img/' . preg_replace('#^https?://#', '', get_term_meta( $post->term_id, 'foto_kategori', true )) . '?quality=70&f=auto' : '');		
  }
  if($layanan_cdn == 'bunny'){
	$image = ((get_term_meta( $post->term_id, 'foto_kategori', true )) ? 'https://' . $bunny_hostname . parse_url(get_term_meta( $post->term_id, 'foto_kategori', true ), PHP_URL_PATH) : '');		
  }	
  unset ( $data->data ['id']);
  unset ( $data->data ['description']);
  unset ( $data->data ['taxonomy']);
  unset ( $data->data ['parent']);
  unset ( $data->data ['_links']);
  unset ( $data->data ['curies']);
  $_data = $data->data;
  $_data['foto_kategori'] = $image;  
  $data->data = $_data;
  if(is_valid_page()) return $data;
}

add_filter( 'rest_tf-order_query', 'tforder_request_params', 99, 2 );
function tforder_request_params( $args, $request ) {
	$args += array(
		'meta_key'   => $request['meta_key'],
		'meta_value' => $request['meta_value'],
		'meta_query' => $request['meta_query'],
	);
	return $args;
}

// Update stok dari admin column
add_action( 'wp_ajax_update_meta', 'update_stok_column' );
function update_stok_column() {
   $post_id = $_POST['post_id'];
   $meta = $_POST['post_meta'];
   update_post_meta( $post_id, 'stok_produk', $meta );
   if (is_plugin_active('wp-rest-cache/wp-rest-cache.php')){
		\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-produk?per_page=1000' );		
	}
   die();
}
// Update price dari admin column
add_action( 'wp_ajax_update_price', 'update_price_column' );
function update_price_column() {
   $post_id = $_POST['post_id'];
   $price_reg = $_POST['price_reg'];
   $price_disc = $_POST['price_disc'];
   if(!empty($price_reg)) {
	update_post_meta( $post_id, 'harga_produk', $price_reg );	   
   }
   if(!empty($price_reg) && !empty($price_disc)) {
	update_post_meta( $post_id, 'harga_produk', $price_reg );	   
	update_post_meta( $post_id, 'harga_diskon', $price_disc );	   
   }
   if (is_plugin_active('wp-rest-cache/wp-rest-cache.php')){
		\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-produk?per_page=1000' );		
	}
   die();
}
// Update status dari admin column
add_action( 'wp_ajax_update_status', 'update_status_column' );
function update_status_column() {
	global $result;
    $post_id = $_POST['post_id'];
    $meta = $_POST['post_meta'];
    $term = get_term_by('slug', $meta, 'order_status');
    wp_set_object_terms( $post_id, $term->term_id, 'order_status' );   
    if (is_plugin_active('wp-rest-cache/wp-rest-cache.php')){
		\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-order?per_page=10' );		
	}
	themefood_send_whatsapp_bot($post_id);
	die();
}
// Catatan Order
add_action( 'wp_ajax_catatan_order', 'catatan_order_column' );
function catatan_order_column() {
   $post_id = $_POST['post_id'];
   $meta = $_POST['post_meta'];
   update_post_meta( $post_id, 'catatan_order', $meta );
   if (is_plugin_active('wp-rest-cache/wp-rest-cache.php')){
		\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-order?per_page=10' );		
	}
   echo $meta;
   die();
}
// Load detail produk di admin column
add_action( 'wp_ajax_load_produk', 'load_produk_column' );
function load_produk_column() {
   $post_id = $_POST['post_id'];
   if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
		$nama_kodeunik = ot_get_option( 'nama_kodeunik' );
		$label_fee = ot_get_option( 'label_fee' );
	}
   ?>
	<table id="list-produk">
		<tr class="tabletitle">
			<td>Item</td>
			<td></td>
			<td>Qty</td>
			<td>Subtotal</td>
		</tr>
		<?php
  	    $json = get_post_meta($post_id, 'order_produk', true);
	    $json = json_decode($json, true);
		foreach($json as $item) {
		?>
		<tr class="item">
			<td class="tableitem"><img src="<?=$item['image'];?>" /></td>
			<td class="tableitem"><strong><a href="<?=admin_url()?>post.php?post=<?=strtok($item['id'], '-')?>&action=edit"><?=$item['name'];?></a></strong><?=!empty($item['summary']) ? 'Catatan: ' . $item['summary'] : '';?></td>
			<td class="tableitem"><?=$item['quantity'];?></td>
			<td class="tableitem"><?=$currency_sym?><?=number_format( $item['price'] * $item['quantity'], 0 , ',' , '.' );?></td>
		</tr>
		<?php } ?>
		<tr class="tabletitle subtotal">
			<td></td>
			<td></td>
			<td>Subtotal</td>
			<td><?=get_post_meta($post_id, 'order_subtotal', true);?></td>
		</tr>
		<?php if(!empty(get_post_meta($post_id, 'order_ongkir', true))) { ?>
		<tr class="tabletitle">
			<td></td>
			<td></td>
			<td>Ongkir</td>
			<td><?=get_post_meta($post_id, 'order_ongkir', true);?></td>
		</tr>
		<?php } ?>
		<tr class="tabletitle">
			<td></td>
			<td></td>
			<td>Diskon</td>
			<td><?= (get_post_meta($post_id, 'order_nominaldiskon', true) !== $currency_sym.'0') ? get_post_meta($post_id, 'order_nominaldiskon', true) .' ('. get_post_meta($post_id, 'order_kodediskon', true) .')' : '-';?></td>
		</tr>
		<?php if(!empty(get_post_meta($post_id, 'order_unik', true))) { ?>
		<tr class="tabletitle">
			<td></td>
			<td></td>
			<td><?=$nama_kodeunik;?></td>
			<td><?=get_post_meta($post_id, 'order_unik', true);?></td>
		</tr>
		<?php } ?>		
		<?php if(!empty(get_post_meta($post_id, 'order_fee', true))) { ?>
		<tr class="tabletitle">
			<td></td>
			<td></td>
			<td><?=$label_fee;?></td>
			<td><?=get_post_meta($post_id, 'order_fee', true);?></td>
		</tr>
		<?php } ?>		
		<tr class="tabletitle total">
			<td></td>
			<td></td>
			<td>Total</td>
			<td><?=get_post_meta($post_id, 'order_total', true);?></td>
		</tr>
	</table>   
   <?php
   die();
}
add_action('admin_footer-edit.php', 'update_stok_enqueue');
function update_stok_enqueue(){
	$screen = get_current_screen();
	if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
	}
	if( is_object( $screen ) && $screen->post_type == 'tf-produk' ){
		?>
		<script>
		(function ($) {
			$('select.select_stok').on('change', function (e) {
				var element = this;
				$('+ span', element).html('Diproses...');
				$.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						action: "update_meta",
						post_id: $(element).data('id'),
						post_meta: this.value,
					},
					success: function( data ) {
						$('+ span', element).html('Berhasil!');
						setTimeout(function() {
							$('+ span', element).html('');
						  }, 2000); 
					}
				});
			});
			$('.edit-harga a').click(function (e) {
				e.preventDefault();
				$(this).parents('.harga_produk').find('.edit-price-wrapper').slideToggle();
			});
			$('.edit-price-cancel').click(function (e) {
				e.preventDefault();
				$(this).parents('.edit-price-wrapper').slideToggle();
			});
			$('.edit-price-save').click(function (e) {
				e.preventDefault();
				var element = this;
				$(element).parents('.harga_produk').find('.edit-price-notif').html('Diproses...');
				$.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						action: "update_price",
						post_id: $(element).parents('.edit-price-wrapper').data('id'),
						price_reg: $(element).parents('.edit-price-wrapper').find('.price-reg').val(),
						price_disc: $(element).parents('.edit-price-wrapper').find('.price-disc').val(),
					},
					success: function( data ) {
						$(element).parents('.edit-price-wrapper').slideToggle();
						if($(element).parents('.edit-price-wrapper').find('.price-disc').val() == '') {
							$(element).parents('.harga_produk').find('.harga-reg').html('<?=$currency_sym?>'+$(element).parents('.edit-price-wrapper').find('.price-reg').val().toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
						}
						if($(element).parents('.edit-price-wrapper').find('.price-disc').val() !== '') {
							$(element).parents('.harga_produk').find('.harga-reg').html('<?=$currency_sym?>'+$(element).parents('.edit-price-wrapper').find('.price-disc').val().toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));
							$(element).parents('.harga_produk').find('.harga-disc').html('<?=$currency_sym?>'+$(element).parents('.edit-price-wrapper').find('.price-reg').val().toString().replace(/\B(?=(\d{3})+(?!\d))/g, "."));							
						}
						$(element).parents('.harga_produk').find('.edit-price-notif').html('Berhasil!');
						setTimeout(function() {
							$(element).parents('.harga_produk').find('.edit-price-notif').html('');
						  }, 2000); 
					}
				});
			});
			$('.price-reg, .price-disc').on('keyup input', function (e) {
				if($(this).parents('.edit-price-wrapper').find('.price-reg').val() !== '' && $(this).parents('.edit-price-wrapper').find('.price-disc').val() !== '') {
					if (parseInt($(this).parents('.edit-price-wrapper').find('.price-reg').val()) > parseInt($(this).parents('.edit-price-wrapper').find('.price-disc').val())) {
						$(this).parents('.edit-price-wrapper').find('.price-disc').css("border-color","#ccc");
						$(this).parents('.edit-price-wrapper').find('.edit-price-save').attr("disabled",false);
					} else {
						$(this).parents('.edit-price-wrapper').find('.price-disc').css("border-color","red");
						$(this).parents('.edit-price-wrapper').find('.edit-price-save').attr("disabled",true);
					}
				} else {
					$(this).parents('.edit-price-wrapper').find('.price-disc').css("border-color","#ccc");
					$(this).parents('.edit-price-wrapper').find('.edit-price-save').attr("disabled",false);
				}
			});				
		})(jQuery);
		</script>
		<?php
	}
	if( is_object( $screen ) && $screen->post_type == 'tf-order' ){
		?>
		<script>
		(function ($) {
			$('select.select_status').on('change', function (e) {
				var element = this;
				$('+ span', element).html('Diproses...');
				$.ajax({
					type: "POST",
					url: ajaxurl,
					data: {
						action: "update_status",
						post_id: $(element).data('id'),
						post_meta: this.value.toLowerCase(),
					},
					success: function( data ) {
						$('+ span', element).html('Berhasil!');
						setTimeout(function() {
							$('+ span', element).html('');
						  }, 2000); 
					}
				});
			});
			$('.detail-order').click(function () {
			  var elm = $(this);
			  var id = elm.parent().parent().parent().attr('id').replace('post-','');
			  $('body.post-type-tf-order table.posts tr.open').not(elm.parent().parent().parent()).removeClass('open');			  
				elm.parent().parent().parent().toggleClass('open');
				if(elm.parent().parent().parent(':not(.appended)').length){
				  $.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							action: "load_produk",
							post_id: id
						},
						success: function( data ) {	
							elm.parent().parent().parent(':not(.appended)').after('<tr class="detail-produk"><td colspan="9">'+data+'</td></tr>');
							setTimeout(function() {
								elm.parent().parent().parent().addClass('appended');
							}, 100); 
						}
					});
				}
			});
			$('.catatan-order').click(function (e) {
			  e.preventDefault();
			  var elm = $(this),
				  catatan_order = elm.data('tip'),
				  catatan = prompt("Tambah Catatan ", catatan_order);
				if (catatan != null) {
				  $.ajax({
						type: "POST",
						url: ajaxurl,
						data: {
							action: "catatan_order",
							post_id: elm.data('id'),
							post_meta: catatan,
						},
						success: function( data ) {	
							console.log(data);
							elm.addClass('tf-tooltip left').attr('data-tip', data);
							elm.css('color','red');
						}
					});
				}
			});
		})(jQuery);
		</script>
		<?php
	}	
}

// Custom Column Produk
add_filter( 'manage_tf-produk_posts_columns', 'add_tfproduk_columns' );
add_filter( 'manage_tf-slider_posts_columns', 'add_tfslider_columns' );
add_filter( 'manage_tf-kupon_posts_columns', 'add_tfkupon_columns' );
add_filter( 'manage_tf-cs_posts_columns', 'add_tfcs_columns' );
add_filter( 'manage_tf-order_posts_columns', 'add_tforder_columns' );
add_action( 'manage_tf-produk_posts_custom_column' , 'manage_tfproduk_column', 10, 2 );
add_action( 'manage_tf-slider_posts_custom_column' , 'manage_tfslider_column', 10, 2 );
add_action( 'manage_tf-kupon_posts_custom_column' , 'manage_tfkupon_column', 10, 2 );
add_action( 'manage_tf-cs_posts_custom_column' , 'manage_tfcs_column', 10, 2 );
add_action( 'manage_tf-order_posts_custom_column' , 'manage_tforder_column', 10, 2 );
add_filter( 'manage_edit-tf-produk_sortable_columns', 'tfproduk_sortable_columns' );
add_filter( 'manage_edit-tf-slider_sortable_columns', 'tfslider_sortable_columns' );
add_filter( 'manage_edit-tf-order_sortable_columns', 'tforder_sortable_columns' );
add_filter( 'post_row_actions', 'tfproduk_row_actions', 10, 2 );
add_action( 'posts_clauses', 'tfproduk_cat_custom_column_query', 10, 2 );
add_action( 'posts_clauses', 'tforder_status_custom_column_query', 10, 2 );
add_action( 'pre_get_posts', 'tf_sortable_by_meta' );

function add_tfproduk_columns($columns) {
	unset( $columns['title'] );
	unset( $columns['date'] );
    $columns['foto_produk'] = '<span class="dashicons dashicons-format-image"></span>';
    $columns['title'] = __( 'Produk', 'themefood' );
    $columns['harga_produk'] = __( 'Harga', 'themefood' );
    $columns['variasi_produk'] = __( 'Variasi', 'themefood' );
    $columns['kategori_produk'] = __( 'Kategori', 'themefood' );
    $columns['stok_produk'] = __( 'Stok', 'themefood' );
    $columns['view_produk'] = __( 'Dilihat', 'themefood' );
    $columns['date'] = __( 'Tanggal', 'themefood' );
    return $columns;
}
function add_tfslider_columns($columns) {
	unset( $columns['title'] );
	unset( $columns['date'] );
    $columns['foto_slider'] = '<span class="dashicons dashicons-format-image"></span>';
    $columns['title'] = __( 'Judul', 'themefood' );
    $columns['target_slider'] = __( 'Target', 'themefood' );
    $columns['date'] = __( 'Tanggal', 'themefood' );
    return $columns;
}
function add_tfkupon_columns($columns) {
	unset( $columns['title'] );
	unset( $columns['date'] );
    $columns['title'] = __( 'Judul', 'themefood' );
    $columns['kode_kupon'] = __( 'Kode Kupon', 'themefood' );
    $columns['nilai_kupon'] = __( 'Nilai Kupon', 'themefood' );
    $columns['berlaku_dari'] = __( 'Berlaku Dari', 'themefood' );
    $columns['berlaku_hingga'] = __( 'Berlaku Hingga', 'themefood' );
    return $columns;
}
function add_tfcs_columns($columns) {
	unset( $columns['title'] );
	unset( $columns['date'] );
    $columns['foto_cs'] = '<span class="dashicons dashicons-format-image"></span>';
    $columns['title'] = __( 'Judul', 'themefood' );
    $columns['nama_cs'] = __( 'Nama', 'themefood' );
    $columns['followup_order'] = __( 'Follow-Up', 'themefood' );
    $columns['no_cs'] = __( 'No WhatsApp', 'themefood' );
    $columns['posisi_cs'] = __( 'Posisi', 'themefood' );
    $columns['jadwal_cs'] = __( 'Jadwal', 'themefood' );
    return $columns;
}
function add_tforder_columns($columns) {
	if ( function_exists( 'ot_get_option' ) ) {
		$metode_checkout = ot_get_option( 'metode_checkout' );  
	}
	unset( $columns['title'] );
	unset( $columns['date'] );
    $columns['orderan'] = __( 'Orderan', 'themefood' );
    $columns['dipesan_pada'] = __( 'Dipesan Pada', 'themefood' );
    $columns['total_order'] = __( 'Total Order', 'themefood' );
    $columns['no_whatsapp'] = __( 'No WhatsApp', 'themefood' );
	if($metode_checkout == 'both' || $metode_checkout == 'delivery') {
		$columns['pengiriman'] = __( 'Pengiriman', 'themefood' );
	}
	if($metode_checkout == 'both' || $metode_checkout == 'dine') {
		$columns['no_meja'] = __( 'No Meja', 'themefood' );
	}
    $columns['metode_pembayaran'] = __( 'Metode Pembayaran', 'themefood' );
    $columns['status_order'] = __( 'Status Order', 'themefood' );
    $columns['aksi'] = __( 'Aksi', 'themefood' );
    return $columns;
}
function manage_tfproduk_column( $column, $post_id ) {
	if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
	}
    switch ( $column ) {
        case 'foto_produk' :
			if(get_the_post_thumbnail()) {
				echo '<a href="'.get_edit_post_link( $post_id ).'"><img src="'.get_the_post_thumbnail_url($post_id, 'thumbnail').'" /></a>';
			} else {
				echo '<a href="'.get_edit_post_link( $post_id ).'"><img src="'.get_template_directory_uri().'/img/placeholder.webp" /></a>';
			}
            break;
			
        case 'harga_produk' :
			$harga_produk = get_post_meta( $post_id , 'harga_produk' , true );
			$harga_diskon = get_post_meta( $post_id , 'harga_diskon' , true );
			if ($harga_produk == '') { 
				echo '-';
			} else {
				if ( $harga_diskon == '' || $harga_diskon == 0 ) {
					echo '<span class="harga-reg">'.$currency_sym.number_format( $harga_produk, 0 , ',' , '.' ).'</span> <del class="harga-disc"></del>';
				} else {
					echo '<span class="harga-reg">'.$currency_sym.number_format( $harga_diskon, 0 , ',' , '.' ).'</span> <del class="harga-disc">'.$currency_sym.number_format( $harga_produk, 0 , ',' , '.' ).'</del>';				
				}					
			}
			echo '<div class="row-actions"><span class="edit-harga"><a href="#">Edit Harga</a></span></div>';
			echo '<div class="edit-price-wrapper" data-id="'.$post_id.'"><div class="edit-price-reg"><span>Harga Produk ('.$currency_sym.')</span><input type="number" class="price-reg" value="'.$harga_produk.'"></div><div class="edit-price-disc"><span>Harga Diskon ('.$currency_sym.')</span><input type="number" class="price-disc" value="'.$harga_diskon.'"></div><div class="edit-price-btn"><button class="edit-price-cancel button">Batal</button><button class="edit-price-save button button-primary">Simpan</button></div></div><span class="edit-price-notif"></span>';
            break;

        case 'variasi_produk' :
            $tipe = get_post_meta( $post_id , 'variasi_group' , true );
			echo '<span class="'.((empty($tipe)) ? '"' : 'tf-tooltip top variasi-exist" data-tip="Produk memiliki variasi"').'><i class="dashicons dashicons-yes-alt"></i></span>';
            break;

        case 'kategori_produk' :
            $terms = get_the_term_list( $post_id , 'kategori_produk' , '' , ',' , '' );
            if ( is_string( $terms ) )
                echo strip_tags($terms);
            else
                _e( '-', 'themefood' );
            break;

        case 'stok_produk' :
			$stok_produk = get_post_meta( $post_id , 'stok_produk' , true );
			?>
				<select name="stok_produk_<?=$post_id?>" data-id="<?=$post_id?>" class="select_stok" data-chosen="<?=$stok_produk?>" onchange="this.dataset.chosen = this.value;">
					<option value="readystock" <?php selected( $stok_produk, 'readystock' ); ?>>Tersedia</option>
					<option value="outofstock" <?php selected( $stok_produk, 'outofstock' ); ?>>Habis</option>
				</select>
				<span></span>
			<?php
            break;
			
        case 'view_produk' :
			$count = get_post_meta( $post_id , '_produk_count' , true ) ?: 0;
			echo $count . 'x';
            break;
			
    }
	return $column;
}
function manage_tfslider_column( $column, $post_id ) {
    switch ( $column ) {
        case 'foto_slider' :
			if(get_the_post_thumbnail()) {
				echo '<a href="'.get_edit_post_link( $post_id ).'"><img src="'.get_the_post_thumbnail_url($post_id, 'thumbnail').'" /></a>';
			} else {
				echo '<a href="'.get_edit_post_link( $post_id ).'"><img src="'.get_template_directory_uri().'/img/placeholder.webp" /></a>';
			}
            break;
			
        case 'target_slider' :
			$target = get_post_meta( $post_id , 'slider_target' , true );
			echo '<span class="'.explode('-',$target)[1].'">'.explode('-',$target)[1].'</span>';
            break;
    }
	return $column;
}
function manage_tfkupon_column( $column, $post_id ) {
	if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
	}
	if(get_post_meta( $post_id , 'nilai_kupon' , true ) !== '') {
		$nilai_kupon = get_post_meta( $post_id , 'nilai_kupon' , true ) . '%';
		$tipe_kupon = get_post_meta($post_id, 'tipe_kupon', true);
		if($tipe_kupon == 'nominal') {
			$nilai_kupon = $currency_sym . number_format( get_post_meta( $post_id , 'nilai_kupon' , true ), 0 , ',' , '.' );
		}
	} else {
		$nilai_kupon = '-';
	}
    switch ( $column ) {
        case 'kode_kupon' :
			echo (get_post_meta( $post_id , 'kode_kupon' , true )) ? '<span>'.get_post_meta( $post_id , 'kode_kupon' , true ).'</span>' : '-';
            break;
			
        case 'nilai_kupon' :
			echo $nilai_kupon;
            break;
			
        case 'berlaku_dari' :
			echo (get_post_meta( $post_id , 'berlaku_dari' , true )) ? '<span class="dashicons dashicons-calendar-alt"></span> '.date("d-m-Y", strtotime(get_post_meta( $post_id , 'berlaku_dari' , true ))) : '-';
            break;
			
        case 'berlaku_hingga' :
			echo (get_post_meta( $post_id , 'berlaku_hingga' , true )) ? '<span class="dashicons dashicons-calendar-alt"></span> '.date("d-m-Y", strtotime(get_post_meta( $post_id , 'berlaku_hingga' , true ))) : '-';
            break;
    }
	return $column;
}
function manage_tfcs_column( $column, $post_id ) {
    switch ( $column ) {
        case 'foto_cs' :
			if(get_the_post_thumbnail()) {
				echo '<a href="'.get_edit_post_link( $post_id ).'"><img src="'.get_the_post_thumbnail_url($post_id, 'thumbnail').'" /></a>';
			} else {
				echo '<a href="'.get_edit_post_link( $post_id ).'"><img src="'.get_template_directory_uri().'/img/placeholder.webp" /></a>';
			}
            break;
			
        case 'nama_cs' :
			echo '<a href="'.get_edit_post_link( $post_id ).'">'.get_post_meta( $post_id , 'nama_cs' , true ).'</a>';
            break;
			
        case 'followup_order' :
            $followup = get_post_meta( $post_id , 'followup_order' , true );
			echo '<span class="'.(($followup == 'no') ? '"' : 'tf-tooltip top followup-new" data-tip="CS menerima followup orderan baru"').'><i class="dashicons dashicons-yes-alt"></i></span>';
            break;
			
        case 'no_cs' :
			echo get_post_meta( $post_id , 'no_cs' , true );
            break;
			
        case 'posisi_cs' :
			echo (get_post_meta( $post_id , 'posisi_cs' , true )) ? get_post_meta( $post_id , 'posisi_cs' , true ) : '-';
            break;
			
        case 'jadwal_cs' :
			$postmeta = maybe_unserialize( get_post_meta( $post_id, 'jadwal_cs', true ) );
			$elements = array(
				'1'  => 'Senin',
				'2'  => 'Selasa',
				'3'  => 'Rabu',
				'4'  => 'Kamis',
				'5'  => 'Jumat',
				'6'  => 'Sabtu',
				'0'  => 'Minggu'
			);
			foreach ( $elements as $id => $element) {
				if ( is_array( $postmeta ) && in_array( $id, $postmeta ) ) {
					$checked = 'cs-active';
				} else {
					$checked = null;
				}
				echo '<span class="'.$checked.'" title="'.$element.'">'.$element[0].'</span>';
			}
			
            break;
			
    }
	return $column;
}

function themefood_process_variables($msg, $postID, $variables) {
	global $wpdb;
	if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
		$data_bank = ot_get_option( 'data_bank', array() );
	}
	$short_code = array( "id", "nama", "nowa", "email", "alamat", "kecamatan", "lokasi", "meja", "produk", "subtotal", "ongkir", "diskon", "unik", "fee", "delivtime", "total", "struk", "pembayaran", "instruksi" ); 
	$variables = str_replace(array("\r\n", "\r"), "\n", $variables);
	$variables = explode("\n", $variables);
	preg_match_all("/\[(.*?)\]/", $msg, $search);
	foreach ($search[1] as $variable) { 
		$variable = strtolower($variable);
		if (!in_array($variable, $short_code) && !in_array($variable, $variables)) continue;
		if ($variable == "id") {
			$result = $postID;
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "nama") {
			$result = get_post_meta( $postID , 'order_nama' , true );
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "nowa") {
			$result = get_post_meta( $postID , 'order_nowa' , true );
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "email") {
			$result = get_post_meta( $postID , 'order_email' , true );
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "alamat") {
			$result = (get_post_meta( $postID , 'order_alamat' , true ) !== '') ? get_post_meta( $postID , 'order_alamat' , true ) : '';
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "kecamatan") {
			$result = (get_post_meta( $postID , 'order_kec' , true ) !== '') ? get_post_meta( $postID , 'order_kec' , true ) : '';
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "lokasi") {
			$result = (get_post_meta( $postID , 'order_koordinat' , true ) !== '') ? 'https://maps.google.com/?daddr=' . get_post_meta( $postID , 'order_koordinat' , true ) : '';
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "meja") {
			$result = (get_post_meta( $postID, 'order_method', true ) == 'Dine In') ? get_post_meta( $postID , 'order_meja' , true ) : '-';
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "produk") {
			$json = get_post_meta($postID, 'order_produk', true);
			$json = json_decode($json, true);
			$result = '';
			$numItems = count($json);
			$i = 0;
			foreach($json as $item) {
				$i++;
				$new_line = ($i === $numItems) ? '' : '\n';
				$notes = !empty($item['summary']) ? $item['summary'] : '-';
				$result .= '*' . $i . '. ' . $item['name'] . '*\n' .
						   '📦 Qty: ' . $item['quantity'] . ' pcs\n' .
						   '️🏷️ Harga: ' . $currency_sym . number_format( $item['price'] * $item['quantity'], 0 , ',' , '.' ) . '\n' .
						   '📝 Catatan: ' . $notes . $new_line;
			}			
			$msg = str_replace("[" . $variable . "]", html_entity_decode($result), $msg);						
		}			
		if ($variable == "subtotal") {
			$result = get_post_meta( $postID , 'order_subtotal' , true );
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "ongkir") {
			$result = (get_post_meta( $postID , 'order_ongkir' , true ) !== '') ? get_post_meta( $postID , 'order_ongkir' , true ) : '-';
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}	
		if ($variable == "diskon") {
			$result = (get_post_meta($postID, 'order_nominaldiskon', true) !== $currency_sym.'0') ? get_post_meta($postID, 'order_nominaldiskon', true) .' ('. get_post_meta($postID, 'order_kodediskon', true) .')' : '-';
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "unik") {
			$result = (get_post_meta( $postID , 'order_unik' , true ) !== '') ? get_post_meta( $postID , 'order_unik' , true ) : '-';
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}
		if ($variable == "fee") {
			$result = (get_post_meta( $postID , 'order_fee' , true ) !== '') ? get_post_meta( $postID , 'order_fee' , true ) : '-';
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}
		if ($variable == "delivtime") {
			$result = (get_post_meta( $postID , 'order_delivtime' , true ) !== '') ? get_post_meta( $postID , 'order_delivtime' , true ) : '-';
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}
		if ($variable == "total") {
			$result = get_post_meta( $postID , 'order_total' , true );
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "struk") {
			$result = get_permalink( $postID ) . '?view=' . strtotime(get_the_time('c', $postID));
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}			
		if ($variable == "pembayaran") {
			$payment = get_post_meta( $postID , 'order_payment' , true );
			if(!empty($payment)) {
				$arrMetode = array('cod','bayar di kasir');
				if ( in_array(strtolower($payment), $arrMetode) ) {
					$result = $payment;						
				} else {
					if(strtolower($payment) !== 'transfer bank') {
						$new = array_filter($data_bank, function ($var) use ($payment) {
							return ($var['bank_name'] == $payment);
						});
						if(!empty($new)) {
							$result = '🏦 ' . array_values($new)[0]['bank_name'] . '\n' .
								  '️💳 ' . array_values($new)[0]['bank_number'] . ' an ' . array_values($new)[0]['bank_owner'];
						} else {
							$result = $payment;																			
						}
					} else {
						$result = $payment;												
					}
				}
			} else {
				$result = '';
			}
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}
		if ($variable == "instruksi") {
			$payment = str_replace(' ', '_', get_post_meta( $postID , 'order_payment' , true ) );
			if(!empty($payment)) {
				$arrMetode = array('cod','bayar di kasir');
				if ( in_array(strtolower($payment), $arrMetode) ) {
					if ( function_exists( 'ot_get_option' ) ) {
						$instruksi = ot_get_option( 'instruksi_' . strtolower($payment) );
					}									
				} else {
					$instruksi = ot_get_option( 'instruksi_transfer_bank' );
				}
			} else {
				$instruksi = '';				
			}
			$result = $instruksi;
			$msg = str_replace("[" . $variable . "]", $result, $msg);						
		}
	}
	return $msg;
}

function isMobile() {
    return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
}

function manage_tforder_column( $column, $post_id ) {
    switch ( $column ) {
        case 'orderan' :
			echo '<strong><a href="'.get_edit_post_link( $post_id ).'">#'.get_the_ID().' '.get_post_meta( $post_id , 'order_nama' , true ).'</a><span class="dashicons dashicons-arrow-down-alt2 detail-order"></span></strong><span class="'.get_post_meta( $post_id, 'order_method', true ).'">'.get_post_meta( $post_id, 'order_method', true ).'</span><button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>';
            break;
			
        case 'dipesan_pada' :
			echo humanTiming(strtotime(get_the_date('y-m-d H:i', $post_id)));
            break;
			
        case 'total_order' :
			echo get_post_meta( $post_id , 'order_total' , true );
            break;
			
        case 'no_whatsapp' :
			if ( function_exists( 'ot_get_option' ) ) {
				$order_diterima = ot_get_option( 'order_diterima' );
				$order_diterima_1 = ot_get_option( 'order_diterima_1' );
				$order_diterima_2 = ot_get_option( 'order_diterima_2' );
				$order_diterima_3 = ot_get_option( 'order_diterima_3' );
				$order_diproses = ot_get_option( 'order_diproses' );
				$order_diantar = ot_get_option( 'order_diantar' );
				$order_selesai = ot_get_option( 'order_selesai' );
				$order_dibatalkan = ot_get_option( 'order_dibatalkan' );
				$link_followup = ot_get_option( 'link_followup' ) ?: 'web';
			}
			$url = 'https://'.$link_followup.'.whatsapp.com/send';
			if(isMobile()){
				$url = 'whatsapp://send';
			}
	        $status = strip_tags(get_the_term_list( $post_id , 'order_status' , '' , ',' , '' ));
			$msg = themefood_process_variables(${"order_" . strtolower($status)}, get_the_ID(), '');
			$msg_fup1 = themefood_process_variables($order_diterima_1, get_the_ID(), '');
			$msg_fup2 = themefood_process_variables($order_diterima_2, get_the_ID(), '');
			$msg_fup3 = themefood_process_variables($order_diterima_3, get_the_ID(), '');
			$link = '';
			if($status == 'Diterima') {
				$link1 = '';
				$link2 = '';
				$link3 = '';
				if(!empty($msg_fup1)){
					$link1 = '<a href="'.$url.'?text='.str_replace('%5Cn', '%0A', rawurlencode($msg_fup1)).'&phone='.get_post_meta( $post_id , 'order_nowa' , true ).'" target="_blank" title="Follow-Up 1: '.ucwords($status).'" class="button fu-btn"><span class="dashicons dashicons-whatsapp"></span> FU 1 : <strong>'.ucwords($status).'</strong></a>';				
				}				
				if(!empty($msg_fup2)){
					$link2 = '<a href="'.$url.'?text='.str_replace('%5Cn', '%0A', rawurlencode($msg_fup2)).'&phone='.get_post_meta( $post_id , 'order_nowa' , true ).'" target="_blank" title="Follow-Up 2: '.ucwords($status).'" class="button fu-btn"><span class="dashicons dashicons-whatsapp"></span> FU 2 : <strong>'.ucwords($status).'</strong></a>';				
				}				
				if(!empty($msg_fup3)){
					$link3 = '<a href="'.$url.'?text='.str_replace('%5Cn', '%0A', rawurlencode($msg_fup3)).'&phone='.get_post_meta( $post_id , 'order_nowa' , true ).'" target="_blank" title="Follow-Up 3: '.ucwords($status).'" class="button fu-btn"><span class="dashicons dashicons-whatsapp"></span> FU 3 : <strong>'.ucwords($status).'</strong></a>';				
				}
				$link = $link1 . $link2 . $link3;
			} else {
				if(!empty($msg)){
					$link = '<a href="'.$url.'?text='.str_replace('%5Cn', '%0A', rawurlencode($msg)).'&phone='.get_post_meta( $post_id , 'order_nowa' , true ).'" target="_blank" title="Follow-Up : '.ucwords($status).'" class="button fu-btn"><span class="dashicons dashicons-whatsapp"></span> FU : <strong>'.ucwords($status).'</strong></a>';				
				}
			}
			echo get_post_meta( $post_id , 'order_nowa' , true ) . $link;
            break;
			
        case 'pengiriman' :
			echo (get_post_meta( $post_id , 'order_ongkir' , true )) ? get_post_meta( $post_id , 'order_ongkir' , true ) : '';
            break;
			
        case 'no_meja' :
			echo (get_post_meta( $post_id, 'order_method', true ) == 'Dine In') ? get_post_meta( $post_id , 'order_meja' , true ) : '';
            break;

        case 'metode_pembayaran' :
			$selected_payment = get_post_meta( $post_id, 'order_payment', true );
			if ( preg_match('[bni|bnis]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-bni.webp">';
			if ( preg_match('[mandiri]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-mandiri.webp">';
			if ( preg_match('[bsm|syariah mandiri]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-bsm.webp">';
			if ( preg_match('[bca|bcas|bca syariah]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-bca.webp">';
			if ( preg_match('[bri|bris|bri syariah]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-bri.webp">';
			if ( preg_match('[btpn]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-btpn.webp">';
			if ( preg_match('[cimb]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-cimb.webp">';
			if ( preg_match('[bjb]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-bjb.webp">';
			if ( preg_match('[bsi|syariah indonesia]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-bsi.webp">';
			if ( preg_match('[btn|tabungan negara]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-btn.webp">';
			if ( preg_match('[mega]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-mega.webp">';
			if ( preg_match('[danamon]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-danamon.webp">';
			if ( preg_match('[ocbc|nisp]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-ocbc.webp">';
			if ( preg_match('[muamalat]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-muamalat.webp">';
			if ( preg_match('[permata]', strtolower($selected_payment)) ) $selected_payment = '<img src="' . get_template_directory_uri() . '/img/bank/logo-bank-permata.webp">';
			echo $selected_payment ?: '';
            break;

        case 'status_order' :
	        $status = strip_tags(get_the_term_list( $post_id , 'order_status' , '' , ',' , '' ));
			?>
				<select name="status_order_<?=$post_id?>" data-id="<?=$post_id?>" class="select_status" data-status="<?=$status?>" onchange="this.dataset.status = this.value;">
					<option value="Diterima" <?php selected( $status, 'Diterima' ); ?>>Diterima</option>
					<option value="Diproses" <?php selected( $status, 'Diproses' ); ?>>Diproses</option>
					<option value="Diantar" <?php selected( $status, 'Diantar' ); ?>>Diantar</option>
					<option value="Selesai" <?php selected( $status, 'Selesai' ); ?>>Selesai</option>
					<option value="Dibatalkan" <?php selected( $status, 'Dibatalkan' ); ?>>Dibatalkan</option>
				</select>
				<span></span>
			<?php
            break;
			
        case 'aksi' :
			echo '<a href="'.get_permalink( $post_id ).'?view='.strtotime(get_the_time('c')).'" target="_blank" title="Lihat Struk" class="button"><span class="dashicons dashicons-visibility"></span> Lihat Struk</a> <a href="'.get_permalink( $post_id ).'?view='.strtotime(get_the_time('c')).'&print=1" target="_blank" title="Cetak Struk" class="button"><span class="dashicons dashicons-printer"></span> Cetak Struk</a> <a href="#" class="button catatan-order '.((get_post_meta( $post_id , 'catatan_order' , true )) ? 'tf-tooltip left' : '').'" style="'.((get_post_meta( $post_id , 'catatan_order' , true )) ? 'color:red' : '').'" data-tip="'.((get_post_meta( $post_id , 'catatan_order' , true )) ? get_post_meta( $post_id , 'catatan_order' , true ) : '').'" title="Catatan Order" data-id="'.$post_id.'"><span class="dashicons dashicons-welcome-write-blog"></span> Catatan Order</a>';
            break;
			
    }
	return $column;
}
function humanTiming($time) {
	$timeRaw = $time;
    $time = current_time( 'timestamp' ) - $time;
    $time = ($time<1)? 1 : $time;
    $tokens = array (
        3600 => 'jam',
        60 => 'menit',
		1 => 'detik'
    );
    foreach ($tokens as $unit => $text) {
		if($time < 86400) {
			if ($time < $unit) continue;
			$numberOfUnits = floor($time / $unit);
			return $numberOfUnits.' '.$text.' lalu';
		} else {
			return date("d M Y",$timeRaw);
		}
    }
}
function tfproduk_sortable_columns( $columns ) {
    $columns['kategori_produk'] = 'kategori_produk';
    $columns['stok_produk'] = 'stok_produk';
    $columns['view_produk'] = 'view_produk';
    return $columns;
}
function tfslider_sortable_columns( $columns ) {
    $columns['target_slider'] = 'target_slider';
    return $columns;
}
function tforder_sortable_columns( $columns ) {
    $columns['orderan'] = 'orderan';
    $columns['status_order'] = 'status_order';
    $columns['dipesan_pada'] = 'dipesan_pada';
    $columns['total_order'] = 'total_order';
    return $columns;
}
function tfproduk_row_actions( $actions, $post ) {
    if ( $post->post_type === 'tf-order' ) {
        return array();
    }
    if ( $post->post_type === 'tf-produk' || $post->post_type === 'tf-info' || $post->post_type === 'tf-slider' || $post->post_type === 'tf-kupon' || $post->post_type === 'tf-cs' ) {
        unset($actions['inline hide-if-no-js']);
    }
    if ( $post->post_type === 'tf-slider' || $post->post_type === 'tf-kupon' || $post->post_type === 'tf-cs' ) {
        unset($actions['view']);
    }
    return $actions;
}
function tfproduk_cat_custom_column_query( $clauses, $wp_query ) {
	global $wpdb;
	if(isset($wp_query->query['orderby']) && $wp_query->query['orderby'] == 'kategori_produk'){
		$clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
		$clauses['where'] .= "AND (taxonomy = 'kategori_produk' OR taxonomy IS NULL)";
		$clauses['groupby'] = "object_id";
		$clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC)";
		if(strtoupper($wp_query->get('order')) == 'ASC'){
			$clauses['orderby'] .= 'ASC';
		} else{
			$clauses['orderby'] .= 'DESC';
		}
	}
	return $clauses;
}
function tforder_status_custom_column_query( $clauses, $wp_query ) {
	global $wpdb;
	if(isset($wp_query->query['orderby']) && $wp_query->query['orderby'] == 'order_status'){
		$clauses['join'] .= <<<SQL
LEFT OUTER JOIN {$wpdb->term_relationships} ON {$wpdb->posts}.ID={$wpdb->term_relationships}.object_id
LEFT OUTER JOIN {$wpdb->term_taxonomy} USING (term_taxonomy_id)
LEFT OUTER JOIN {$wpdb->terms} USING (term_id)
SQL;
		$clauses['where'] .= "AND (taxonomy = 'order_status' OR taxonomy IS NULL)";
		$clauses['groupby'] = "object_id";
		$clauses['orderby'] = "GROUP_CONCAT({$wpdb->terms}.name ORDER BY name ASC)";
		if(strtoupper($wp_query->get('order')) == 'ASC'){
			$clauses['orderby'] .= 'ASC';
		} else{
			$clauses['orderby'] .= 'DESC';
		}
	}
	return $clauses;
}
function tf_sortable_by_meta( $query ) {
   if ( $query->is_main_query() && $query->get( 'orderby' ) === 'stok_produk' ) {
        $query->set( 'meta_key', 'stok_produk' );
        $query->set( 'orderby', 'meta_value' );
   }
   if ( $query->is_main_query() && $query->get( 'orderby' ) === 'view_produk' ) {
        $query->set( 'meta_key', '_produk_count' );
        $query->set( 'orderby', 'meta_value_num' );
   }
   if ( $query->is_main_query() && $query->get( 'orderby' ) === 'target_slider' ) {
        $query->set( 'meta_key', 'slider_target' );
        $query->set( 'orderby', 'meta_value' );
   }
   if ( $query->is_main_query() && $query->get( 'orderby' ) === 'total_order' ) {
        $query->set( 'meta_key', 'order_total' );
        $query->set( 'orderby', 'meta_value' );
   }
   if ( $query->is_main_query() && $query->get( 'orderby' ) === 'orderan' ) {
        $query->set( 'orderby', 'title' );
   }
   if ( $query->is_main_query() && $query->get( 'orderby' ) === 'dipesan_pada' ) {
        $query->set( 'orderby', 'modified' );
   }
   if (is_tax('order_status')){
        $query->set_404();
   }
}

// Custom Bulk Action
add_filter( 'bulk_actions-edit-tf-order', 'status_bulk_actions' );
function status_bulk_actions( $bulk_array ) {
	$bulk_array['status_diterima'] = 'Ubah status ke diterima';
	$bulk_array['status_diproses'] = 'Ubah status ke diproses';
	$bulk_array['status_diantar'] = 'Ubah status ke diantar';
	$bulk_array['status_selesai'] = 'Ubah status ke selesai';
	$bulk_array['status_dibatalkan'] = 'Ubah status ke dibatalkan';
	return $bulk_array; 
}

add_filter( 'handle_bulk_actions-edit-tf-order', 'status_bulk_action_handler', 10, 3 );
function status_bulk_action_handler( $redirect, $doaction, $object_ids ) {
	$redirect = remove_query_arg( array( 'bulk_status_changed' ), $redirect ); 
	if ( $doaction == 'status_diterima' ) {
		foreach ( $object_ids as $post_id ) {
		   $term = get_term_by('slug', 'diterima', 'order_status');
		   wp_set_object_terms( $post_id, $term->term_id, 'order_status' );   
		}
		$redirect = add_query_arg( 'bulk_status_changed', count( $object_ids ), $redirect );
	}
	if ( $doaction == 'status_diproses' ) {
		foreach ( $object_ids as $post_id ) {
		   $term = get_term_by('slug', 'diproses', 'order_status');
		   wp_set_object_terms( $post_id, $term->term_id, 'order_status' );   
		}
		$redirect = add_query_arg( 'bulk_status_changed', count( $object_ids ), $redirect );
	}
	if ( $doaction == 'status_diantar' ) {
		foreach ( $object_ids as $post_id ) {
		   $term = get_term_by('slug', 'diantar', 'order_status');
		   wp_set_object_terms( $post_id, $term->term_id, 'order_status' );   
		}
		$redirect = add_query_arg( 'bulk_status_changed', count( $object_ids ), $redirect );
	}
	if ( $doaction == 'status_selesai' ) {
		foreach ( $object_ids as $post_id ) {
		   $term = get_term_by('slug', 'selesai', 'order_status');
		   wp_set_object_terms( $post_id, $term->term_id, 'order_status' );   
		}
		$redirect = add_query_arg( 'bulk_status_changed', count( $object_ids ), $redirect );
	}
	if ( $doaction == 'status_dibatalkan' ) {
		foreach ( $object_ids as $post_id ) {
		   $term = get_term_by('slug', 'dibatalkan', 'order_status');
		   wp_set_object_terms( $post_id, $term->term_id, 'order_status' );   
		}
		$redirect = add_query_arg( 'bulk_status_changed', count( $object_ids ), $redirect );
	}
	if (is_plugin_active('wp-rest-cache/wp-rest-cache.php')){
		\WP_Rest_Cache_Plugin\Includes\Caching\Caching::get_instance()->delete_cache_by_endpoint( '/wp-json/wp/v2/tf-order?per_page=10' );		
	}
	return $redirect;
}

add_action( 'admin_notices', 'status_bulk_action_notices' );
function status_bulk_action_notices() {
	if ( ! empty( $_REQUEST['bulk_status_changed'] ) ) {
		echo '<div id="message" class="updated notice is-dismissible">
			<p>'.intval( $_REQUEST['bulk_status_changed'] ).' Status orderan berhasil diubah.</p>
		</div>';
	} 
}

// Edit filter link cpt order
add_action( 'views_edit-tf-order', 'status_filter_views' );
function status_filter_views( $views ) {
    global $wp_query;  
    unset($views['mine']);
    unset($views['publish']);
    unset($views['trash']);
	
    $types = array(  
        array( 'status' => 'diterima' ),  
        array( 'status' => 'diproses' ),  
        array( 'status' => 'diantar' ),  
        array( 'status' => 'selesai' ),  
        array( 'status' => 'dibatalkan' ),  
        array( 'status' => 'trash' )  
    );  
    foreach( $types as $type ) {  
        $query = array(  
            'post_type'   => 'tf-order',  
            'post_status' => 'publish',  
			'tax_query' => array(
				array (
					'taxonomy' => 'order_status',
					'field' => 'slug',
					'terms' => $type['status']
				)
			)
        );  
        $result = new WP_Query($query);  
        $query_trash = array(  
            'post_type'   => 'tf-order',  
            'post_status' => 'trash'
        );  
        $result_trash = new WP_Query($query_trash);  
        if( $type['status'] == 'diterima' ):  
            $class = (isset($_GET['order_status']) && $_GET['order_status'] == 'diterima') ? ' class="current"' : '';  
            $views['diterima'] = sprintf(__('<a href="%s"'. $class .'>Diterima <span class="count">(%d)</span></a>', 'diterima'),  
                admin_url('edit.php?post_status=publish&post_type=tf-order&order_status=diterima'),  
                $result->found_posts);  
        elseif( $type['status'] == 'diproses' ):  
            $class = (isset($_GET['order_status']) && $_GET['order_status'] == 'diproses') ? ' class="current"' : '';  
            $views['diproses'] = sprintf(__('<a href="%s"'. $class .'>Diproses <span class="count">(%d)</span></a>', 'diproses'),  
                admin_url('edit.php?post_status=publish&post_type=tf-order&order_status=diproses'),  
                $result->found_posts);  
        elseif( $type['status'] == 'diantar' ):  
            $class = (isset($_GET['order_status']) && $_GET['order_status'] == 'diantar') ? ' class="current"' : '';  
            $views['diantar'] = sprintf(__('<a href="%s"'. $class .'>Diantar <span class="count">(%d)</span></a>', 'diantar'),  
                admin_url('edit.php?post_status=publish&post_type=tf-order&order_status=diantar'),  
                $result->found_posts);  
        elseif( $type['status'] == 'selesai' ):  
            $class = (isset($_GET['order_status']) && $_GET['order_status'] == 'selesai') ? ' class="current"' : '';  
            $views['selesai'] = sprintf(__('<a href="%s"'. $class .'>Selesai <span class="count">(%d)</span></a>', 'selesai'),  
                admin_url('edit.php?post_status=publish&post_type=tf-order&order_status=selesai'),  
                $result->found_posts);  
        elseif( $type['status'] == 'dibatalkan' ):  
            $class = (isset($_GET['order_status']) && $_GET['order_status'] == 'dibatalkan') ? ' class="current"' : '';  
            $views['dibatalkan'] = sprintf(__('<a href="%s"'. $class .'>Dibatalkan <span class="count">(%d)</span></a>', 'dibatalkan'),  
                admin_url('edit.php?post_status=publish&post_type=tf-order&order_status=dibatalkan'),  
                $result->found_posts);  
        elseif( $type['status'] == 'trash' ):  
            $class = ($wp_query->query_vars['post_status'] == 'trash') ? ' class="current"' : '';  
            $views['trash'] = sprintf(__('<a href="%s"'. $class .'>Trash <span class="count">(%d)</span></a>', 'trash'),  
                admin_url('edit.php?post_status=trash&post_type=tf-order'),  
                $result_trash->found_posts);  
        endif;  
    }  		
	
    return $views;
}

// Extend search feature on tf-order admin 
function cf_search_join( $join ) {
    global $wpdb;
    if ( is_search() && !empty($_GET['s']) ) {    
        $join .=' LEFT JOIN '.$wpdb->postmeta. ' ON '. $wpdb->posts . '.ID = ' . $wpdb->postmeta . '.post_id ';
    }
    return $join;
}
add_filter('posts_join', 'cf_search_join' );
function cf_search_where( $where ) {
    global $pagenow, $wpdb;
    if ( is_search() && !empty($_GET['s']) ) {
        $where = preg_replace(
            "/\(\s*".$wpdb->posts.".post_title\s+LIKE\s*(\'[^\']+\')\s*\)/",
            "(".$wpdb->posts.".post_title LIKE $1) OR (".$wpdb->postmeta.".meta_value LIKE $1)", $where );
    }
    return $where;
}
add_filter( 'posts_where', 'cf_search_where' );
function cf_search_distinct( $where ) {
    global $wpdb;
    if ( is_search() && !empty($_GET['s']) ) {
        return "DISTINCT";
    }
    return $where;
}
add_filter( 'posts_distinct', 'cf_search_distinct' );

// Filter Tipe Order
add_action( 'restrict_manage_posts', 'tipe_order_filter_restrict_manage_posts' );
function tipe_order_filter_restrict_manage_posts(){
  global $typenow;
  global $wp_query;
    if ( $typenow == 'tf-order' ) {
      $tipe = array( 'Delivery', 'Dine In' );
      $tipe_now = '';
      if( isset( $_GET['tipe_order'] ) ) {
        $tipe_now = $_GET['tipe_order'];
      } ?>
      <select name="tipe_order" id="tipe_order">
        <option value="Tipe Order" <?php selected( 'Tipe Order', $tipe_now ); ?>><?php _e( 'Tipe Order', 'themefood' ); ?></option>
        <?php foreach( $tipe as $opsi ) { ?>
          <option value="<?php echo esc_attr( $opsi ); ?>" <?php selected( $opsi, $tipe_now ); ?>><?php echo esc_attr( $opsi ); ?></option>
        <?php } ?>
      </select>
  <?php }
	if ($typenow == 'tf-produk') {
		$filters = array('kategori_produk');
        foreach ($filters as $tax_slug) {
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;
            $terms = get_terms($tax_slug);
			$kat_now = '';
			if( isset( $_GET[$tax_slug] ) ) {
				$kat_now = $_GET[$tax_slug];
			}
            echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
            echo "<option value=''>Semua $tax_name</option>";
            foreach ($terms as $term) {
                echo '<option value='. $term->slug .' '. selected( $term->slug, $kat_now ) .'>' . $term->name .' (' . $term->count .')</option>';
            }
            echo "</select>";
        }
    }  
}

add_filter( 'parse_query', 'tipe_order_posts_filter' );
function tipe_order_posts_filter( $query, $error = true ){
  global $pagenow;
  $post_type = isset( $_GET['post_type'] ) ? $_GET['post_type'] : '';
  if ( is_admin() && $pagenow=='edit.php' && $post_type == 'tf-order' && isset( $_GET['tipe_order'] ) && $_GET['tipe_order'] !='Tipe Order' && $query->is_main_query() ) {
    $query->query_vars['meta_key'] = 'order_method';
    $query->query_vars['meta_value'] = $_GET['tipe_order'];
    $query->query_vars['meta_compare'] = '=';
  }
  if (is_search() && !is_admin()) {
	$query->is_search = false;
	$query->query_vars['s'] = false;
	$query->query['s'] = false;
	if ($error == true) $query->is_404 = true;
  }
}

add_action('post_updated', 'themefood_send_whatsapp_bot');
// function themefood_send_whatsapp_bot($post_id,$msg_follup){
function themefood_send_whatsapp_bot($post_id){
	global $post;
	$post = get_post($post_id);
    $post_id = $post->ID;
	$post_type = 'tf-order';
	if(get_post_type($post) === $post_type) {
		themefood_send_email_wa($post_id,'');
	}
}

function themefood_send_email_wa($post_id,$msg_follup) {
	$phone = get_post_meta( $post_id , 'order_nowa' , true );
	$to_email = get_post_meta( $post_id, 'order_email', true );
	$status = strip_tags(get_the_term_list( $post_id , 'order_status' , '' , ',' , '' ));
	if ( function_exists( 'ot_get_option' ) ) {
		$key = ot_get_option( 'token_key' );	
		$api = ot_get_option( 'bot_service' );
		$order_diterima = ot_get_option( 'order_diterima' );
		$order_diproses = ot_get_option( 'order_diproses' );
		$order_diantar = ot_get_option( 'order_diantar' );
		$order_selesai = ot_get_option( 'order_selesai' );
		$order_dibatalkan = ot_get_option( 'order_dibatalkan' );
		$favicon_website = ot_get_option( 'favicon_website' );
		if($favicon_website == '') $favicon_website = preg_replace('#^https?://#', '', get_template_directory_uri()) . '/img/icon-48.png';
		$logo_website = ot_get_option( 'logo_website' );
		$nama_toko = ot_get_option( 'nama_toko' ) ?: get_bloginfo( 'name' );
		$background_struk = ot_get_option( 'background_struk' );
	}
	$msg = themefood_process_variables(${"order_" . strtolower($status)}, $post_id, '');
	if(!empty($msg_follup)) $msg = $msg_follup;
	if( !empty($key) && !empty($msg) ) {
		themefood_send_wa_msg($phone,$key,$msg,$api);				
	}
	if( !empty($to_email)) {
		$email_content = $msg;
		$email_content = str_replace(["\r\n", "\r", "\n"], "<br/>", $email_content);
		$email_content = preg_replace('#\*{1}(.*?)\*{1}#', '<b>$1</b>', $email_content);
		$email_content = preg_replace('/(http|https)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/', '<a href="$0" target="_blank" title="$0">$0</a>', $email_content);
		$email_content = str_replace('\n', '<br/>', $email_content);
		$email_bg_color = $background_struk['background-color'] ? 'background-color:'.$background_struk['background-color'].';' : '';
		$email_bg_repeat = $background_struk['background-repeat'] ? 'background-repeat:'.$background_struk['background-repeat'].';' : '';
		$email_bg_attachment = $background_struk['background-attachment'] ? 'background-attachment:'.$background_struk['background-attachment'].';' : '';
		$email_bg_position = $background_struk['background-position'] ? 'background-position:'.$background_struk['background-position'].';' : '';
		$email_bg_size = $background_struk['background-size'] ? 'background-size:'.$background_struk['background-size'].';' : '';
		$email_bg_image = $background_struk['background-image'] ? 'background-image:url('.$background_struk['background-image'].');' : 'background-image:url('.get_template_directory_uri().'/img/bg-pattern.jpg'.');';				
		$email_logo = (($logo_website == '') ? '<img src="'.$favicon_website.'" style="vertical-align:middle;display:inline-block;margin-right:10px;max-height:40px;max-width:100%;"><span style="font-size:24px;font-weight:700;vertical-align:middle;display:inline-block;">'.$nama_toko.'</span>' : '<img src="'.$logo_website.'" style="margin:0 auto;display:block;max-height:40px;max-width:100%;">');
		$email_content_html = <<<HTML
<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<meta name="x-apple-disable-message-reformatting">
<title></title>
</head>
<body style="margin:0;padding:1.5em; $email_bg_color $email_bg_repeat $email_bg_attachment $email_bg_position $email_bg_size $email_bg_image">  
<div style="background:#fff;box-sizing:border-box;font-family:Arial,sans-serif;padding:1.5em;line-height:1.6;font-size:15px;max-width:480px;margin:0 auto;word-wrap:break-word;">
<div style="text-align:center;margin-bottom:20px;">$email_logo</div>
$email_content
</div>
</body>
</html>
HTML;
		$headers = array('Content-Type: text/html; charset=UTF-8');
		$mail = wp_mail($to_email, sprintf( __( 'Orderan #%s telah ', 'themefood' ) . strtolower($status), $post_id ), $email_content_html, $headers);
	}
}

add_action( 'admin_menu', 'themefood_options_menu', 999 );
function themefood_options_menu() {
	global $menu;
	add_submenu_page(
		'themefood-options',
		__( 'ThemeFood Options', 'themefood' ),
		__( 'ThemeFood Options', 'themefood' ),
		'manage_options', 
		'themefood-options',
		''
	);
	if(is_valid_page()):
	add_submenu_page(
		'themefood-options',
		__( 'Laporan Orderan', 'themefood' ),
		__( 'Laporan Orderan', 'themefood' ),
		'manage_options', 
		'laporan_orderan',
		'laporan_page'
	);
	add_submenu_page(
		'themefood-options',
		__( 'Laporan CS', 'themefood' ),
		__( 'Laporan CS', 'themefood' ),
		'manage_options', 
		'laporan_cs',
		'cs_page'
	);
	add_submenu_page(
		'themefood-options',
		__( 'Menu QR-Code', 'themefood' ),
		__( 'Menu QR-Code', 'themefood' ),
		'manage_options', 
		'menu_qrcode',
		'qrcode_page'
	);
	if (is_plugin_active('one-click-demo-import/one-click-demo-import.php')){
		add_submenu_page(
			'themefood-options',
			__( 'Import Demo Data', 'themefood' ),
			__( 'Import Demo Data', 'themefood' ),
			'manage_options', 
			'themes.php?page=one-click-demo-import',
			''
		);
	}
	$menu_item = wp_list_filter(
		$menu,
		array( 2 => 'edit.php?post_type=tf-order' )
	); 
	if ( ! empty( $menu_item )  ) {		
		$term_diterima = get_term_by( 'slug', 'diterima', 'order_status' );
		$menu_item_position = key( $menu_item );
		$menu[ $menu_item_position ][0] .= ' <span class="awaiting-mod">' . $term_diterima->count . '</span>';
	}
	endif;
} 

function getTplPageURL($TEMPLATE_NAME){
    $url = null;
    $pages = get_pages(array(
        'meta_key' => '_wp_page_template',
        'meta_value' => $TEMPLATE_NAME
    ));
    if(isset($pages[0])) {
        $url = get_page_link($pages[0]->ID);
    }
	$urlLink = parse_url($url);
    return $url;
}

function qrcode_page() {
	if ( function_exists( 'ot_get_option' ) ) {
	  $site_title = ot_get_option( 'nama_toko' );
	  if($site_title == '') $site_title = get_bloginfo( 'name' );
	  $favicon_website = ot_get_option( 'favicon_website' );
	  if($favicon_website == '') $favicon_website = get_template_directory_uri() . '/img/icon-48.png';
	  $logo_website = ot_get_option( 'logo_website' );
	}
	?>
		<style>
			#wpcontent, #wpbody-content {
				padding: 0;
			}
			#themefood-wrap {
				text-align: center;
				background: repeat url('<?=get_template_directory_uri()?>/img/back.png');
				height: 100vh;
				width: 100%;
				position: fixed;
			}
			#themefood-wrap #container {
				display: inline-block;
				margin: 20px auto;
				box-shadow: 0 0 16px rgba(0,0,0,0.5);
				-webkit-print-color-adjust: exact;
			}
			#themefood-wrap #container > * {
				display: block;
			}
			#themefood-wrap .control {
				position: absolute;
				background-color: #f8f8f8;
				top: 10px;
				left: 20px;
				width: 190px;
				box-shadow: 0 0 32px rgba(0,0,0,0.5);
				overflow: hidden;
				text-align: left;
				padding: 10px;
			}
			#themefood-wrap hr {
				margin: 12px 0 0;
				padding: 0;
				border: none;
				height: 1px;
				background-color: rgba(0,0,0,0.1);
			}
			#themefood-wrap label {
				display: block;
				font-size: 12px;
				color: #555;
				padding: 5px 0;
			}
			#themefood-wrap input, #themefood-wrap textarea, #themefood-wrap select {
				display: block;
				background-color: #fff;
				margin: 0;
				padding: 0 5px;
				border: 1px solid #ddd;
				width: 100%;
				height: 22px;
			}
			#themefood-wrap #text {
				height: 48px;
			}
			#themefood-wrap #img-buffer {
				display: none;
			}
			#themefood-wrap input[type='range'] {
				-webkit-appearance: none;
				cursor: pointer;
			}
			#themefood-wrap input::-webkit-slider-thumb {
				-webkit-appearance: none;
				width: 16px;
				height: 16px;
				border-radius: 3px;
				background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0, #aaa), color-stop(1, #aaa));
			}
			#themefood-wrap .control #download button {
				width: 100%;
				padding: 5px 0;
				margin-top: 10px;
				background: #2271b1;
				color: #fff;
				border: 0;
				border-radius: 4px;
				cursor: pointer;
				border-bottom: 4px solid rgba(0,0,0,.3);
			}
			#themefood-wrap .color-group {
				display: flex;
			}
			#themefood-wrap .color-group > div {
				flex: 1;
				padding: 0 3px;
			}
			@media only screen and (max-width: 480px) {
				.auto-fold #wpcontent {
					padding: 0;
				}
				#themefood-wrap {
					margin: 0;
					padding: 10px;
					box-sizing: border-box;
				}
				#themefood-wrap #container {
					margin: 0;
				}
				#themefood-wrap, #themefood-wrap .control {
					position: relative;
				}
				#themefood-wrap .control {
					width: 100%;
					left: 0;
					box-sizing: border-box;
				}
				#themefood-wrap #container canvas {
					max-width: 100%;
				}
			}
			@media print {
				#themefood-wrap #container {
					box-shadow: none;
				}
				#themefood-wrap .control, #wpadminbar, #adminmenumain {
					display: none;
				}
				#wpcontent, #wpfooter {
					margin: 0;
				}
			}		
		</style>
        <div class="wrap" id="themefood-wrap">
			<div id="container"></div>
			<div class="control left">
				<label for="mode">Mode QR</label><select id="mode">
					<option selected="selected" value="2">
						Teks
					</option>
					<option value="4">
						Logo
					</option>
				</select>
				<hr>
				<label for="size">Ukuran QR:</label>
				<input id="size" max="1000" min="100" step="50" type="range" value="400">
				<hr>
				<label for="msize">Ukuran Objek:</label>
				<input id="msize" max="40" min="0" step="1" type="range" value="11">
				<hr>
				<div class="color-group">
					<div>
						<label for="fill">Fill</label>
						<input id="fill" type="color" value="#333333">
					</div>
					<div>
						<label for="background">BG</label>
						<input id="background" type="color" value="#ffffff">
					</div>
					<div>
						<label for="fontcolor">Font</label>
						<input id="fontcolor" type="color" value="#079c10">
					</div>
				</div>
				<hr>
				<img id="img-buffer" src="<?=(($logo_website == '') ? $favicon_website : $logo_website)?>">
				<a id="download" download="qrcode.png">
					<button type="button" onClick="download()">Download QR Code</button>
				</a>
			</div>
        </div>
		<script src="<?=get_template_directory_uri()?>/js/jquery-qrcode-0.18.0.min.js"></script>
		<script>
		function download() {
			var download = document.getElementById("download");
			var image = document.querySelector("canvas").toDataURL("image/png")
				.replace("image/png", "image/octet-stream");
			download.setAttribute("href", image);
		}
		const WIN = window;
		const JQ = WIN.jQuery;
		const GUI_VALUE_PAIRS = [
			['size', 'px'],
			['minversion', ''],
			['quiet', ' modules'],
			['radius', '%'],
			['msize', '%'],
			['mposx', '%'],
			['mposy', '%']
		];
		const update_gui = () => {
			JQ.each(GUI_VALUE_PAIRS, (idx, pair) => {
				const $label = JQ('label[for="' + pair[0] + '"]');
				$label.text($label.text().replace(/:.*/, ': ' + JQ('#' + pair[0]).val() + pair[1]));
			});
		};
		const update_qrcode = () => {
			const options = {
				render: 'canvas',
				ecLevel: 'H',
				minVersion: parseInt(6, 10),
				fill: JQ('#fill').val(),
				background: JQ('#background').val(),
				text: '<?=getTplPageURL("page-katalog.php")?>',
				size: parseInt(JQ('#size').val(), 10),
				radius: parseInt(50, 10) * 0.01,
				quiet: parseInt(1, 10),
				mode: parseInt(JQ('#mode').val(), 10),
				mSize: parseInt(JQ('#msize').val(), 10) * 0.01,
				mPosX: parseInt(50, 10) * 0.01,
				mPosY: parseInt(50, 10) * 0.01,
				label: '<?=$site_title?>',
				fontname: 'Monospace',
				fontcolor: JQ('#fontcolor').val(),
				image: JQ('#img-buffer')[0]
			};
			JQ('#container').empty().qrcode(options);
		};
		const update = () => {
			update_gui();
			update_qrcode();
		};
		const init = () => {
			JQ('input, textarea, select').on('input change', update);
			JQ(WIN).on('load', update);
			update();
		};
		JQ(init);		
		</script>
	<?php
}

function laporan_page() {
	if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
	}
	$date = new DateTime();
	$start = 'first day of this month';
	$end = 'today';
	if(isset($_GET['start'])) $start = $_GET['start'];
	$beforeDate = $date->modify($start)->format('Y-m-d\T00:00:00');
	if(isset($_GET['end'])) {
		$end = $_GET['end'];		
		$afterDate = $date->modify($end)->format('Y-m-d\T23:59:59');
	} else {
		$afterDate = date('Y-m-d\T23:59:59');		
	}
	$json = url_get_contents(get_bloginfo('url') . '/wp-json/wp/v2/tf-order?per_page=1000&before=' . $afterDate . '&after=' . $beforeDate . '&skip_cache=1');
	$obj = json_decode($json, true);
	
	$period = new DatePeriod(
		 new DateTime($start),
		 new DateInterval('P1D'),
		 new DateTime($end)
	);
	$output = array();
	$statuses = array('Diterima','Diproses','Diantar','Selesai','Dibatalkan');
	foreach ($period as $key => $value) {
		$d = $value->format('d-m-Y');
		if(!isset($output[$d])) {
			$output[$d] = array();
		}
		foreach($statuses as $status) {
			$output[$d][$status] = 0;
		}
		foreach($obj as $item) {
			$d = date("d-m-Y",strtotime($item['date']));
			$stts = $item['status_order'];
			foreach($statuses as $status) {
				if($d == $value->format('d-m-Y') && $stts == $status){
					$output[$d][$status] += 1;	
				}
			}
		}	
	}
	$total = [];
	foreach($statuses as $status) {
		$total[$status] = 0;
		foreach($obj as $tot) {
			$stts = $tot['status_order'];
			if($stts == $status){
				$total[$tot['status_order']] += intval(preg_replace('/[^0-9]/', '', $tot['order_total']));
			}
		}
	}
	?>
	<link rel="stylesheet" type="text/css" href="<?=get_template_directory_uri() . '/css/daterangepicker.min.css';?>" />
	<link rel="stylesheet" type="text/css" href="<?=get_template_directory_uri() . '/css/jquery.dataTables.min.css';?>" />
	<script src="<?=get_template_directory_uri() . '/js/moment.min.js';?>"></script>
	<script src="<?=get_template_directory_uri() . '/js/daterangepicker.min.js';?>"></script>
	<script src="<?=get_template_directory_uri() . '/js/Chart.min.js';?>"></script>	
	<script src="<?=get_template_directory_uri() . '/js/jquery.dataTables.min.js';?>"></script>
	<script src="<?=get_template_directory_uri() . '/js/dataTables.buttons.min.js';?>"></script>
	<script src="<?=get_template_directory_uri() . '/js/jszip.min.js';?>"></script>
	<script src="<?=get_template_directory_uri() . '/js/buttons.html5.min.js';?>"></script>
	<script src="<?=get_template_directory_uri() . '/js/buttons.print.min.js';?>"></script>
	
	<style>#wpbody-content #dashboard-widgets .postbox-container { width: 100%; }</style>
	
	<div class="wrap laporan-orderan" id="themefood-wrap">
	  <h1 class="wp-heading-inline"><?php echo __( 'Laporan Orderan' , 'themefood' )?></h1>
	  <span class="filter_tanggal"><span class="dashicons dashicons-calendar-alt"></span> <input type="text" name="daterange" value="<?=date("m/d/Y", strtotime($beforeDate))?> - <?=date("m/d/Y", strtotime($afterDate))?>"/></span>

		<div class="row count-status_order">
			<div class="col status_diterima">
				<strong><?=(!empty(array_count_values(array_column($obj, 'status_order'))['Diterima'])? array_count_values(array_column($obj, 'status_order'))['Diterima'] : '0')?></strong>
				<span><?php echo __( 'Orderan<br>Diterima' , 'themefood' )?></span>
			</div>
			<div class="col status_diproses">
				<strong><?=(!empty(array_count_values(array_column($obj, 'status_order'))['Diproses'])? array_count_values(array_column($obj, 'status_order'))['Diproses'] : '0')?></strong>
				<span><?php echo __( 'Orderan<br>Diproses' , 'themefood' )?></span>
			</div>
			<div class="col status_diantar">
				<strong><?=(!empty(array_count_values(array_column($obj, 'status_order'))['Diantar'])? array_count_values(array_column($obj, 'status_order'))['Diantar'] : '0')?></strong>
				<span><?php echo __( 'Orderan<br>Diantar' , 'themefood' )?></span>
			</div>
			<div class="col status_selesai">
				<strong><?=(!empty(array_count_values(array_column($obj, 'status_order'))['Selesai'])? array_count_values(array_column($obj, 'status_order'))['Selesai'] : '0')?></strong>
				<span><?php echo __( 'Orderan<br>Selesai' , 'themefood' )?></span>
			</div>
			<div class="col status_dibatalkan">
				<strong><?=(!empty(array_count_values(array_column($obj, 'status_order'))['Dibatalkan'])? array_count_values(array_column($obj, 'status_order'))['Dibatalkan'] : '0')?></strong>
				<span><?php echo __( 'Orderan<br>Dibatalkan' , 'themefood' )?></span>
			</div>
		</div>
		<div class="row chart-1">
			<div class="col">
				<canvas id="line"></canvas>
			</div>
		</div>
		<div class="row chart-2">
			<div class="col">
				<canvas id="pie"></canvas>
			</div>
			<div class="col">
				<canvas id="bar"></canvas>
			</div>
		</div>
		<div class="row tabel-wrapper">
			<div class="col">				
				<table id="tabel-orderan" class="display compact">
					<thead>
						<tr>
							<th><?php echo __( 'ID' , 'themefood' )?></th>
							<th><?php echo __( 'Tanggal' , 'themefood' )?></th>
							<th><?php echo __( 'Customer' , 'themefood' )?></th>
							<th><?php echo __( 'No WhatsApp' , 'themefood' )?></th>
							<th><?php echo __( 'Status' , 'themefood' )?></th>
							<th><?php echo __( 'Nama Produk' , 'themefood' )?></th>
							<th><?php echo __( 'Berat' , 'themefood' )?></th>
							<th><?php echo __( 'Qty' , 'themefood' )?></th>
							<th><?php echo __( 'Harga' , 'themefood' )?></th>
							<th><?php echo __( 'Catatan' , 'themefood' )?></th>
						</tr>
					</thead>
					<tbody>			
				<?php
					foreach ($obj as $row) {
						$product = $row['order_produk'];
						$items = json_decode($product, true);
						foreach($items as $item) {
							?>
							<tr>
								<td><a href="<?=get_edit_post_link($row['id'])?>" target="_blank"><?=$row['id']?></a></td>
								<td><?=date("d/m/Y",strtotime($row['date']))?></td>
								<td><?=$row['order_nama']?></td>
								<td><?=$row['order_nowa']?></td>
								<td><?=$row['status_order']?></td>
								<td><?=$item['name']?></td>
								<td><?=$item['weight'] * $item['quantity'] . 'g'?></td>
								<td><?=$item['quantity']?></td>
								<td><?=$currency_sym . number_format( $item['price'] * $item['quantity'], 0 , ',' , '.' )?></td>
								<td><?=$item['summary']?></td>
							</tr>							
						<?php
						}
					}
				?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<script>
	(function ($) {	
		$('#tabel-orderan').DataTable({
			dom: 'Bfrtip',
			pageLength: 50,
			buttons: [
				'copy', 'csv', 'excel', 'print'
			]
		});	
		$('input[name="daterange"]').daterangepicker({
			opens: 'left',
			maxSpan: {
				'days': 30
			},
		  }, function(start, end, label) {
			window.location = "<?=admin_url()?>admin.php?page=laporan_orderan&start="+start.format('YYYY-MM-DD\T00:00:00')+"&end="+end.format('YYYY-MM-DD\T23:59:59');
		});
  
		var json = [<?php echo json_encode($output);?>],
			chartDate = new Array(),
			diterima = new Array(),
			diproses = new Array(),
			diantar = new Array(),
			selesai = new Array(),
			dibatalkan = new Array();
		$.each(json, function (key, data) {
			$.each(data, function (index, data) {
				chartDate.push(index);
				diterima.push(data.Diterima);
				diproses.push(data.Diproses);
				diantar.push(data.Diantar);
				selesai.push(data.Selesai);
				dibatalkan.push(data.Dibatalkan);
			})
		})
		var line = new Chart(document.getElementById('line').getContext('2d'), {
			type: 'line',
			data: {
				labels: chartDate,
				datasets: [
					{
						label: 'Orderan Diterima',
						backgroundColor: '#0076ff',
						borderColor: '#0076ff',
						data: diterima,
						fill: false,
					},
					{
						label: 'Orderan Diproses',
						backgroundColor: '#e2bc00',
						borderColor: '#e2bc00',
						data: diproses,
						fill: false,
					},
					{
						label: 'Orderan Diantar',
						backgroundColor: '#9913ff',
						borderColor: '#9913ff',
						data: diantar,
						fill: false,
					},
					{
						label: 'Orderan Selesai',
						backgroundColor: '#03AA0E',
						borderColor: '#03AA0E',
						data: selesai,
						fill: false,
					},
					{
						label: 'Orderan Dibatalkan',
						backgroundColor: 'red',
						borderColor: 'red',
						data: dibatalkan,
						fill: false,
					},
				]
			},
			options: {
				legend: {
					position: 'bottom' 
				}
			}			
		});	
		var pie = new Chart(document.getElementById('pie').getContext('2d'), {
			type: 'pie',
			data: {
				labels: <?php echo json_encode($statuses);?>,
				datasets: [{
					data: [
						<?=(!empty(array_count_values(array_column($obj, 'status_order'))['Diterima'])? array_count_values(array_column($obj, 'status_order'))['Diterima'] : '0')?>,
						<?=(!empty(array_count_values(array_column($obj, 'status_order'))['Diproses'])? array_count_values(array_column($obj, 'status_order'))['Diproses'] : '0')?>,
						<?=(!empty(array_count_values(array_column($obj, 'status_order'))['Diantar'])? array_count_values(array_column($obj, 'status_order'))['Diantar'] : '0')?>,
						<?=(!empty(array_count_values(array_column($obj, 'status_order'))['Selesai'])? array_count_values(array_column($obj, 'status_order'))['Selesai'] : '0')?>,
						<?=(!empty(array_count_values(array_column($obj, 'status_order'))['Dibatalkan'])? array_count_values(array_column($obj, 'status_order'))['Dibatalkan'] : '0')?>,
					],
					backgroundColor: [
						'#0076ff',
						'#e2bc00',
						'#9913ff',
						'#03AA0E',
						'red',
					],
					label: ''
				}],
			},
			options: {
				legend: {
					position: 'bottom' 
				}
			}			
		});			
		var bar = new Chart(document.getElementById('bar').getContext('2d'), {
			type: 'bar',
			data: {
				labels: [''],
				datasets: [
					{
						label: 'Orderan Diterima',
						backgroundColor: '#0076ff',
						borderColor: '#0076ff',
						data: [<?=$total['Diterima']?>]
					},
					{
						label: 'Orderan Diproses',
						backgroundColor: '#e2bc00',
						borderColor: '#e2bc00',
						data: [<?=$total['Diproses']?>]
					},
					{
						label: 'Orderan Diantar',
						backgroundColor: '#9913ff',
						borderColor: '#9913ff',
						data: [<?=$total['Diantar']?>]
					},
					{
						label: 'Orderan Selesai',
						backgroundColor: '#03AA0E',
						borderColor: '#03AA0E',
						data: [<?=$total['Selesai']?>]
					},
					{
						label: 'Orderan Dibatalkan',
						backgroundColor: 'red',
						borderColor: 'red',
						data: [<?=$total['Dibatalkan']?>]
					},
				]
			},
			options: {
				legend: {
					position: 'bottom' 
				},
				scales: {
					yAxes: [{
					  ticks: {
						beginAtZero: true,
						callback: function(value, index, values) {
						  return '<?=$currency_sym?>' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						}
					  }
					}]
				},
				tooltips: {
					callbacks: {
						label: function(tooltipItem, data) {
							return '<?=$currency_sym?>' + tooltipItem.yLabel.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
						}
					}
				}				
			}			
		});			
	})(jQuery);
	</script>
	<?php
}

function cs_page() {
	if ( function_exists( 'ot_get_option' ) ) {
		$currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';  
	}
	$date = new DateTime();
	$start = 'first day of this month';
	$end = 'today';
	if(isset($_GET['start'])) $start = $_GET['start'];
	$beforeDate = $date->modify($start)->format('Y-m-d\T00:00:00');
	if(isset($_GET['end'])) {
		$end = $_GET['end'];		
		$afterDate = $date->modify($end)->format('Y-m-d\T23:59:59');
	} else {
		$afterDate = date('Y-m-d\T23:59:59');		
	}
	$json = url_get_contents(get_bloginfo('url') . '/wp-json/wp/v2/tf-order?per_page=1000&before=' . $afterDate . '&after=' . $beforeDate . '&skip_cache=1');
	$obj = json_decode($json, true);	
	$cs_group = array();
	foreach($obj as $cs){
			$cs_group[$cs['order_cs']][] = $cs;
	}	
	$json_cs = url_get_contents(get_bloginfo('url') . '/wp-json/wp/v2/tf-cs?per_page=1000');
	$obj_cs = json_decode($json_cs, true);
	?>
	<link rel="stylesheet" type="text/css" href="<?=get_template_directory_uri() . '/css/daterangepicker.min.css';?>" />
	<script src="<?=get_template_directory_uri() . '/js/moment.min.js';?>"></script>
	<script src="<?=get_template_directory_uri() . '/js/daterangepicker.min.js';?>"></script>
	<script src="<?=get_template_directory_uri() . '/js/Chart.min.js';?>"></script>
	<div class="wrap" id="themefood-wrap">
		<h1 class="wp-heading-inline"><?php echo __( 'Laporan CS' , 'themefood' )?></h1>
		<span class="filter_tanggal"><span class="dashicons dashicons-calendar-alt"></span> <input type="text" name="daterange" value="<?=date("m/d/Y", strtotime($beforeDate))?> - <?=date("m/d/Y", strtotime($afterDate))?>"/></span>
		
		<?php
		foreach($cs_group as $group){
			$cs_pic = $group[0]['order_cs'];
			$list_cs = array_filter($obj_cs, function ($var) use ($cs_pic) {
				return ($var['no_cs'] == $cs_pic);
			});
			$list_cs = reset($list_cs);
			$total = [];
			$total['omset'] = 0;
			foreach($group as $tot) {
				$total['omset'] += intval(preg_replace('/[^0-9]/', '', $tot['order_total']));
			}
			?>
			<div class="report-cs">
				<div class="cs-title">			
				<?php
				if($list_cs) {
				?>
					<img src="<?=$list_cs['foto_cs']?>" />
					<h4><?=$list_cs['nama_cs']?></h4>
					<span><a href="https://wa.me/<?=$list_cs['no_cs']?>" target="_blank"><?=$list_cs['no_cs']?></a></span>
				<?php 
				} else {
				?>
					<img src="<?=get_template_directory_uri() . '/img/icon-512.png'?>" />
					<h4><?php echo __( 'Not Found' , 'themefood' )?></h4>
					<span><a href="https://wa.me/<?=$cs_pic;?>" target="_blank"><?=$cs_pic;?></a></span>
				<?php 
				}				
				?>
				<div class="potensi-omset">
					<?php echo __( 'Potensi Omset' , 'themefood' )?>
					<strong><?=$currency_sym.number_format( $total['omset'], 0 , ',' , '.' )?></strong>
				</div>
				</div>
				<div class="row count-status_order">
					<div class="col status_diterima">
						<strong><?=(!empty(array_count_values(array_column($group, 'status_order'))['Diterima'])? array_count_values(array_column($group, 'status_order'))['Diterima'] : '0')?></strong>
						<span><?php echo __( 'Orderan<br>Diterima' , 'themefood' )?></span>
					</div>
					<div class="col status_diproses">
						<strong><?=(!empty(array_count_values(array_column($group, 'status_order'))['Diproses'])? array_count_values(array_column($group, 'status_order'))['Diproses'] : '0')?></strong>
						<span><?php echo __( 'Orderan<br>Diproses' , 'themefood' )?></span>
					</div>
					<div class="col status_diantar">
						<strong><?=(!empty(array_count_values(array_column($group, 'status_order'))['Diantar'])? array_count_values(array_column($group, 'status_order'))['Diantar'] : '0')?></strong>
						<span><?php echo __( 'Orderan<br>Diantar' , 'themefood' )?></span>
					</div>
					<div class="col status_selesai">
						<strong><?=(!empty(array_count_values(array_column($group, 'status_order'))['Selesai'])? array_count_values(array_column($group, 'status_order'))['Selesai'] : '0')?></strong>
						<span><?php echo __( 'Orderan<br>Selesai' , 'themefood' )?></span>
					</div>
					<div class="col status_dibatalkan">
						<strong><?=(!empty(array_count_values(array_column($group, 'status_order'))['Dibatalkan'])? array_count_values(array_column($group, 'status_order'))['Dibatalkan'] : '0')?></strong>
						<span><?php echo __( 'Orderan<br>Dibatalkan' , 'themefood' )?></span>
					</div>
				</div>
			</div>
		<?php			
		}
		?>
	</div>	  
	<script>
	(function ($) {	
		$('input[name="daterange"]').daterangepicker({
			opens: 'left',
			maxSpan: {
				'days': 30
			},
		  }, function(start, end, label) {
				window.location = "<?=admin_url()?>admin.php?page=laporan_cs&start="+start.format('YYYY-MM-DD\T00:00:00')+"&end="+end.format('YYYY-MM-DD\T23:59:59');
		});
	})(jQuery);	
	</script>
	<?php
}

add_action('wp_footer', 'post_view_count', 999);
function post_view_count() {
	if(is_single() && get_post_type(get_the_ID()) == 'tf-produk') :
		?>
		<script type="text/javascript">
			function produk_add_post_view(id, endpoint) {
				var xmlhttp;
				var params = "/?produk_count=1&produk_post_id=" + id + "&cachebuster=" +  Math.floor((Math.random() * 100000));
				if (window.XMLHttpRequest)
					xmlhttp = new XMLHttpRequest();
				else
					xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
				xmlhttp.open("GET", endpoint + params, true);
				xmlhttp.send();
			}
			produk_add_post_view(<?php echo get_the_ID(); ?>, '<?php echo get_site_url(); ?>');
		</script>
	<?php
	endif;
}

add_filter('query_vars', 'query_vars');
function query_vars($query_vars) {
	$query_vars[] = 'produk_count';
	$query_vars[] = 'produk_post_id';
	return $query_vars;
}

add_action('wp', 'produk_count');
function produk_count() {
	if(intval(get_query_var('produk_count')) === 1 && intval(get_query_var('produk_post_id')) !== 0) {
		header('Content-Type: application/json');
		$id = intval(get_query_var('produk_post_id'));
		$current_count = get_post_meta($id, '_produk_count', true);
		if($current_count === '')
			$count = 1;
		else
			$count = intval($current_count)+1;
		update_post_meta($id, '_produk_count', $count);
		echo json_encode(array('status' => 'OK', 'visits' => intval($current_count)+1));
		die();
	}
}

add_action( 'followup_cron_hook', 'followup_order' );
add_filter( 'cron_schedules', 'followup_cron_schedule' );
if ( ! wp_next_scheduled( 'followup_cron_hook' ) ) {
	wp_schedule_event( time(), 'every_half_hours', 'followup_cron_hook' );
}
function followup_cron_schedule( $schedules ) {
	$schedules['every_half_hours'] = array(
		'interval' => 1800,
		'display'  => __( 'Every 30 minutes' ),
	);
	return $schedules;
}
function followup_order() {
	$date = new DateTime();
	$dateRange = $date->modify('-7 days')->format('Y-m-d\TH:i:s');
	$term = get_term_by('slug', 'diterima', 'order_status');
	$json = url_get_contents(get_bloginfo('url') . '/wp-json/wp/v2/tf-order?per_page=1000&order_status=' . $term->term_id . '&after=' . $dateRange . '&skip_cache=1');
	$obj = json_decode($json, true);
	if ( function_exists( 'ot_get_option' ) ) {		
		$key = ot_get_option( 'token_key' );	
		$api = ot_get_option( 'bot_service' );
		$fupmsg_1 = ot_get_option('order_diterima_1');
		$fupmsg_2 = ot_get_option('order_diterima_2');
		$fupmsg_3 = ot_get_option('order_diterima_3');
		$fupday_1 = ot_get_option('order_diterima_waktu_1') !== '' ? ot_get_option('order_diterima_waktu_1') : '24';
		$fupday_2 = ot_get_option('order_diterima_waktu_2') !== '' ? ot_get_option('order_diterima_waktu_2') :'48';
		$fupday_3 = ot_get_option('order_diterima_waktu_3') !== '' ? ot_get_option('order_diterima_waktu_3') :'96';
	}
	if( !empty($obj) ) {
		$followup_1 = [];
		$followup_2 = [];
		$followup_3 = [];
		foreach ( $obj as $fup ) {
			$ts1 = strtotime(date('Y-m-d\TH:i:s'));
			$ts2 = strtotime($fup['date']);
			$day_range = abs($ts1 - $ts2) / 3600;
			if(!empty($fupmsg_1)){
				if( $day_range >= $fupday_1 ) {
					$sent = get_post_meta( $fup['id'], 'followup', true );
					if(empty($sent) || $sent == null){
						update_post_meta($fup['id'], 'followup', '0');
					}					
					if($sent == '0'){
						$followup_1[] = $fup['id'];
					}
				}				
			}
			if(!empty($fupmsg_2)){
				if( $day_range >= $fupday_2 ) {
					$sent = get_post_meta( $fup['id'], 'followup2', true );
					if(empty($sent) || $sent == null){
						update_post_meta($fup['id'], 'followup2', '0');
					}					
					if($sent == '0'){
						$followup_2[] = $fup['id'];
					}
				}				
			}
			if(!empty($fupmsg_3)){
				if( $day_range >= $fupday_3 ) {
					$sent = get_post_meta( $fup['id'], 'followup3', true );
					if(empty($sent) || $sent == null){
						update_post_meta($fup['id'], 'followup3', '0');
					}					
					if($sent == '0'){
						$followup_3[] = $fup['id'];
					}
				}				
			}			
		}
		if( count( $followup_1 ) != 0 ) {
			foreach ( $followup_1 as $flw => $foll_id ) {				
				$msg = themefood_process_variables($fupmsg_1, $foll_id, '');
				themefood_send_email_wa($foll_id,$msg);
				update_post_meta($foll_id, 'followup', '1');
			}
		}
		if( count( $followup_2 ) != 0 ) {
			foreach ( $followup_2 as $flw => $foll_id ) {				
				$msg = themefood_process_variables($fupmsg_2, $foll_id, '');
				themefood_send_email_wa($foll_id,$msg);
				update_post_meta($foll_id, 'followup2', '1');
			}
		}
		if( count( $followup_3 ) != 0 ) {
			foreach ( $followup_3 as $flw => $foll_id ) {				
				$msg = themefood_process_variables($fupmsg_3, $foll_id, '');
				themefood_send_email_wa($foll_id,$msg);
				update_post_meta($foll_id, 'followup3', '1');
			}
		}
	}
}

function wpimpexp_product_format($value) {
	$json = json_decode($value, true);
	$data = '';
	foreach($json as $item) {
		$data .= $item['name'] . '
' .
				 $item['quantity'] . ' x ' . $item['price'] . ' = ' . number_format( $item['price'] * $item['quantity'], 0 , ',' , '.' ) . '
' .
				 'Catatan: ' . $item['summary'] . '

';
	}	
	return $data;
}

add_action('wp_loaded', 'webhook_notification_handler');
function webhook_notification_handler() {
	$notifications = file_get_contents('php://input');
	$notify = json_decode($notifications, true);
	if(isset($_GET['gateway'])) {
		// Moota
		if($_GET['gateway'] == 'moota') {
			if ( function_exists( 'ot_get_option' ) ) {
			  $moota_secret = ot_get_option( 'moota_secret' );
			  $moota_status = ot_get_option( 'moota_status' );
			  $moota_cekhari = ot_get_option( 'moota_cekhari' );
			  $currency_sym = ot_get_option( 'mata_uang' ) ?: 'Rp';
			}
			$secret = $moota_secret;
			$signature = hash_hmac('sha256', $notifications, $secret);
			if($signature === $_SERVER['HTTP_SIGNATURE']){
				if($notifications){
					foreach($notify as $notif){
						$nominal = $currency_sym.number_format( $notif['amount'], 0 , ',' , '.' );
						$args = array(  
							'post_type' => 'tf-order',
							'posts_per_page' => -1,
							'meta_key' => 'order_total',
							'meta_query' => array(
							   array(
								   'key' => 'order_total',
								   'value' => $nominal,
								   'compare' => '=',
							   )
							),
							'date_query'    => array(
								'column'  => 'post_date',
								'after'   => '- '.$moota_cekhari.' days'
							),
							'tax_query' => array(
								array(
								   'taxonomy' => 'order_status',
								   'field' => 'slug',
								   'terms' => 'diterima',
								)
							)
						);
						$query = new WP_Query( $args );
						if ( $query->have_posts() ) {
							while ( $query->have_posts() ) {
								$query->the_post();
								echo get_the_ID();
								if(has_term( 'diterima', 'order_status', get_the_ID() )){
									wp_set_object_terms( get_the_ID(), $moota_status, 'order_status' );
									update_post_meta(get_the_ID(), 'order_payment_proof', 'Pembayaran Melalui Bank : ' . strtoupper($notif['bank_type']) . ' - Moota (' . $notif['date'] . ')', true);
									themefood_send_whatsapp_bot(get_the_ID());
								}
							}
						}
						wp_reset_postdata();		
					}
				}
			}		
		}
		// Durianpay
		if($_GET['gateway'] == 'durianpay') {
			// error_log($notifications);
			if ( function_exists( 'ot_get_option' ) ) {
				$api_durianpay = ot_get_option( 'api_durianpay' );
				$durianpay_status = ot_get_option( 'durianpay_status' );
			}
			if($notifications){
				if($notify['data']['is_live'] == true) {
					$ch = curl_init();
					$curlConfig = array(
						CURLOPT_URL            => 'https://api.durianpay.id/v1/payments/' . $notify['data']['id'] . '/status',
						CURLOPT_RETURNTRANSFER => true,
						CURLOPT_HTTPHEADER     => array(
							'Content-Type: application/json',
							'Authorization: BASIC ' . base64_encode(sprintf("%s%s", $api_durianpay, ":"))
						)
					);
					curl_setopt_array($ch, $curlConfig);
					$result = curl_exec($ch);
					$status = json_decode($result, true);
					if($status['data']['status'] == 'completed' && $status['data']['is_completed'] == true){
						$payment_id = $notify['data']['order_id'];
						$args = array(  
							'post_type' => 'tf-order',
							'posts_per_page' => -1,
							'meta_key' => 'order_total',
							'meta_query' => array(
							   array(
								   'key' => 'order_payment_id',
								   'value' => $payment_id,
								   'compare' => '=',
							   )
							),
							'tax_query' => array(
								array(
								   'taxonomy' => 'order_status',
								   'field' => 'slug',
								   'terms' => 'diterima',
								)
							)
						);
						$query = new WP_Query( $args );
						if ( $query->have_posts() ) {
							while ( $query->have_posts() ) {
								$query->the_post();
								echo get_the_ID();									
								if(has_term( 'diterima', 'order_status', get_the_ID() )){
									wp_set_object_terms( get_the_ID(), $durianpay_status, 'order_status' );
									update_post_meta(get_the_ID(), 'order_payment_proof', 'Pembayaran Melalui DurianPay : ' . strtoupper($notify['data']['payment_method']) . ' - ' . $notify['data']['id'] . ' (' . date("Y-m-d H:i:s", strtotime($notify['data']['created_at'] . "+7hours")) . ')', true);
									themefood_send_whatsapp_bot(get_the_ID());
								}
							}
						}
						wp_reset_postdata();	
					}
				}
			}
		}
		exit();
    }
}

add_action('wp_head', 'iframe_form_style', 100);
function iframe_form_style() {
	if (is_page_template('page-katalog.php') && isset($_GET['form'])) {
		?>
		<style>body.admin-bar { margin-top: 0; } body.page.page-template-page-katalog { background: transparent!important; } ons-navigator, #extra .extra-cta, .cartBtn-wrapper .cart-menu, .action-sheet, ons-toast, #cart .checkout-cta { max-width: 100%; } .toolbar--material { display: none; } #cart .page__content { top: 0; padding: 0; } .page--material, .page--material__background, #cart #my-cart-table { background: transparent; } #cart .form-list { border-radius: 6px; margin: 0 10px; } #cart #my-cart-table tbody > tr { margin: 10px 8px; } #cart .form-list, #cart #my-cart-table tbody > tr, #cart .checkout-summary > ons-col { border: 1px solid #ddd; box-shadow: none!important; -webkit-box-shadow: none!important; } #cart .checkout-summary { padding-bottom: 0; background: transparent; } #cart .checkout-cta { position: relative; left: 0; padding-top: 0; background: transparent; box-shadow: none!important; -webkit-box-shadow: none!important; } #cart #my-cart-table tbody > tr td .my-product-cta a.my-product-remove, #cart #my-cart-table tbody > tr td .my-product-cta .my-product-notes, #success .detail-sukses .btn-sukses .btn-back-home, #wpadminbar, #layoutSwitcher { display: none; } ons-toast .toast { top: initial!important; bottom: 90px!important; max-width: calc(100% - 20px); left: 10px; border-radius: 6px; } .alert-dialog { bottom: 5px; top: initial; }</style>
		<?php
	}
}

add_action('wp_footer', 'iframe_form_script', 100);
function iframe_form_script() {
	if (is_page_template('page-katalog.php') && isset($_GET['form'])) {
		$products = explode(',',$_GET['form']);
		$pID = '';
		foreach($products as $product){
			$pID .= 'include[]='.$product.'&';
		}
		$json = url_get_contents(get_bloginfo('url') . '/wp-json/wp/v2/tf-produk?'.$pID.'skip_cache=1');
		$obj = json_decode($json, true);
		?>
		<script>
		(function ($) {
			var formCart = [
					<?php
					foreach($obj as $cart){
						$harga = $cart['harga_produk'];
						if(!empty($cart['harga_diskon'])) $harga = $cart['harga_diskon'];
						echo "{id: ".$cart['id'].", name: '".$cart['title']['rendered']."', summary: '', weight: ".$cart['berat_produk'].", price: ".$harga.", quantity: 1, image: '".$cart['foto_produk']."'},";
					}
					?>
				];
			localStorage.setItem('__tfcart', JSON.stringify(formCart));	
			setTimeout(function() {
				$('.checkout-payment').html($('input[name=metode-pembayaran]:checked', '#paymentMethod').parents('.list-item').find('label.center').text());
				$('.checkout-payment').attr('data-id', $('input[name=metode-pembayaran]:checked', '#paymentMethod').val());
			}, 1000);					
			function iframeResize() {
				var height = $('#cart-wrapper').outerHeight();
				parent.postMessage("resize::"+height,"*");
			}
			setInterval(iframeResize, 1000);
		})(jQuery);
		</script>
		<?php
	}
}

add_shortcode('tf_form', 'themefood_form_iframe');
function themefood_form_iframe($atts, $content) {
	?>
	<iframe border="0" id="themefood_form_iframe" src="<?=getTplPageURL('page-katalog.php')?>?form=<?=$atts['id']?>"></iframe>
	<script>
	  var eventMethod = window.addEventListener ? "addEventListener" : "attachEvent";
	  var eventer = window[eventMethod];
	  var messageEvent = eventMethod == "attachEvent" ? "onmessage" : "message";
	  eventer(messageEvent,function(e) {
		if (e.data.indexOf('resize::') != -1) {
		  var height = e.data.replace('resize::', '');
		  document.getElementById('themefood_form_iframe').style.height = height+'px';
		}
	  } ,false);
	</script>
<?php
}