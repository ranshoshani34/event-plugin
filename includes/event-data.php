<?php
/**
 * Class file for Event_Data
 *
 * @package event-plugin.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * Class Event_Data, contains all the necessary data
 * and a method to generate a link to the event page in html.
 */
class Event_Data {
	/**
	 * The start date of the event.
	 *
	 * @var int
	 */
	public $date;
	/**
	 * The link for the event page.
	 *
	 * @var string
	 */
	public $permalink;
	/**
	 * The title of the event.
	 *
	 * @var string
	 */
	public $title;
	/**
	 * Is this a weekly (recurring) event.
	 *
	 * @var bool
	 */
	public $is_weekly;

	/**
	 * Constructor to receive the necessary information.
	 *
	 * @param int    $date - the event date.
	 * @param string $permalink - the event permalink.
	 * @param string $title - the event title.
	 * @param bool   $is_weekly - if this event is a weekly event.
	 */
	public function __construct( int $date, string $permalink, string $title, bool $is_weekly = false ) {
		$this->date      = $date;
		$this->permalink = $permalink;
		$this->title     = $title;
		$this->is_weekly = $is_weekly;
	}

	/**
	 * Getter for the date.
	 *
	 * @return int
	 */
	public function get_date() : int {
		return $this->date;
	}

	/**
	 * Method to generate the link for the event as html.
	 *
	 * @return string
	 */
	public function generate_html_link() : string {
		return "<a href='$this->permalink'>$this->title</a>";
	}

	/**
	 * Getter for is weekly.
	 *
	 * @return bool
	 */
	public function is_weekly() : bool {
		return $this->is_weekly;
	}
}
