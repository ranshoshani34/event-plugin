<?php
/**
 * Class Event_Form_Action
 *
 * @package event-plugin
 */

use ElementorPro\Modules\Forms\Classes\Ajax_Handler;
use ElementorPro\Modules\Forms\Classes\Form_Record;
use ElementorPro\Modules\Forms\Widgets\Form;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * A class for a new Create Event action in the elementor pro Form widget.
 */
class Event_Form_Action extends ElementorPro\Modules\Forms\Classes\Action_Base {

	/**
	 * Return the name of the action.
	 *
	 * @return string
	 */
	public function get_name() : string {
		return 'Create Event';
	}

	/**
	 * Return the label of the action.
	 *
	 * @return string
	 */
	public function get_label() : string {
		return __( 'Create Event', 'event-plugin' );

	}

	/**
	 * The action itself that needs to happen when the form is submitted.
	 *
	 * @param Form_Record  $record record of the input.
	 * @param Ajax_Handler $ajax_handler ajax handler.
	 */
	public function run( $record, $ajax_handler ) {
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/event-type-creator.php';

		$event_type_creator = Event_Type_Creator::instance();

		$raw_fields = $record->get( 'fields' );
		$fields     = [];

		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		$fields = $this->prepare_fields( $fields );
		if ( isset( $fields['event_plugin_title'] ) ) {
			$post_id = Form_Processor::create_event_instance( sanitize_text_field( wp_unslash( $fields['event_plugin_title'] ) ) );
		} else {
			$post_id = Form_Processor::create_event_instance( sanitize_text_field( wp_unslash( '' ) ) );
		}

		$event_type_creator->save_event_data( $post_id, $fields );
	}

	/**
	 * Register settings. not used.
	 *
	 * @param Form $form form.
	 */
	public function register_settings_section( $form ) {
	}

	/**
	 * On export. not used.
	 *
	 * @param array $element element.
	 */
	public function on_export( $element ) {
	}

	/**
	 * Do any special preparation for the fields to pass to the event type creator.
	 *
	 * @param array $fields array of data (field => value).
	 *
	 * @return array
	 */
	private function prepare_fields( array $fields ) : array {
		$fields['event_plugin_users'] = explode( ',', $fields['event_plugin_users'] );

		return $fields;
	}
}
