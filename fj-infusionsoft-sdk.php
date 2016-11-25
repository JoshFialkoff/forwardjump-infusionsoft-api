<?php
/**
 * Integrates WP with the Infusionsoft API using OAuth 2.0.
 * This plugin includes the latest version of the officially supported Infusionsoft PHP SDK
 * @see https://github.com/infusionsoft/infusionsoft-php
 * @see https://developer.infusionsoft.com/docs/xml-rpc/
 *
 * @package   FJ-Infusionsoft-SDK
 * @author    Tim Jensen <tim@forwardjump.com>
 * @license   GPL-2.0+
 *
 * Plugin Name:       ForwardJump Infusionsoft SDK
 * Plugin URI:        https://github.com/timothyjensen/forwardjump-infusionsoft-sdk
 * Description:       Integrates WP with the Infusionsoft API using OAuth 2.0.  Configure settings from the "Settings" menu.
 * Author:            Tim Jensen
 * Author URI:        http://forwardjump.com
 * Text Domain:       fj-infusionsoft-sdk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:           1.0.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Loads the Infusionsoft SDK
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// Plugin options page
include_once plugin_dir_path( __FILE__ ) . 'includes/options.php';

/**
 * Instantiates the Infusionsoft class and checks for a valid token
 *
 * @return obj $infusionsoft    Required for each API call
 */
function fj_infusionsoft_init() {
	// Get the serialized token from the WP options table
	$infusionsoft_token = get_option( 'fj_infusionsoft_sdk_token' );

	// Return early if token is not set
	if ( ! $infusionsoft_token ) {
		return;
	}

	$infusionsoft = new \Infusionsoft\Infusionsoft( array(
		'clientId'     => get_option( 'fj_infusionsoft_sdk_client_id' ),
		'clientSecret' => get_option( 'fj_infusionsoft_sdk_client_secret' ),
		'redirectUri'  => admin_url(),
	) );

	$infusionsoft->setToken( unserialize( $infusionsoft_token ) );

	// Refresh the token if it is set to expire in less than 3 hrs
	if ( 10800 > unserialize( $infusionsoft_token )->endOfLife - time() ) {
		$infusionsoft->refreshAccessToken();
	}

	// Save the token for future requests
	$infusionsoft_token = serialize( $infusionsoft->getToken() );

	// Store the serialized token in the WP options table
	update_option( 'fj_infusionsoft_sdk_token', $infusionsoft_token );

	return $infusionsoft;
}

add_action( 'admin_init', 'fj_exchange_infusionsoft_code_for_token' );
/**
 * If we are returning from Infusionsoft we need to exchange the code for an access token.
 *
 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-an-access-token
 */
function fj_exchange_infusionsoft_code_for_token() {
	if ( ! $_GET['code'] || ! preg_match( '/infusionsoft/i', $_GET['scope'] ) ) {
		return;
	}

	$infusionsoft = new \Infusionsoft\Infusionsoft( array(
		'clientId'     => get_option( 'fj_infusionsoft_sdk_client_id' ),
		'clientSecret' => get_option( 'fj_infusionsoft_sdk_client_secret' ),
		'redirectUri'  => admin_url(),
	) );

	$infusionsoft->requestAccessToken( $_GET['code'] );

	// Save the serialized token to the current session for subsequent requests
	$infusionsoft_token = serialize( $infusionsoft->getToken() );

	// Store serialized token in the WP options table
	update_option( 'fj_infusionsoft_sdk_token', $infusionsoft_token );

	if ( $infusionsoft_token ) {
		add_action( 'admin_notices', 'fj_infusionsoft_token_success_admin_notice' );
	}
}