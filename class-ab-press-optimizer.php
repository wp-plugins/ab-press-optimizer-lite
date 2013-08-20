<?php
/**
 * A/B Press Optimizer.
 *
 * @package   ab-press-optimizer
 * @author    Ivan Lopez
 * @link      http://ABPressOptimizer.com
 * @copyright 2013 Ivan Lopez
 */

/**
 * Plugin class.
 *
 * @package ab-press-optimizer
 * @author  Ivan Lopez
 */
class ABPressOptimizer {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';

	/**
	 * Unique identifier for your plugin.
	 *
	 * Use this value (not the variable name) as the text domain when internationalizing strings of text. It should
	 * match the Text Domain file header in the main plugin file.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'ab-press-optimizer-lite';

	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Slug of the plugin screen.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	protected $plugin_screen_hook_suffix = null;

	/**
	 * Initialize the plugin by setting localization, filters, and administration functions.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {

		// Load plugin text domain
		add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// init process for button control
		add_action('init', array( $this, 'ab_press_shortcode_button')) 	;

		// Add the options page and menu item.
		add_action( 'admin_menu', array( $this, 'add_plugin_admin_menu' ) );

		// Load admin style sheet and JavaScript.
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );

		// Load public-facing style sheet and JavaScript.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'wp_ajax_nopriv_'.$this->plugin_slug.'-submit', array( $this, 'abPress_ajax_submit') );
		add_action( 'wp_ajax_'.$this->plugin_slug.'-submit', array( $this, 'abPress_ajax_submit') );

		add_action( 'wp_ajax_nopriv_'.$this->plugin_slug.'-get', array( $this, 'abPress_ajax_get') );
		add_action( 'wp_ajax_'.$this->plugin_slug.'-get', array( $this, 'abPress_ajax_get') );

		// Add Experiment Link to plugin page
		add_filter('plugin_action_links',  array( $this, 'plugin_action_links') , 10, 2);

		//Create ShortCode
		add_shortcode('abPress', array( $this, 'ab_press_shortcode'));
		add_shortcode('abpress', array( $this, 'ab_press_shortcode'));

		//Welcome Popup
		add_action( 'admin_print_footer_scripts',  array( $this, 'welcome_popup' ));
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog.
	 */
	public function activate( $network_wide ) {
		update_option('ab_press_optimizer_version', '1.0.0');
	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    1.0.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses "Network Deactivate" action, false if WPMU is disabled or plugin is deactivated on an individual blog.
	 */
	public function deactivate( $network_wide ) {
		delete_option('ab_press_optimizer_version');
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, WP_LANG_DIR . '/' . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
	
		$this->create_experiment_table();
		$this->create_variations_table();

		$this->cronJob();

	}

	/**
	 * Register and enqueue admin-specific style sheet.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_styles() {
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_style( $this->plugin_slug .'-admin-styles', plugins_url( 'css/admin.css', __FILE__ ), array(), $this->version );
		wp_enqueue_style( $this->plugin_slug .'-admin-jquery-ui', plugins_url( 'css/jquery-ui.css', __FILE__ ), array(), $this->version );
	}

	/**
	 * Register and enqueue admin-specific JavaScript.
	 *
	 * @since     1.0.0
	 *
	 * @return    null    Return early if no settings page is registered.
	 */
	public function enqueue_admin_scripts() {
		wp_enqueue_script( $this->plugin_slug . '-admin-validation', plugins_url( 'js/jquery.validate.min.js', __FILE__ ), array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->plugin_slug . '-admin-validationMethod', plugins_url( 'js/additional-methods.js', __FILE__ ), array( 'jquery' ), $this->version );
		wp_enqueue_script( $this->plugin_slug . '-admin-script', plugins_url( 'js/admin.js', __FILE__ ), array( 'jquery' ), $this->version );
	    wp_enqueue_script( $this->plugin_slug . '-admin-sparkline', plugins_url( 'js/jquery.sparkline.min.js', __FILE__ ), array( 'jquery' ), $this->version );
	    wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script('jquery-ui-datepicker');
	}


	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		// embed the javascript file that makes the AJAX request
		wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery' ), $this->version  );

		$nonce = wp_create_nonce( 'abpress-click-nonce') ;
		// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
		wp_localize_script( $this->plugin_slug . '-plugin-script', 'abPressAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'abpresNonce' => $nonce) );
	}

	/**
	 * Interaction Click experiment.
	 *
	 * @since    1.0.0
	 */
	public function abPress_ajax_submit() {


		if(isset($_POST['_wpnonce']))
		{	
			
			$ab_press_data = ab_press_getExperimentIds();
			foreach ($ab_press_data as $experiment) {

				if( $_POST['experiment'] == $experiment->id)
				{
					$id = $experiment->id;
					$varId = $_POST['variation'];
					if(!isset($_COOKIE['_ab_press_exp_' .$id  .'_conv']))
					{
						if($varId == "c")
						{
							ab_press_updateConvertion($id, "control", $experiment->original_convertions);
						}
						else
						{
							$variationCount = 0;
							foreach ($experiment->variations as $variation) {
								if($variation->id == $varId)
								{
									$variationCount = $variation->convertions ; 
									break; 
								} 
							}

							ab_press_updateConvertion($varId, "variation", $variationCount );
						}
						setcookie('_ab_press_exp_' . $experiment->id .'_conv', 1, time()+60*60*24*1, '/');

						echo 'success';
					}

				}
				
			}

		}//End if
	 	
		// IMPORTANT: don't forget to "exit"
		die();
	}

	public function abPress_ajax_get() {

		header( "Content-Type: application/json" );

		echo json_encode(ab_press_getAllActiveExperiments());
	 	
		// IMPORTANT: don't forget to "exit"
		exit;
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    1.0.0
	 */
	public function add_plugin_admin_menu() {

		add_menu_page(
			__( 'AB Press Optimizer', $this->plugin_slug ),
			__( 'AB Press ', $this->plugin_slug ),
			'administrator',
			'abpo-experiment',
			array( $this, 'display_plugin_experiment_page' ),
			plugin_dir_url( __FILE__ ) . '/assets/abPress-icon.png',
			1000
		);

		add_submenu_page(
			'abpo-experiment',
			__( 'AB Press Optimizer', $this->plugin_slug ),
			__( 'Experiments', $this->plugin_slug ),
			'administrator',
			'abpo-experiment',
			array( $this, 'display_plugin_experiment_page' )
		);

		add_submenu_page(
			'abpo-experiment',
			__( 'Getting Started', $this->plugin_slug ),
			__( 'Getting Started', $this->plugin_slug ),
			'administrator',
			'abpo-gettingStarted',
			array( $this, 'display_plugin_getting_started' )
		);

		$hook = add_submenu_page(
			'abpo-experiment',
			__( '', $this->plugin_slug ),
			'New',
			'administrator',
			'abpo-new',
			array( $this, 'display_new_experiment' )
		);

		//Redirects
		add_action( 'load-ab-press_page_abpo-new', array( $this, 'new_experiment_redirect') );

		add_submenu_page(
			'abpo-experiment',
			__( '', $this->plugin_slug ),
			"Detail",
			'administrator',
			'abpo-details',
			array( $this, 'display_detail_experiment' )
		);

		add_action( 'load-ab-press_page_abpo-details', array( $this, 'details_experiment_redirect') );

		add_submenu_page(
			'abpo-experiment',
			__( '', $this->plugin_slug ),
			"Edit",
			'administrator',
			'abpo-edit',
			array( $this, 'display_edit_experiment' )
		);

		add_action( 'load-ab-press_page_abpo-edit', array( $this, 'edit_experiment_redirect') );

		
		add_submenu_page(
			'abpo-experiment',
			__( '', $this->plugin_slug ),
			"Delete",
			'administrator',
			'abpo-delete',
			array( $this, 'display_delete_experiment' )
		);


	}

	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function plugin_action_links($links, $file) {
		static $this_plugin;
 
		if (!$this_plugin) {
		    $this_plugin = plugin_basename('ab-press-optimizer-lite/ab-press-optimizer.php');
		}
		
		if ($file == $this_plugin) {
		    $dashboard_link = '<a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=abpo-experiment">Experiments</a>';
			array_unshift($links, $dashboard_link);
		}
 
    	return $links;
	}

	/**
	 * Render the Experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_experiment_page() {
		include_once( 'views/experiment.php' );
	}

	/**
	 * Render the Getting Started page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_getting_started() {
		include_once( 'views/gettingStarted.php' );
	}

	/**
	 * Render the new experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public  function display_new_experiment() {
		include_once( 'views/new.php' );
	}

	/**
	 * Render view experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public  function display_detail_experiment() {
		include_once( 'views/details.php' );
	}



	/**
	 * Render edit experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public  function display_edit_experiment() {
		include_once( 'views/edit.php' );
	}

	/**
	 * Captures post and redirects for new experiment
	 *
	 * @since    1.0.0
	 */
	public function new_experiment_redirect() {
       $status = get_option( 'ab_press_license_status' );

		if(isset($_POST['save']))
		{
			if(ab_press_storeExperiment($_POST, $_FILES ) )
			{
				header( 'Location: admin.php?page=abpo-experiment' ) ;
				exit();
			}
		}
	}

	/**
	 * Captures post and redirects for details page
	 *
	 * @since    1.0.0
	 */
	public function details_experiment_redirect() {
    	$experiment = ab_press_getExperiment($_GET['eid']);
		if(!$experiment)
		{
			ab_press_createMessage("The experiment you selected does not exist!|ERROR");
			header( 'Location: admin.php?page=abpo-experiment' ) ;
			exit();
		}
	}

	/**
	 * Captures post and redirects for details page
	 *
	 * @since    1.0.0
	 */
	public function edit_experiment_redirect() {
      	if(isset($_POST['update']))
		{
			if(ab_press_updateExperiment($_POST, $_FILES ) )
			{
				header( 'Location: admin.php?page=abpo-experiment' ) ;
				exit();
			}
		}

		$experiment = ab_press_getExperiment($_GET['eid']);
		if(!$experiment)
		{
			ab_press_createMessage("The experiment you selected does not exist!|ERROR");
			header( 'Location: admin.php?page=abpo-experiment' ) ;
			exit();
		}
	}

	/**
	 * Render view experiment page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public  function display_delete_experiment() {
		$experiment = ab_press_getExperiment($_GET['eid']);
		if(!$experiment)
		{
			ab_press_createMessage("The experiment you selected does not exist!|ERROR");
			header( 'Location: admin.php?page=abpo-details&eid='.$_GET['eid'] ) ;
		}
		else
		{
			global $wpdb;
			$wpdb->delete( self::get_table_name('experiment'), array( 'id' => $_GET['eid'] ) );

			$wpdb->query( 
				$wpdb->prepare( 
					"DELETE FROM " .self::get_table_name('variations') ."
					 WHERE experiment_id = %d",
				       $_GET['eid']
			        )
			);
			ab_press_createMessage("Your experiment has been deleted succesfully!");
			header( 'Location: admin.php?page=abpo-experiment' ) ;	
		}

		
	}

	/**
	 * Create Databes for plugin
	 */
	private function create_experiment_table() {
		$table_name = self::get_table_name('experiment');
		
		if (!$this->database_table_exists($table_name)) {
			$sql = "CREATE TABLE " . $table_name . " (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					name VARCHAR(250) NOT NULL DEFAULT '',
					description VARCHAR(500) NOT NULL DEFAULT '',
					status VARCHAR(25) NOT NULL DEFAULT '',
					start_date DATE NOT NULL DEFAULT '0000-00-00 00:00:00',
					end_date DATE NOT NULL DEFAULT '0000-00-00 00:00:00',
					goal VARCHAR(500) NOT NULL DEFAULT '', 
					goal_type VARCHAR(100) NOT NULL DEFAULT '',
					url VARCHAR(500) NOT NULL DEFAULT '',
					original_visits INT NOT NULL DEFAULT 0,
					original_convertions INT NOT NULL DEFAULT 0,
					date_created DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
					);";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	/**
	 * Create Databes for plugin
	 */
	private function create_variations_table() {
		$table_name = self::get_table_name('variations');
		
		if (!$this->database_table_exists($table_name)) {
			$sql = "CREATE TABLE " . $table_name . " (
					id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
					experiment_id INT NOT NULL,
					type VARCHAR(100) NOT NULL DEFAULT '',
					name VARCHAR(250) NOT NULL DEFAULT '',
					value VARCHAR(500) NOT NULL DEFAULT '',
					class VARCHAR(500) NOT NULL DEFAULT '',
					visits INT NOT NULL DEFAULT 0,
					convertions INT NOT NULL DEFAULT 0,
					date_created DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00'
					);";

			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			dbDelta($sql);
		}
	}

	/**
	 * Return Table name
	 *
	 * @return String
	 */
	public static function get_table_name($name) {
		global $wpdb;
		return $wpdb->prefix . 'ab_press_optimizer_lite_' . $name;
	}

	/**
	 * Check if database exist
	 *
	 * @return Boolean
	 */
	private function database_table_exists($table_name) {
		global $wpdb;
		return strtolower($wpdb->get_var("SHOW TABLES LIKE '$table_name'")) == strtolower($table_name);
	}

	/**
	 * Setup cron job
	 */
	private function cronJob(){
		$data = get_transient('ab_press_cron');

		if(!$data)
		{
			$experiments = ab_press_getAllExperiment();
			foreach ($experiments as $experiment) {
				$startDate = $experiment->start_date;
				$endDate = $experiment->end_date;
				$today = date("Y-m-d");


				if($today > $endDate)
					ab_press_updateExperimentStatus($experiment->id, 'complete');
				elseif($today < $startDate  )
					ab_press_updateExperimentStatus($experiment->id, 'paused');
				elseif($today >= $startDate   && $today <= $endDate  )
					ab_press_updateExperimentStatus($experiment->id, 'running');
			}

			set_transient('ab_press_cron', "true", 60*60*12);
		}
		

	}

	
	/**
	 * Ab Press ShortCode
	 */
	public function ab_press_shortcode( $atts, $content = "") {

		extract( shortcode_atts( array(
		'id' => ''), $atts ) );

		if(!isset($id)) return $content;

		return ab_press_optimizer($id , $content);
	}

	// registers the buttons for use
	function register_ab_press_button($buttons) {
		array_push($buttons, "|", "ab_press_button");
		return $buttons;
	}

	// filters the tinyMCE buttons and adds our custom buttons
	function ab_press_shortcode_button() {
		// Don't bother doing this stuff if the current user lacks permissions
		if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
			return;
		 
		// Add only in Rich Editor mode
		if ( get_user_option('rich_editing') == 'true') {
			// filter the tinyMCE buttons and add our own
			add_filter("mce_external_plugins", array( $this, "add_ab_press_tinymce_plugin"));
			add_filter('mce_buttons', array( $this, 'register_ab_press_button'));
		}
	}

	// add the button to the tinyMCE bar
	function add_ab_press_tinymce_plugin($plugin_array) {
		$dir = WP_PLUGIN_URL . '/' . str_replace(basename( __FILE__), "" ,plugin_basename(__FILE__));
		$plugin_array['ab_press_button'] = $dir . 'js/ab-press-shortcode-button.js'; 
		$plugin_array['ABPressSiteURL'] = admin_url( 'admin-ajax.php' ); 
		return $plugin_array;
	}


	/**
	 * Ab Press Welcom Popup
	 */
	public function welcome_popup() {

		 //Check option to hide pointer after initial display
   		if ( !get_option( 'ab_press_hide_pointer' ) ) {
	        $pointer_content = '<h3>' . __( 'Start A/B Testing', 'ab_press' ) . '</h3>';
	        $pointer_content .= '<p>' . __( 'Congratulations. You have just installed AB Press Optimizer. ' .
	            'Start optimizing your website today!', 'ab_press' ) . '</p>';

	        $url = admin_url( 'admin.php?page=abpo-new' );
	        
	        ?>

	        <script type="text/javascript">
	            //<![CDATA[
	            jQuery(document).ready( function($) {
            		if(jQuery.fn.pointer)
            		{
		        		$("#menu-plugins").pointer({
		                content: '<?php echo $pointer_content; ?>',
		                buttons: function( event, t ) {
		                    button = $('<a id="pointer-close" class="button-secondary"><?php _e( 'Close', 'ab_press' ); ?></a>');
		                    button.bind("click.pointer", function() {
		                        t.element.pointer("close");
		                    });
		                    return button;
		                },
		                position: "left",
		                close: function() { }
		        
		            	}).pointer("open");
		          
		            	$("#pointer-close").after('<a id="pointer-primary" class="button-primary" style="margin-right: 5px;" href="<?php echo $url; ?>">' +  '<?php _e( 'Start a New Experiment', 'ab_press' ); ?>');
			        }
		           
		        });
	            //]]>
	        </script>

	        <?php
	        
	        //Update option so this pointer is never seen again
	        update_option( 'ab_press_hide_pointer', 1 );
		}
	}


}