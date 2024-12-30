<?

session_start();

require_once '../config.php';
require_once '../utils/database.php';

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

$stmt = $connection->prepare("SELECT * FROM services WHERE name LIKE ?");

if ($stmt) {
    $name = '%';
    if (isset($_GET['name'])) {
        $name = $_GET['name'] . '%';
    }

    $stmt->bind_param("s", $name);
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        $_SESSION['services'] = $result->fetch_all(MYSQLI_ASSOC);

        header('Location: ' . $base_url . '/main');
        exit();
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/main');
exit();
