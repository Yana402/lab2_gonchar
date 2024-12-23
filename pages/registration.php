<?

session_start();

require_once '../config.php';
require_once './_template.php';

page_top("Регистрация");

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://';

?>

<div class="fixed w-screen h-screen bg-[url(<?= $base_url ?>/static/images/bg.jpg)] bg-center bg-cover bg-no-repeat">
    <div class="fixed w-screen h-screen bg-white opacity-60"></div>
    <div class="fixed w-screen h-screen">
        <form class="flex flex-col absolute inset-0 m-auto w-80 h-[572px] bg-white border-[1px] border-amber-200" method="post" action="api/registration">
            <h2 class="bg-amber-50 text-pink-900 font-bold text-2xl p-6">Регистрация</h2>
            <div class="flex flex-col m-6 grow justify-between items-center">
                <input name="firstname" class="w-full border-b-2 border-black outline-none box-border text-xl focus:border-b-2 focus:border-pink-800" pattern="^[A-Za-zА-Яа-я]+$" title="Имя должно содержать только буквы латинского/русского алфавитов и иметь длину от 1 до 32 символов" type="text" maxlength="32" minlength="1" placeholder="Имя" required />
                <input name="lastname" class="w-full border-b-2 border-black outline-none box-border text-xl focus:border-b-2 focus:border-pink-800" pattern="^[A-Za-zА-Яа-я]+$" title="Фамилия должно содержать только буквы латинского/русского алфавитов и иметь длину от 1 до 32 символов" type="text" maxlength="32" minlength="1" placeholder="Фамилия" required />
                <input name="email" class="w-full border-b-2 border-black outline-none box-border text-xl focus:border-b-2 focus:border-pink-800" pattern="^[A-Za-z0-9\.]+@[a-z]+\.[a-z]{1,5}$" title="E-mail должен удовлетворять шаблону ^[A-Za-z0-9\.]+@[a-z]+\.[a-z]{1,5}$" type="email" maxlength="64" minlength="8" placeholder="E-mail" required />
                <input name="password" class="w-full border-b-2 border-black outline-none box-border text-xl focus:border-b-2 focus:border-pink-800" pattern="^[A-Za-z0-9\.!@#$%^&*]+$" title="Пароль может состоять из букв латинского алфавита, цифр, спецсимволов и иметь длину от 8 до 32 символов" type="password" maxlength="32" minlength="8" placeholder="Пароль" required />
                <input name="password_confirm" class="w-full border-b-2 border-black outline-none box-border text-xl focus:border-b-2 focus:border-pink-800" type="password" maxlength="32" minlength="8" placeholder="Повторите пароль" required />
                <button class="w-fit h-fit text-pink-800 bg-transparent text-xl hover:text-amber-600" type="submit">Зарегистрироваться</button>
                <p class="text-red-500 text-center"><?= $_SESSION['error'] ?? '&nbsp;' ?></p>
                <p class="text-gray-600">Есть аккаунт? <a class="text-pink-900 hover:text-amber-600" href="./authorization">Авторизоваться</a></p>
            </div>
        </form>
    </div>
</div>

<?
page_bottom();

unset($_SESSION['error']);
?>