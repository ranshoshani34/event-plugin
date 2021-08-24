<?php //phpcs:ignore
/**
 * Plugin Name: Events plugin.
 * Description: Plugin to add events post type and a template do display the events in a calendar view. available as an elementor widget as well.
 * Version:     1.0.0
 * Author:      Ran Shoshani
 * Text Domain: elementor-event-widget.
 *
 * @package event-plugin.
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'EVENT_PLUGIN_ROOT', plugins_url( '', __FILE__ ) );


require_once 'includes/event-type-creator.php';
require_once 'includes/template-manager.php';
require_once 'includes/event-plugin.php';

Event_Plugin::instance();

