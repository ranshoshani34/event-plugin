<?php

/**
 * Plugin Name: Event manager
 *
 * Description: Plugin to create events through WordPress dashboard.
 *
 * Version: 1.0
 *
 * Author: Ran Shoshani.
 *
 * Text Domain: rep .
 */

require 'includes/event-type-creator.php';
require 'includes/template-manager.php';

define( 'ROOT', plugins_url( '', __FILE__ ) );
const IMAGES = ROOT . '/img/';
const STYLES = ROOT . '/css/';

wp_enqueue_style( 'style.css', STYLES . 'style.css', array(), '1' );

$event_creator    = new Event_Type_Creator();
$template_manager = new Template_Manager();

$event_creator->initialize();
$template_manager->initialize();


