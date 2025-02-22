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

$stmt = $connection->prepare("SELECT b.id, speciality, firstname, lastname, stage, name, price, total_appointments,
    (SELECT value FROM params WHERE name = 'service_price_coef') * price + 
    (SELECT value FROM params WHERE name = 'master_stage_coef') * stage + 
    (SELECT value FROM params WHERE name = 'master_rate_coef') * rate AS total_rate 
    FROM (
        SELECT users.id, speciality, firstname, lastname, stage, total_appointments, total_appointments / (SELECT COUNT(*) FROM appointments) AS rate, name, price 
        FROM masters INNER JOIN (
            SELECT master_id, COUNT(*) AS total_appointments FROM users 
            INNER JOIN appointments ON appointments.master_id = users.id GROUP BY master_id
        ) a 
        ON a.master_id = masters.user_id 
        INNER JOIN users ON users.id = masters.user_id
        INNER JOIN master_service ON users.id = master_service.master_id
        INNER JOIN services ON services.id = master_service.service_id
    ) b 
    ORDER BY total_rate DESC;");

if ($stmt) {
    if ($stmt->execute()) {
        $result = $stmt->get_result();

        $_SESSION['masters'] = $result->fetch_all(MYSQLI_ASSOC);

        header('Location: ' . $base_url . '/main');
        exit();
    }
}

$_SESSION['error'] = "Ошибка выполнения запроса";
header('Location: ' . $base_url . '/main');
exit();
