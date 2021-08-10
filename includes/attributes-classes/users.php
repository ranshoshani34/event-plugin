<?php

class Users extends Event_Attribute {
    private $users_array;

    public function __construct() {
        // this array will be in the form of nickname => WP_USER object
        $this->users_array = array();

        $users = get_users();

        foreach ($users as $user){
            $this->users_array[$user->get('display_name')] = $user;
        }
    }

    public function render_metabox($post_id) {
        ?>
        <p>Users to assign:</p>
        <?php
        foreach ($this->users_array as $username => $user){
            ?>
            <br>

            <input type="checkbox" id="<?php echo $username?>" name="<?php echo $username?>">
            <label for="<?php echo $username?>"><?php echo $username?></label>
            <br>
            <?php
        }
    }

    public function get_value($post_id) {
        // empty
    }

    public function update_value($post_id) {
        $emails = array();
        foreach ($this->users_array as $username => $user){
            update_post_meta( $post_id, $username, isset( $_POST[$username]));
            if ($this->is_user_assigned($username, $post_id)) {
                $emails[] = $user->get('user_email');
            }
        }
        $this->mail_user($emails, "New event published",
            $this->generate_mail_message($post_id));
    }

    public function render_single_field($post_id) {
        //empty
    }

    private function mail_user($emails, $title, $message){
        $headers = array('Content-Type: text/html');
        wp_mail($emails, $title, $message, $headers);
    }

    private function generate_mail_message($post_id): string {
        $event_title = get_the_title($post_id);
        $event_link = get_permalink($post_id);

        return 'A new event was added: <br>' . $event_title . '<br>link:<br>' . $event_link;
    }

    private function is_user_assigned($username, $post_id): bool {
        return get_post_meta($post_id, $username, true);
    }
}