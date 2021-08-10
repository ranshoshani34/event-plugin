<?php

class Start_Date extends Event_Attribute {

    public function render_metabox($post_id) {
        $event_start_date =  $this->get_value($post_id);
        $event_start_date = ! empty($event_start_date) ? $event_start_date : time();
        ?>
        <label for="rep-event-start-date">
            <?php _e( 'Event Start Date:', 'rep' ); ?>
        </label>
        <input
            class="widefat rep-event-date-input"
            id="rep-event-start-date"
            type="date"
            name="rep-event-start-date"
            value="<?php echo date( 'Y-m-d', $event_start_date ); ?>"
        />
        <?php
    }

    public function get_value($post_id) {
        return get_post_meta( $post_id, 'event-start-date', true );
    }

    public function update_value($post_id) {
        if ( isset( $_POST['rep-event-start-date'] ) ) {
            update_post_meta( $post_id, 'event-start-date', strtotime( $_POST['rep-event-start-date'] ) );
        }
    }

    public function render_single_field($post_id) {

        $start_date = $this->get_value($post_id);
        ?>
        <h3>Start date:  <?php echo date('d/m/y',$start_date)?></h3>
        <?php
    }
}