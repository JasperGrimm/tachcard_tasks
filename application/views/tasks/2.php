<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 6/11/14
 * Time: 7:27 PM
 */
?>
<?php include $template_path . '/_header.php' ?>
<div class="bs-example">

    <div class="bs-callout bs-callout-info">
    <span class="h3"><span class="badge">2</span> Вывести древовидную структуру, основываясь на данных из таблицы MySQL</span>
<pre>
Скрипт должен отформатировать текст используя шаблон.

Структура таблицы:
</pre>
<div class="code">
    <pre><span class="cm-keyword">CREATE</span> <span class="cm-keyword">TABLE</span> <span
            class="cm-variable-2">`tree`</span> </pre>
    <pre>( </pre>
    <pre>    <span class="cm-variable-2">`id`</span>       <span class="cm-keyword">INT</span>(<span
            class="cm-variable">11</span>) <span class="cm-keyword">NOT</span> <span
            class="cm-keyword">NULL</span> <span class="cm-variable">auto_increment</span>, </pre>
    <pre>    <span class="cm-variable-2">`parentid`</span> <span class="cm-keyword">INT</span>(<span
            class="cm-variable">11</span>) <span class="cm-keyword">NOT</span> <span
            class="cm-keyword">NULL</span> <span class="cm-keyword">DEFAULT</span> <span
            class="cm-string">'0'</span>, </pre>
    <pre>    <span class="cm-variable-2">`name`</span>     <span class="cm-keyword">VARCHAR</span>(<span
            class="cm-variable">50</span>) <span class="cm-keyword">NOT</span> <span
            class="cm-keyword">NULL</span> <span class="cm-keyword">DEFAULT</span> <span
            class="cm-string">''</span>, </pre>
    <pre>    <span class="cm-variable-2">`pub`</span>      <span class="cm-keyword">TINYINT</span>(<span
            class="cm-variable">1</span>) <span class="cm-keyword">NOT</span> <span
            class="cm-keyword">NULL</span> <span class="cm-keyword">DEFAULT</span> <span
            class="cm-string">'1'</span>, </pre>
    <pre>    <span class="cm-keyword">PRIMARY</span> <span class="cm-keyword">KEY</span> (<span
            class="cm-variable-2">`id`</span>), </pre>
    <pre>    <span class="cm-keyword">KEY</span> <span class="cm-variable-2">`parentid`</span> (<span
            class="cm-variable-2">`parentid`</span>) </pre>
    <pre>)</pre>
</div>
<pre>
Пояснения:
id - первичный ключ
parentID - ключ «родительского» элемента (0- для корневого)
name - информация ключа
pub - признак публикации (1- запись учитывается, 0-запись не учитывается)
</pre>
</div>
<div class="well-lg">
    <span class="h3"><a href="https://github.com/Djakson/tachcard_tasks/blob/master/application/System/Controllers/TaskController.php">Исходный код</a></span>
</div>
<div class="well-lg">
    <div class="h3">Результат</div>
    <ul>
        <?php
        foreach($tree as $node) {
            include $template_path . '/partials/_node.php';
        }
        ?>
    </ul>
</div>
</div>
<?php include $template_path . '/_footer.php' ?>


