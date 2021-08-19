<?php
/**
 * Class file for the attributes manager.
 *
 * @package event-plugin.
 */
require_once 'attributes-classes/custom-post-attribute.php';
require_once 'attributes-classes/start-date.php';
require_once 'attributes-classes/end-date.php';
require_once 'attributes-classes/location.php';
require_once 'attributes-classes/details.php';
require_once 'attributes-classes/weekly.php';
require_once 'attributes-classes/users.php';

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
	 * Singleton instance.
	 *
	 * @var Attributes_Manager
	 */
	private static $instance;

	/**
	 *  Method to add all the attributes instances to the attributes array.
	 */
	public function register_attributes() {

		$this->register_new_attribute( new Start_Date() );
		$this->register_new_attribute( new End_Date() );
		$this->register_new_attribute( new Location() );
		$this->register_new_attribute( new Details() );
		$this->register_new_attribute( new Weekly() );
		$this->register_new_attribute( new Users() );
	}

	/**
	 * Method to add a new attribute dynamically.
	 *
	 * @param Custom_Post_Attribute $attribute the attribute to add.
	 */
	public function register_new_attribute( Custom_Post_Attribute $attribute ) {

		$this->attributes_array[] = $attribute;
	}

	/**
	 * Instance method to get the singleton.
	 *
	 * @return Attributes_Manager
	 */
	public static function instance() : Attributes_Manager {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

}
