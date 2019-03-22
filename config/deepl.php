<?php

return [

    'key' => env('DEEPL_KEY'),


    // DeepL sometimes adds spaces around chars (mostly when it is more then one sentence)
    // Leaving empty array wil not trim

    'trim' => [
        'space_before_char'   => ['?', '!', '%', ':'],
        'spaces_between_char' => ['"', '\''], // Like     The spaceman said " rocket " and left.   to    The spaceman said "rocket" and left.
    ],

];
