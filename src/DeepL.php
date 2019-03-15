<?php

namespace JorisvanW\DeepL\Laravel;

use Illuminate\Contracts\Container\Container;

class DeepL
{
    /**
     * @var Container
     */
    protected $app;

    /**
     * DeepL constructor.
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
