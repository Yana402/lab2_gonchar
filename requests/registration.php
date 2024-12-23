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
    header('Location: ' . $base_url . '/registration');
    exit();
}

if ($_POST['password'] != $_POST['password_confirm']) {
    $_SESSION['error'] = "Пароли не совпадают";
    header('Location: ' . $base_url . '/registration');
    exit();
}

$stmt = $connection->prepare("SELECT * FROM users WHERE email = ?");

if ($stmt) {
    $stmt->bind_param("s", $_POST['email']);

    if ($stmt->execute()) {
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $_SESSION['error'] = "Пользователь с данной почтой уже существует";
            header('Location: ' . $base_url . '/registration');
            exit();
        }

        $error = validate([
            $_POST['firstname'],
            $_POST['lastname'],
            $_POST['email'],
            $_POST['password']
        ], [
            '^[A-Za-zА-Яа-я]+$',
            '^[A-Za-zА-Яа-я]+$',
            '^[A-Za-z0-9\.]+@[a-z]+\.[a-z]{1,5}$',
            '^[A-Za-z0-9\.!@#$%^&*]+$'
        ], [
            'Имя должно содержать только буквы латинского/русского алфавитов и иметь длину от 1 до 32 символов',
            'Фамилия должна содержать только буквы латинского/русского алфавитов и иметь длину от 1 до 32 символов',
            'E-mail должен удовлетворять шаблону ^[A-Za-z0-9\.]+@[a-z]+\.[a-z]{1,5}$ и иметь длину от 8 до 64 символов',
            'Пароль может состоять из букв латинского алфавита, цифр, спецсимволов и иметь длину от 8 до 32 символов'
        ]);

        if (!is_null($error)) {
            $_SESSION['error'] = $error;
            header('Location: ' . $base_url . '/registration');
            exit();
        }
        $stmt = $connection->prepare("INSERT INTO users (firstname, lastname, email, password, role) VALUES (?, ?, ?, md5(?), 'c')");
        $stmt->bind_param("ssss", $_POST['firstname'], $_POST['lastname'], $_POST['email'], $_POST['password']);
        if ($stmt->execute()) {
            if ($stmt->insert_id > 0) {
                header('Location: ' . $base_url . '/authorization');
                exit();
            } else {
                $_SESSION['error'] = 'Ошибка добавления пользователя';
                header('Location: ' . $base_url . '/registration');
                exit();
            }
        }
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/registration');
exit();
