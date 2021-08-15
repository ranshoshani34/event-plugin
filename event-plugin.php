<?php //phpcs:ignore

/**
 * Plugin Name: Elementor Event Widget
 * Description: Custom event extension with 'calendar' widget and 'create event' widget.
 * Version:     1.0.0
 * Author:      Ran Shoshani
 * Text Domain: elementor-event-widget.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'ROOT', plugins_url( '', __FILE__ ) );
const IMAGES = ROOT . '/img/';
const STYLES = ROOT . '/css/';

require 'includes/event-type-creator.php';
require 'includes/template-manager.php';

/**
 * Main Event Plugin class
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Event_Plugin {

	/**
	 * Plugin Version
	 *
	 * @since 1.0.0
	 * @var string The plugin version.
	 */
	const VERSION = '1.0.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '7.0';

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var Event_Plugin The single instance of the class.
	 */
	private static $instance = null;

	/**
	 * Instance
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Event_Plugin An instance of the class.
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function instance() : Event_Plugin {

		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );

	}

	/**
	 * Load Textdomain
	 * Load plugin localization files.
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain( 'event-plugin' );

	}

	/**
	 * On Plugins Loaded
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function on_plugins_loaded() {

		if ( $this->is_compatible() ) {
			$this->init();
		} else {
			return;
		}

		if ( $this->is_elementor_compatible() ) {
			$this->elementor_init();
		}

	}

	/**
	 * Compatibility Checks.
	 * Checks if the installed PHP version meets the plugin's minimum requirement.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return bool
	 */
	public function is_compatible() : bool {

		// Check for required PHP version.
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_php_version' ) );

			return false;
		}

		return true;

	}

	/**
	 * Compatibility Checks.
	 * Checks if the installed version of Elementor meets the plugin's minimum requirement.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return bool
	 */
	public function is_elementor_compatible() : bool {

		// Check if Elementor installed and activated.
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_missing_main_plugin' ) );

			return false;
		}
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', array( $this, 'admin_notice_minimum_elementor_version' ) );

			return false;
		}

		return true;
	}

	/**
	 * Initialize the plugin
	 * Load the plugin and adds all the necessary actions that is unrelated to elementor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {

		$this->i18n();
		flush_rewrite_rules();

		$event_type_creator = new Event_Type_Creator();
		$template_manager   = new Template_Manager();
		$event_type_creator->initialize();
		$template_manager->initialize();
	}

	/**
	 * Initialize the plugin elementor part.
	 * Load the plugin and adds all the necessary actions that is related to elementor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function elementor_init() {
		add_action( 'elementor/init', array( $this, 'add_actions_elementor' ) );
	}

	/**
	 * Adds necessary actions after elementor initialization.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_actions_elementor() {
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'init_widgets' ) );
		add_action( 'elementor/controls/controls_registered', array( $this, 'init_controls' ) );
	}

	/**
	 * Init Widgets
	 * Include widgets files and register them
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files.
		require_once __DIR__ . '/widgets/calendar-widget.php';
		// todo
		// require_once __DIR__ . '/widgets/event-creator-widget.php';

		// Register widget.
		Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Calendar_Widget() );

		// todo
		// \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Event_Creator_Widget() );
	}

	/**
	 * Init Controls
	 * Include controls files and register them
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init_controls() {

		// Include Control files.
		// todo
		// require_once __DIR__ . '/controls/test-control.php';

		// Register control.
		// todo
		// \Elementor\Plugin::$instance->controls_manager->register_control( 'control-type-', new \Test_Control() );
	}

	/**
	 * Admin notice
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) {  //phpcs:ignore
			unset( $_GET['activate'] );  //phpcs:ignore
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" noticed that "%2$s" is not installed. You could install elementor to enhance this plugins capabilities', 'event-plugin' ),
			'<strong>' . esc_html__( 'Event Plugin', 'event-plugin' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'event-plugin' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); //phpcs:ignore

	}

	/**
	 * Admin notice
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) {  //phpcs:ignore
			unset( $_GET['activate'] );  //phpcs:ignore
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" can use "%2$s" with version %3$s or greater. Please update version to use the elementor widget.', 'event-plugin' ),
			'<strong>' . esc_html__( 'Elementor Event Widget', 'event-plugin' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'event-plugin' ) . '</strong>',
			self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); //phpcs:ignore

	}

	/**
	 * Admin notice
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) { //phpcs:ignore
			unset( $_GET['activate'] ); //phpcs:ignore
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'event-plugin' ),
			'<strong>' . esc_html__( 'Elementor Event Widget', 'event-plugin' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'event-plugin' ) . '</strong>',
			self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message ); //phpcs:ignore

	}

}

Event_Plugin::instance();
