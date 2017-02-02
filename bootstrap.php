<?php
/**
 * Integrates WP with the Infusionsoft API using OAuth 2.0.
 * This plugin includes the latest version of the officially supported Infusionsoft PHP SDK
 * @see       https://github.com/infusionsoft/infusionsoft-php
 * @see       https://developer.infusionsoft.com/docs/xml-rpc/
 *
 * @package   FJ-Infusionsoft-SDK
 * @author    Tim Jensen <tim@forwardjump.com>
 * @license   GPL-2.0+
 *
 * Plugin Name:       ForwardJump Infusionsoft API
 * Plugin URI:        https://github.com/timothyjensen/forwardjump-infusionsoft-api
 * Description:       Integrates WP with the Infusionsoft API using OAuth 2.0.  Configure settings from the "Settings" menu.
 * Author:            Tim Jensen
 * Author URI:        http://forwardjump.com
 * Text Domain:       fj-infusionsoft-api
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:           1.1.0
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Loads the Infusionsoft SDK.
require_once plugin_dir_path( __FILE__ ) . 'vendor/autoload.php';

// Plugin options page.
include_once plugin_dir_path( __FILE__ ) . 'src/options.php';

// FJ Infusionsoft class.
require_once plugin_dir_path( __FILE__ ) . 'src/Init.php';

// FJ Infusionsoft ExchangeToken class.
require_once plugin_dir_path( __FILE__ ) . 'src/ExchangeToken.php';

// FJ Infusionsoft functions.
require_once plugin_dir_path( __FILE__ ) . 'src/functions.php';