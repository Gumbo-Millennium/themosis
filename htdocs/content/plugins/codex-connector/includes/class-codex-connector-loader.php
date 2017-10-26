<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://codex.management
 * @since      1.0.0
 *
 * @package    Codex_Connector
 * @subpackage Codex_Connector/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Codex_Connector
 * @subpackage Codex_Connector/includes
 * @author     Daan Rijpkema <info@codex.management>
 */
class Codex_Connector_Loader {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $actions;

	/**
	 * The array of filters registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $filters    The filters registered with WordPress to fire when the plugin loads.
	 */
	protected $filters;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->actions = array();
		$this->filters = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress action that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the action is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1.
	 */
	public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * Add a new filter to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         Optional. he priority at which the function should be fired. Default is 10.
	 * @param    int                  $accepted_args    Optional. The number of arguments that should be passed to the $callback. Default is 1
	 */
	public function add_filter( $hook, $component, $callback, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $component, $callback, $priority, $accepted_args );
	}

	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $hooks            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $hook             The name of the WordPress filter that is being registered.
	 * @param    object               $component        A reference to the instance of the object on which the filter is defined.
	 * @param    string               $callback         The name of the function definition on the $component.
	 * @param    int                  $priority         The priority at which the function should be fired.
	 * @param    int                  $accepted_args    The number of arguments that should be passed to the $callback.
	 * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {

		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
			);

		return $hooks;

	}

	/**
	 * Register the filters and actions with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {


		// echo "Loader of Connector";
		// mind the backslashes - they need to be forward or backward depending on platform, apparently
		require_once plugin_dir_path( __FILE__ ) . '../api/class-codex-connector-api.php';
		require_once plugin_dir_path( __FILE__ ) . '../api/class-codex-connector-actions.php';
		
		
		$server_name=get_option( 'codex_connector_server' );
		if($server_name) {
			Codex_Connector_API::initiate(
				get_option( 'codex_connector_server' ),
				get_option( 'codex_connector_username'),
				get_option( 'codex_connector_password')
				);
		} else {
			// no api, because there was no server set
		}




		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}



		// todo: loop this shiz
		add_action( 'codex_test', 'codex_connector_do_codex_test');
		add_action( 'codex_api_authenticate', 'codex_connector_api_authenticate');

		
		// group
		add_action( 'codex_groups', 'codex_connector_action_groups',10);
		add_action( 'codex_group', 'codex_connector_action_group',10,1);
		add_action( 'codex_groups_filter', 'codex_connector_action_groups_filter',10,2);
		add_action( 'codex_group_subscribeform', 'codex_connector_action_group_subscriptionform',10,1);

		add_shortcode( 'codex_groups', 'codex_connector_shortcode_groups');
		add_shortcode( 'codex_group', 'codex_connector_shortcode_group');
		add_shortcode( 'codex_groups_filter', 'codex_connector_shortcode_groups_filter');
		add_shortcode( 'codex_group_subscribeform', 'codex_connector_shortcode_group_subscriptionform');

		// member
		add_action( 'codex_members', 'codex_connector_action_members',10);
		add_action( 'codex_member', 'codex_connector_action_member',10,1);
		add_action( 'codex_members_filter', 'codex_connector_action_members_filter',10,2);

		add_shortcode( 'codex_members', 'codex_connector_shortcode_members');
		add_shortcode( 'codex_member', 'codex_connector_shortcode_member');
		add_shortcode( 'codex_members_filter', 'codex_connector_shortcode_members_filter');

		//mambo
		add_action( 'codex_mambo', 'codex_connector_action_mambo',10);
		add_action( 'codex_mambo_filter', 'codex_connector_action_mambo_filter',10,2);

		add_shortcode( 'codex_mambo', 'codex_connector_shortcode_mambo');
		add_shortcode( 'codex_mambo_filter', 'codex_connector_shortcode_mambo_filter');



		add_action( 'codex_mambo_send_update_request', 'codex_connector_action_mambo_send_update_request',10,2);


	}



}
