<?php
/**
 *  File for main plugin class.
 *
 * @package event-plugin.
 */

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
	 * Styles path const.
	 *
	 * @var string Styles path.
	 */
	const STYLES = EVENT_PLUGIN_ROOT . '/assets/css/';

	/**
	 * Scripts path const.
	 *
	 * @var string Scripts path.
	 */
	const SCRIPTS = EVENT_PLUGIN_ROOT . '/assets/scripts/';

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
		register_activation_hook( basename( EVENT_PLUGIN_ROOT ), [ $this, 'activate' ] );
		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );
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
	 * 'on activation' function.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function activate() {
		flush_rewrite_rules();
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
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

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
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

			return false;
		}
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

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
		$this->add_styles_and_scripts();
		$this->initialize_utility_classes();
	}

	/**
	 * Initialize the plugin elementor part.
	 * Load the plugin and adds all the necessary actions that is related to elementor.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function elementor_init() {
		add_action( 'elementor/init', [ $this, 'add_actions_elementor' ] );
		add_action( 'elementor_pro/init', [$this, 'add_form_action']);
	}

	public function add_form_action(){
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/create-event-form-action.php';
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/user-form-field.php';

		$action = new Event_Form_Action();
		$user_field = new User_Form_Field();

		// Register the action with form widget
		ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_action( $action->get_name(), $action );
		ElementorPro\Plugin::instance()->modules_manager->get_modules( 'forms' )->add_form_field_type( $user_field->get_name(), $user_field );
	}

	/**
	 * Adds necessary actions after elementor initialization.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function add_actions_elementor() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'init_controls' ] );
		$this->add_ajax_actions_elementor();
		$this->add_ajax_actions();

	}

	/**
	 * Adds necessary actions for ajax operation regarding elementor widgets.
	 */
	public function add_ajax_actions_elementor() {
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/process-form.php';

		add_action( 'wp_ajax_process_form', 'Form_Processor::process_form' );
		add_action( 'wp_ajax_nopriv_process_form', 'Form_Processor::process_form' );
	}

	/**
	 * Adds necessary actions for ajax operation.
	 */
	public function add_ajax_actions() {
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/calendar-creator.php';

		add_action( 'wp_ajax_change_month', 'Calendar_Creator::respond_with_calendar' );
		add_action( 'wp_ajax_nopriv_change_month', 'Calendar_Creator::respond_with_calendar' );
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
		require_once WP_PLUGIN_DIR . '/event-plugin/widgets/calendar-widget.php';
		require_once WP_PLUGIN_DIR . '/event-plugin/widgets/event-creator-widget.php';

		// Register widget.
		Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Calendar_Widget() );
		Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Event_Creator_Widget() );
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

	/**
	 * Method to initialize all the utility class.
	 */
	private function initialize_utility_classes() {
		$event_type_creator = Event_Type_Creator::instance();
		$event_type_creator->initialize();

		Template_Manager::initialize();


	}

	/**
	 * Method to add styles and scripts.
	 */
	private function add_styles_and_scripts() {
		wp_enqueue_style( 'style.css', self::STYLES . 'style.css', [], self::VERSION );

		wp_register_script(
			'event_scripts',
			self::SCRIPTS . 'event-scripts.js',
			[ 'jquery' ],
			self::VERSION,
			false
		);
		wp_localize_script( 'event_scripts', 'myAjax', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );

		wp_register_script(
			'calendar_scripts',
			self::SCRIPTS . 'calendar-scripts.js',
			[ 'jquery' ],
			self::VERSION,
			false
		);
		wp_localize_script( 'calendar_scripts', 'myAjax', [ 'ajaxurl' => admin_url( 'admin-ajax.php' ) ] );

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'event_scripts' );
		wp_enqueue_script( 'calendar_scripts' );

	}

}

