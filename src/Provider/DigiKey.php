<?php

namespace Bggardner\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use League\OAuth2\Client\Tool\BearerAuthorizationTrait;
use Psr\Http\Message\ResponseInterface;

class DigiKey extends AbstractProvider
{
    use BearerAuthorizationTrait;

    /**
     * Client is in sandbox mode
     *
     * @var string
     */
    protected $isSandbox = false;


    /**
     * Two letter code for Digi-Key product website to search on.
     *
     * @var string
     */

    protected $localeSite;
    /**
     * Two letter code for language to search on.
     * Language must be supported by the selected site.
     *
     * @var string
     */
    protected $localeLanguage;

    /**
     * Three letter code for Currency to return part pricing for.
     * Currency must be supported by the selected site.
     *
     * @var string
     */
    protected $localeCurrency;

    /**
     * Your Digi-Key Customer id.
     *
     * @var string
     */
    protected $customerId;

    /**
     * Creates and returns api base url base on client configuration.
     *
     * @return string
     */
    protected function getApiUrl()
    {
        return (bool) $this->isSandbox ? 'https://sandbox-api.sandbox.paypal.com' : 'https://api.digikey.com';
    }

    /**
     * @inheritdoc
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getApiUrl().'/v1/oauth2/authorize';
    }

    /**
     * @inheritdoc
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getApiUrl().'/v1/oauth2/token';
    }

    /**
     * @inheritdoc
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        throw new Exception('Digi-Key does not support Resource Owners');
    }

    /**
     * @inheritdoc
     */
    public function getDefaultScopes()
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 400) {
            throw new IdentityProviderException(
                $data['ErrorMessage'] ?: $response->getReasonPhrase(),
                $statusCode,
                $response
            );
        }
    }

    /**
     * @inheritdoc
     */
    protected function getDefaultHeaders()
    {
        return array_filter([
            'X-DIGIKEY-Client-Id' => $this->clientId,
            'X-DIGIKEY-Locale-Site' => $this->localeSite,
            'X-DIGIKEY-Locale-Language' => $this->localeLanguage,
            'X-DIGIKEY-Locale-Currency' => $this->localeCurrency,
            'X-DIGIKEY-Customer-Id' => $this->customerId
        ]);
    }

    /**
     * @inheritdoc
     */
    public function createResourceOwner(array $response, AccessToken $token)
    {
        throw new Exception('Digi-Key does not support Resource Owners');
    }
}
