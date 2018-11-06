<?php

$primary = '#' . $_GET['primary'];
$primary_darken = '#' . $_GET['primary_darken'];

$secondary = '#' . $_GET['secondary'];
$secondary_darken = '#' . $_GET['secondary_darken'];
$secondary_lighten = $_GET['secondary_lighten'];

$tertiary = '#' . $_GET['tertiary'];
$ertiary_darken = '#' . $_GET['tertiary_darken'];

header('Content-Type: text/css');

echo <<<END
.color-primary{color:{$primary} !important}.color-primary--background{background-color:{$primary} !important}.color-primary--background:hover:not(.nav-wrapper):not(.hover-disable){background-color:{$primary_darken} !important}.color-primary--border{border-color:{$primary} !important}.color-primary--border:hover:not(.hover-disable){border-color:{$primary_darken} !important}.color-secondary{color:{$secondary} !important}.color-secondary--background{background-color:{$secondary} !important}.color-secondary--background:hover:not(.hover-disable){background-color:{$secondary_darken} !important}.color-tertiary{color:{$tertiary} !important}.color-tertiary--background{background-color:{$tertiary} !important}.color-tertiary--background:hover:not(.hover-disable){background-color:{$ertiary_darken} !important}@media only screen and (max-width: 600px){.col.s12{margin-top:5px}}@media only screen and (min-width: 601px) and (max-width: 992px){.col.m12{margin-top:5px}}@media only screen and (min-width: 993px) and (max-width: 1200px){.col.l12{margin-top:5px}}@media only screen and (min-width: 1201px){.col.xl12{margin-top:5px}}.brand-logo{padding-left:15px !important}.card{height:auto !important;width:250px}.card-reveal{overflow-y:hidden !important}.card-reveal--links li{width:100%;margin-bottom:10px}.card-reveal--links li a{display:block;color:inherit;text-decoration:inherit}.card .card-image img{height:15rem}@media only screen and (max-width: 1500px){.card{width:220px}}@media only screen and (max-width: 1250px){.card{width:200px}}@media only screen and (max-width: 992px){.card{width:300px}}@media only screen and (max-width: 725px){.card{width:250px}}@media only screen and (max-width: 256px){.card{width:170px}}.bold{font-weight:bold}.transform-uppercase{text-transform:uppercase}.width-full{width:100%}.margin-top-5{margin-top:5px}.login{margin-top:5rem;width:auto}@media only screen and (max-width: 600px){.login{margin-top:0}}.background{position:absolute;top:0;bottom:0;left:0;right:0;z-index:-1}.input-field input:focus{border-bottom:1px solid {$primary} !important;box-shadow:0 1px 0 0 {$primary} !important}.input-field input:focus+label{color:{$primary} !important}.input-field input.valid{border-bottom:1px solid {$primary} !important;box-shadow:0 1px 0 0 {$primary} !important}.input-field input.invalid{border-bottom:1px solid {$secondary} !important;box-shadow:0 1px 0 0 {$secondary} !important}.tabs .tab a{color:{$secondary_lighten}}.tabs .indicator{background-color:{$secondary}}.tabs .tab a:hover,.tabs .tab a.active{background-color:transparent;color:#000}
END;

//HBL Url
//https://cdn.lucacastelnuovo.nl/php/css.php?primary=003d14&primary_darken=00270d&secondary=7a0036&secondary_darken=4f0023&secondary_lighten=rgba(122,0,54,0.7)&tertiary=FAA817&tertiary_darken=ae7003
