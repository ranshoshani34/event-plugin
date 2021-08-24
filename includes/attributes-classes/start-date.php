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
	private $id = 'event_plugin_start_date';


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
		<label for="<?php echo $this->id; //phpcs:ignore ?>">
			<?php esc_html_e( 'Event Start Date:', 'event-plugin' ); ?>
		</label>
		<input
			class="widefat"
			id="<?php echo $this->id; //phpcs:ignore?>"
			type="date"
			name="<?php echo $this->id; //phpcs:ignore?>"
			value="<?php echo esc_html( gmdate( 'Y-m-d', $event_start_date ) ); ?>"
		>
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
	 * Method to update the database with the given values.
	 *
	 * @param int $post_id the post id.
	 * @param array $values array of values to add to the database.
	 */
	public function update_value( int $post_id , array $values) : void {
		update_post_meta( $post_id, 'event-start-date', strtotime( $values[0] )); //phpcs:ignore
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
	 * Method to save the data in the post meta.
	 *
	 * @param int $post_id the post id.
	 * @param array $data array of attribute id => value.
	 */
	public function save_data( int $post_id, array $data ) {
		if ( isset( $data[$this->id] ) ) {
			$this->update_value( $post_id , [ sanitize_text_field( wp_unslash($data[$this->id]))] );
		}
	}
}
