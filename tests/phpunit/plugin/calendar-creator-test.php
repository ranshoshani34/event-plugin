<?php


class Calendar_Creator_Test extends WP_UnitTestCase {

	public $event_creator;

	public function setUp() {
		parent::setUp();
		$this->event_creator = Event_Type_Creator::instance();
	}

	public static function setUpBeforeClass(){
		parent::setUpBeforeClass();
	}

	public function test_get_events(){
		$expected = [
		'2020-12-20' => true,
		'2020-06-21' => true,
		'2020-11-11' => false,
		'2020-10-04' => false,
		'2020-11-08' => true,
		'2020-04-20' => true,
		'2020-01-21' => false,
		];

		foreach ($expected as $date => $is_weakly){
			$this->create_event($date, $is_weakly);
		}

		$actual = Calendar_Creator::get_events();

		self::assertSame(count($expected), count($actual));

		foreach ($actual as $event){
			self::assertTrue($expected);
			//todo
		}
	}

	private function create_event(string $date, bool $is_weekly) {
		$post_id = Event_Type_Creator::create_event_instance('');
		$data_array = [
			'event_plugin_start_date' => $date
		];

		if ($is_weekly){
			$data_array['event_plugin_weakly'] = '1';
		}

		$this->event_creator->save_event_data($post_id, $data_array);
	}

	private function is_all_fields_saved() : bool {

		$post_id = Event_Type_Creator::create_event_instance('');
		$data_array = [
			'event_plugin_start_date' => '2012-06-30' ,
			'event_plugin_end_date' => '2022-06-30' ,
			'event_plugin_location' => 'localocation' ,
			'event_plugin_details' => 'this is the details' ,
			'event_plugin_weakly' => '1' ,
		];

		$this->event_creator->save_event_data($post_id, $data_array);

		$bool_array = [];

		$key = 'event_plugin_start_date';
		$bool_array[] = gmdate( 'Y-m-d',get_post_meta($post_id, $key, true)) === $data_array[$key];
		$key = 'event_plugin_end_date';
		$bool_array[] = gmdate( 'Y-m-d',get_post_meta($post_id, $key, true)) === $data_array[$key];
		$key = 'event_plugin_location';
		$bool_array[] = get_post_meta($post_id, $key, true) === $data_array[$key];
		$key = 'event_plugin_details';
		$bool_array[] = get_post_meta($post_id, $key, true) === $data_array[$key];
		$key = 'event_plugin_weakly';
		$bool_array[] = get_post_meta($post_id, $key, true) === $data_array[$key];

		return ! in_array(false , $bool_array, true);

	}

	private function is_single_field_saved(string $key, string $value) : bool {
		$post_id = Event_Type_Creator::create_event_instance('');
		$data_array = [$key => $value];
		$this->event_creator->save_event_data($post_id, $data_array);
		$retrieved_data = get_post_meta($post_id, $key, true);

		return $retrieved_data === $value;

	}

	private function is_single_field_saved_dates(string $key, string $value) : bool {
		$post_id = Event_Type_Creator::create_event_instance('');
		$data_array = [$key => $value];
		$this->event_creator->save_event_data($post_id, $data_array);
		$retrieved_data = get_post_meta($post_id, $key, true);

		return gmdate( 'Y-m-d',$retrieved_data) === $value;

	}

	private function is_not_set(string $key) : bool{
		$post_id = Event_Type_Creator::create_event_instance('');
		$data_array = [];
		$this->event_creator->save_event_data($post_id, $data_array);
		$retrieved_data = get_post_meta($post_id, $key, true);

		return '' === $retrieved_data;
	}
}
