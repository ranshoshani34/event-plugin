<?php
/**
 * Class file for location attribute.
 *
 * @package event-plugin.
 */

/**
 * Class Location.
 */
class Location extends Custom_Post_Attribute {

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
		$tag_id = 'rep-event-venue';
		?>
		<label
			for="<?php echo $tag_id; ?>"><?php esc_html_e( 'Event Location:', 'rep' ); ?>
		</label>
		<input
			class="widefat"
			id="<?php echo $tag_id; ?>"
			type="text"
			name="<?php echo $tag_id; ?>"
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

		if ( isset( $_POST['rep-event-venue'] ) ) { //phpcs:ignore
			update_post_meta( $post_id, 'event-venue', sanitize_text_field( wp_unslash( $_POST['rep-event-venue'] ) ) );//phpcs:ignore
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

	/**
	 * Method that does any action that should happen after a post is saved.
	 *
	 * @param int $post_id id of the post.
	 */
	public function after_save_post( int $post_id ) {
		$this->update_value( $post_id );
	}
}
