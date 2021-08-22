<?php
/**
 * Class file for abstract class Costume_Post_Attribute.
 *
 * @package event-plugin.
 */

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
	 * @param int $post_id - the post id.
	 */
	abstract public function update_value( int $post_id , array $values);

	/**
	 * Description - method to render the field about the attribute in the event page (single).
	 *
	 * @param int $post_id - the post id.
	 */
	abstract public function render_single_field( int $post_id ) : void;

	/**
	 * Method that does any action that should happen after a post is saved.
	 *
	 * @param int $post_id id of the post.
	 */
	abstract public function after_save_post( int $post_id);

	abstract public function after_elementor_form_submit(int $post_id, array $fields);


}
