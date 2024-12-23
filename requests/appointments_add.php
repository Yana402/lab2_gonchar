
<?

session_start();

require_once '../config.php';
require_once '../utils/database.php';
require_once '../utils/validator.php';

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

$stmt = $connection->prepare("INSERT INTO appointments (user_id, date_time, master_id) VALUES (?, ?, ?)");

if ($stmt) {
    $stmt->bind_param("isi", $_SESSION['user']['id'], $_POST['date_time'], $_POST['master_id']);
    if ($stmt->execute()) {
        header('Location: ' . $base_url . '/main');
        exit();
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/main');
exit();
