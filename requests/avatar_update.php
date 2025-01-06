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
    header('Location: ' . $base_url . '/profile');
    exit();
}

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Вы не авторизованы";
    header('Location: ' . $base_url . '/authorization');
    exit();
}

if (!isset($_FILES['avatar']) || $_FILES['avatar']['tmp_name'] == '') {
    $_SESSION['error'] = "Файл не был загружен";
    header('Location: ' . $base_url . '/profile');
    exit();
}

$file = $_FILES['avatar'];
$info = pathinfo($file['name']);
$ext = strtolower($info['extension']);

$allowed_exts = ['jpg', 'jpeg', 'png'];
if (!in_array($ext, $allowed_exts)) {
    $_SESSION['error'] = "Разрешены только следующие форматы: .jpg, .jpeg, .png";
    header('Location: ' . $base_url . '/profile');
    exit();
}

if ($file['size'] > 16777215) {
    $_SESSION['error'] = "Размер изображения не должен превышать 16 Мб";
    header('Location: ' . $base_url . '/profile');
    exit();
}

if (!getimagesize($file['tmp_name'])) {
    $_SESSION['error'] = "Файл повреждён или не является изображением";
    header('Location: ' . $base_url . '/profile');
    exit();
}

$avatar = $ext . ';base64, ' . base64_encode(file_get_contents($file['tmp_name']));

$stmt = $connection->prepare("UPDATE users SET avatar = ? WHERE id = ?");

if ($stmt) {
    $stmt->bind_param("si", $avatar, $_SESSION['user']['id']);
    if ($stmt->execute()) {
        $_SESSION['user']['avatar'] = $avatar;
        header('Location: ' . $base_url . '/profile');
        exit();
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/profile');
exit();
