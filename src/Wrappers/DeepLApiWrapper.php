<?php

namespace JorisvanW\DeepL\Laravel\Wrappers;

use JorisvanW\DeepL\Api\DeepLApiClient;
use Illuminate\Contracts\Config\Repository;
use JorisvanW\DeepL\Api\Exceptions\ApiException;

/**
 * Class DeepLApiWrapper.
 */
class DeepLApiWrapper
{
    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var DeepLApiClient
     */
    protected $client;

    /**
     * MollieApiWrapper constructor.
     *
     * @param Repository     $config
     * @param DeepLApiClient $client
     *
     * @throws \JorisvanW\DeepL\Api\Exceptions\ApiExcesption
     * @return void
     */
    public function __construct(Repository $config, DeepLApiClient $client)
    {
        $this->config = $config;
        $this->client = $client;

        $this->setApiKey($this->config->get('deepl.key'));
    }

    /**
     * @param string $url
     */
    public function setApiEndpoint($url)
    {
        $this->client->setApiEndpoint($url);
    }

    /**
     * @return string
     */
    public function getApiEndpoint()
    {
        return $this->client->getApiEndpoint();
    }

    /**
     * @param string $api_key The DeepL API key.
     *
     * @throws ApiException
     */
    public function setApiKey($api_key)
    {
        $this->client->setApiKey($api_key);
    }

    /**
     * @return \JorisvanW\DeepL\Api\Endpoints\UsageEndpoint
     */
    public function usage()
    {
        return $this->client->usage;
    }

    /**
     * @return \JorisvanW\DeepL\Api\Endpoints\TranslateEndpoint
     */
    public function translate()
    {
        return $this->client->translate;
    }
}
