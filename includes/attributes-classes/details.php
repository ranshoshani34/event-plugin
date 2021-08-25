<?php /** @noinspection PhpCSValidationInspection */
/**
 * Class file for details attribute.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Detail
 */
class Details extends Custom_Post_Attribute {
	/**
	 * The id and name used for the html input and label.
	 *
	 * @var string
	 */
	private $id = 'event_plugin_details';


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
		<label for="<?php echo $this->id; ?>"><?php esc_html_e( 'Event Details:', 'event-plugin' ); ?>
		</label>
		<textarea class="widefat" id="<?php echo $this->id; ?>" name="<?php echo $this->id ; ?>"><?php echo esc_html( $event_details ); ?></textarea>
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
	 * Method to update the database with the given values.
	 *
	 * @param int $post_id the post id.
	 * @param array $values array of values to add to the database.
	 */
	public function update_value( int $post_id , array $values) : void {
		update_post_meta( $post_id, 'event-details', $values[0]);//phpcs:ignore
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
		<p><?php echo esc_html( $event_details ); ?> </p>
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
