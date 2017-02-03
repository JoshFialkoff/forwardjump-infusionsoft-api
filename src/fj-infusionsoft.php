<?php
/**
 * ForwardJump Infusionsoft
 *
 * @package     ForwardJump\Infusionsoft
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

namespace ForwardJump\Infusionsoft;

/**
 * Instantiates the Infusionsoft class and checks for a valid token
 *
 * @return obj $infusionsoft    Required for each API call
 */
function infusionsoft_init() {
	// Get the serialized token from the WP options table
	$infusionsoft_token = get_token();

	// Return early if token is not set
	if ( ! unserialize( $infusionsoft_token ) ) {

		update_error_messages( array( 'valid_access_token' => 'false' ) );

		return;
	}

	$infusionsoft = get_infusionsoft_object();

	$infusionsoft->setToken( unserialize( $infusionsoft_token ) );

	// Refresh the token if it is set to expire in less than 3 hrs
	if ( 10800 > unserialize( $infusionsoft_token )->endOfLife - time() ) {

		try {

			$infusionsoft->refreshAccessToken();

			update_error_messages( array( 'refresh_access_token' => 'true' ) );

		}
		catch ( \GuzzleHttp\Exception\RequestException $e ) {

			update_error_messages( array( 'refresh_access_token' => 'false' ) );

		}
	}

	// Save the token for future requests
	$infusionsoft_token = serialize( $infusionsoft->getToken() );

	// Store the serialized token in the WP options table
	update_option( 'fj_infusionsoft_api_token', $infusionsoft_token );

	return $infusionsoft;
}

add_action( 'admin_init', __NAMESPACE__ . '\fj_exchange_infusionsoft_code_for_token' );
/**
 * If we are returning from Infusionsoft we need to exchange the code for an access token.
 *
 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-an-access-token
 */
function fj_exchange_infusionsoft_code_for_token() {
	if ( ! isset( $_GET['code'] ) || ! preg_match( '/infusionsoft/i', $_GET['scope'] ) ) {
		return;
	}

	$infusionsoft = get_infusionsoft_object();

	try {
		$infusionsoft->requestAccessToken( $_GET['code'] );

		$infusionsoft_token = serialize( $infusionsoft->getToken() );

		// Store serialized token in the WP options table
		update_option( 'fj_infusionsoft_api_token', $infusionsoft_token );

		update_error_messages( array( 'refresh_access_token' => 'true' ) );

		if ( $infusionsoft_token ) {
			add_action( 'admin_notices', 'fj_infusionsoft_token_success_admin_notice' );
		}
	}
	catch ( \GuzzleHttp\Exception\RequestException $e ) {

		update_error_messages( array( 'refresh_access_token' => 'false' ) );

	}
}

/**
 * Display error messages in Admin Notices
 */
add_action( 'admin_notices', function () {
	$errors = get_error_messages();

	if ( 'false' === $errors['request_access_token'] ) {
		?>
        <div class="notice notice-error is-dismissible">
            <p><b>Infusionsoft Error: </b>There was a problem <b>requesting</b> the Infusionsoft Access Token.</p>
        </div>
		<?php
	}

	if ( 'false' === $errors['refresh_access_token'] ) {
		?>
        <div class="notice notice-error is-dismissible">
            <p><b>Infusionsoft Error: </b>There was a problem <b>refreshing</b> the Infusionsoft Access Token.</p>
        </div>
		<?php
	}

	if ( 'false' === $errors['valid_access_token'] ) {
		?>
        <div class="notice notice-error is-dismissible">
            <p><b>Infusionsoft Error: </b>It looks like you have an <b>invalid</b> Infusionsoft Access Token.</p>
        </div>
		<?php
	}

	if ( ! empty( $errors['error_message'] ) ) {
		?>
        <div class="notice notice-error is-dismissible">
            <p><b>Infusionsoft Error: </b><?php echo esc_html( $errors['error_message'] ); ?></p>
        </div>
		<?php
	}

} );

/**
 * @return object \Infusionsoft\Infusionsoft
 */
function get_infusionsoft_object() {

	return new \Infusionsoft\Infusionsoft( array(
		'clientId'     => get_client_id(),
		'clientSecret' => get_client_secret(),
		'redirectUri'  => admin_url(),
	) );

}

/**
 * Retrieves the stored Client ID
 *
 * @return string|void
 */
function get_client_id() {
	return get_option( 'fj_infusionsoft_api_client_id' );
}

/**
 * Retrieves the stored Client Secret
 *
 * @return string|void
 */
function get_client_secret() {
	return get_option( 'fj_infusionsoft_api_client_secret' );
}

/**
 * Retrieves the stored API token
 *
 * @return object|void
 */
function get_token() {
	return get_option( 'fj_infusionsoft_api_token' );
}

/**
 * Retrieves the stored error messages
 *
 * @return array
 */
function get_error_messages() {
	$error_messages = get_option( 'fj_infusionsoft_api_errors' );

	if ( ! is_array( $error_messages ) ) {
		$error_messages = array();
	}

	return $error_messages;
}

/**
 * Updates error messages
 *
 * @param array $error_message
 */
function update_error_messages( $error_message = null ) {
	$error_messages = \ForwardJump\Infusionsoft\get_error_messages();

	if ( is_array( $error_message ) ) {

		$merge = array_merge( (array) $error_messages, $error_message );

		update_option( 'fj_infusionsoft_api_errors', $merge );

	}
}