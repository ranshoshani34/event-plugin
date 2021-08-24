<?php
/**
 * Class file for Event_Type_Creator.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'attributes-manager.php';
require_once 'custom_post_status.php';

/**
 * Class Event_Type_Creator for creating the events custom post type.
 */
class Event_Type_Creator {
	/**
	 * Instance of attributes manager to manage attributes.
	 *
	 * @var Attributes_Manager
	 */
	private $attributes_manager;

	/**
	 * Singleton instance.
	 *
	 * @var Event_Type_Creator
	 */
	public static $instance;

	/**
	 * Constructor to initialize attributes manager.
	 */
	private function __construct() {
		$this->attributes_manager = Attributes_Manager::instance();
		$this->attributes_manager->register_attributes();

	}

	/**
	 * Instance method to get the singleton.
	 *
	 * @return Event_Type_Creator
	 */
	public static function instance() : Event_Type_Creator {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Method to add all the actions necessary for creating the post type.
	 */
	public function initialize() {
		add_action( 'init', [ $this, 'register' ] );
		add_action( 'add_meta_boxes', [ $this, 'add_metabox' ] );
		add_action( 'save_post', [ $this, 'save_data_from_dashboard' ] );

		$archive = new Custom_Post_Status(
			'archive',
			'Archive',
			'event',
			array(
				'label'                     => _x( 'Completed', 'post' ),
				'label_count'               => _n_noop( 'Archived <span class="count">(%s)</span>', 'Archived <span class="count">(%s)</span>'),
				'public'                    => false,
				'exclude_from_search'       => false,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true
			)
		);
		$archive->register();
	}

	/**
	 * Method that registers the custom post type.
	 */
	public function register() {
		$labels = [
			'name'               => __( 'Events', 'event-plugin' ),
			'singular_name'      => __( 'Event', 'event-plugin' ),
			'add_new_item'       => __( 'Add New Event', 'event-plugin' ),
			'all_items'          => __( 'All Events', 'event-plugin' ),
			'edit_item'          => __( 'Edit Event', 'event-plugin' ),
			'new_item'           => __( 'New Event', 'event-plugin' ),
			'view_item'          => __( 'View Event', 'event-plugin' ),
			'not_found'          => __( 'No Events Found', 'event-plugin' ),
			'not_found_in_trash' => __( 'No Events Found in Trash', 'event-plugin' ),
		];

		$supports = [
			'title',
			'thumbnail',
		];

		$args = [
			'label'        => __( 'Events', 'event-plugin' ),
			'labels'       => $labels,
			'description'  => __( 'A list of upcoming events', 'event-plugin' ),
			'public'       => true,
			'show_in_menu' => true,
			'has_archive'  => false,
			'rewrite'      => true,
			'supports'     => $supports,
		];

		register_post_type( 'event', $args );
	}

	/**
	 * Method to add the custom metabox.
	 */
	public function add_metabox() {
		add_meta_box(
			'event-plugin-info-metabox',
			__( 'Event Info', 'event-plugin' ),
			[ $this, 'render_metabox' ],
			'event',
			'normal',
			'core'
		);
	}

	/**
	 * Method to render the custom metabox.
	 */
	public function render_metabox() {
		// generate a nonce field.
		wp_nonce_field( basename( EVENT_PLUGIN_ROOT ), 'event_plugin_nonce' );

		foreach ( $this->attributes_manager->attributes_array as $attribute ) {
			$attribute->render_metabox( get_the_ID() );
		}
	}

	public function save_data_from_dashboard(int $post_id){
		if ( isset( $_POST['post_type'] ) ) {
			if ( 'event' !== $_POST['post_type'] ) {
				return;
			}
			// checking for the 'save' status.
			$is_autosave    = wp_is_post_autosave( $post_id );
			$is_revision    = wp_is_post_revision( $post_id );
			$is_valid_nonce = isset( $_POST['event_plugin_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['event_plugin_nonce'] ) ), basename( EVENT_PLUGIN_ROOT ) );

			// exit depending on the save status or if the nonce is not valid.
			if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
				return;
			}

			$this->save_event_data($post_id, $_POST);
		}
	}

	public function save_event_data( int $post_id , array $data) {
		foreach ( $this->attributes_manager->attributes_array as $attribute ) {
			$attribute->save_data( $post_id , $data);
		}
	}

	/**
	 * Method to echo and html form for event creation.
	 */
	public function echo_form_html() {

		foreach ( $this->attributes_manager->attributes_array as $attribute ) {
			$attribute->render_metabox();
			echo '<br><br>';
		}
	}

	public function register_new_attribute(Custom_Post_Attribute $attribute){
		$this->attributes_manager->register_new_attribute($attribute);
	}

}
