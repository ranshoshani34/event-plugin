<?php
/**
 * Template Name: Calendar
 *
 * This is a template file for the calendar page.
 *
 * @package event-plugin.
 */

require WP_PLUGIN_DIR . '/event-plugin/includes/calendar-creator.php';

get_header();

echo Calendar_Creator::generate_calendar_header_html(); //phpcs:ignore
echo Calendar_Creator::generate_calendar_html(); //phpcs:ignore

get_footer();

