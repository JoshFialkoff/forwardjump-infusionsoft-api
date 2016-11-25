# ForwardJump Infusionsoft API

This plugin is intended to assist developers with integrating their WordPress installation with the official [Infusionsoft SDK](https://github.com/infusionsoft/infusionsoft-php) using OAuth 2.0 authentication.
The plugin includes the latest version of the officially supported Infusionsoft PHP SDK.

## Requirements
* The Infusionsoft API requires an SSL connection

## Installation

### Upload

1. Download the latest tagged archive (choose the "zip" option).
2. Go to the __Plugins -> Add New__ screen and click the __Upload__ tab.
3. Upload the zipped archive directly.
4. Go to the Plugins screen and click __Activate__.

### Manual

1. Download the latest tagged archive (choose the "zip" option).
2. Unzip the archive.
3. Copy the folder to your `/wp-content/plugins/` directory.
4. Go to the Plugins screen and click __Activate__.

Check out the Codex for more information about [installing plugins manually](http://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation).

## Setup

* You must have an account with Infusionsoft and be able to sign into that account.
* Register for a free [Infusionsoft developers account](https://keys.developer.infusionsoft.com/member/register).
* Obtain the Client ID and Client Secret keys from your Infusionsoft developer's account, which are available at [https://keys.developer.infusionsoft.com/apps/mykeys](https://keys.developer.infusionsoft.com/apps/mykeys).
* Paste the keys into their respective input boxes on the plugin settings page.
* After saving the keys you must click "Click here to authorize" to obtain an access token from Infusionsoft.  If successful, you will be redirected back to the WordPress Dashboard and will see a message that says "Your Infusionsoft Token has been successfully added!".
* Once you have received an Infusionsoft token you can begin making API calls.  

## Usage

Your API calls will be very similar to what is presented in the [Infusionsoft API documentation](https://developer.infusionsoft.com/docs/xml-rpc/ ).  See below for an example usage.

```php
add_action( 'wp_login', 'fj_infusionsoft_api_sample_usage', 10, 2 );
/**
 * Adds a user as an Infusionsoft contact after they log in to WordPress.
 *
 * @param  string   $user_login WP user login
 * @param  object   $user       WP user object
 */
function fj_infusionsoft_api_sample_usage( $user_login, $user ) {
    // Instantiates the Infusionsoft object and ensures that we have a valid access token.
    $infusionsoft = fj_infusionsoft_init();

    // Gather relevant user data
    $user_email = $user->data->user_email;
    $user_first_name = get_userdata( $user->ID )->first_name;
    $user_last_name = get_userdata( $user->ID )->last_name;

    $contact = array(
        'Email'     => $user_email,
        'FirstName' => $user_first_name,
        'LastName'  => $user_last_name
    );

    // Adds the WP user as an Infusionsoft contact if they are not already in Infusionsoft
    $contact_id = $infusionsoft->contacts()->addWithDupCheck( $contact, 'Email' );
}
```

## Credits

Built by [Tim Jensen](https://www.timjensen.us/)
