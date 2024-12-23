<?php

session_start();

require_once '../config.php';
require_once './_template.php';

page_top("Главная");

if (!isset($_SESSION['services'])) {
    header('Location: api/services');
    exit();
}

if (!isset($_SESSION['masters'])) {
    header('Location: api/masters');
    exit();
}

if (!isset($_SESSION['appointments'])) {
    header('Location: api/appointments');
    exit();
}

?>

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
            <h2><?= ($_SESSION['user']['firstname'] ?? '') . ' ' . ($_SESSION['user']['lastname'] ?? '') ?></h2>
        </header>
        
        <main class="flex-1 overflow-auto">
            <?php if (isset($_SESSION['user'])): ?>
                <?php if ($_SESSION['user']['role'] == 'c'): ?>
                    <div class="grid grid-cols-3 w-fit gap-6 inset-0 mx-auto mt-10">
                        <?php foreach ($_SESSION['masters'] as $master): ?>
                            <form method="post" class="flex flex-col rounded-xl p-5 w-fit h-fit bg-white" action="api/appointments/add" onsubmit="return validateDate(this)">
                                <img src="<?= $base_url ?>/static/images/profile.jpg">
                                <h3 class="text-center text-pink-900"><?= $master['firstname'] . ' ' . $master['lastname'] ?></h3>
                                <input name="date_time" type="datetime-local" required />
                                <input name="master_id" value="<?= $master['id'] ?>" type="hidden" readonly />
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
                <?php endif; ?>
            <?php else: ?>
                <h1 class="text-pink-900 text-6xl text-center inset-0 mt-40">Вы не авторизованы</h1>
            <?php endif; ?>
        </main>
    </div>
</div>

<script>
function validateDate(form) {
    const dateInput = form.querySelector('input[name="date_time"]');
    const dateValue = new Date(dateInput.value);
    const currentDate = new Date();

    // Установим время текущей даты в 00:00:00 для сравнения
    currentDate.setHours(0, 0, 0, 0);

    const currentYear = new Date().getFullYear();
    const minDate = `${currentYear}-01-01T00:00`; // Минимальная дата (начало текущего года)
    const maxDate = `${currentYear + 5}-12-31T23:59`; // Максимальная дата (5 лет в будущем)

    // Проверка на правильность даты
    if (dateInput.value === "" || 
        dateInput.value === "0000-00-00T00:00" || 
        dateInput.value === "9999-12-31T00:00" || 
        dateInput.value < minDate || 
        dateInput.value > maxDate || 
        dateValue < currentDate) {
        alert("Пожалуйста, введите корректную дату и время. Дата должна быть в диапазоне с " + minDate + " по " + maxDate + ", и не может быть в прошлом.");
        return false; // Отменяем отправку формы
    }
    return true; // Разрешаем отправку формы
}
</script>

<?php
page_bottom();

unset($_SESSION['services']);
unset($_SESSION['masters']);
unset($_SESSION['appointments']);
?>