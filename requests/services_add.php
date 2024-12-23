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

$error = validate([
    $_POST['name'],
    $_POST['price']
], [
    '^[A-Za-zА-Яа-я\s0-9]+$',
    '^[1-9][0-9]+$'
], [
    'Название услуги должно состоять из одного или нескольких слов русского или латинского алфавита и цифр',
    'Цена должна состоять из цифр'
]);

if (!is_null($error)) {
    $_SESSION['error'] = $error;
    header('Location: ' . $base_url . '/main');
    exit();
}

$stmt = $connection->prepare("INSERT INTO services (name, price) VALUES (?, ?)");

if ($stmt) {
    $stmt->bind_param("si", $_POST['name'], $_POST['price']);
    if ($stmt->execute()) {
        header('Location: ' . $base_url . '/main');
        exit();
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/main');
exit();
