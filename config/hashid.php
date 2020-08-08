<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hashid Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the hashid drivers or connections below you
    | wish to use as your default connection for all hashid work. Of course
    | you may use many connections at once using the Hashid manager.
    |
    */

    'default' => env('HASHID_CONNECTION', 'optimus'),

    /*
    |--------------------------------------------------------------------------
    | Hashid Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the hashid connections setup for your application.
    | Of course, examples of configuring each hashid driver is shown below
    | to make development simple. You are free to add more.
    |
    | Built-in drivers: "base62", "base62_integer", "base64", "base64_integer",
    | "hashids", "hashids_hex", "hashids_integer", "hashids_string",
    | "hex", "hex_integer", "optimus".
    |
    */

    'connections' => [
        'optimus' => [
            'driver' => 'optimus',
            'prime' => env('OPTIMUS_PRIME'),
            'inverse' => env('OPTIMUS_INVERSE'),
            'random' => env('OPTIMUS_RANDOM', 0),
        ],
    ],

];
