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
 * Plugin URI:        https://forwardjump.com
 * Description:       Integrates WP with the Infusionsoft API using OAuth 2.0.
 * Author:            Tim Jensen
 * Author URI:        http://forwardjump.com
 * Text Domain:       fj-infusionsoft-sdk
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:           1.0.0
 *
 * @TODO Update Plugin URI
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
    die;
}

session_start();

require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

include plugin_dir_path( __FILE__ ) . 'includes/options.php';

include plugin_dir_path( __FILE__ ) . 'includes/functions/contact-functions.php';

/**
 * If we are returning from Infusionsoft we need to exchange the code for an access token.
 *
 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-an-access-token
 */
if ( isset($_GET['code']) ) {
    add_action( 'admin_init', function() {
        $infusionsoft = new \Infusionsoft\Infusionsoft(array(
            'clientId'     => get_option( 'fj_infusionsoft_sdk_client_id' ),
            'clientSecret' => get_option( 'fj_infusionsoft_sdk_client_secret' ),
            'redirectUri'  => admin_url(),
        ));

        $infusionsoft->requestAccessToken($_GET['code']);

        // Save the serialized token to the current session for subsequent requests
        $_SESSION['token'] = serialize($infusionsoft->getToken());

        // Store serialized token in the WP options table
        update_option( 'fj_infusionsoft_sdk_token', $_SESSION['token'] );

        if( $_SESSION['token'] ) {
            add_action( 'admin_notices', 'fj_infusionsoft_token_success_admin_notice' );
        }
    });
}

