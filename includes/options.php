<?php
/**
 * Adds options page
 */

add_action( 'admin_menu', 'forwardjump_infusionsoft_sdk_admin_menu' );
/**
 * Add the settings page to the menu.
 */
function forwardjump_infusionsoft_sdk_admin_menu() {
    add_options_page(
        'ForwardJump Infusionsoft SDK Settings',    // Page title
        'FJ Infusionsoft SDK',             // Menu title
        'install_plugins',              // Available to users with this capability
        'forwardjump-infusionsoft-sdk-admin-menu',                       // Menu slug
        'forwardjump_infusionsoft_sdk_display_admin_page'    // Function to call
    );
}

/**
 * Display the settings form.
 */
function forwardjump_infusionsoft_sdk_display_admin_page() {
	?>
	<h1>ForwardJump Infusionsoft SDK Settings</h1>
		<p>This plugin is intended for assist developers with integrating their WordPress installation with the <a href="https://github.com/infusionsoft/infusionsoft-php" target="_blank">Infusionsoft SDK</a>.  This integration authenticates using OAuth 2.0.</p>
		<ol>
			<li>You must have an account with Infusionsoft.</li>
			<li>Register for a free <a href="https://keys.developer.infusionsoft.com/member/register" target="_blank">Infusionsoft developers account</a>.</li>
			<li>Obtain the Client ID and Client Secret keys from your Infusionsoft developer's account, which are available at <a href="https://keys.developer.infusionsoft.com/apps/mykeys" target="_blank">https://keys.developer.infusionsoft.com/apps/mykeys</a>.</li>
			<li>Paste the keys below and click "Save Changes".</li>
			<li>After saving the keys you must click "Click here to authorize" to obtain an access token from Infusionsoft.  If successful, you will be redirected back to the WordPress Dashboard and will see a message that says "Your Infusionsoft Token has been successfully added!".</li>
			<li>Once you have received an Infusionsoft token you can begin making API calls.  Your API calls will be very similar to what is presented in the <a href="https://developer.infusionsoft.com/docs/xml-rpc/" target="_blank">Infusionsoft API documentation</a>.  An example usage is provided at the bottom of this page.</li>
		</ol>
	<?php

	echo '<form method="POST" action="options.php">';
        settings_fields( 'fj_infusionsoft_sdk_settings' );

        do_settings_sections( 'fj_infusionsoft_sdk_settings' );

        submit_button();
    echo '</form>';

	fj_infusionsoft_request_permission_link();

	fj_infusionsoft_example_usage();
}

add_action( 'admin_init', 'forwardjump_infusionsoft_sdk_admin_init' );
/**
 * Define the fields used on the settings page.
 */
function forwardjump_infusionsoft_sdk_admin_init() {
    add_settings_section(
    	'fj_infusionsoft_sdk_settings',
        '',
        null,
        'fj_infusionsoft_sdk_settings'
    );

    add_settings_field(
    	'fj_infusionsoft_sdk_client_id',
        'Infusionsoft&reg; Client ID',
        'fj_infusionsoft_sdk_client_id_field',
        'fj_infusionsoft_sdk_settings',
	    'fj_infusionsoft_sdk_settings'
    );

    add_settings_field(
    	'fj_infusionsoft_sdk_client_secret',
        'Infusionsoft&reg; Client Secret',
        'fj_infusionsoft_sdk_client_secret_field',
        'fj_infusionsoft_sdk_settings',
	    'fj_infusionsoft_sdk_settings'
    );

    register_setting( 'fj_infusionsoft_sdk_settings', 'fj_infusionsoft_sdk_client_id', 'infusionsoft_sdk_sanitize' );
    register_setting( 'fj_infusionsoft_sdk_settings', 'fj_infusionsoft_sdk_client_secret', 'infusionsoft_sdk_sanitize' );
}

/**
 * Display the Client ID field.
 */
function fj_infusionsoft_sdk_client_id_field() {
    echo '<input type="text" name="fj_infusionsoft_sdk_client_id" value="' . get_option( 'fj_infusionsoft_sdk_client_id' ) . '" size="30" /><br />';
}

/**
 * Display the Client Secret field.
 */
function fj_infusionsoft_sdk_client_secret_field() {
    echo '<input type="text" name="fj_infusionsoft_sdk_client_secret" value="' . get_option( 'fj_infusionsoft_sdk_client_secret' ) . '" size="15" /><br />';
}

/**
 * Request permission by signing in to Infusionsoft
 *
 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-permission
 */
function fj_infusionsoft_request_permission_link() {
    $infusionsoft = new \Infusionsoft\Infusionsoft( array(
        'clientId'     => get_option( 'fj_infusionsoft_sdk_client_id' ),
        'clientSecret' => get_option( 'fj_infusionsoft_sdk_client_secret' ),
        'redirectUri'  => admin_url()
    ) );

	?>
	<h3>Authorize with Infusionsoft</h3>
	<p>After clicking the button below you must sign in to your Infusionsoft account and then click "Allow" to grant this application permission to connect with your Infusionsoft account.</p>
	<p class="submit"><a href="<?php echo $infusionsoft->getAuthorizationUrl() ?>" target="_blank" class="button-primary">Click here to authorize</a></p>
	<?php
}

function fj_infusionsoft_example_usage() {
	?>
	<h3>Example usage:</h3>
	<script src="https://gist.github.com/timothyjensen/0efadea1e7bd1e442c9c4035c5078d5a.js"></script>
	<?php
}

/**
 * Remove non-alphanumeric characters.
 *
 * @param string $value
 * @return string
 */
function infusionsoft_sdk_sanitize( $value ) {
    return preg_replace( "/[^a-zA-Z0-9]+/", "", $value );
}

function fj_infusionsoft_token_success_admin_notice() {
    ?>
    <div class="notice notice-success is-dismissible">
        <p>Your Infusionsoft Token has been successfully added!</p>
    </div>
    <?php
}
