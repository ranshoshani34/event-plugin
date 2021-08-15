<?php //phpcs:ignore
/**
 * File for class Calendar_Widget.
 *
 * @package elementor-event-widget
 */


/**
 * The widget class for the calendar widget.
 */
class Calendar_Widget extends Elementor\Widget_Base {


	/**
	 * Method to get the widget name.
	 *
	 * @return string
	 */
	public function get_name() :string {
		return 'Calendar';
	}

	/**
	 * Method to get the widgets title.
	 *
	 * @return string
	 */
	public function get_title() : string {
		return 'Calendar';
	}

	/**
	 *  Method to get the widget icon.
	 *
	 * @return string
	 */
	public function get_icon() : string {
		return 'fas fa-calendar-alt';
	}

	/**
	 * Method that renders the actual frontend representation of the widget.
	 */
	protected function render() {
		require_once WP_PLUGIN_DIR . '/event-plugin/includes/calendar-creator.php';

		echo Calendar_Creator::generate_calendar_html(); //phpcs:ignore
	}

}
