<?php
/**
 * Class file for weekly attribute (is this a weekly event).
 *
 * @package event-plugin.
 */

/**
 * Class Weekly.
 */
class Weekly extends Custom_Post_Attribute {
	private $id = 'event_plugin_weakly';
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
		<input type="checkbox" id="<?php echo $this->id; ?>" name="<?php echo $this->id; ?>"
			<?php
			if ( $is_checked ) {
				echo 'checked';
			}
			?>
		>
		<label for="<?php echo $this->id; ?>">Weekly event</label>
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
	 * Method to update the database with the given values.
	 *
	 * @param int $post_id the post id.
	 * @param array $values array of values to add to the database.
	 */
	public function update_value( int $post_id , array $values) : void {
		update_post_meta( $post_id, 'event-weekly', $values[0] );
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

	/**
	 * Method to save the data in the post meta.
	 *
	 * @param int $post_id the post id.
	 * @param array $data array of attribute id => value.
	 */
	public function save_data( int $post_id, array $data) {
		if ( isset( $data[$this->id] ) ) {
			$this->update_value( $post_id , [ isset($data[$this->id])] );
		}
	}

}
