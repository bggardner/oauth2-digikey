# Digi-Key Provider for OAuth 2.0 Client

This package provides Digi-Key OAuth 2.0 support for the PHP League's [OAuth 2.0 Client](https://github.com/thephpleague/oauth2-client) for use in accessing [Digi-Key API Solutions](https://developer.digikey.com/).

## Installation

To install, use composer:

```
composer require bggardner/oauth2-digikey:dev-master
```

## Usage

Usage is the same as The League's OAuth client, using `\Bggardner\OAuth2\Client\Provider\DigiKey` as the provider.

### Authorization Code Flow

```php
$provider = new Bggardner\OAuth2\Client\Provider\DigiKey([
    'clientId'          => '{digikey-client-id}',
    'clientSecret'      => '{digikey-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url',
    'isSandbox'         => true, // Optional, defaults to false. When true, client uses sandbox urls.
    'localeSite'        => 'US', // Optional, defaults to null. Two letter code for Digi-Key product website to search on.
    'localeLanguage'    => 'en', // Optional, defaults to null. Two letter code for language to search on. Language must be supported by the selected site.
    'localeCurrency'    => 'USD', // Optional, defaults to null. Three letter code for Currency to return part pricing for. Currency must be supported by the selected site.
    'customerId'        => '{digikey-customer-id}', // Optional, defaults to null. Your Digi-Key Customer id. If your account has multiple Customer Ids for different regions, this allows you to select one of them.
]);
```

For further usage of this package please refer to the [core package documentation on "Authorization Code Grant"](https://github.com/thephpleague/oauth2-client#usage).

### Refreshing a Token

```php
$provider = new Bggardner\OAuth2\Client\Provider\DigiKey([
    'clientId'          => '{digikey-client-id}',
    'clientSecret'      => '{digikey-client-secret}',
    'redirectUri'       => 'https://example.com/callback-url'
]);

$accessToken = getAccessTokenFromYourDataStore();

if ($accessToken->hasExpired()) {
    $accessToken = $provider->getAccessToken('refresh_token', [
        'refresh_token' => $accessToken->getRefreshToken()
    ]);

    // Purge old access token and store new access token to your data store.
}
```

For further usage of this package please refer to the [core package documentation on "Refreshing a Token"](https://github.com/thephpleague/oauth2-client#refreshing-a-token).


### Making an API call

The example below makes a request to the [Product Details API](https://developer.digikey.com/products/product-information/partsearch/productdetails):

```php
$request = $provider->getAuthenticatedRequest(
    'GET',
    'https://api.digikey.com/Search/v3/Products/{digikey-part-number}',
    $accessToken
);

# Response returns an associative array
$response = $provider->getParsedResponse($request);
```

## License

The MIT License (MIT). Please see [License File](https://github.com/bggardner/oauth2-digikey/blob/master/LICENSE) for more information.
