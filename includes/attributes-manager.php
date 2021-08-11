<?php
/**
 * Class file for the attributes manager.
 *
 * @package event-plugin.
 */
require 'attributes-classes/event-attribute.php';
require 'attributes-classes/start-date.php';
require 'attributes-classes/end-date.php';
require 'attributes-classes/location.php';
require 'attributes-classes/details.php';
require 'attributes-classes/weekly.php';
require 'attributes-classes/users.php';

/**
 * Class Attributes_Manager to manage the different event attributes.
 */
class Attributes_Manager {

	/**
	 * Array for the attributes instances.
	 *
	 * @var array
	 */
	public $attributes_array = array();

	/**
	 *  Constructor to add all the attributes instances to the attributes array.
	 */
	public function __construct() {

		$this->attributes_array[] = new Start_Date();
		$this->attributes_array[] = new End_Date();
		$this->attributes_array[] = new Location();
		$this->attributes_array[] = new Details();
		$this->attributes_array[] = new Weekly();
		$this->attributes_array[] = new Users();
	}
}
