<?php

namespace JorisvanW\DeepL\Laravel;

use Illuminate\Contracts\Container\Container;

/**
 * Class DeepLManager.
 */
class DeepLManager
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * DeepLManager constructor.
     *
     * @param Container $app
     *
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    /**
     * @return mixed
     */
    public function api()
    {
        return $this->app['deepl.api'];
    }
}
