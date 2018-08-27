<?php
$admin_require = true; require($_SERVER['DOCUMENT_ROOT'] . "/init.php");

login_admin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_val($_POST['CSRFtoken']);

    switch ($_POST['type']) {
        case 'gen':
            switch ($_POST['gen']) {
                case 'register':
                    $token = gen(128);
                    $current_date = current_date(true);
                    $user = clean_data($_POST['user']);
                    $ip = ip();

                    $query =
                    "INSERT INTO
                        tokens
                            ('token','type','created', 'days_valid','user','gen_ip')
                    VALUES
                        ('{$token}','register','$current_date', '7','$user','$ip')";
                    break;

                default:
                    redirect('/admin', 'Wrong type was passed!');
                    break;
            }
            break;

        default:
            redirect('/admin', 'Wrong type was passed!');
            break;
    }

    redirect('/admin', 'Success');
} else {
    csrf_val($_GET['CSRFtoken']);

    if (empty($_GET['type']) || empty($_GET['id']) || empty($_GET['class'])) {
        redirect('/admin', 'Not all required vars were passed!');
    }

    $type = clean_data($_GET['type']);
    $user_id = clean_data($_GET['id']);
    $user_class = clean_data($_GET['class']);
    $state = clean_data($_GET['state']) ?? 0;

    $sql_table = ($user_class === 'docent') ? 'docenten' : 'leerlingen';

    $value = $state;

    $customQuery = false;

    switch ($type) {
        case 'active':
            $set = 'active';
            break;

        case 'utalent':
            $set = 'utalent';
            break;

        case 'unblock':
            $set = 'failed_login';
            $value = 0;
            break;

        case 'reset':
            //TODO: create reset process (disable account and set password recovery email)
            redirect('/admin', 'Reset function not yet implemented!');
            break;

        case 'delete':
            //TODO: create delete process (delete account and delete steropdrachten)
            redirect('/admin', 'Delete function not yet implemented!');
            break;

        case 'log_clear':
            $customQuery = true;
            $query = "DELETE FROM logs";
            break;

        default:
            redirect('/admin', 'Wrong type was passed!');
            break;
    }

    if (!$customQuery) {
        $query = "UPDATE {$sql_table} SET {$set}='{$value}' WHERE id='{$user_id}'";
    }


    sql_query($query, false);

    redirect('/admin', 'Success');
}
