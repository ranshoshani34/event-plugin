<?php
/**
 * Class file for end date attribute.
 *
 * @package event-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class End_Date.
 */
class End_Date extends Custom_Post_Attribute {

	/**
	 * The id and name used for the html input and label.
	 *
	 * @var string
	 */
	private $id = 'event_plugin_end_date';

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
		?>
		<label for="<?php echo $this->id; ?>"><?php esc_html_e( 'Event End Date:', 'event-plugin' ); //phpcs:ignore?></label>
		<input class="widefat <?php echo $this->id; //phpcs:ignore?>" id="<?php echo $this->id; ?>" type="date" name="<?php echo $this->id; ?>" placeholder="Format: February 18, 2014" value="<?php echo esc_html( gmdate( 'Y-m-d', $event_end_date ) ); ?>">
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
		return get_post_meta( $post_id, $this->id, true );
	}

	/**
	 * Method to update the database with the given values.
	 *
	 * @param int   $post_id the post id.
	 * @param array $values array of values to add to the database.
	 */
	public function update_value( int $post_id, array $values ) : void {
		update_post_meta( $post_id, $this->id, strtotime($values[0]));//phpcs:ignore
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
	 * Method to save the data in the post meta.
	 *
	 * @param int   $post_id the post id.
	 * @param array $data array of attribute id => value.
	 */
	public function save_data( int $post_id, array $data ) {
		if ( isset( $data[ $this->id ] ) ) {
			$this->update_value( $post_id, [ sanitize_text_field( wp_unslash( $data[ $this->id ] ) ) ] );
		}
	}
}
