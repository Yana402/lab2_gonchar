<?

require_once '../config.php';
require_once './_template.php';

page_top("Страница не найдена");

?>

<div class="fixed w-screen h-screen bg-[url(http://<?= $base_url ?>/static/images/bg.jpg)] bg-center bg-cover bg-no-repeat">
    <h1 class="absolute inset-0 m-auto w-fit h-fit text-6xl text-pink-900 text-center font-bold">Страница <br>не найдена :(</h1>
</div>

<?
page_bottom();

unset($_SESSION['error']);
?>