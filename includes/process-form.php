<?php
/**
 * File to process event creation form.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once WP_PLUGIN_DIR . '/event-plugin/includes/event-type-creator.php';

/**
 * Class that handles the processing of form data to create event.
 */
class Form_Processor {

	/**
	 * Function to process the event creation form.
	 */
	public static function process_form() {

		if ( ! isset( $_REQUEST['nonce'] ) ) {
			return;
		}

		$is_valid_nonce = wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'event_plugin_event_nonce' );

		if ( ! $is_valid_nonce ) {
			return;
		}

		$event_type_creator = Event_Type_Creator::instance();

		if ( isset( $_POST['event_plugin_title'] ) ) {
			$post_id = Event_Type_Creator::create_event_instance( sanitize_text_field( wp_unslash( $_POST['event_plugin_title'] ) ) );
		} else {
			$post_id = Event_Type_Creator::create_event_instance( sanitize_text_field( wp_unslash( '' ) ) );
		}

		$event_type_creator->save_event_data( $post_id, $_POST );

		$result['type']      = 'success';
		$result['permalink'] = get_the_permalink( $post_id );

		if ( ! empty( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) && strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) === 'xmlhttprequest' ) {
			$result = wp_json_encode( $result );
			echo $result; //phpcs:ignore
		} elseif ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			header( 'Location: ' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) );
		}
		die();
	}



}
