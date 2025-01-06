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

if ($_SESSION['user']['role'] != 'm') {
    $_SESSION['error'] = "Вы не мастер";
    header('Location: ' . $base_url . '/authorization');
    exit();
}

if (!isset($_FILES['photo']) || $_FILES['photo']['tmp_name'] == '') {
    $_SESSION['error'] = "Файл не был загружен";
    header('Location: ' . $base_url . '/main');
    exit();
}

$file = $_FILES['photo'];
$info = pathinfo($file['name']);
$ext = strtolower($info['extension']);

$allowed_exts = ['jpg', 'jpeg', 'png'];
if (!in_array($ext, $allowed_exts)) {
    $_SESSION['error'] = "Разрешены только следующие форматы: .jpg, .jpeg, .png";
    header('Location: ' . $base_url . '/main');
    exit();
}

if ($file['size'] > 16777215) {
    $_SESSION['error'] = "Размер изображения не должен превышать 16 Мб";
    header('Location: ' . $base_url . '/main');
    exit();
}

if (!getimagesize($file['tmp_name'])) {
    $_SESSION['error'] = "Файл повреждён или не является изображением";
    header('Location: ' . $base_url . '/main');
    exit();
}

if (!is_dir('../static')) {
    $_SESSION['error'] = 'Директория static не существует';
    header('Location: ' . $base_url . '/main');
    exit();  
} else if (!is_writable('../static')) {
    $_SESSION['error'] = 'Отсутствуют права доступа к директории static';
    header('Location: ' . $base_url . '/main');
    exit();  
} else if (!is_dir('../static/images')) {
    $_SESSION['error'] = 'Директория static/images не существует';
    header('Location: ' . $base_url . '/main');
    exit();  
} else if (!is_writable('../static/images')) {
    $_SESSION['error'] = 'Отсутствуют права доступа к директории static/images';
    header('Location: ' . $base_url . '/main');
    exit();  
} else if (!is_dir('../static/images/masters')) {
    $_SESSION['error'] = 'Директория static/images/masters не существует';
    header('Location: ' . $base_url . '/main');
    exit();  
} else if (!is_writable('../static/images/masters')) {
    $_SESSION['error'] = 'Отсутствуют права доступа к директории static/images/masters';
    header('Location: ' . $base_url . '/main');
    exit();  
}

if (file_exists('../static/images/masters/' . $_SESSION['user']['id'] . '.jpg')){
    if (!unlink('../static/images/masters/' . $_SESSION['user']['id'] . '.jpg')){
        $_SESSION['error'] = "Ошибка удаления прежнего файла";
        header('Location: ' . $base_url . '/main');
        exit();  
    }
}

if (file_exists('../static/images/masters/' . $_SESSION['user']['id'] . '.jpeg')){
    if (!unlink('../static/images/masters/' . $_SESSION['user']['id'] . '.jpeg')){
        $_SESSION['error'] = "Ошибка удаления прежнего файла";
        header('Location: ' . $base_url . '/main');
        exit();  
    }
}

if (file_exists('../static/images/masters/' . $_SESSION['user']['id'] . '.png')){
    if (!unlink('../static/images/masters/' . $_SESSION['user']['id'] . '.png')){
        $_SESSION['error'] = "Ошибка удаления прежнего файла";
        header('Location: ' . $base_url . '/main');
        exit();  
    }
}

if (move_uploaded_file($file['tmp_name'], '../static/images/masters/' . $_SESSION['user']['id'] . '.' . $ext)) {
    header('Location: ' . $base_url . '/main');
    exit();
}

$_SESSION['error'] = "Ошибка обновления файла";
header('Location: ' . $base_url . '/main');
exit();
