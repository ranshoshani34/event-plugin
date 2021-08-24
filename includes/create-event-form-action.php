<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


class Event_Form_Action extends ElementorPro\Modules\Forms\Classes\Action_Base {

	public function get_name() : string {
		return 'Create Event';
	}

	public function get_label() : string {
		return __('Create Event', 'event-plugin');

	}

	public function run( $record, $ajax_handler ) {
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/event-type-creator.php';

		$event_type_creator = Event_Type_Creator::instance();

		$raw_fields = $record->get( 'fields' );
		$fields = [];
		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		$fields = $this->prepare_fields( $fields);
		if ( isset( $fields['event_plugin_title'] ) ) {
			$post_id = Form_Processor::create_event_instance( sanitize_text_field( wp_unslash( $fields['event_plugin_title'] ) ) );
		} else {
			$post_id = Form_Processor::create_event_instance( sanitize_text_field( wp_unslash( '' ) ) );
		}

		$event_type_creator->save_event_data($post_id, $fields);
	}

	public function register_settings_section( $form ) {
		// TODO: Implement register_settings_section() method.
	}

	public function on_export( $element ) {
		// TODO: Implement on_export() method.
	}

	private function prepare_fields( array $fields ) : array {
		$fields['event_plugin_users'] = explode(',', $fields['event_plugin_users']);

		return $fields;
	}
}