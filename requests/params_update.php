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

if ($_SESSION['user']['role'] != 'a') {
    $_SESSION['error'] = "Вы не администратор";
    header('Location: ' . $base_url . '/authorization');
    exit();
}

$stmt = $connection->prepare("UPDATE params SET value = (CASE
    WHEN (name = 'service_price_coef') THEN ? 
    WHEN (name = 'master_stage_coef') THEN ? 
    WHEN (name = 'master_rate_coef') THEN ? 
END);");

if ($stmt) {
    $stmt->bind_param("ddd", $_POST['service_price_coef'], $_POST['master_stage_coef'], $_POST['master_rate_coef']);
    if ($stmt->execute()) {
        header('Location: ' . $base_url . '/api/params');
        exit();
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/main');
exit();
