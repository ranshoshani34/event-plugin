<?php
/**
 * Class file Users attribute.
 *
 * @package event-plugin.
 */

/**
 * Class Users.
 */
class Users extends Event_Attribute {

	/**
	 * Array in the form of nickname => WP_USER object to represent the users.
	 *
	 * @var array
	 */
	private $users_array;

	/**
	 * Constructor to create the users array.
	 */
	public function __construct() {
		$this->users_array = array();

		$users = get_users();

		foreach ( $users as $user ) {
			$this->users_array[ $user->get( 'display_name' ) ] = $user;
		}
	}

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id -  (optional) the id of the post to retrieve old data from (if specified).
	 */
	public function render_metabox( int $post_id = 0 ) : void {
		?>
		<p>Users to assign:</p>
		<?php
		foreach ( $this->users_array as $username => $user ) {
			?>
			<br>

			<input type="checkbox" id="<?php echo esc_attr( $username ); ?>" name="<?php echo esc_attr( $username ); ?>">
			<label for="<?php echo esc_attr( $username ); ?>"><?php echo esc_html( $username ); ?></label>
			<br>
			<?php
		}
	}

	/**
	 * Description - method to get the attribute value from the database.
	 * this method is supposed to be empty.
	 *
	 * @param int $post_id - the post id.
	 *
	 * @return string
	 */
	public function get_value( int $post_id ) : string {
		// empty.
		return '';
	}

	/**
	 * Description - method to update the database from the submitted form.
	 *
	 * @param int $post_id - the post id.
	 */
	public function update_value( int $post_id ) : void {
		$is_nonce_valid = isset( $_POST['rep-event-info-nonce'] ) && ( wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rep-event-info-nonce'] ) ), basename( ROOT ) ) );
		if ( ! $is_nonce_valid ) {
			return;
		}

		$emails = array();
		foreach ( $this->users_array as $username => $user ) {
			update_post_meta( $post_id, $username, isset( $_POST[ $username ] ) );

			if ( $this->is_user_assigned( $username, $post_id ) ) {
				$emails[] = $user->get( 'user_email' );
			}
		}
		$this->mail_user(
			$emails,
			$this->generate_mail_message( $post_id )
		);
	}

	/**
	 * Description - method to render the field about the attribute in the event page (single).
	 * this method is supposed to be empty.
	 *
	 * @param int $post_id - the post id.
	 */
	public function render_single_field( int $post_id ) : void {
		// empty.
	}

	/**
	 * Method to mail a message to an array of emails.
	 *
	 * @param array  $emails - array of emails to send the notification to.
	 * @param string $message - message to send containing event details.
	 */
	private function mail_user( array $emails, string $message ) : void {
		$headers = array( 'Content-Type: text/html' );
		wp_mail( $emails, 'New event published', $message, $headers );
	}

	/**
	 * Method to generate email message containing event information.
	 *
	 * @param int $post_id - the post id to retrieve event information.
	 *
	 * @return string
	 */
	private function generate_mail_message( int $post_id ): string {
		$event_title = get_the_title( $post_id );
		$event_link  = get_permalink( $post_id );

		return 'A new event was added: <br>' . $event_title . '<br>link:<br>' . $event_link;
	}

	/**
	 * Method to test if a user is assigned to the event.
	 *
	 * @param string $username - the username of the user.
	 * @param int    $post_id - the pod id of the event.
	 *
	 * @return bool
	 */
	private function is_user_assigned( string $username, int $post_id ): bool {
		return get_post_meta( $post_id, $username, true );
	}
}
