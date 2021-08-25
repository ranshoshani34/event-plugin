<?php
/**
 * Class User_Form_Field
 *
 * @package event-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class for a new form field that creates users checkbox dynamically.
 */
class User_Form_Field extends ElementorPro\Modules\Forms\Fields\Field_Base {

	/**
	 * Get type for field.
	 *
	 * @return string
	 */
	public function get_type() : string {
		return 'users';
	}

	/**
	 * Get name for field.
	 *
	 * @return string
	 */
	public function get_name() : string {
		return __( 'Users', 'event-plugin' );
	}

	/**
	 * Renders the field in the form.
	 *
	 * @param array                                   $item item.
	 * @param int                                     $item_index item index.
	 * @param ElementorPro\Modules\Forms\Widgets\Form $form the form.
	 */
	public function render( $item, $item_index, $form ) {
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/attributes-classes/users.php';

		$users_attribute = new Users();

		$users_attribute->echo_dynamic_users_elementor_form();
	}
}
