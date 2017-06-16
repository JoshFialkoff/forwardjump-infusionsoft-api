<?php
/**
 * Integrates WP with the Infusionsoft API using OAuth 2.0.
 * This plugin includes the latest version of the officially supported Infusionsoft PHP SDK
 * @see       https://github.com/infusionsoft/infusionsoft-php
 * @see       https://developer.infusionsoft.com/docs/xml-rpc/
 *
 * @package   ForwardJump-Infusionsoft-API
 * @author    Tim Jensen <tim@forwardjump.com>
 * @license   GPL-2.0+
 *
 * Plugin Name:       ForwardJump Infusionsoft API
 * GitHub Plugin URI: https://github.com/timothyjensen/forwardjump-infusionsoft-api
 * GitHub Branch:     master
 * Description:       Integrates WP with the Infusionsoft API using OAuth 2.0.  Configure settings from the "Settings" menu.
 * Author:            Tim Jensen
 * Author URI:        http://forwardjump.com
 * Text Domain:       fj-infusionsoft-api
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:           2.1.0
 */

namespace ForwardJump\InfusionsoftAPI;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Remember plugin root folder.
if ( ! defined( 'FJ_INFUSIONSOFT_API_DIR' ) ) {
	define( 'FJ_INFUSIONSOFT_API_DIR', __DIR__ . '/' );
}

// Composer autoload.
if ( file_exists( FJ_INFUSIONSOFT_API_DIR . 'vendor/autoload.php' ) ) {
	require_once FJ_INFUSIONSOFT_API_DIR . 'vendor/autoload.php';
}

// Load the options page.
if ( is_admin() ) {
	include_once FJ_INFUSIONSOFT_API_DIR . 'src/options.php';
}

add_action( 'admin_init', function () {
	new \ForwardJump\InfusionsoftAPI\Exchange_Token();

	new \ForwardJump\InfusionsoftAPI\Print_Notices();
} );
