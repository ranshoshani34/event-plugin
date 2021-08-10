<?php

class End_Date extends Event_Attribute {

    public function render_metabox($post_id) {
        $event_end_date = $this->get_value($post_id);
        $event_end_date = !empty($event_end_date) ? $event_end_date : time();
        ?>
        <label
            for="rep-event-end-date"><?php _e( 'Event End Date:', 'rep' ); ?>
        </label>
        <input
            class="widefat rep-event-date-input"
            id="rep-event-end-date"
            type="date"
            name="rep-event-end-date"
            placeholder="Format: February 18, 2014"
            value="<?php echo date( 'Y-m-d', $event_end_date ); ?>"
        />
        <?php
    }

    public function get_value($post_id) {
        return get_post_meta( $post_id, 'event-end-date', true );
    }

    public function update_value($post_id) {
        if ( isset( $_POST['rep-event-end-date'] ) ) {
            update_post_meta( $post_id, 'event-end-date', strtotime( $_POST['rep-event-end-date'] ) );
        }
    }

    public function render_single_field($post_id) {
        $end_date = $this->get_value($post_id);

        if (! empty($end_date)){
            ?>
            <h3>End date:  <?php echo date('d/m/y',$end_date)?></h3>
            <?php
        }
    }
}