<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>404 Страница не найдена</title>
    <meta name="description" content="">
    <link rel="stylesheet" href="<?=\AppH::getRelativePath(__DIR__)?>/css/main.css">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
</head>
<body class="b_grd">
<div class="bg_logo"></div>
<div class="not_found-main">
    <div class="not_found-wrap">
        <div class="wraper_404">
            <h1>40 <span class="rotate_sybml">4</span></h1>
            <span class="not_found-txt"><?=$message ?? ''?>...</span>
            <span class="not-found_mask"></span>
            <a href="<?=\App::PATH()?>" class="go_back">Вернуться на главную</a>
        </div>
    </div>
</div>
</body>
</html>