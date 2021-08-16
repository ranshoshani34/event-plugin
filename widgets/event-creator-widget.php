<?php
/**
 * File for class Event_Creator_Widget.
 *
 * @package event-plugin.
 */

/**
 * The widget class for the event form widget.
 */
class Event_Creator_Widget extends Elementor\Widget_Base {

	/**
	 * Method to get the widget name.
	 *
	 * @return string
	 */
	public function get_name() :string {
		return 'Event Form';
	}

	/**
	 * Method to get the widgets title.
	 *
	 * @return string
	 */
	public function get_title() : string {
		return 'Event Form';
	}

	/**
	 *  Method to get the widget icon.
	 *
	 * @return string
	 */
	public function get_icon() : string {
		return 'far fa-calendar-plus';
	}

	/**
	 * Method that renders the actual frontend representation of the widget.
	 */
	protected function render() {
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/event-type-creator.php';

		$event_type_creator = Event_Type_Creator::instance();

		$event_type_creator->echo_form_html(); //phpcs:ignore
	}

}
