<?php
/**
 * Custom post status class
 *
 * @package event-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class that adds a custom post status
 */
class Custom_Post_Status {
	/**
	 * Name of the post status
	 *
	 * @var string
	 */
	private $name;

	/**
	 * Settings array to pass to register_post_status
	 *
	 * @var array
	 */
	private $settings;

	/**
	 * The post type to add the custom status
	 *
	 * @var string
	 */
	private $post_type;

	/**
	 * The title of the status (for display)
	 *
	 * @var string
	 */
	private $title;

	/**
	 * Constructor
	 *
	 * @param string $name Name of the post status.
	 * @param string $title The title of the status (for display).
	 * @param string $post_type The post type to add the custom status.
	 * @param array  $settings Settings array to pass to register_post_status.
	 */
	public function __construct( string $name, string $title, string $post_type, array $settings ) {
		$this->name      = $name;
		$this->settings  = $settings;
		$this->post_type = $post_type;
		$this->title     = $title;
	}

	/**
	 * Getter for the name
	 *
	 * @return string
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Getter for the title.
	 *
	 * @return string
	 */
	public function get_title(): string {
		return $this->title;
	}


	/**
	 * Handles the necessary actions to register the custom post status.
	 */
	public function register() {
		register_post_status( $this->get_name(), $this->settings );

		add_filter( 'display_post_states', [ $this, 'display_archive_state' ] );
		add_action( 'post_submitbox_misc_actions', [ $this, 'add_to_post_status_dropdown' ] );
		add_action( 'admin_footer-edit.php', [ $this, 'custom_status_add_in_quick_edit' ] );
	}

	/**
	 * Adds the status to dropdown menu.
	 *
	 * @return false|void
	 */
	public function add_to_post_status_dropdown() {
		global $post;
		if ( $post->post_type !== $this->post_type ) {
			return false;
		}

		$status = ( $post->post_status === $this->get_name() ) ? "jQuery( '#post-status-display' ).text( '{$this->get_title()}' );
						jQuery( 'select[name=\"post_status\"]' ).val('{$this->get_name()}');" : '';

		$to_echo = "<script> 
					jQuery(document).ready( function() {
					jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"completed\">{$this->get_title()}</option>' );
					$status
					});
					</script>";

		echo $to_echo; //phpcs:ignore
	}

	/**
	 * Add the status to quick edit.
	 *
	 * @return false|void
	 */
	public function custom_status_add_in_quick_edit() {
		global $post;
		if ( $post->post_type !== $this->post_type ) {
			return false;
		}

		$to_echo = "<script>
					jQuery(document).ready( function() {
					jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"{$this->get_name()}\">{$this->get_title()}</option>' );
					});
				</script>";

		echo $to_echo;//phpcs:ignore
	}

	/**
	 * Method to add the status as a state in the post table.
	 *
	 * @param array $states An array of post display states.
	 *
	 * @return string[]
	 */
	public function display_archive_state( array $states ): array {
		global $post;
		$arg = get_query_var( 'post_status' );
		if ( $arg !== $this->get_name() && $post->post_status === $this->get_name() ) {
				$to_echo = "<script>
							jQuery(document).ready( function() {
							jQuery( '#post-status-display' ).text( '{$this->get_name()}' );
							});
						</script>";

				echo $to_echo; //phpcs:ignore

				return [ $this->get_title() ];
		}
		return $states;
	}

}



