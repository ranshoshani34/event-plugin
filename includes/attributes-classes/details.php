<?php

class Details extends Event_Attribute {

    public function render_metabox($post_id) {
        $event_details = $this->get_value($post_id);

        ?>
        <label
            for="rep-event-details"><?php _e( 'Event Details:', 'rep' ); ?>
        </label>
        <textarea
            class="widefat"
            id="rep-event-details"
            name="rep-event-details"
        ><?php echo $event_details; ?></textarea>
        <?php
    }

    public function get_value($post_id) {
        return get_post_meta( $post_id, 'event-details', true );
    }

    public function update_value($post_id) {
        if ( isset( $_POST['rep-event-details'] ) ) {
            update_post_meta( $post_id, 'event-details', sanitize_text_field( $_POST['rep-event-details'] ) );
        }
    }

    public function render_single_field($post_id) {
        $event_details = $this->get_value($post_id);
        ?>
        <h3>Details:</h3>
        <p>         <?php echo $event_details?> </p>
        <?php
    }
}