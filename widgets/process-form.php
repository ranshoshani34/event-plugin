<?php

require WP_PLUGIN_DIR . '/event-plugin/includes/event-type-creator.php';

function process_form(){
	$event_type_creator = Event_Type_Creator::instance();

	$event_type_creator->save_event_info();
}