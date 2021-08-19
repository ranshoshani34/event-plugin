<?php
/**
 * Class file for end date attribute.
 *
 * @package event-plugin
 */

/**
 * Class End_Date.
 */
class End_Date extends Custom_Post_Attribute {

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id -  (optional) the id of the post to retrieve old data from (if specified).
	 */
	public function render_metabox( int $post_id = 0 ) : void {
		if ( 0 !== $post_id ) {
			$event_end_date = $this->get_value( $post_id );
		}

		$event_end_date = ! empty( $event_end_date ) ? $event_end_date : time();
		$tag_id = 'rep-event-end-date';
		?>
		<label for="<?php echo $tag_id; ?>"><?php esc_html_e( 'Event End Date:', 'rep' ); ?>
		</label>
		<input class="widefat <?php echo $tag_id; ?>" id="<?php echo $tag_id; ?>" type="date" name="<?php echo $tag_id; ?>" placeholder="Format: February 18, 2014" value="<?php echo esc_html( gmdate( 'Y-m-d', $event_end_date ) ); ?>"/>
		<?php
	}

	/**
	 * Description - method to get the attribute value from the database.
	 *
	 * @param int $post_id - the post id.
	 *
	 * @return string
	 */
	public function get_value( int $post_id ): string {
		return get_post_meta( $post_id, 'event-end-date', true );
	}

	/**
	 * Description - method to update the database from the submitted form.
	 *
	 * @param int $post_id - the post id.
	 */
	public function update_value( int $post_id ) : void {

		if ( isset( $_POST['rep-event-end-date'] ) ) { //phpcs:ignore
			update_post_meta( $post_id, 'event-end-date', strtotime( sanitize_text_field( wp_unslash( $_POST['rep-event-end-date'] ) ) ) );//phpcs:ignore
		}
	}

	/**
	 * Description - method to render the field about the attribute in the event page (single).
	 *
	 * @param int $post_id - the post id.
	 */
	public function render_single_field( int $post_id ) : void {
		$end_date = $this->get_value( $post_id );

		if ( ! empty( $end_date ) ) {
			?>
			<h3>End date: <?php echo esc_html( gmdate( 'd/m/y', (int) $end_date ) ); ?></h3>
			<?php
		}
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
