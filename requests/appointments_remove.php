<?

session_start();

require_once '../config.php';
require_once '../utils/database.php';

unset($_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['error'] = '';
    header('Location: ' . $base_url . '/404');
    exit();
}

if ($connection->connect_error) {
    $_SESSION['error'] = "Ошибка соединения с БД";
    header('Location: ' . $base_url . '/main');
    exit();
}

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Вы не авторизованы";
    header('Location: ' . $base_url . '/authorization');
    exit();
}

if ($_SESSION['user']['role'] != 'c') {
    $_SESSION['error'] = "Вы не являетесь клиентом";
    header('Location: ' . $base_url . '/authorization');
    exit();
}

$stmt = $connection->prepare("DELETE FROM appointments WHERE id = ?");

if ($stmt) {
    $stmt->bind_param("i", $_POST['id']);
    if ($stmt->execute()) {
        header('Location: ' . $base_url . '/main');
        exit();
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/main');
exit();
