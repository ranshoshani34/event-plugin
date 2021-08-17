<?php
/**
 * Class file start date attribute.
 *
 * @package event-plugin.
 */

/**
 * Class Start_Date.
 */
class Start_Date extends Custom_Post_Attribute {

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id -  (optional) the id of the post to retrieve old data from (if specified).
	 */
	public function render_metabox( int $post_id = 0 ) : void {

		if ( 0 !== $post_id ) {
			$event_start_date = $this->get_value( $post_id );
		}
		$event_start_date = ! empty( $event_start_date ) ? $event_start_date : time();

		?>
		<label for="rep-event-start-date">
			<?php esc_html_e( 'Event Start Date:', 'rep' ); ?>
		</label>
		<input
			class="widefat rep-event-date-input"
			id="rep-event-start-date"
			type="date"
			name="rep-event-start-date"
			value="<?php echo esc_html( gmdate( 'Y-m-d', $event_start_date ) ); ?>"
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
		return get_post_meta( $post_id, 'event-start-date', true );
	}

	/**
	 * Description - method to update the database from the submitted form.
	 *
	 * @param int $post_id - the post id.
	 */
	public function update_value( int $post_id ) : void {

		if ( isset( $_POST['rep-event-start-date'] ) ) { //phpcs:ignore
			update_post_meta(
				$post_id,
				'event-start-date',
				strtotime(
					sanitize_text_field( wp_unslash( $_POST['rep-event-start-date'] ) ) //phpcs:ignore
				)
			);
		}
	}

	/**
	 * Description - method to render the field about the attribute in the event page (single).
	 *
	 * @param int $post_id - the post id.
	 */
	public function render_single_field( int $post_id ) : void {
		$start_date = $this->get_value( $post_id );
		?>
		<h3>Start date:  <?php echo esc_html( gmdate( 'd/m/y', (int) $start_date ) ); ?></h3>
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
