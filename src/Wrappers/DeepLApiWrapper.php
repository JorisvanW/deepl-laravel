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
        if (array_get($options, 'translate_keys', true)) {
            if (preg_match_all('~(:\w+)~', $text, $matches, PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $word) {
                    $regexTemp["_100{$key}"] = $word;
                }
            }

            if (preg_match_all('~(@\w+)~', $text, $matches, PREG_PATTERN_ORDER)) {
                foreach ($matches[1] as $key => $word) {
                    $regexTemp["_200{$key}"] = $word;
                }
            }
        }

        if (!empty($words = array_get($options, 'prevent_translation_words', []))) {
            foreach ($words as $key => $word) {
                $regexTemp["_100{$key}"] = $word;
            }
        }

        $text = str_replace(array_values($regexTemp), array_keys($regexTemp), $text);

        $response = $this->client->translations->translate($text, $to, $from, $options = []);

        // Trim the text
        foreach ($response->translations as $key => $translation) {
            $response->translations[$key]->text = $this->trimText($translation->text);
        }

        if (!empty($regexTemp)) {
            foreach ($response->translations as $key => $translation) {
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
        return $this->trimText($this->translate($text, $to, $from, $options)->translations[0]->text);
    }

    /**
     * Trim the text.
     *
     * @param $text
     *
     * @return string
     */
    public function trimText($text): string
    {
        $to   = [];
        $from = [];

        if (!empty($trims = array_get($this->config->get('deepl'), 'trim.space_before_char', []))) {
            $from[] = '/\s([' . implode('|', $trims) . '])\s/';
            $to[]   = '${1} ';
            $from[] = '/\s([' . implode('|', $trims) . '])$/';
            $to[]   = '${1}';
        }

        if (!empty($trims = array_get($this->config->get('deepl'), 'trim.spaces_between_char', []))) {
            foreach ($trims as $trim) {
                $to[]   = $trim . '${1}' . $trim;
                $from[] = "/{$trim}\s(.*?)\s{$trim}/";
            }
        }

        if (!empty($from) && !empty($to) && count($from) === count($to)) {
            $text = preg_replace($from, $to, $text);
        }

        return $text;
    }
}
