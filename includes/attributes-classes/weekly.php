<?php
/**
 * Class file for weekly attribute (is this a weekly event).
 *
 * @package event-plugin.
 */

/**
 * Class Weekly.
 */
class Weekly extends Event_Attribute {

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id -  (optional) the id of the post to retrieve old data from (if specified).
	 */
	public function render_metabox( int $post_id = 0 ) : void {
		if ( 0 !== $post_id ) {
			$is_checked = $this->get_value( $post_id );
		}

		$is_checked = ! empty( $is_checked ) && $is_checked;

		?>
		<br>
		<input type="checkbox" id="rep-weekly" name="rep-weekly"
			<?php
			if ( $is_checked ) {
				echo 'checked';
			}
			?>
		>
		<label for="rep-weekly">Weekly event</label>
		<br>
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
		return get_post_meta( $post_id, 'event-weekly', true );
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

		update_post_meta( $post_id, 'event-weekly', isset( $_POST['rep-weekly'] ) );
	}


	/**
	 * Description - method to render the field about the attribute in the event page (single).
	 *
	 * @param int $post_id - the post id.
	 */
	public function render_single_field( int $post_id ) : void {
		$event_weekly = $this->get_value( $post_id );
		?>
		<h3>Weekly event:
			<?php
			if ( $event_weekly ) {
				echo 'yes';
			} else {
				echo 'no';
			}
			?>
		</h3>
		<?php
	}
}
