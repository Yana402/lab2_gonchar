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

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Вы не авторизованы";
    header('Location: ' . $base_url . '/authorization');
    exit();
}

$stmt = '';

if ($_SESSION['user']['role'] == 'c')
    $stmt = $connection->prepare("SELECT appointments.id, date_time, firstname, lastname, speciality, status FROM appointments INNER JOIN masters ON master_id = masters.user_id INNER JOIN users ON master_id = users.id WHERE appointments.user_id = ? AND (users.firstname LIKE ? OR users.lastname LIKE ?) ORDER BY date_time");
else if ($_SESSION['user']['role'] == 'm')
    $stmt = $connection->prepare("SELECT appointments.id, date_time, firstname, lastname, status FROM appointments INNER JOIN users ON user_id = users.id WHERE master_id = ? ORDER BY date_time");


if ($stmt) {
    if ($_SESSION['user']['role'] == 'c'){
        $name = $_GET['name'] ?? '%';
        $stmt->bind_param("iss", $_SESSION['user']['id'], $name, $name);        
    }
    else
        $stmt->bind_param("i", $_SESSION['user']['id']);
    
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        $_SESSION['appointments'] = $result->fetch_all(MYSQLI_ASSOC);

        header('Location: ' . $base_url . '/main');
        exit();
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/main');
exit();
