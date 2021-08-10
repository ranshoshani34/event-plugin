<?php

class Weekly extends Event_Attribute {

    public function render_metabox($post_id) {
        $is_checked = $this->get_value($post_id);

        ?>
        <br>
        <input type="checkbox" id="rep-weekly" name="rep-weekly"
            <?php
                if ($is_checked){
                    echo "checked";
                }
            ?>
        >
        <label for="rep-weekly">Weekly event</label>
        <br>
        <?php
    }

    public function get_value($post_id) {
        return get_post_meta( $post_id, 'event-weekly', true );
    }

    public function update_value($post_id) {
        update_post_meta( $post_id, 'event-weekly', isset( $_POST['rep-weekly']));
    }

    public function render_single_field($post_id) {
        $event_weekly = $this->get_value($post_id);
        ?>
        <h3>Weekly event:
            <?php
            if ($event_weekly){
                echo 'yes';
            } else {
                echo 'no';
            }
            ?>
        </h3>
        <?php
    }
}