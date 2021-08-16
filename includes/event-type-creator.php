<?php
/**
 * Class file for Event_Type_Creator.
 *
 * @package event-plugin.
 */
require 'attributes-manager.php';

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
		add_action( 'save_post', array( $this, 'save_event_info' ) );
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
			'menu_icon'    => IMAGES . 'event.svg',
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
		wp_nonce_field( basename( ROOT ), 'rep-event-info-nonce' );

		foreach ( $this->attributes_manager->attributes_array as $attribute ) {
			$attribute->render_metabox( get_the_ID() );
		}
	}

	/**
	 * Method that saves the attributes data after the post is saved.
	 *
	 * @param int $post_id - the post id.
	 */
	public function save_event_info( int $post_id ) {

		// checking if the post being saved is an 'event',
		// if not, then return.
		if ( isset( $_POST['post_type'] ) ) {
			if ( 'event' !== $_POST['post_type'] ) {
				return;
			}
		}

		// checking for the 'save' status.
		$is_autosave    = wp_is_post_autosave( $post_id );
		$is_revision    = wp_is_post_revision( $post_id );
		$is_valid_nonce = isset( $_POST['rep-event-info-nonce'] ) &&
			( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rep-event-info-nonce'] ) ), basename( ROOT ) ) );

		// exit depending on the save status or if the nonce is not valid.
		if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
			return;
		}

		// loop through the attributes and update internally the database values.
		foreach ( $this->attributes_manager->attributes_array as $attribute ) {
			$attribute->update_value( $post_id );
		}

	}
	// todo ajax.
	public function create_event_instance() {
		// insert the post and set the category.
		$post_id = wp_insert_post(
			array(
				'post_type'      => 'event',
				'post_title'     => 'TEST',
				'post_content'   => '',
				'post_status'    => 'publish',
				'comment_status' => 'closed',   // if you prefer.
				'ping_status'    => 'closed',      // if you prefer.
			)
		);

		if ( $post_id ) {
			// todo.
		}
	}

	public function echo_form_html() {
		// generate a nonce field.
		wp_nonce_field( basename( ROOT ), 'rep-event-info-nonce' );

		foreach ( $this->attributes_manager->attributes_array as $attribute ) {
			$attribute->render_metabox();
			echo '<br><br>';
		}
	}

}
