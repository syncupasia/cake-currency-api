<?php
return [
    'Cors' => [
        // Set the Access-Control-Allow-Origin header to the allowed origin(s).
        'AllowOrigin' => ['*'],

        // Set the Access-Control-Allow-Headers header to the allowed header(s).
        'AllowHeaders' => ['*'],

        // Set the Access-Control-Allow-Methods header to the allowed method(s).
        'AllowMethods' => ['*'],

        // Set the Access-Control-Allow-Credentials header to true or false.
        'AllowCredentials' => false,

        // Set the Access-Control-Max-Age header to the allowed number of seconds.
        'MaxAge' => 3600,
    ],
];
