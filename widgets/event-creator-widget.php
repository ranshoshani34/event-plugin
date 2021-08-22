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

		$processing_path = WP_PLUGIN_DIR . '/event-plugin/widgets/process-form.php';

		// generate a nonce field.
		$nonce = wp_create_nonce( 'rep_event_nonce' );

		$event_type_creator = Event_Type_Creator::instance();
		?>
			<form action="" method="post" class="js_create_event_form" nonce="<?php echo $nonce; //phpcs:ignore?>">
				<label
						for="rep-title"><?php esc_html_e( 'Event Title:', 'rep' ); ?>
				</label>
				<input
						class="widefat"
						id="rep-title"
						type="text"
						name="rep-title"
						placeholder="eg. Times Square"
				/><br><br>
		<?php

		$event_type_creator->echo_form_html(); //phpcs:ignore
		?>
			<br>
			<input type="submit" value="Submit">
			</form>
		<h3 id="rep_success_header"></h3>
		<?php
	}

}
