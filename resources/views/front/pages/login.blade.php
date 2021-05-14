<!doctype html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Giriş - Oyuneks</title>
</head>
<body>
<form method="post">
    @csrf
    <input type="text" name="email">
    <br>
    <input type="password" name="password">
    <br>
    <label>
        <input type="checkbox" name="remember">
        Beni Hatırla
    </label>
    <br>
    <input type="submit">
</form>
</body>
</html>
