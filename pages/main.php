<?php

session_start();

require_once '../config.php';
require_once './_template.php';

page_top("Главная");

if (!isset($_SESSION['user'])){
    header('Location: authorization');
    exit();
}

if (!isset($_SESSION['services']) && $_SESSION['user']['role'] != 'a') {
    header('Location: api/services');
    exit();
}

if (!isset($_SESSION['params']) && $_SESSION['user']['role'] == 'a') {
    header('Location: api/params');
    exit();
}

if (!isset($_SESSION['masters']) && $_SESSION['user']['role'] != 'm') {
    header('Location: api/masters');
    exit();
}

if (!isset($_SESSION['appointments']) && $_SESSION['user']['role'] != 'a') {
    header('Location: api/appointments');
    exit();
}

if (isset($_COOKIE['user'])){
    $data = sodium_crypto_aead_aes256gcm_decrypt($_COOKIE['user'], 'user', '123456654321', $_SESSION['cookie_key']);  
    $data = json_decode($data, true);  
}

?>

<script src="<?= $base_url ?>/static/scripts/date_validator_min.js"></script>
<script src="<?= $base_url ?>/static/scripts/file_validator_min.js"></script>

<div class="min-h-screen bg-[url(<?= $base_url ?>/static/images/bg.jpg)] bg-center bg-cover bg-no-repeat">
    <div class="fixed inset-0 bg-white opacity-60"></div>
    <div class="relative min-h-screen flex flex-col">
        <header class="flex h-16 bg-white p-5 justify-between items-center border-b-[1px] border-b-amber-100">
            <a href="api/leave" class="text-pink-900 hover:text-amber-600">Выйти</a>
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] == 'c'): ?>
                    <form method="get" action="api/appointments">
                        <input name="name" type="search" class="w-[280px] mr-4 border-b-2 border-black outline-none box-border text-xl focus:border-b-2 focus:border-pink-800" placeholder="Введите имя мастера для поиска" maxlength="256" />
                        <button class="rounded-xl w-fit h-fit p-2 bg-slate-100 hover:text-amber-500 hover:bg-slate-200" type="submit">Найти</button>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
            <div class="flex items-center">
                <img width="50" height="50" class="rounded-full" 
                    src="data:image/<?= $_SESSION['user']['avatar'] ?? 'jpeg;base64,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' ?>" 
                />
                <a href="profile" class="hover:text-amber-400"><?= ($data['firstname'] ?? '') . ' ' . ($data['lastname'] ?? '') ?></a>
            </div>        
        </header>
        
        <main class="flex-1 overflow-auto">
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] == 'c'): ?>
                    <div class="grid grid-cols-3 w-fit gap-6 inset-0 mx-auto mt-[3000px]">
                        <?php foreach ($_SESSION['masters'] as $master): ?>
                            <?
                                $alt = '';
                                $file = null;

                                error_reporting(E_ERROR);

                                $path = '../static/images/masters/' . $master['id'];

                                if (!is_dir('../static')) {
                                    $alt = 'Директория static не существует';
                                } else if (!is_readable('../static')) {
                                    $alt = 'Отсутствуют права доступа к директории static';
                                } else if (!is_dir('../static/images')) {
                                    $alt = 'Директория static/images не существует';
                                } else if (!is_readable('../static/images')) {
                                    $alt = 'Отсутствуют права доступа к директории static/images';
                                } else if (!is_dir('../static/images/masters')) {
                                    $alt = 'Директория static/images/masters не существует';
                                } else if (!is_readable('../static/images/masters')) {
                                    $alt = 'Отсутствуют права доступа к директории static/images/masters';
                                } else if (!file_exists($path . '.jpg') && !file_exists($path . '.jpeg') && !file_exists($path . '.png')) {
                                    $alt = 'Файл отсутствует';
                                } else if (!is_readable($path . '.jpg') && !is_readable($path . '.jpeg') && !is_readable($path . '.png')) {
                                    $alt = 'Отсутствуют права доступа к файлу';
                                } else if (!getimagesize($path . '.jpg') && !getimagesize($path . '.jpeg') && !getimagesize($path . '.png')) {
                                    $alt = 'Файл поврежден или не является изображением';
                                } else {
                                    if (file_exists('../static/images/masters/' . $master['id'] . '.jpg')){
                                        $file = 'masters/' . $master['id'] . '.jpg';
                                    }
                                    
                                    if (file_exists('../static/images/masters/' . $master['id'] . '.jpeg')){
                                        $file = 'masters/' . $master['id'] . '.jpeg';
                                    }
                                    
                                    if (file_exists('../static/images/masters/' . $master['id'] . '.png')){
                                        $file = 'masters/' . $master['id'] . '.png';
                                    }
                                }
                            ?>
                            <form method="post" class="flex flex-col rounded-xl p-5 w-fit h-fit bg-white" action="api/appointments/add" onsubmit="return validateDate(this)">
                                <img loading="lazy" class="w-[200px] h-[200px]" width="200" height="200" alt="Фото по-умолчанию недоступно" src="<?= $base_url ?>/static/images/<?= $file ?? 'profile.jpg' ?>" title="<?= $alt ?>">
                                <h3 class="text-center text-pink-900"><?= $master['firstname'] . ' ' . $master['lastname'] ?></h3>
                                <input name="date_time" type="datetime-local" required />
                                <input name="master_id" value="<?= $master['id'] ?>" type="hidden" readonly />
                                <p>Рейтинг: <?= number_format($master['total_rate'], 2) ?></p>
                                <p>Стаж: <?= $master['stage'] . ' ' . ($master['stage'] < 5 ? 'год(а)' : 'лет') ?></p>
                                <p>Услуга: <?= $master['name'] ?></p>
                                <button type="submit" class="rounded-xl inset-0 m-auto p-3 bg-slate-100 hover:bg-slate-200 mt-5">Записаться</button>
                            </form>
                        <?php endforeach; ?>
                    </div>

                    <table class="w-3/4 inset-0 m-auto mt-10">
                        <thead class="bg-slate-400">
                            <tr>
                                <th>Дата и время</th>
                                <th>Мастер</th>
                                <th>Специализация</th>
                                <th>Статус</th>
                                <th>Действие</th>
                            </tr>
                        </thead>
                        <tbody class="bg-slate-100">
                            <?php foreach ($_SESSION['appointments'] as $appointment): ?>
                                <tr>
                                    <td class="text-center"><?= $appointment['date_time'] ?></td>
                                    <td class="text-center"><?= $appointment['firstname'] . ' ' . $appointment['lastname'] ?></td>
                                    <td class="text-center"><?= $appointment['speciality'] ?></td>
                                    <td class="text-center"><?= $appointment['status'] == 'w' ? 'Ожидание' : ($appointment['status'] == 's' ? 'Подтверждено' : 'Отказано') ?></td>
                                    <td class="text-center">
                                        <form method="post" action="api/appointments/remove">
                                            <input name="id" value="<?= $appointment['id'] ?>" readonly hidden />
                                            <button class="text-pink-900 hover:text-amber-500" type="submit">Удалить</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php elseif ($_SESSION['user']['role'] == 'm'): ?>
                    <table class="w-3/4 inset-0 m-auto mt-10">
                        <thead class="bg-slate-400">
                            <tr>
                                <th>Дата и время</th>
                                <th>Клиент</th>
                                <th>Статус</th>
                            </tr>
                        </thead>
                        <tbody class="bg-slate-100">
                            <?php foreach ($_SESSION['appointments'] as $appointment): ?>
                                <tr>
                                    <td class="text-center"><?= $appointment['date_time'] ?></td>
                                    <td class="text-center"><?= $appointment['firstname'] . ' ' . $appointment['lastname'] ?></td>
                                    <?php if ($appointment['status'] != 'w'): ?>
                                        <td class="text-center">
                                            <?= $appointment['status'] == 's' ? 'Подтверждено' : 'Отказано' ?>
                                        </td>
                                    <?php else: ?>
                                        <td class="text-center">
                                            <form method="post" action="api/appointments/submit">
                                                <input name="id" value="<?= $appointment['id'] ?>" readonly hidden />
                                                <button class="text-pink-900 hover:text-amber-500" type="submit">Подтвердить</button>
                                            </form>
                                            <form method="post" action="api/appointments/reject">
                                                <input name="id" value="<?= $appointment['id'] ?>" readonly hidden />
                                                <button class="text-red-700 hover:text-amber-500" type="submit">Отказать</button>
                                            </form>
                                        </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <form onsubmit="validateFile(event, 'photo')" method="POST" action="api/photo/update" enctype="multipart/form-data" class="flex flex-col gap-4 w-96 p-6 bg-white mx-auto mt-20">
                        <h2 class="font-bold text-center text-xl">Фотография мастера</h2>
                        <label for="photo">Файл фотографии</label>
                        <input type="file" name="photo" accept=".png, .jpg, .jpeg" required>
                        <p class="text-red-500 text-center"><?= $_SESSION['error'] ?? '&nbsp;' ?></p>
                        <button type="submit" class="rounded-xl mx-auto hover:bg-slate-100 p-2">Загрузить</button>
                    </form>
                <?php elseif ($_SESSION['user']['role'] == 'a'): ?>
                    <table class="w-3/4 inset-0 m-auto mt-10">
                        <thead class="bg-slate-400">
                            <tr>
                                <th>Мастер</th>
                                <th>Специализация</th>
                                <th>Посещаемость (чел.)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-slate-100">
                            <?php foreach ($_SESSION['masters'] as $master): ?>
                                <tr>
                                    <td class="text-center"><?= $master['firstname'] . ' ' . $master['lastname'] ?></td>
                                    <td class="text-center"><?= $master['speciality'] ?></td>
                                    <td class="text-center"><?= $master['total_appointments'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <form method="POST" action="api/params/update" class="flex flex-col p-5 w-96 mx-auto mt-10 gap-6 bg-white">
                        <h2 class="font-bold text-center">Настройка весов поиска</h2>
                        <label for="service_price_coef">Цена услуги: <span id="service_price_coef"><?= $_SESSION['params'][0]['value'] ?></span></label>
                        <input oninput="this.previousElementSibling.lastChild.innerText = this.value" name="service_price_coef" type='range' value="<?= $_SESSION['params'][0]['value'] ?>" min="-1" max="1" step="0.1" required/>
                        <label for="master_stage_coef">Стаж мастера: <span id="master_stage_coef"><?= $_SESSION['params'][1]['value'] ?></span></label>
                        <input oninput="this.previousElementSibling.lastChild.innerText = this.value" name="master_stage_coef" type='range' value="<?= $_SESSION['params'][1]['value'] ?>" min="-1" max="1" step="0.1" required/>
                        <label for="master_rate_coef">Рейтинг мастера: <span id="master_rate_coef"><?= $_SESSION['params'][2]['value'] ?></span></label>
                        <input oninput="this.previousElementSibling.lastChild.innerText = this.value" name="master_rate_coef" type='range' value="<?= $_SESSION['params'][2]['value'] ?>" min="-1" max="1" step="0.1" required/>
                        <p class="text-red-500 text-center"><?= $_SESSION['error'] ?? '&nbsp;' ?></p>
                        <button type="submit" class="mx-auto">Сохранить</button>
                    </form>
                <?php endif; ?>
            <?php else: ?>
                <h1 class="text-pink-900 text-6xl text-center inset-0 mt-40">Вы не авторизованы</h1>
            <?php endif; ?>
        </main>
    </div>
</div>

<?php
page_bottom();

unset($_SESSION['error']);
unset($_SESSION['services']);
unset($_SESSION['masters']);
unset($_SESSION['appointments']);
?>