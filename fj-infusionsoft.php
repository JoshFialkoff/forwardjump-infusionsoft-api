<?php

/*
Plugin Name: ForwardJump Infusionsoft WP
Description: Integrates WP with the Infusionsoft API using OAuth 2.0
Version: 1.0.0
Description: Integrate with the Infusionsoft API.
*/

session_start();

require_once 'vendor/autoload.php';

// Get the serialized token from the options table
$_SESSION['token'] = get_option( 'fj_infusionsoft_api_token' );

// var_dump( $_SESSION['token'] );

$infusionsoft = new \Infusionsoft\Infusionsoft(array(
    'clientId'     => 'c9fq2kdmb4e8wdqxucvkgudx',
    'clientSecret' => '6g6qxRTAZZ',
    'redirectUri'  => get_bloginfo( 'url' )
));

// By default, the SDK uses the Guzzle HTTP library for requests. To use CURL,
// you can change the HTTP client by using following line:
// $infusionsoft->setHttpClient(new \Infusionsoft\Http\CurlClient());

/**
 * If the serialized token is available in the session storage, we tell the SDK
 * to use that token for subsequent requests.
 *
 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-refresh-an-access-token
 */
if (isset($_SESSION['token'])) {
    $infusionsoft->setToken(unserialize($_SESSION['token']));
}

/**
 * If we are returning from Infusionsoft we need to exchange the code for an
 * access token.
 *
 * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-an-access-token
 */
if (isset($_GET['code']) and !$infusionsoft->getToken()) {
    $infusionsoft->requestAccessToken($_GET['code']);
}

function addWithDupCheck($infusionsoft) {
    $contact = array('FirstName' => 'John', 'LastName' => 'Doe', 'Email' => 'johndoe@mailinator.com');

    return $infusionsoft->contacts->addWithDupCheck($contact, 'Email');
}

/**
 * Make sure to also store the new refresh token every time you request and store a new access token.
 */
if ($infusionsoft->getToken()) {
    try {
        $cid = addWithDupCheck($infusionsoft);
    } catch (\Infusionsoft\TokenExpiredException $e) {
        // If the request fails due to an expired access token, we can refresh
        // the token and then do the request again.
        $infusionsoft->refreshAccessToken();

        $cid = addWithDupCheck($infusionsoft);
    }

    $contact = $infusionsoft->contacts->load($cid, array('Id', 'FirstName', 'LastName', 'Email'));

    // var_dump($contact);

    // Save the serialized token to the current session for subsequent requests
    $_SESSION['token'] = serialize($infusionsoft->getToken());

    // Store serialized token in the WP options table
    update_option( 'fj_infusionsoft_api_token', $_SESSION['token'] );
} else {
    /**
     * Request permission by signing in to Infusionsoft
     *
     * @see https://developer.infusionsoft.com/docs/xml-rpc/#authentication-request-permission
     */
    echo '<a href="' . $infusionsoft->getAuthorizationUrl() . '">Click here to authorize</a>';
}
