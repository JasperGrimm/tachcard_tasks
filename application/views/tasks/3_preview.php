<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 6/12/14
 * Time: 9:33 AM
 */
?>
<?php
/**
 * Template settings
 */
$hide_default_navbar = true;
$main_container_class = 'justify_container';
?>

<?php include $template_path . '/_header.php' ?>

<div class="ya_header">
        <div class="left">
            <a class="start_page_link" href="#">Сделать стартовой</a>
        </div>
        <div class="right">
            <ul class="horizontal">
                <li><a href="https://mail.yandex.ru">Почта</a>&nbsp;(0)</li>
                <li>
                    <a class="user" href="#">
                        <span>j</span>asper
                    </a>
                </li>
                <li><a class="exit" href="#">Выход</a></li>
            </ul>
        </div>
</div>
<div class="ya_container">
    <div class="search_row">
        <form class="form-horizontal">
            <div class="row">
                <div class="col-xs-10 search_input">
                    <div class="logo"></div>
                    <input type="text" autofocus class="text">
                </div>
                <div class="col-xs-2">
                    <a class="ya_button">
                        <input type="submit" value="Найти">
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
<div class="ya_footer">
    <div class="copy">
        <p class="left">
            <span>©&nbsp;980 до н.э.—2014</span>
            &nbsp;«<a href="http://www.yandex.ru">неЯндекс</a>»</p>
        <p class="right">По мотивам дизайна&nbsp;—&nbsp;<a href="http://www.artlebedev.ru">Студия Артемия&nbsp;Лебедева</a>
        </p>
    </div>
</div>

<?php include $template_path . '/_footer.php' ?>