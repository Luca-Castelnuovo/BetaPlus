<?php

//Header
function head($title, $active_menu_item = null, $differen_menu_title = null)
{
    echo <<<END
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!--Import Materialize CSS-->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">

    <link rel="stylesheet" href="/css/style.css">

    <!--Import Google Icon Font-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <title>{$title}</title>
</head>
END;

    $menu_title = $differen_menu_title ?? $title;

    $active_menu_item = $active_menu_item ?? null;

    menu($active_menu_item, $menu_title);
}

//Navbar
function menu($active_menu_item, $menu_title) //0 = Home, 1 = Leerlingen, 2 = Steropdrachten
{
    switch ($active_menu_item) {
        case 0:
            $home = 'class="active"';
            $students = null;
            $steropdrachten = null;
            break;

        case 1:
            $home = null;
            $students = 'class="active"';
            $steropdrachten = null;
            break;

        case 2:
            $home = null;
            $students = null;
            $steropdrachten = 'class="active"';
            break;

        default:
            $home = null;
            $students = null;
            $steropdrachten = null;
    }

    echo <<<END
<body>
    <nav>
        <div class="nav-wrapper color-primary--background">
            <a href="#" class="brand-logo">{$menu_title}</a>
            <a href="#" data-target="menu-mobile" class="right sidenav-trigger"><i class="material-icons">menu</i></a>
            <ul class="right hide-on-med-and-down">
                <li {$steropdrachten}><a href="/ster-opdrachten/"><i class="material-icons tooltipped" data-tooltip="Steropdrachten">folder_special</i></a></li>
                <li {$students}><a href="/leerlingen/"><i class="material-icons tooltipped" data-tooltip="Leerlingen">folder_shared</i></a></li>
                <li {$home}><a href="/general/home"><i class="material-icons tooltipped" data-tooltip="Home">assignment_ind</i></a></li>
                <li><a href="" class="dropdown-trigger" data-target="menu-desktop"><i class="material-icons">more_vert</i></a></li>
            </ul>
        </div>

        <ul id="menu-desktop" class="dropdown-content">
            <li><a href="/general/agenda"><span class="black-text">Agenda</span></a></li>
            <li><a href="/general/pdf/3947192837928739812"><span class="black-text">Schema</span></a></li>
        </ul>

        <ul id="menu-mobile" class="sidenav">
            <li><a href="/general/home">Home</a></li>
            <li><a href="/leerlingen/">Leerlingen</a></li>
            <li><a href="/ster-opdrachten/">Steropdrachten</a></li>
            <li><a href="/general/agenda">Agenda</a></li>
            <li><a href="/general/pdf/3947192837928739812">Jaarschema</a></li>
        </ul>
    </nav>
END;
}

function footer($extra = null)
{
    echo <<<END
    <!--Init Materialize Navbar Components-->
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var elems = document.querySelectorAll(".sidenav");
            var instances = M.Sidenav.init(elems, {
                edge: "right",
                draggable: true
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            var elems = document.querySelectorAll(".dropdown-trigger");
            var instances = M.Dropdown.init(elems, {
                alignment: "right",
                hover: true,
                coverTrigger: false
            });
        });

        document.addEventListener("DOMContentLoaded", function () {
            var elems = document.querySelectorAll(".tooltipped");
            var instances = M.Tooltip.init(elems, {});
        });
    </script>
    <!--Import Materialize JavaScript-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
END;
    echo $extra;
    alert_display();
    echo <<<END
</body>

</html>
END;
}
