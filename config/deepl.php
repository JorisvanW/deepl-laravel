<?php

return [

    'key' => env('DEEPL_KEY'),

    // Should be true in most cases, as it prevents :name and @name to be translated (Like to :naam @naam).
    'translate_keys' => true,

    // A list of words you do not want to be translared.
    // For example you business name.
    'prevent_translation_words' => [],

    // DeepL sometimes adds spaces around chars (mostly when it is more then one sentence)
    // Leaving empty array wil not trim

    'trim' => [
        'space_before_char'   => ['?', '!', '%', ':'],
        'spaces_between_char' => ['"', '\''], // Like     The spaceman said " rocket " and left.   to    The spaceman said "rocket" and left.
    ],

];
