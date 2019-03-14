<?php

namespace JorisvanW\DeepL\Laravel\Wrappers;

use JorisvanW\DeepL\Api\DeepLApiClient;
use Illuminate\Contracts\Config\Repository;
use JorisvanW\DeepL\Api\Exceptions\ApiException;
use JorisvanW\DeepL\Api\Cons\Translate as TranslateType;

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
    public function translations()
    {
        return $this->client->translations;
    }

    /**
     * Translate a text with DeepL.
     *
     * @param string $text
     * @param string $to
     * @param string $from
     * @param array  $options
     *
     * @return \JorisvanW\DeepL\Api\Resources\BaseResource|\JorisvanW\DeepL\Api\Resources\Translate
     * @throws \JorisvanW\DeepL\Api\Exceptions\ApiException
     */
    public function translate(
        $text,
        $to = TranslateType::LANG_EN,
        $from = TranslateType::LANG_AUTO,
        $options = []
    ) {
        return $this->client->translations->translate($text,
            $to = TranslateType::LANG_EN,
            $from = TranslateType::LANG_AUTO,
            $options = []);
    }
}
