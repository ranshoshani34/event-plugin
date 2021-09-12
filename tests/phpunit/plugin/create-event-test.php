<?php


class Create_Event_Test extends WP_UnitTestCase {

	public $event_creator;

	public function setUp() {
		parent::setUp();
		$this->event_creator = Event_Type_Creator::instance();
		wp_create_user('one', 'one');
		wp_create_user('two', 'two');
	}

	public static function setUpBeforeClass(){
		parent::setUpBeforeClass();
		wp_create_user('one', 'one');
		wp_create_user('two', 'two');
	}

	public function test_post_type_exists(){
		self::assertTrue(post_type_exists('event'));
	}

	public function test_save_start_date(){
		self::assertTrue($this->is_single_field_saved_dates('event_plugin_start_date','2023-06-18'), 'first');
		self::assertTrue($this->is_single_field_saved_dates('event_plugin_start_date','2018-12-18'), 'second');
		self::assertTrue($this->is_single_field_saved_dates('event_plugin_start_date','2023-08-01'), 'third');
		self::assertTrue($this->is_not_set('event_plugin_start_date'), 'not set');
	}

	public function test_save_end_date(){
		self::assertTrue($this->is_single_field_saved_dates('event_plugin_end_date','2023-06-18'), 'first');
		self::assertTrue($this->is_single_field_saved_dates('event_plugin_end_date','2018-12-18'), 'second');
		self::assertTrue($this->is_single_field_saved_dates('event_plugin_end_date','2023-08-01'), 'third');
		self::assertTrue($this->is_not_set('event_plugin_end_date'), 'not set');
	}

	public function test_save_location(){
		self::assertTrue($this->is_single_field_saved('event_plugin_location','Tel aviv'), 'first');
		self::assertTrue($this->is_single_field_saved('event_plugin_location','Los angeles'), 'second');
		self::assertTrue($this->is_single_field_saved('event_plugin_location','Something else'), 'third');
		self::assertTrue($this->is_not_set('event_plugin_location'), 'not set');
	}

	public function test_save_details(){
		self::assertTrue($this->is_single_field_saved('event_plugin_details','this is the event details'), 'first');
		self::assertTrue($this->is_single_field_saved('event_plugin_details','some more event details'), 'second');
		self::assertTrue($this->is_not_set('event_plugin_details'), 'not set');
	}

	public function test_save_weakly(){
		self::assertTrue($this->is_single_field_saved('event_plugin_weakly','1'), 'first');
		self::assertTrue($this->is_not_set('event_plugin_weakly'), 'not set');
	}

	public function test_all_saved(){
		self::assertTrue($this->is_all_fields_saved());
	}

	public function test_is_valid_event(){
		$post_id = Event_Type_Creator::create_event_instance('');

		self::assertFalse($this->event_creator->is_valid_event($post_id), 'first');

		$_POST['post_type'] = 'event';
		self::assertFalse($this->event_creator->is_valid_event($post_id), 'second');

		$_POST['event_plugin_nonce'] = wp_create_nonce(basename( EVENT_PLUGIN_ROOT ));
		self::assertTrue($this->event_creator->is_valid_event($post_id), 'third');

	}

	public function test_users(){
		$post_id = Event_Type_Creator::create_event_instance('');
		wp_create_user('one', 'one');
		wp_create_user('two', 'two');
		$data_array = ['event_plugin_users' => ['1']];
		$this->event_creator->save_event_data($post_id, $data_array);

		self::assertTrue( $this->user_assigned_and_mailed($post_id, '1') , 'first');
		self::assertFalse( $this->user_assigned_and_mailed($post_id, '2'), 'second');

		$data_array = ['event_plugin_users' => ['2', '3']];
		$this->event_creator->save_event_data($post_id, $data_array);
		self::assertTrue( $this->user_assigned_and_mailed($post_id, '2'), 'third' );
	}

	private function user_assigned_and_mailed(int $post_id, string $user_id) : bool{
		if (! get_post_meta($post_id, 'event-user' . $user_id, true)){
			return false;
		}
		if (! get_post_meta($post_id, 'event-user' . $user_id  . '-mailed' , true)){
			return false;
		}

		return true;
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
