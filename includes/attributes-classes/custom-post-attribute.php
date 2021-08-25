<?php
/**
 * Class file for abstract class Costume_Post_Attribute.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Custom_Post_Attribute.
 * Description - abstract class for a custom post attribute
 * (information that is relevant to the post and can be filled out in a form or metabox).
 */
abstract class Custom_Post_Attribute {

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id -  (optional) the id of the post to retrieve old data from (if specified).
	 */
	abstract public function render_metabox( int $post_id);

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
	 * @param int   $post_id - the post id.
	 * @param array $values - values to update.
	 */
	abstract public function update_value( int $post_id, array $values);

	/**
	 * Description - method to render the field about the attribute in the event page (single).
	 *
	 * @param int $post_id - the post id.
	 */
	abstract public function render_single_field( int $post_id ) : void;

	/**
	 * Method to save the data in the post meta.
	 *
	 * @param int   $post_id the post id.
	 * @param array $data array of attribute id => value.
	 */
	abstract public function save_data( int $post_id, array $data);
}
