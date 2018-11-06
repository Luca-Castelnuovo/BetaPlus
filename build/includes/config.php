<?php

return (object) array(
    'app' => array(
        'url' => "https://betasterren.hetbaarnschlyceum.nl",

        'admin' => array(
            'email' => "lucacastelnuovo@hetbaarnschlyceum.nl",
        ),
    ),

    'security' => array(
        'database' => array(
            'host' => "localhost",
            'user' => "betasterren",
            'password' => "I0$pge69v2ukoHF9iec1YDuli73racMWXC2JM^&$nQkSsy#M",
            'database' => "betasterren_db",
        ),

        'hmac' => array(
            'key'=>"XpkTQwW6K5Ni4rf3xpF2gNkmT0zlGJhWDtIXQRbWu3CJ5PJznZy4HzBYhJu0z8h",
        ),
    ),

    'api' => array(
        'mail' => array(
            'key' => "rqc4o57337jp9d9ilueflk6rwl5s48ra",
            'url' => "https://api.lucacastelnuovo.nl/mail/",
        ),

        'recaptcha' => array(
            'key' => "ln8k76qgxhbtukg2qu8lbxwsc6lgcveay",
            'url' => "https://api.lucacastelnuovo.nl/recaptcha/",
            'library' => "https://www.google.com/recaptcha/api.js",
        ),

        'imgur' => array(
            'key' => "b2c72661027878c",
        ),
    ),

    'cdn' => array(
        'css' => array(
            'materialize' => array(
                'library' => "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css",
                'library_icons' => "https://fonts.googleapis.com/icon?family=Material+Icons",
            ),

            'main' => "https://cdn.lucacastelnuovo.nl/css/betasterren/style.css",
            'simplemde' => "https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css",
            'imgur' => "https://cdn.lucacastelnuovo.nl/css/betasterren/imgur.css",
        ),

        'js' => array(
            'materialize' => array(
                'library' => "https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js",
                'init' => "https://cdn.lucacastelnuovo.nl/js/betasterren/init.js",
            ),

            'particle' => array(
                'library' => "https://cdnjs.cloudflare.com/ajax/libs/particlesjs/2.2.2/particles.min.js",
                'init' => "https://cdn.lucacastelnuovo.nl/js/betasterren/particles.js",
            ),

            'simplemde' => array(
                'library' => "https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js",
                'init' => "https://cdn.lucacastelnuovo.nl/js/betasterren/particles.js",
            ),

            'ajax' => "https://cdn.lucacastelnuovo.nl/js/ajax.js",
            'gen' => "https://cdn.lucacastelnuovo.nl/js/betasterren/gen.6.js",
            'filter' => "https://cdn.lucacastelnuovo.nl/js/betasterren/filter.2.js",
            'imgur' => "https://cdn.lucacastelnuovo.nl/js/betasterren/imgur.php.4.js?response_url=/general/upload.php&type=",
        ),

        'images' => array(
            'icons' => array(
                '72x72' => "https://cdn.lucacastelnuovo.nl/images/betasterren/icons/icon-72x72.png",
                '96x96' => "https://cdn.lucacastelnuovo.nl/images/betasterren/icons/icon-96x96.png",
                '128x128' => "https://cdn.lucacastelnuovo.nl/images/betasterren/icons/icon-128x128.png",
                '144x144' => "https://cdn.lucacastelnuovo.nl/images/betasterren/icons/icon-144x144.png",
                '152x152' => "https://cdn.lucacastelnuovo.nl/images/betasterren/icons/icon-152x152.png",
                '192x192' => "https://cdn.lucacastelnuovo.nl/images/betasterren/icons/icon-192x192.png",
                '384x384' => "https://cdn.lucacastelnuovo.nl/images/betasterren/icons/icon-384x384.png",
                '512x512' => "https://cdn.lucacastelnuovo.nl/images/betasterren/icons/icon-512x512.png",
            ),

            'logo' => "https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png",
            'default_profile' => "https://cdn.lucacastelnuovo.nl/images/betasterren/default_profile.png",
            'hack_attempt' => "https://cdn.lucacastelnuovo.nl/images/dont-hack.png",
        ),
    ),
);
