<?php

if (!function_exists('deepl')) {
    function deepl()
    {
        return app('deepl.api');
    }
}
