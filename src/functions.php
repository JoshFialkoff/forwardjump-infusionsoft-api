<?php
/**
 * Infusionsoft API functions
 *
 * @package     ForwardJump Infusionsoft API
 * @since       1.0.0
 * @author      Tim Jensen
 * @link        https://www.timjensen.us
 * @license     GNU General Public License 2.0+
 */

/**
 * Instantiates the Infusionsoft class and checks for a valid token
 *
 * @return obj $infusionsoft    Required for each API call
 */
function fj_infusionsoft_init() {

	return new \ForwardJump\Infusionsoft\Init;

}

add_action( 'admin_init', 'fj_exchange_infusionsoft_code_for_token' );
/**
 * If we are returning from Infusionsoft we need to exchange the code for an access token.
 *
 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-an-access-token
 */
function fj_exchange_infusionsoft_code_for_token() {
	if ( ! isset( $_GET['code'] ) || ! preg_match( '/infusionsoft/i', $_GET['scope'] ) ) {
		return;
	}


}

/**
 * Display error in Admin Notices
 */
add_action( 'admin_notices', function() {
	$errors = get_option( 'fj_infusionsoft_api_errors' );

	if ( false === $errors['request_access_token'] ) {
		?>
		<div class="notice notice-error is-dismissible">
			<p>There was a problem <b>requesting</b> the Infusionsoft Access Token.</p>
		</div>
		<?php
	}

	if ( false === $errors['refresh_access_token'] ) {
		?>
		<div class="notice notice-error is-dismissible">
			<p>There was a problem <b>refreshing</b> the Infusionsoft Access Token.</p>
		</div>
		<?php
	}
} );

add_action( 'genesis_before', function() {
	// Instantiates the Infusionsoft object and ensures that we have a valid access token.
	$infusionsoft = fj_infusionsoft_init();

	$contact = array(
		'Email'     => 'tim+test@forwardjump.com',
		'FirstName' => 'Tim',
		'LastName'  => 'Jensen',
	);

	// Adds the WP user as an Infusionsoft contact if they are not already in Infusionsoft
	$contact_id = $infusionsoft->contacts()->addWithDupCheck( $contact, 'Email' );

	var_dump( $contact_id );
} );


