<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://codex.management
 * @since      1.0.0
 *
 * @package    Codex_Connector
 * @subpackage Codex_Connector/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Codex_Connector
 * @subpackage Codex_Connector/admin
 * @author     Codex <info@codex.management>
 */
class Codex_Connector_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;



	/**
	 * The options name to be used in this plugin
	 *
	 * @since  	1.0.0
	 * @access 	private
	 * @var  	string 		$option_name 	Option name of this plugin
	 */
	private $option_name = 'codex_connector';


	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Codex_Connector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Codex_Connector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/codex-connector-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Codex_Connector_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Codex_Connector_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/codex-connector-admin.js', array( 'jquery' ), $this->version, false );

	}


	/**
	 * Add an options page under the Settings submenu
	 *
	 * @since  1.0.0
	 */
	public function add_options_page() {

		$this->plugin_screen_hook_suffix = add_options_page(
			__( 'Codex Connector Settings', 'codex-connector' ),
			__( 'Codex Connector', 'codex-connector' ),
			'manage_options',
			$this->plugin_name,
			array( $this, 'display_options_page' )
			);

	}

	/**
	 * Render the options page for plugin
	 *
	 * @since  1.0.0
	 */
	public function display_options_page() {

			
		try {
			$connection = $this->codex_connector_api_authentication();
			
		} catch (Exception $e) {
			$connection = false;
			$this->api_error= $e->getMessage();
		}
		$this->api_connection = $connection;
		
		// when changing the settings page, you are changing credentials, so you need to check the api again to see if it can authenticate using the possibly changed credentials
		// @todo: only reset token when you update the credentials. now it fires every time you load the settings page
		unset($_SESSION['codex_api_token']);

		include_once 'partials/codex-connector-admin-display.php';
	}

	/*
	* Register a setting
	*/
	public function register_setting()
	{
		// Add a General section
		add_settings_section(
			$this->option_name . '_general',
			__( 'Verbinding', 'codex-connector' ),
			array( $this, $this->option_name . '_general_cb' ),
			$this->plugin_name
			);

		add_settings_field(
			$this->option_name . '_server',
			__( 'Codex Server', 'codex-connector' ),
			array( $this, $this->option_name . '_server_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_server' )
			);
		add_settings_field(
			$this->option_name . '_username',
			__( 'Gebruikersnaam', 'codex-connector' ),
			array( $this, $this->option_name . '_username_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_username' )
			);
		add_settings_field(
			$this->option_name . '_password',
			__( 'Wachtwoord', 'codex-connector' ),
			array( $this, $this->option_name . '_password_cb' ),
			$this->plugin_name,
			$this->option_name . '_general',
			array( 'label_for' => $this->option_name . '_password' )
			);

		// real settings
		// TODO: add sanitation
		register_setting( $this->plugin_name, $this->option_name . '_server' );
		register_setting( $this->plugin_name, $this->option_name . '_username' );
		register_setting( $this->plugin_name, $this->option_name . '_password' );

	}

	/**
	 * Render the text for the general section
	 *
	 * @since  1.0.0
	 */
	public function codex_connector_general_cb() {
		echo '<p>' . __( 'Met de instellingen hieronder kan je deze plug-in verbinden met jouw Codex. De inloggegevens kan je aanmaken in je Codex in de module "API Manager".', 'codex-connector' ) . '</p>';
	}

	/**
	 * Render the radio input field for position option
	 *
	 * @since  1.0.0
	 */
	public function codex_connector_position_cb() {
		$position = get_option( $this->option_name . '_position' );
		?>
		<fieldset>
			<label>
				<input type="radio" name="<?php echo $this->option_name . '_position' ?>" id="<?php echo $this->option_name . '_position' ?>" value="before" <?php checked( $position, 'before' ); ?>>
				<?php _e( 'Before the content', 'codex-connector' ); ?>
			</label>
			<br>
			<label>
				<input type="radio" name="<?php echo $this->option_name . '_position' ?>" value="after" <?php checked( $position, 'after' ); ?>>
				<?php _e( 'After the content', 'codex-connector' ); ?>
			</label>
		</fieldset>
		<?php
	}
	/**
	 * Render the treshold day input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function codex_connector_day_cb() {
		$day = get_option( $this->option_name . '_day' );
		echo '<input type="text" name="' . $this->option_name . '_day' . '" id="'
		. $this->option_name . '_day' . '" value="' . $day . '"> '
		. __( 'days', 'codex-connector' );
	}






	/**
	 * Render the treshold day input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function codex_connector_server_cb() {
		$server = get_option( $this->option_name . '_server' );
		echo 'https://<input type="text" name="' . $this->option_name . '_server' . '" id="' .
		$this->option_name . '_server' . '" value="' . $server . '">.codex.link<br/><small>'
		. __( 'Dit is de naam van je codex. Dit is het dikgedrukte gedeelte van je bestuurscodex, bijv: https://<strong>verenigingsnaam</strong>.codex.link', 'codex-connector' )
		.'</small>';
	}


	/**
	 * Render the treshold day input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function codex_connector_username_cb() {
		$username = get_option( $this->option_name . '_username' );
		echo '<input type="text" name="' . $this->option_name . '_username' . '" id="'
		. $this->option_name . '_username' . '" value="' . $username . '"><br/><small>'
		. __( 'Dit is de gebruikersnaam dat in Codex gegeven staat.', 'codex-connector' )
		.'</small>';
	}


	/**
	 * Render the treshold day input for this plugin
	 *
	 * @since  1.0.0
	 */
	public function codex_connector_password_cb() {
		$password = get_option( $this->option_name . '_password' );
		echo '<input type="password" name="' . $this->option_name . '_password' . '" id="'
		. $this->option_name . '_password' . '" value="' . $password . '"><br/><small>'
		. __( 'Dit is het wachtwoord dat in Codex gegeven staat', 'codex-connector' )
		.'</small>';
	}







	

	/**
	 * Test the connection to Codex through an API call of authentication
	 * @return json response or false
	 */
	public function codex_connector_api_authentication()
	{
		session_start();
		if(is_null($_SESSION['codex_api_token']) || $_SESSION['codex_api_token'] ==="")  {
	
			$creds['server'] = get_option( $this->option_name . '_server' );
			if($creds['server'] ===false|| $creds['server']==="") {
				throw new Exception("Kon niet verbinden: server is leeg");
				
			}
			$creds['username'] = get_option( $this->option_name . '_username' );
			
			if($creds['username'] ===false|| $creds['username']==="") {
				throw new Exception("Kon niet verbinden: username is leeg");
				
			}
				$creds['password'] = get_option( $this->option_name . '_password' );
			if($creds['password'] ===false|| $creds['password']==="") {
				throw new Exception("Kon niet verbinden: password is leeg");
			}
			$response = Codex_Connector_API::authenticate();
			
			if(is_null($response) || $response === false || $response['status'] === false || isset($response->error)) {
				throw new Exception("Kreeg een fout terug: waarschijnlijk kloppen je credentials niet. ". $response->error);
			}

		} else {
			// token is still set.
		}
		return $response;
	}

	/**
	 * Sanitize the text position value before being saved to database
	 *
	 * @param  string $position $_POST value
	 * @since  1.0.0
	 * @return string           Sanitized value
	 */
	public function codex_connector_sanitize_position( $position ) {
		if ( in_array( $position,
		 array( 'before', 'after' ), true ) ) {
			return $position;
		}
	}


}
