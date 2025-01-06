<?

session_start();

require_once '../config.php';
require_once '../utils/database.php';

unset($_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] != 'GET') {
    $_SESSION['error'] = '';
    header('Location: ' . $base_url . '/404');
    exit();
}

if ($connection->connect_error) {
    $_SESSION['error'] = "Ошибка соединения с БД";
    header('Location: ' . $base_url . '/main');
    exit();
}

if ($_SESSION['user']['role'] != 'a') {
    $_SESSION['error'] = "Вы не администратор";
    header('Location: ' . $base_url . '/authorization');
    exit();
}

$stmt = $connection->prepare("SELECT * FROM params");

if ($stmt) {
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        $_SESSION['params'] = $result->fetch_all(MYSQLI_ASSOC);

        header('Location: ' . $base_url . '/main');
        exit();
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/main');
exit();
