<?php

class User_Form_Field extends ElementorPro\Modules\Forms\Fields\Field_Base {
	public function get_type() : string {
		return 'users';
	}

	public function get_name() : string {
		return __('Users', 'event-plugin');
	}

	public function render( $item, $item_index, $form ) {
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/attributes-classes/users.php';

		$users_attribute = new Users();

		$users_attribute->echo_dynamic_users_elementor_form();
	}
}
