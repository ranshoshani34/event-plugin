<?php
/**
 * Class file for event creator class.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'event-data.php';

/**
 * Class Calendar_Creator for drawing the calendar with html.
 */
class Calendar_Creator {

	/**
	 * Get an array of Event_Data representing the current events
	 *
	 * @return array
	 */
	public static function get_events() : array {
		$events = [];

		$query = new WP_Query( [ 'post_type' => 'event' ] );

		while ( $query->have_posts() ) {
			$query->the_post();
			$event_start_date = get_post_meta( get_the_ID(), 'event_plugin_start_date', true );
			$event_is_weekly = get_post_meta( get_the_ID(), 'event_plugin_weakly', true );
			$events[] = new Event_Data( (int) $event_start_date, get_the_permalink(), get_the_title(), $event_is_weekly );
		}

		return $events;
	}

	/**
	 * Method to draw the calendar as html.
	 *
	 * @param int $month - The month to draw (1 - 12).
	 * @param int $year - The year to draw (four digits).
	 *
	 * @return string
	 */
	public static function draw_calendar( int $month, int $year ): string {

		$events = self::get_events();

		$calendar = '<table class="calendar">';

		$headings  = [ 'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday' ];
		$calendar .= '<tr class="calendar-row"><td class="calendar-day-head">' . implode( '</td><td class="calendar-day-head">', $headings ) . '</td></tr>';

		$running_day       = gmdate( 'w', mktime( 0, 0, 0, $month, 1, $year ) );
		$num_days_in_month = gmdate( 't', mktime( 0, 0, 0, $month, 1, $year ) );
		$days_in_this_week = 1;
		$day_counter       = 0;

		/* row for week one */
		$calendar .= '<tr class="calendar-row">';

		/* print "blank" days until the first of the current week */
		for ( $x = 0; $x < $running_day; $x ++ ) {
			$calendar .= '<td class="calendar-day-np"> </td>';
			$days_in_this_week++;
		}

		/* keep going with days.... */
		for ( $list_day = 1; $list_day <= $num_days_in_month; $list_day++ ) {
			$current_date   = mktime( 0, 0, 0, $month, $list_day, $year );
			$calendar      .= '<td class="calendar-day">';
			$calendar      .= '<div class="day-number">' . $list_day . '</div>';
			$filtered_array = array_filter(
				$events,
				function ( $event ) use ( $current_date ) {
					if ( $event->is_weekly() ) {
						return gmdate( 'w', $event->get_date() ) === gmdate( 'w', $current_date ) && $event->get_date() <= $current_date;
					}

					return $event->get_date() === $current_date;
				}
			);
			foreach ( $filtered_array as $event ) {
				$calendar .= $event->generate_html_link() . '<br>';
			}

			$calendar .= str_repeat( '<p> </p>', 2 );

			$calendar .= '</td>';
			if ( 6 === (int) $running_day ) {
				$calendar .= '</tr>';
				if ( ( $day_counter + 1 ) !== $num_days_in_month ) {
					$calendar .= '<tr class="calendar-row">';
				}

				$running_day       = - 1;
				$days_in_this_week = 0;
			}
			$days_in_this_week++;
			$running_day++;
			$day_counter++;
		}

		/* fill in the rest of the days in the week */
		if ( $days_in_this_week < 8 ) {
			for ( $x = 1; $x <= ( 8 - $days_in_this_week ); $x ++ ) {
				$calendar .= '<td class="calendar-day-np"> </td>';
			}
		}

		$calendar .= '</tr>';
		$calendar .= '</table>';

		return $calendar;
	}

	/**
	 * Method to generate the necessary html to render the calendar.
	 *
	 * @param int $date timestamp of the date to display.
	 *
	 * @return string
	 */
	public static function generate_calendar_html( int $date ) : string {
		return self::generate_month_picker( $date ) . self::generate_calendar_header_html( $date ) . '<br><br><div id="event_plugin_calendar">' . self::draw_calendar( gmdate( 'm', $date ), gmdate( 'Y', $date ) ) . '</div>';
	}

	/**
	 * Method to respond to an ajax request with the requested calendar month.
	 */
	public static function respond_with_calendar() {

		if ( ! isset( $_POST['event_plugin_month'] ) ) { //phpcs:ignore
			return;
		}

		$new_date  = explode( '-', sanitize_text_field( wp_unslash( $_POST['event_plugin_month'] ) ) ); //phpcs:ignore
		$new_month = (int) $new_date[1];
		$new_year  = (int) $new_date[0];

		$result['type']     = 'success';
		$result['calendar'] = self::draw_calendar( $new_month, $new_year );

		if ( ! empty( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) && strtolower( sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_REQUESTED_WITH'] ) ) ) === 'xmlhttprequest' ) {
			$result = wp_json_encode( $result );
			echo $result; //phpcs:ignore
		} elseif ( isset( $_SERVER['HTTP_REFERER'] ) ) {
			header( 'Location: ' . sanitize_text_field( wp_unslash( $_SERVER['HTTP_REFERER'] ) ) );
		}
		die();
	}

	/**
	 * Method to render a header with month and year information.
	 *
	 * @param int $date the date to render.
	 *
	 * @return string
	 */
	public static function generate_calendar_header_html( int $date ) : string {
		return '<h2>' . gmdate( 'F', $date ) . ' ' . gmdate( 'Y', $date ) . '</>';
	}

	/**
	 * Method to return the html string for the month picker that changes the month in the calendar dynamically.
	 *
	 * @param int $date the date to render.
	 *
	 * @return string the html for the month picker.
	 */
	private static function generate_month_picker( int $date ) : string {
		$month        = gmdate( 'm', $date );
		$year         = gmdate( 'Y', $date );
		$current_time = $year . '-' . $month;
		return '<form class="event_plugin_month_picker"><input type="month" id="event_plugin_month" name="event_plugin_month" value="' . $current_time . '"><input type="submit" value="Change month"></form>';
	}

}

