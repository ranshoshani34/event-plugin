<?php
/**
 * File to process event creation form.
 */
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

		$is_valid_nonce = wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['nonce'] ) ), 'rep_event_nonce' );

		if ( ! $is_valid_nonce ) {
			return;
		}

		$event_type_creator = Event_Type_Creator::instance();

		if ( isset( $_POST['rep-title'] ) ) {
			$post_id = self::create_event_instance( sanitize_text_field( wp_unslash( $_POST['rep-title'] ) ) );
		} else {
			$post_id = '';
		}

		$event_type_creator->after_submit( $post_id );

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

	/**
	 * Method to create event.
	 *
	 * @param string $title title of the new post.
	 *
	 * @return int the post id that was created.
	 */
	public static function create_event_instance( string $title ): int {
		// insert the post and set the category.
		return wp_insert_post(
			[
				'post_type'      => 'event',
				'post_title'     => $title,
				'post_content'   => '',
				'post_status'    => 'publish',
				'comment_status' => 'closed',
				'ping_status'    => 'closed',
			]
		);
	}

}
