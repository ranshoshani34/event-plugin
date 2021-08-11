<?php
/**
 * Class file for abstract class Event_Attribute.
 *
 * @package event-plugin.
 */

/**
 * Class Event_Attribute.
 * Description - abstract class for an event attribute (start date, location, etc.).
 */
abstract class Event_Attribute {

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id - the id of the post to render.
	 */
	abstract public function render_metabox( int $post_id ) : void;

	/**
	 * Description - method to get the attribute value from the database.
	 *
	 * @param int $post_id - the post id.
	 *
	 * @return string
	 */
	abstract public function get_value( int $post_id ) : string;

	/**
	 * Description - method to update the database from the submitted form.
	 *
	 * @param int $post_id - the post id.
	 */
	abstract public function update_value( int $post_id ) : void;

	/**
	 * Description - method to render the field about the attribute in the event page (single).
	 *
	 * @param int $post_id - the post id.
	 */
	abstract public function render_single_field( int $post_id ) : void;

	/**
	 * Method to check if nonce is valid.
	 *
	 * @return bool
	 */


}
