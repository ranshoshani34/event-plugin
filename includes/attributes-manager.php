<?php

require('attributes-classes/event-attribute.php');
require('attributes-classes/start-date.php');
require('attributes-classes/end-date.php');
require('attributes-classes/location.php');
require('attributes-classes/details.php');
require('attributes-classes/weekly.php');
require('attributes-classes/users.php');

class Attributes_Manager {
    public $attributes_array = array();

    public function __construct() {

        $this->attributes_array[] = new Start_Date();
        $this->attributes_array[] = new End_Date();
        $this->attributes_array[] = new Location();
        $this->attributes_array[] = new Details();
        $this->attributes_array[] = new Weekly();
        $this->attributes_array[] = new Users();
    }
}