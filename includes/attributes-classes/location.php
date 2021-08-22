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
	private $tag_id = 'rep-event-venue';

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id -  (optional) the id of the post to retrieve old data from (if specified).
	 */
	public function render_metabox( int $post_id = 0) : void {
		if ( 0 !== $post_id ) {
			$event_venue = $this->get_value( $post_id );
		}

		$event_venue = ! empty( $event_venue ) ? $event_venue : '';
		?>
		<label
			for="<?php echo $this->tag_id; //phpcs:ignore?>"><?php esc_html_e( 'Event Location:', 'rep' ); ?>
		</label>
		<input
			class="widefat"
			id="<?php echo $this->tag_id; //phpcs:ignore?>"
			type="text"
			name="<?php echo $this->tag_id; //phpcs:ignore?>"
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
	 * Method to update the database with the given values.
	 *
	 * @param int $post_id the post id.
	 * @param array $values array of values to add to the database.
	 */
	public function update_value( int $post_id ,array $values) : void {

		update_post_meta( $post_id, 'event-venue', $values[0] );//phpcs:ignore
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
		if ( isset( $_POST[$this->tag_id] ) ) { //phpcs:ignore
			$this->update_value( $post_id , [ sanitize_text_field( wp_unslash($_POST[$this->tag_id]))] );
		}
	}

	/**
	 * Method to process Form API information for this attribute
	 *
	 * @param int $post_id the post id.
	 * @param array $fields array of record fields to process form information from.
	 */
	public function after_elementor_form_submit( int $post_id, array $fields) {
		$values = [$fields['location']];

		$this->update_value( $post_id, $values);
	}
}
