<?php //phpcs:ignore
/**
 * File for class Calendar_Widget.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


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
		return __( 'Calendar', 'rep' );
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

		$settings = $this->get_settings_for_display();
		if ('yes' === $settings['current_month']){
			$default_date = time();
		} else {
			$default_date = strtotime( $settings[ 'default_month' ] );
		}

		echo Calendar_Creator::generate_calendar_html($default_date); //phpcs:ignore
	}

	protected function _register_controls() {

		$this->start_controls_section(
			'default_section',
			[
				'label' => __( 'Default', 'event-plugin' ),
			]
		);

		$this->add_control(
			'default_month',
			[
				'label' => __( 'Default Month', 'event-plugin' ),
				'type'  => Elementor\Controls_Manager::DATE_TIME,
				'description'  => 'Pick any day of the month you want to be the default month',
				'picker_options'  => [
					'enableTime' => 'false',
					'monthSelectorType' => 'dropdown',
				],
			]
		);

		$this->add_control(
			'current_month',
			[
				'label' => __( 'Default month as current month?', 'event-plugin' ),
				'type'  => Elementor\Controls_Manager::SWITCHER,
				'description'  => 'If this is on, the default month is irrelevant and will always display the current month as the default ',
			]
		);

		$this->end_controls_section();

	}

}
