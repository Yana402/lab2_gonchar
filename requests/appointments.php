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

if (!isset($_SESSION['user'])) {
    $_SESSION['error'] = "Вы не авторизованы";
    header('Location: ' . $base_url . '/authorization');
    exit();
}

$stmt = '';

if ($_SESSION['user']['role'] == 'c')
    $stmt = $connection->prepare("SELECT * FROM appointments_masters WHERE user_id = ? AND (firstname LIKE ? OR lastname LIKE ?) ORDER BY date_time");
else if ($_SESSION['user']['role'] == 'm')
    $stmt = $connection->prepare("SELECT * FROM appointments_clients WHERE master_id = ? ORDER BY date_time");


if ($stmt) {
    if ($_SESSION['user']['role'] == 'c'){
        $name = '%';
        if (isset($_COOKIE['search']) && !isset($_GET['name'])){
            $name = sodium_crypto_aead_aes256gcm_decrypt($_COOKIE['search'], 'search', '123456654321', $_SESSION['cookie_key']);              
        } else {
            if (isset($_GET['name'])){
                $name = $_GET['name'];      
                $cipher = sodium_crypto_aead_aes256gcm_encrypt($name, 'search', '123456654321', $_SESSION['cookie_key']);  
                setcookie('search', $cipher, time() + 86400, '/');
            }
        }

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
