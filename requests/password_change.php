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

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] != $_SESSION['csrf_token']){
    $_SESSION['error'] = "Отсутсвует csrf-токен или csrf-токен невалиден";
    header('Location: ' . $base_url . '/profile');
    exit();
}

if (!isset($_POST['password']) || !isset($_POST['password_confirm']) ){
    $_SESSION['error'] = "Отсутствуют данные формы";
    header('Location: ' . $base_url . '/profile');
    exit();
}

if ($_POST['password'] != $_POST['password_confirm']){
    $_SESSION['error'] = "Пароли не совпадают";
    header('Location: ' . $base_url . '/profile');
    exit();
}

$stmt = $connection->prepare("UPDATE users SET password = md5(?) WHERE id = ?");

if ($stmt) {
    $stmt->bind_param("si", $_POST['password'], $_SESSION['user']['id']);
    if ($stmt->execute()) {
        header('Location: ' . $base_url . '/profile');
        exit();
    }
}

$_SESSION['error'] = "Ошибка обновления пароля";
header('Location: ' . $base_url . '/main');
exit();
