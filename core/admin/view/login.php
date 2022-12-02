<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WQ-Engine Enter</title>

    <link rel="stylesheet" href="<?=PATH . ADMIN_TEMPLATE . 'css/decoration.css'?>">
    <link rel="stylesheet" href="<?=PATH . ADMIN_TEMPLATE . 'css/login_page_styles.css'?>">

</head>
<body>
<div class="wq-page">

    <main class="wq-main">

        <div class="login">
            <div class="login__wrap">
                <h1 class="login__title">Авторизация</h1>
                <form method="post" action="<?=PATH . ($adminPath ?? 'admin')?>/login" class="login__form">
                    <label for="login" class="login__caption">Имя пользователя</label>
                    <input id="login" type="text" name="login" class="login__input">
                    <label for="password" class="login__caption">Пароль</label>
                    <input id="password" type="password" name="password" class="login__input">
                    <button type="submit" value="Войти" class="login__button _btn">Войти</button>
                </form>
            </div>
        </div>

    </main>

</div>

<div class="wq-message__wrap">
    <?php
    if(isset($_SESSION['res']['answer'])){
        echo $_SESSION['res']['answer'];
        unset($_SESSION['res']);
    }
    ?>
</div>

<?php
    if(defined('BASE_CSS_JS') && !empty(BASE_CSS_JS['scripts'])){

        foreach(BASE_CSS_JS['scripts'] as $item)
            echo '<script src="' . (!preg_match('/^\s*https?:\/\//i', $item) ? PATH : '') . trim($item, '/') . '"></script>';

    }
?>

<script src="<?=PATH . ADMIN_TEMPLATE . 'js/login_page_scripts.js'?>"></script>


</body>
</html>