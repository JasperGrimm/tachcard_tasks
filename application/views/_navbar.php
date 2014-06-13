<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 6/12/14
 * Time: 12:05 AM
 */
?>

<nav class="navbar navbar-default" role="navigation">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">TachCard</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="<?php echo $app->request()->getPath() == '/task/1' ? 'active': '' ?>"><a href="/task/1">Задание 1</a></li>
                <li class="<?php echo $app->request()->getPath() == '/task/2' ? 'active': '' ?>"><a href="/task/2">Задание 2</a></li>
                <li class="<?php echo $app->request()->getPath() == '/task/3' ? 'active': '' ?>"><a href="/task/3">Задание 3</a></li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>