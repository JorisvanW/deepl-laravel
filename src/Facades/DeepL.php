<?php

namespace JorisvanW\DeepL\Laravel\Facades;

use Illuminate\Support\Facades\Facade;
use JorisvanW\DeepL\Laravel\Wrappers\DeepLApiWrapper;

/**
 * (Facade) Class DeepL.
 *
 * @method static DeepLApiWrapper api()
 */
class DeepL extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'deepl';
    }
}
