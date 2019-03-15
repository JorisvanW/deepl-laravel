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
     * Translate a collection of translations with \JorisvanW\DeepL\Api\Resources\Translate items from DeepL.
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
        $regexTemp = [];

        // Prevent translating the :keys (and make it cheaper)
        if (!array_get($options, 'translate_keys', false) && preg_match_all('~(:\w+)~', $text, $matches, PREG_PATTERN_ORDER)) {
            foreach ($matches[1] as $key => $word) {
                $regexTemp["_{$key}"] = $word;
            }

            $text = str_replace(array_values($regexTemp), array_keys($regexTemp), $text);
        }

        $response = $this->client->translations->translate($text, $to, $from, $options = []);

        if (!empty($regexTemp)) {
            foreach ($response->translations as $key => $translation) {
                /** @var  text */
                $response->translations[$key]->text = str_replace(array_keys($regexTemp), array_values($regexTemp), $translation->text);
            }
        }

        return $response;
    }

    /**
     * Translate a text with DeepL.
     *
     * @param string $text
     * @param string $to
     * @param string $from
     * @param array  $options
     *
     * @return string
     * @throws \JorisvanW\DeepL\Api\Exceptions\ApiException
     */
    public function translateText(
        $text,
        $to = TranslateType::LANG_EN,
        $from = TranslateType::LANG_AUTO,
        $options = []
    ) {
        return $this->translate($text, $to, $from, $options, true)->translations[0]->text;
    }
}
