<?php

class Location extends Event_Attribute {

    public function render_metabox($post_id) {
        $event_venue = $this->get_value($post_id)

        ?>
        <label
            for="rep-event-venue"><?php _e( 'Event Location:', 'rep' ); ?>
        </label>
        <input
            class="widefat"
            id="rep-event-venue"
            type="text"
            name="rep-event-venue"
            placeholder="eg. Times Square"
            value="<?php echo $event_venue; ?>"
        />
        <?php
    }

    public function get_value($post_id) {
        return get_post_meta( $post_id, 'event-venue', true );
    }

    public function update_value($post_id) {
        if ( isset( $_POST['rep-event-venue'] ) ) {
            update_post_meta( $post_id, 'event-venue', sanitize_text_field( $_POST['rep-event-venue'] ) );
        }
    }

    public function render_single_field($post_id) {
        $event_venue = $this->get_value($post_id);
        ?>
        <h3>Location:  <?php echo $event_venue?></h3>
        <?php
    }
}