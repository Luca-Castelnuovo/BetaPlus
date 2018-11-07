<?php

return (object) array(
    'app' => (object) array(
        'url' => "https://betasterren.hetbaarnschlyceum.nl",
        'domain' => "betasterren.hetbaarnschlyceum.nl",

        'admin' => (object) array(
            'email' => "lucacastelnuovo@hetbaarnschlyceum.nl",
        ),
    ),

    'security' => (object) array(
        'database' => (object) array(
            'host' => "localhost",
            'user' => "betasterren",
            'password' => 'I0$pge69v2ukoHF9iec1YDuli73racMWXC2JM^&$nQkSsy#M',
            'database' => "betasterren_db",
        ),

        'hmac'=>"XpkTQwW6K5Ni4rf3xpF2gNkmT0zlGJhWDtIXQRbWu3CJ5PJznZy4HzBYhJu0z8h",
    ),

    'api' => (object) array(
        'mail' => (object) array(
            'key' => "rqc4o57337jp9d9ilueflk6rwl5s48ra",
            'url' => "https://api.lucacastelnuovo.nl/mail/",
        ),

        'recaptcha' => (object) array(
            'key' => "ln8k76qgxhbtukg2qu8lbxwsc6lgcveay",
            'url' => "https://api.lucacastelnuovo.nl/recaptcha/",
            'library' => "https://www.google.com/recaptcha/api.js",
        ),

        'imgur' => (object) array(
            'key' => "b2c72661027878c",
            'url' => "/libs/Imgur.php",
        ),
    ),

    'cdn' => (object) array(
        'css' => (object) array(
            'materialize' => (object) array(
                'library' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/css/materialize.css",
                'icons' => "https://fonts.googleapis.com/icon?family=Material+Icons",
            ),

            'main' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/css/style.css",
            'simplemde' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/css/simplemde.css",
            'imgur' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/css/imgur.css",
        ),

        'js' => (object) array(
            'materialize' => (object) array(
                'library' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/js/materialize.js",
                'init' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/js/materialize_init.js",
            ),

            'particle' => (object) array(
                'library' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/js/particles.js",
                'init' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/js/particles_init.js",
            ),

            'simplemde' => (object) array(
                'library' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/js/simplemde.js",
                'init' => '"https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/js/simplemde_init.js"',
            ),

            'ajax' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/js/ajax.js",
            'gen' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/js/gen.js",
            'filter' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/js/filter.js",
        ),

        'images' => (object) array(
            'icons' => (object) array(
                '72x72' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/images/icons/icon-72x72.png",
                '96x96' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/images/icons/icon-96x96.png",
                '128x128' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/images/icons/icon-128x128.png",
                '144x144' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/images/icons/icon-144x144.png",
                '152x152' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/images/icons/icon-512x512.png",
                '192x192' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/images/icons/icon-192x192.png",
                '384x384' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/images/icons/icon-384x384.png",
                '512x512' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/images/icons/icon-512x512.png",
            ),

            'logo' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/hbl/images/logo.png",
            'default_profile' => "https://betaplus.ams3.cdn.digitaloceanspaces.com/images/default_profile.png",
        ),
    ),
);
