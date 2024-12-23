<?

session_start();

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    $_SESSION['error'] = '';
    header('Location: ' . $base_url . '/404');
    exit();
}

unset($_SESSION['user']);
header('Location: ' . $base_url . '/authorization');
