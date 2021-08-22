<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Event_Form_Action extends ElementorPro\Modules\Forms\Classes\Action_Base {

	public function get_name() : string {
		return 'Create Event';
	}

	public function get_label() : string {
		return __('Create Event', 'rep');

	}

	public function run( $record, $ajax_handler ) {
		require_once(WP_PLUGIN_DIR . '/event-plugin/includes/event-type-creator.php');


	}

	public function register_settings_section( $form ) {
		// TODO: Implement register_settings_section() method.
	}

	public function on_export( $element ) {
		// TODO: Implement on_export() method.
	}
}