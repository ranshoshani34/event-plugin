<?php
/**
 * Class file for Event_Type_Creator.
 *
 * @package event-plugin.
 */
require_once 'attributes-manager.php';

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
	private static $instance;

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
		add_action( 'init', array( $this, 'register' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_metabox' ) );
		add_action( 'save_post', array( $this, 'after_submit' ) );
	}

	/**
	 * Method that registers the custom post type.
	 */
	public function register() {
		$labels = array(
			'name'               => __( 'Events', 'rep' ),
			'singular_name'      => __( 'Event', 'rep' ),
			'add_new_item'       => __( 'Add New Event', 'rep' ),
			'all_items'          => __( 'All Events', 'rep' ),
			'edit_item'          => __( 'Edit Event', 'rep' ),
			'new_item'           => __( 'New Event', 'rep' ),
			'view_item'          => __( 'View Event', 'rep' ),
			'not_found'          => __( 'No Events Found', 'rep' ),
			'not_found_in_trash' => __( 'No Events Found in Trash', 'rep' ),
		);

		$supports = array(
			'title',
			'thumbnail',
		);

		$args = array(
			'label'        => __( 'Events', 'rep' ),
			'labels'       => $labels,
			'description'  => __( 'A list of upcoming events', 'rep' ),
			'public'       => true,
			'show_in_menu' => true,
			'menu_icon'    => Event_Plugin::instance()::IMAGES . 'event.svg',
			'has_archive'  => false,
			'rewrite'      => true,
			'supports'     => $supports,
		);

		register_post_type( 'event', $args );
	}

	/**
	 * Method to add the custom metabox.
	 */
	public function add_metabox() {
		add_meta_box(
			'rep-event-info-metabox',
			__( 'Event Info', 'rep' ),
			array( $this, 'render_metabox' ),
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
		wp_nonce_field( basename( EVENT_PLUGIN_ROOT ), 'rep-event-info-nonce' );

		foreach ( $this->attributes_manager->attributes_array as $attribute ) {
			$attribute->render_metabox( get_the_ID() );
		}
	}

	/**
	 * Method that saves the attributes data after the post is saved.
	 *
	 * @param int $post_id - the post id.
	 */
	public function after_submit( int $post_id ) {

		if ( isset( $_POST['post_type'] ) ) {
			if ( 'event' !== $_POST['post_type'] ) {
				return;
			}

			// checking for the 'save' status.
			$is_autosave    = wp_is_post_autosave( $post_id );
			$is_revision    = wp_is_post_revision( $post_id );
			$is_valid_nonce = isset( $_POST['rep-event-info-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rep-event-info-nonce'] ) ), basename( EVENT_PLUGIN_ROOT ) );

			// exit depending on the save status or if the nonce is not valid.
			if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
				return;
			}
		}

		// loop through the attributes and update internally the database values.
		foreach ( $this->attributes_manager->attributes_array as $attribute ) {
			$attribute->after_save_post( $post_id );
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

}
