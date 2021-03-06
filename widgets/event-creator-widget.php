<?php
/**
 * File for class Event_Creator_Widget.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

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
		return __( 'Event Form', 'event-plugin' );
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

		// generate a nonce field.
		$nonce = wp_create_nonce( 'event_plugin_event_nonce' );

		$title_id           = 'event_plugin_title';
		$event_type_creator = Event_Type_Creator::instance();
		?>
			<form action="" method="post" class="js_create_event_form" nonce="<?php echo $nonce; //phpcs:ignore?>">
				<label
						for="<?php echo $title_id; //phpcs:ignore ?>"><?php esc_html_e( 'Event Title:', 'event-plugin' ); ?>
				</label>
				<input
						class="widefat"
						id="<?php echo $title_id; //phpcs:ignore ?>"
						type="text"
						name="<?php echo $title_id;  //phpcs:ignore ?>"
				/><br><br>
		<?php

		$event_type_creator->echo_form_html();
		?>
			<br>
			<input type="submit" value="Submit">
			</form>
		<h3 id="event_plugin_success_header"></h3>
		<?php
	}

}
