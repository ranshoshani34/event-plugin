<?php
/**
 * Template Name: Calendar
 *
 * This is a template file for the calendar page.
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once WP_PLUGIN_DIR . '/event-plugin/includes/calendar-creator.php';

get_header();
the_content();

echo Calendar_Creator::generate_calendar_header_html(); //phpcs:ignore
echo Calendar_Creator::generate_calendar_html(); //phpcs:ignore

get_footer();

