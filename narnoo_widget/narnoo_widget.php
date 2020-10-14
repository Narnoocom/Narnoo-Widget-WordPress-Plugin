<?php
/**
 * Plugin Name:       Narnoo Widget
 * Plugin URI:        https://www.narnoo.com/
 * Description:       Output the Narnoo widget code
 * Version:           1.0.5
 * Requires at least: 5.3.0
 * Requires PHP:      7.0
 * Author:            Narnoo.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 */

// plugin definitions
define( 'NARNOO_WIDGET_PLUGIN_NAME', 'Narnoo Widget' );
define( 'NARNOO_WIDGET_CURRENT_VERSION', '1.0.5' );
define( 'NARNOO_WIDGET_I18N_DOMAIN', 'narnoo-widget' );

define( 'NARNOO_WIDGET_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'NARNOO_WIDGET_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'NARNOO_WIDGET_SETTINGS_PAGE', 'options-general.php?page=narnoo-widget-settings' );

// begin!
new Narnoo_Widget();

class Narnoo_Widget {

	/**
	 * Plugin's main entry point.
	 **/
	function __construct() {
		register_uninstall_hook( __FILE__, array( 'NarnooWidget', 'uninstall' ) );

		if ( is_admin() ) {
			//add_filter( 'plugin_action_links', 	array( &$this, 'plugin_action_links' ), 10, 2 );

			add_action( 'admin_menu', 			array( &$this, 'create_menus' ), 9 );
			add_action( 'admin_init', 			array( &$this, 'admin_init' ) );

			add_action( 'add_meta_boxes', 		array( &$this, 'noo_set_meta_boxes' ) );
			add_action( 'save_post', 			array( &$this, 'save_noo_widget_data' ) );

		} else {

			add_filter( 'widget_text', 'do_shortcode' );

			add_shortcode('narnoo_cart_button', 		array( &$this, 'narnoo_cart_display_func' ));
			add_shortcode('narnoo_booking', 			array( &$this, 'narnoo_booking_display_func' ));
			add_shortcode('narnoo_booking_button', 		array( &$this, 'narnoo_booking_button_func' ));
		}

		/**
		 * Clean up upon plugin uninstall.
		 **/
		function uninstall() {
			unregister_setting( 'narnoo_widget_settings', 'narnoo_widget_settings', array( &$this, 'settings_sanitize' ) );
		}

		/**
	 * Load language file upon plugin init (for future extension, if any)
	 **/
	function load_language_file() {
		load_plugin_textdomain( NARNOO_WIDGET_I18N_DOMAIN, false, NARNOO_WIDGET_PLUGIN_PATH . 'languages/' );
	}

		/**
		 * Add settings link for this plugin to Wordpress 'Installed plugins' page.
		 **/
		function plugin_action_links( $links, $file ) {
			if ( $file == plugin_basename( dirname(__FILE__) . '/narnoo-widget.php' ) ) {
				$links[] = '<a href="' . NARNOO_WIDGET_SETTINGS_PAGE . '">' . __('Settings') . '</a>';
			}

			return $links;
		}

	}

	function noo_set_meta_boxes(){
		$option = get_option( 'narnoo_widget_settings' );

		if( !empty($option['widget_custom_post_key']) ){

			$_noo_pages = explode(',', $option['widget_custom_post_key']);

			if(is_array($_noo_pages) && count( $_noo_pages )){
				$_noo_load = $_noo_pages;
			}else{
				$_noo_load = $_noo_pages;
			}

			add_meta_box(
	                'noo-widget-class',      							// Unique ID
				    'Enter Product Widget Information', 		 	    // Title
				    array( &$this,'box_display_widget_narnoo'),    		// Callback function
				    array( $_noo_load ),   		// Admin page (or post type)
				    'side',         									// Context
				    'low'         										// Priority
	             );

		}
		return false;
	}

	/*
	*
	*	title: Display the operator listings box
	*	date created: 15-09-16
	*/
	function box_display_widget_narnoo( $post )
	{
		global $post;
		$_supplier 		= get_post_meta($post->ID,'noo_widget_operator',	true);
	    $_product 		= get_post_meta($post->ID,'noo_widget_product',		true);
	    // We'll use this nonce field later on when saving.
	    wp_nonce_field( 'box_display_widget_nonce', 'box_display_widget_nonce' );
	    ?>
	    <p>
	        <label for="noo_widget_supplier">Operator Id:</label>
	        <input type="text" name="noo_widget_operator" id="noo_widget_supplier" value="<?php echo $_supplier; ?>" style="width:100%"/>
	    </p>
	    <p>
	    	<small><em>Enter the supplier ID</em></small>
	    </p>
	    <p>
	        <label for="listing_search_suburb">Product Id:</label>
	        <input type="text" name="noo_widget_product" id="noo_widget_product" value="<?php echo $_product ?>" style="width:100%"/>
	    </p>
        <p>
        	<small><em>Enter the Product ID</em></small>
        </p>

        <?php

	}

	function save_noo_widget_data( $post_id ){

		// Bail if we're doing an auto save
	    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;

	    // if our nonce isn't there, or we can't verify it, bail
	    if( !isset( $_POST['box_display_widget_nonce'] ) || !wp_verify_nonce( $_POST['box_display_widget_nonce'], 'box_display_widget_nonce' ) ) return;

	    // if our current user can't edit this post, bail
	    if( !current_user_can( 'edit_post' ) ) return;

    	if( isset( $_POST['noo_widget_operator'] ) ){
        	update_post_meta( $post_id, 'noo_widget_operator', esc_attr( $_POST['noo_widget_operator'] ) );
    	}
    	if( isset( $_POST['noo_widget_product'] ) ){
        	update_post_meta( $post_id, 'noo_widget_product', esc_attr( $_POST['noo_widget_product'] ) );
    	}
    }

	 /* Add admin menus and submenus to backend.
	 **/
	function create_menus() {
		// add Narnoo API to settings menu
		add_options_page(
			__( 'Narnoo Widget Settings', NARNOO_WIDGET_I18N_DOMAIN ),
			__( 'Narnoo Widget', NARNOO_WIDGET_I18N_DOMAIN ),
			'manage_options',
			'narnoo-widget-settings',
			array( &$this, 'widget_settings_page' )
		);
	}

	function admin_init() {
		register_setting( 'narnoo_widget_settings', 'narnoo_widget_settings', array( &$this, 'settings_sanitize' ) );

		add_settings_section(
			'widget_settings_section',
			__( 'Widget API Settings', NARNOO_WIDGET_I18N_DOMAIN ),
			array( &$this, 'widget_api_section' ),
			'narnoo_widget_api_settings'
		);

		add_settings_field(
			'access_key',
			__( 'Acesss key', NARNOO_WIDGET_I18N_DOMAIN ),
			array( &$this, 'widget_access_key' ),
			'narnoo_widget_api_settings',
			'widget_settings_section'
		);

		add_settings_field(
			'product_type_name',
			__( 'Product Custom Post Name', NARNOO_WIDGET_I18N_DOMAIN ),
			array( &$this, 'widget_custom_post_key' ),
			'narnoo_widget_api_settings',
			'widget_settings_section'
		);
		// register Narnoo shortcode button and MCE plugin
		if ( ! current_user_can( 'edit_posts' ) && ! current_user_can( 'edit_pages' ) ) {
			return;
		}

	}

	function narnoo_widget_section() {
		echo '<p>' . __( 'You can edit your Narnoo Widget settings below.', NARNOO_DISTRIBUTOR_I18N_DOMAIN ) . '</p>';
	}

	function widget_access_key() {
		$options = get_option( 'narnoo_widget_settings' );
		echo "<input id='access_key' name='narnoo_widget_settings[widget_access_key]' size='40' type='text' value='" . esc_attr($options['widget_access_key']). "' />";
	}

	function widget_custom_post_key() {
		$options = get_option( 'narnoo_widget_settings' );
		echo "<input id='post_key' name='narnoo_widget_settings[widget_custom_post_key]' size='40' type='text' value='" . esc_attr($options['widget_custom_post_key']). "' />";
	}
	/**
	 * Sanitize input settings.
	 **/
	function settings_sanitize( $input ) {
		$option = get_option( 'narnoo_widget_settings' );

		if( !empty($input['widget_access_key']) || !empty($input['widget_custom_post_key']) ) {

			$new_input['widget_access_key'] 			= trim( $input['widget_access_key'] );
			$new_input['widget_custom_post_key'] 		= trim( $input['widget_custom_post_key'] );

		}

		return $new_input;
	}

	/**
	 * Display API settings page.
	 **/
	function widget_settings_page() {

		?>
		<div class="wrap">
			<div class="icon32"><img src="<?php echo NARNOO_WIDGET_PLUGIN_URL; ?>/images/icon-32.png" /><br /></div>
			<h2><?php _e( 'Narnoo Widget Settings', NARNOO_WIDGET_I18N_DOMAIN ) ?></h2>
			<form action="options.php" method="post">
				<?php settings_fields( 'narnoo_widget_settings' ); ?>
				<?php do_settings_sections( 'narnoo_widget_api_settings' ); ?>
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
				</p>
			</form>
		</div>
		<?php
	}

	/**
	 * Display single link gallery shortcode.
	 * */
	function narnoo_cart_display_func($atts) {
	    ob_start();
	    require( NARNOO_WIDGET_PLUGIN_PATH . 'inc/narnoo-widget-cart.php' );
	    return ob_get_clean();

	 }

	 /**
	 * Display single link gallery shortcode.
	 * */
	function narnoo_booking_display_func($atts) {
	    ob_start();
	    require( NARNOO_WIDGET_PLUGIN_PATH . 'inc/narnoo-widget-display.php' );
	    return ob_get_clean();

	 }
	 /**
	 * Display narnoo_booking_button.
	 * */
	function narnoo_booking_button_func($atts) {
	    ob_start();
	    require( NARNOO_WIDGET_PLUGIN_PATH . 'inc/narnoo-widget-button.php' );
	    return ob_get_clean();

	 }





}
