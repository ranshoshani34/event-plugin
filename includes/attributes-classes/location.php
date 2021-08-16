<?php
/**
 * Class file for location attribute.
 *
 * @package event-plugin.
 */

/**
 * Class Location.
 */
class Location extends Event_Attribute {

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id -  (optional) the id of the post to retrieve old data from (if specified).
	 */
	public function render_metabox( int $post_id = 0 ) : void {
		if ( 0 !== $post_id ) {
			$event_venue = $this->get_value( $post_id );
		}

		$event_venue = ! empty( $event_venue ) ? $event_venue : '';

		?>
		<label
			for="rep-event-venue"><?php esc_html_e( 'Event Location:', 'rep' ); ?>
		</label>
		<input
			class="widefat"
			id="rep-event-venue"
			type="text"
			name="rep-event-venue"
			placeholder="eg. Times Square"
			value="<?php echo esc_html( $event_venue ); ?>"
		/>
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
		return get_post_meta( $post_id, 'event-venue', true );
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

		if ( isset( $_POST['rep-event-venue'] ) ) {
			update_post_meta( $post_id, 'event-venue', sanitize_text_field( wp_unslash( $_POST['rep-event-venue'] ) ) );
		}
	}

	/**
	 * Description - method to render the field about the attribute in the event page (single).
	 *
	 * @param int $post_id - the post id.
	 */
	public function render_single_field( int $post_id ) : void {
		$event_venue = $this->get_value( $post_id );
		?>
		<h3>Location:  <?php echo esc_html( $event_venue ); ?></h3>
		<?php
	}
}
