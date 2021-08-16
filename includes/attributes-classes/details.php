<?php /** @noinspection PhpCSValidationInspection */
/**
 * Class file for details attribute.
 *
 * @package event-plugin.
 */

/**
 * Class Detail
 */
class Details extends Event_Attribute {

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id -  (optional) the id of the post to retrieve old data from (if specified).
	 */
	public function render_metabox( int $post_id = 0 ) : void {
		if (0 !== $post_id) {
			$event_details = $this->get_value( $post_id );
		}

		$event_details = ! empty ($event_details) ? $event_details : '';

		?>
		<label for="rep-event-details"><?php esc_html_e( 'Event Details:', 'rep' ); ?>
		</label>
		<textarea class="widefat" id="rep-event-details" name="rep-event-details"><?php echo esc_html( $event_details ); ?></textarea>
		<?php
	}

	/**
	 * Description - method to get the attribute value from the database.
	 *
	 * @param int $post_id - the post id.
	 *
	 * @return string
	 */
	public function get_value( int $post_id ) : string {
		return get_post_meta( $post_id, 'event-details', true );
	}

	/**
	 * Description - method to update the database from the submitted form.
	 *
	 * @param int $post_id - the post id.
	 */
	public function update_value( int $post_id ) : void {
		$is_nonce_valid = isset( $_POST['rep-event-info-nonce'] ) && ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rep-event-info-nonce'] ) ), basename( ROOT ) ) );
		if ( ! $is_nonce_valid ) {
			return;
		}

		if ( isset( $_POST['rep-event-details'] ) ) {
			update_post_meta( $post_id, 'event-details', sanitize_text_field( wp_unslash( $_POST['rep-event-details'] ) ) );
		}
	}

	/**
	 * Description - method to render the field about the attribute in the event page (single).
	 *
	 * @param int $post_id - the post id.
	 */
	public function render_single_field( int $post_id ) : void {
		$event_details = $this->get_value( $post_id );
		?>
		<h3>Details:</h3>
		<p>         <?php echo esc_html( $event_details ); ?> </p>
		<?php
	}
}
