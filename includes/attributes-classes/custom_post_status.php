<?php

class Custom_Post_Status{

	private $name;
	private $settings;
	private $post_type;
	private $title;
		//todo change constructor
	/**
	 * @param $name
	 * @param $settings
	 */
	public function __construct( $name, $settings ) {
		$this->name = $name;
		$this->settings = $settings;
	}

	///todo getters

	public function register(){
		$this->my_custom_status_creation();

		add_filter( 'display_post_states', [$this, 'display_archive_state' ]);
		add_action( 'post_submitbox_misc_actions', [$this,'add_to_post_status_dropdown']);
		add_action('admin_footer-edit.php',[$this,'custom_status_add_in_quick_edit']);
	}

	private function my_custom_status_creation(){
		register_post_status( $this->get_name(), $this->settings );
	}

	public function add_to_post_status_dropdown()
	{
		global $post;
		if($post->post_type !== $this->post_type){
			return false;
		}

		$status = ($post->post_status === $this->get_name()) ? "jQuery( '#post-status-display' ).text( '{$this->get_title()}' );
						jQuery( 'select[name=\"post_status\"]' ).val('{$this->get_name()}');" : '';

		echo "<script>
					jQuery(document).ready( function() {
					jQuery( 'select[name=\"post_status\"]' ).append( '<option value=\"completed\">{$this->get_title()}</option>' );
					".$status."
					});
				</script>";
	}

	public function custom_status_add_in_quick_edit() {
		global $post;
		if($post->post_type != $this->post_type){
			return false;
		}

		echo "<script>
					jQuery(document).ready( function() {
					jQuery( 'select[name=\"_status\"]' ).append( '<option value=\"{$this->get_name()}\">{$this->get_title()}</option>' );
					});
				</script>";
	}

	function display_archive_state( $states ) {
		global $post;
		$arg = get_query_var( 'post_status' );
		if($arg !== $this->get_name() && $post->post_status == $this->get_name()){
				echo "<script>
							jQuery(document).ready( function() {
							jQuery( '#post-status-display' ).text( '{$this->get_name()}' );
							});
						</script>";

				return array('Completed');
		}
		return $states;
	}

}







function my_custom_status_creation(){
	register_post_status( 'completed', array(
		'label'                     => _x( 'Completed', 'post' ),
		'label_count'               => _n_noop( 'Completed <span class="count">(%s)</span>', 'Completed <span class="count">(%s)</span>'),
		'public'                    => false,
		'exclude_from_search'       => false,
		'show_in_admin_all_list'    => true,
		'show_in_admin_status_list' => true
	));
}




