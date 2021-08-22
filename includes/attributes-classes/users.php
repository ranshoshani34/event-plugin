<?php
/**
 * Class file Users attribute.
 *
 * @package event-plugin.
 */

/**
 * Class Users.
 */
class Users extends Custom_Post_Attribute {

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
		$this->users_array = get_users();
	}

	/**
	 * Description - method to render a custom metabox to receive the attribute.
	 *
	 * @param int $post_id -  (optional) the id of the post to retrieve old data from (if specified).
	 */
	public function render_metabox( int $post_id = 0 ) : void {
		$is_checked = false;
		?>
		<p>Users to assign:</p>
		<?php
		foreach ( $this->users_array as $user ) {
			$user_id = $user->get( 'ID' );

			if ( 0 !== $post_id ) {
				$is_checked = $this->is_user_assigned( $user_id, $post_id );
			}

			?>
			<br>

			<input type="checkbox" id="<?php echo esc_attr( $user_id ); ?>" name="<?php echo esc_attr( $user_id ); ?>"
				<?php
				if ( $is_checked ) {
					echo 'checked';
				}
				?>
			>
			<label for="<?php echo esc_attr( $user_id ); ?>"><?php echo esc_html( $user->get( 'display_name' ) ); ?></label>
			<br>
			<?php
			$is_checked = false;
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
		foreach ( $this->users_array as $user ) {
			$user_id = $user->get( 'ID' );

			update_post_meta( $post_id, $user_id, isset( $_POST[ $user_id ] ) ); //phpcs:ignore
		}
	}

	/**
	 * Method that does any action that should happen after a post is saved.
	 *
	 * @param int $post_id id of the post.
	 */
	public function after_save_post( int $post_id ) {

		$this->update_value( $post_id );

		$emails = [];
		foreach ( $this->users_array as $user ) {
			$user_id = $user->get( 'ID' );

			if ( $this->is_user_assigned( $user_id, $post_id ) && ! $this->is_user_mailed( $user_id, $post_id ) ) {
				$emails[] = $user->get( 'user_email' );
				update_post_meta( $post_id, $user_id . '-mailed', true );
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
		$headers = [ 'Content-Type: text/html' ];
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
	 * @param string $user_id - the username of the user.
	 * @param int    $post_id - the pod id of the event.
	 *
	 * @return bool
	 */
	private function is_user_assigned( string $user_id, int $post_id ): bool {
		return get_post_meta( $post_id, $user_id, true );
	}

	/**
	 * Method to determine if the user was already mailed about the event.
	 *
	 * @param string $user_id the user's id.
	 * @param int    $post_id the post id.
	 *
	 * @return bool
	 */
	private function is_user_mailed( string $user_id, int $post_id ): bool {
		return get_post_meta( $post_id, $user_id . '-mailed', true );
	}
}
