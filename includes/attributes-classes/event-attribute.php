<?php

abstract class Event_Attribute {

    abstract public function render_metabox($post_id);
    abstract public function get_value($post_id);
    abstract public function update_value($post_id);
    abstract public function render_single_field($post_id);
}
