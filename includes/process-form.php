<?php
/**
 * File to process event creation form.
 */
require_once WP_PLUGIN_DIR . '/event-plugin/includes/event-type-creator.php';

/**
 * class that handles the processing of form data to create event.
 */
class Form_Processor {

	/**
	 * Function to process the event creation form.
	 */
	public static function process_form() {
		$is_valid_nonce = wp_verify_nonce( $_REQUEST['nonce'], "rep_event_nonce");

		if ( ! $is_valid_nonce ) {
			return;
		}

		$event_type_creator = Event_Type_Creator::instance();

		$post_id = self::create_event_instance( $_POST['rep-title'] );

		$event_type_creator->after_submit( $post_id );

		$result['type'] = 'success';
		$result['permalink'] = get_the_permalink($post_id);

		if ( ! empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) == 'xmlhttprequest' ) {
			$result = json_encode( $result );
			echo $result;
		} else {
			header( 'Location: ' . $_SERVER['HTTP_REFERER'] );
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
			array(
				'post_type' => 'event',
				'post_title' => $title,
				'post_content' => '',
				'post_status' => 'publish',
				'comment_status' => 'closed',
				'ping_status' => 'closed',
			)
		);
	}

}