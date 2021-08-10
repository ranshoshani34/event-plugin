<?php

require('attributes-manager.php');

class Event_Type_Creator {
    private $attributes_manager;

    public function __construct() {
        $this->attributes_manager = new Attributes_Manager();
    }

    public function initialize() {
        add_action('init', [$this, 'register'] );
        add_action( 'add_meta_boxes', [$this, 'add_metabox'] );
        add_action( 'save_post', [$this, 'save_event_info'] );
    }

    public function register() {
        $labels = array(
            'name'                  =>   __( 'Events', 'rep' ),
            'singular_name'         =>   __( 'Event', 'rep' ),
            'add_new_item'          =>   __( 'Add New Event', 'rep' ),
            'all_items'             =>   __( 'All Events', 'rep' ),
            'edit_item'             =>   __( 'Edit Event', 'rep' ),
            'new_item'              =>   __( 'New Event', 'rep' ),
            'view_item'             =>   __( 'View Event', 'rep' ),
            'not_found'             =>   __( 'No Events Found', 'rep' ),
            'not_found_in_trash'    =>   __( 'No Events Found in Trash', 'rep' )
        );

        $supports = array(
            'title',
            'thumbnail',
        );

        $args = array(
            'label'         =>   __( 'Events', 'rep' ),
            'labels'        =>   $labels,
            'description'   =>   __( 'A list of upcoming events', 'rep' ),
            'public'        =>   true,
            'show_in_menu'  =>   true,
            'menu_icon'     =>   IMAGES . 'event.svg',
            'has_archive'   =>   true,
            'rewrite'       =>   true,
            'supports'      =>   $supports
        );

        register_post_type( 'event', $args );
    }

    public function add_metabox(){
        add_meta_box(
            'rep-event-info-metabox',
            __( 'Event Info', 'rep' ),
            [$this, 'render_metabox'],
            'event',
            'normal',
            'core'
        );
    }

    public function render_metabox(){
        // generate a nonce field
        wp_nonce_field( basename( __FILE__ ), 'rep-event-info-nonce' );

        foreach ($this->attributes_manager->attributes_array as $attribute){
            $attribute->render_metabox(get_the_ID());
        }
    }

    public function save_event_info( $post_id ) {

        // checking if the post being saved is an 'event',
        // if not, then return
        if ( 'event' != $_POST['post_type'] ) {
            return;
        }

        // checking for the 'save' status
        $is_autosave = wp_is_post_autosave( $post_id );
        $is_revision = wp_is_post_revision( $post_id );
        $is_valid_nonce = isset( $_POST['rep-event-info-nonce'] )     &&
            ( wp_verify_nonce( $_POST['rep-event-info-nonce'], basename( __FILE__ ) ) );

        // exit depending on the save status or if the nonce is not valid
        if ( $is_autosave || $is_revision || ! $is_valid_nonce ) {
            return;
        }
        foreach ($this->attributes_manager->attributes_array as $attribute){
            $attribute->update_value($post_id);
        }
    }

}