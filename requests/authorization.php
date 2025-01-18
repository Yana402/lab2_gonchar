<?

session_start();

require_once '../config.php';
require_once '../utils/database.php';

unset($_SESSION['error']);

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    $_SESSION['error'] = '';
    header('Location: ' . $base_url . '/404');
    exit();
}

if ($connection->connect_error) {
    $_SESSION['error'] = "Ошибка соединения с БД";
    header('Location: ' . $base_url . '/authorization');
    exit();
}

$stmt = $connection->multi_query("SELECT * FROM users WHERE password = md5('" . $_POST['password'] . "') AND email = '" . $_POST['email'] . "'");

if ($result = $connection->store_result()) {
    if ($result->num_rows == 0) {
        $_SESSION['error'] = "Неправильный логин/пароль";
        header('Location: ' . $base_url . '/authorization');
        exit();
    }
    $_SESSION['user'] = $result->fetch_assoc();
    $_SESSION['cookie_key'] = substr(md5($_SESSION['user']['id'] . $_SESSION['user']['email']), 0, 32);
    $_SESSION['user']['avatar'] = $_SESSION['user']['avatar'] == '' ? null : $_SESSION['user']['avatar'];

    $data = json_encode([
        'firstname' => $_SESSION['user']['firstname'],
        'lastname' => $_SESSION['user']['lastname'],
    ]);
    
    $cipher = sodium_crypto_aead_aes256gcm_encrypt($data, 'user', '123456654321', $_SESSION['cookie_key']);
    setcookie('user', $cipher, time() + 86400, '/');

    header('Location: ' . $base_url . '/main');
    exit();
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/authorization');
exit();
