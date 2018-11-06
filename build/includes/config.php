<?php

return (object) array(
    'app' => array(
        'url' => "https://betasterren.hetbaarnschlyceum.nl",

        'admin' => array(
            'email' => "lucacastelnuovo@hetbaarnschlyceum.nl",
        ),
    ),

    'security' => array(
        'hmac' => array(
            'key'=>"XpkTQwW6K5Ni4rf3xpF2gNkmT0zlGJhWDtIXQRbWu3CJ5PJznZy4HzBYhJu0z8h",
        ),

        'database' => array(
            'host' => "localhost",
            'user' => "betasterren",
            'password' => "I0$pge69v2ukoHF9iec1YDuli73racMWXC2JM^&$nQkSsy#M",
            'database' => "betasterren_db",
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
        ),

        'images' => array(
            'logo' => "https://cdn.lucacastelnuovo.nl/images/betasterren/logo.png",
            'default_profile' => "https://cdn.lucacastelnuovo.nl/images/betasterren/default_profile.png",
            'hack_attempt' => "https://cdn.lucacastelnuovo.nl/images/dont-hack.png",
        ),
    ),
);
