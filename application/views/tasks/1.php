<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 6/12/14
 * Time: 1:40 AM
 */
?>

<?php include $template_path . '/_header.php' ?>
<div class="bs-example">
    <div class="bs-callout bs-callout-info">
        <span class="h3"><span class="badge">1</span> Выбрать из таблицы базы данных MySQL случайную запись несколькими способами.
</span>

<pre>
Есть одно важное ограничение – диапазон id НЕ является непрерывной числовой последовательностью!

Структура таблицы:
</pre>
<div class="code">
    <pre><span class="cm-keyword">CREATE</span> <span class="cm-keyword">TABLE</span> <span
            class="cm-variable-2">`randomtest`</span> </pre>
    <pre>( </pre>
    <pre>    <span class="cm-variable-2">`id`</span>   <span class="cm-keyword">INT</span>(<span
            class="cm-variable">11</span>) <span class="cm-keyword">NOT</span> <span
            class="cm-keyword">NULL</span> <span class="cm-variable">auto_increment</span>, </pre>
    <pre>    <span class="cm-variable-2">`name`</span> <span class="cm-keyword">VARCHAR</span>(<span
            class="cm-variable">50</span>) <span class="cm-keyword">NOT</span> <span
            class="cm-keyword">NULL</span> <span class="cm-keyword">DEFAULT</span> <span
            class="cm-string">''</span>, </pre>
    <pre>    <span class="cm-variable-2">`pub`</span>  <span class="cm-keyword">TINYINT</span>(<span
            class="cm-variable">1</span>) <span class="cm-keyword">NOT</span> <span
            class="cm-keyword">NULL</span> <span class="cm-keyword">DEFAULT</span> <span
            class="cm-string">'1'</span>, </pre>
    <pre>    <span class="cm-keyword">PRIMARY</span> <span class="cm-keyword">KEY</span> (<span
            class="cm-variable-2">`id`</span>) </pre>
    <pre>) </pre>
</div>
<pre>
Пояснения:
id - первичный ключ
name - информация ключа
pub - признак публикации (1- запись участвует в выборке, 0-нет)

Необходимо написать скрипт для заполнения таблички и для выборки.
</pre>

</div>
<div class="well-lg">
    <span class="h3">
        <a href="https://github.com/Djakson/tachcard_tasks/blob/master/application/System/Controllers/TaskController.php">Исходный код</a>
    </span>
</div>
<div class="well-lg">
    <span class="h3">Решение</span>
<pre>
<strong>Вариант 1.</strong> Простое решение.
</pre>
<div class="code">
    <pre><span class="cm-keyword">SELECT</span> <span class="cm-variable">r1</span>.<span
            class="cm-variable">id</span>, </pre>
    <pre>       <span class="cm-variable">name</span>, </pre>
    <pre>       <span class="cm-variable">pub</span> </pre>
    <pre><span class="cm-keyword">FROM</span>   <span class="cm-variable">randomtest</span> <span
            class="cm-keyword">AS</span> <span class="cm-variable">r1</span> </pre>
    <pre><span class="cm-keyword">JOIN</span> (<span class="cm-keyword">SELECT</span> ( (<span
            class="cm-keyword">SELECT</span> <span class="cm-keyword">Max</span> (<span
            class="cm-variable">id</span>) <span class="cm-keyword">FROM</span>   <span class="cm-variable">randomtest</span>) * <span
            class="cm-variable">Rand</span>() ) <span class="cm-keyword">AS</span> <span
            class="cm-variable">id</span>) <span class="cm-keyword">AS</span> <span
            class="cm-variable">r2</span> <span class="cm-keyword">ON</span> <span class="cm-variable">r1</span>.<span
            class="cm-variable">id</span> &gt;= <span class="cm-variable">r2</span>.<span
            class="cm-variable">id</span> </pre>
    <pre><span class="cm-keyword">ORDER</span>  <span class="cm-keyword">BY</span> <span
            class="cm-variable">r1</span>.<span class="cm-variable">id</span> <span
            class="cm-keyword">ASC</span> </pre>
    <pre><span class="cm-keyword">LIMIT</span>  <span class="cm-variable">1</span>; </pre>
</div>
<pre>
Работает хорошо при малом кол-ве записей в таблице ( до 100 строк ).
При большом количестве дыр проявляется сильный эффект псевдослучайности выборки

<strong>Вариант 2.</strong> Решение с дополнительной таблицей маппинга.

Создадим map-табличку randomtest_keymapper:</pre>
<div class="code">
    <pre><span class="cm-keyword">CREATE</span> <span class="cm-keyword">TABLE</span> <span class="cm-variable">randomtest_keymapper</span> (</pre>
    <pre>            <span class="cm-variable">id</span> <span class="cm-variable">SERIAL</span>,</pre>
    <pre>            <span class="cm-variable">row_id</span> <span class="cm-keyword">INT</span>(<span
            class="cm-variable">11</span>) <span class="cm-keyword">unsigned</span> <span
            class="cm-keyword">NOT</span> <span class="cm-keyword">NULL</span> <span
            class="cm-keyword">UNIQUE</span></pre>
    <pre>        );</pre>
</div>
<pre>
И инициализируем значениями ключей из таблицы randomtest:
</pre>
<div class="code">
    <pre><span class="cm-keyword">set</span> <span class="cm-variable">@key</span> = <span
            class="cm-variable">0</span>; <span class="cm-keyword">INSERT</span> <span
            class="cm-keyword">INTO</span> <span class="cm-variable">randomtest_keymapper</span> <span
            class="cm-keyword">SELECT</span> <span class="cm-variable">@key</span> <span
            class="cm-atom">:</span>= <span class="cm-variable">@key</span> + <span class="cm-variable">1</span>, <span
            class="cm-variable">id</span> <span class="cm-keyword">FROM</span> <span class="cm-variable">randomtest</span>;</pre>
</div>
<pre>Теперь можно сделать запрос:</pre>
<div class="code">
    <pre><span class="cm-keyword">SELECT</span> <span class="cm-variable">rt</span>.<span
            class="cm-variable">id</span>, <span class="cm-variable">name</span>, <span
            class="cm-variable">pub</span> <span class="cm-keyword">FROM</span> <span class="cm-variable">randomtest</span> <span
            class="cm-variable">rt</span></pre>
    <pre>            <span class="cm-keyword">JOIN</span> (</pre>
    <pre>                <span class="cm-keyword">SELECT</span> <span class="cm-variable">rtkm1</span>.<span
            class="cm-variable">row_id</span> <span class="cm-keyword">FROM</span> <span class="cm-variable">randomtest_keymapper</span> <span
            class="cm-keyword">AS</span> <span class="cm-variable">rtkm1</span></pre>
    <pre>                <span class="cm-keyword">JOIN</span> (</pre>
    <pre>                    <span class="cm-keyword">SELECT</span> (</pre>
    <pre>                        (<span class="cm-keyword">SELECT</span> <span
            class="cm-keyword">MAX</span>(<span class="cm-variable">id</span>) <span
            class="cm-keyword">FROM</span> <span class="cm-variable">randomtest_keymapper</span>) * <span
            class="cm-variable">RAND</span>()</pre>
    <pre>                    ) <span class="cm-keyword">AS</span> <span class="cm-variable-2">`key`</span></pre>
    <pre>                ) <span class="cm-keyword">as</span> <span class="cm-variable">r_random</span> <span
            class="cm-keyword">ON</span> <span class="cm-variable">r_random</span>.<span class="cm-variable-2">`key`</span> &lt;= <span
            class="cm-variable">rtkm1</span>.<span class="cm-variable">id</span></pre>
    <pre>            ) <span class="cm-keyword">AS</span> <span class="cm-variable">rtkm2</span> <span
            class="cm-keyword">ON</span> <span class="cm-variable">rt</span>.<span class="cm-variable">id</span> = <span
            class="cm-variable">rtkm2</span>.<span class="cm-variable">row_id</span></pre>
    <pre>            <span class="cm-keyword">ORDER</span> <span class="cm-keyword">BY</span> <span
            class="cm-variable">rtkm2</span>.<span class="cm-variable">row_id</span> <span class="cm-keyword">ASC</span> <span
            class="cm-keyword">LIMIT</span> <span class="cm-variable">1</span></pre>
</div>
<pre>
Видно, что вспомогательная мап-таблица живет сама по себе, а нам нужно обеспечить целостность данных в связке двух таблиц
randomtest и randomtest_keymapper. При добавлении записи в таблицу randomtest в таблице randomtest_keymapper тоже должна появиться
запись с randomtest.id. При удалении записи из randomtest также должна происходить перелинковка в таблице  randomtest_keymapper

Делать это можно на стороне приложения, но имея на борту СУБД c хранимыми процедурами и триггерами, грех не воспользоваться
этими фичами. Тем более что это не часть бизнес-логики, а сервисная прослойка.

Собственно триггер:
</pre>

<div class="code">
    <pre></pre>
    <pre>
<span class="cm-keyword">DELIMITER</span><span class="cm-variable">//</span></pre>
    <pre><span class="cm-keyword">DROP</span> <span class="cm-keyword">TRIGGER</span> <span class="cm-keyword">IF</span> <span
            class="cm-keyword">EXISTS</span> <span class="cm-variable">randomtest_insert_trigger//</span></pre>
    <pre><span class="cm-keyword">CREATE</span> <span class="cm-keyword">TRIGGER</span> <span
            class="cm-variable">randomtest_insert_trigger</span></pre>
    <pre><span class="cm-variable">AFTER</span> <span class="cm-keyword">INSERT</span> <span class="cm-keyword">ON</span> <span
            class="cm-variable">randomtest</span></pre>
    <pre><span class="cm-keyword">FOR</span> <span class="cm-keyword">EACH</span> <span
            class="cm-variable">ROW</span></pre>
    <pre><span class="cm-variable">BEGIN</span></pre>
    <pre>    <span class="cm-keyword">DECLARE</span> <span class="cm-variable">ai</span> <span
            class="cm-keyword">BIGINT</span> <span class="cm-keyword">UNSIGNED</span> <span class="cm-keyword">DEFAULT</span> <span
            class="cm-variable">1</span>;</pre>
    <pre>    <span class="cm-keyword">SELECT</span> <span class="cm-keyword">MAX</span>(<span
            class="cm-variable">id</span>) + <span class="cm-variable">1</span> <span
            class="cm-keyword">FROM</span> <span class="cm-variable">randomtest_keymapper</span> <span
            class="cm-keyword">INTO</span> <span class="cm-variable">ai</span>;</pre>
    <pre>    <span class="cm-keyword">SELECT</span> <span class="cm-variable">IFNULL</span>(<span
            class="cm-variable">ai</span>, <span class="cm-variable">1</span>) <span
            class="cm-keyword">INTO</span> <span class="cm-variable">ai</span>;</pre>
    <pre>    <span class="cm-keyword">INSERT</span> <span class="cm-keyword">INTO</span> <span
            class="cm-variable">randomtest_keymapper</span>(<span class="cm-variable">id</span>, <span
            class="cm-variable">row_id</span>) <span class="cm-keyword">VALUES</span> (<span
            class="cm-variable">ai</span>, <span class="cm-variable">NEW</span>.<span
            class="cm-variable">id</span>);</pre>
    <pre><span class="cm-variable">END//</span>
    </pre>
    <pre><span class="cm-keyword">DROP</span> <span class="cm-keyword">TRIGGER</span> <span class="cm-keyword">IF</span> <span
            class="cm-keyword">EXISTS</span> <span class="cm-variable">randomtest_update_trigger//</span></pre>
    <pre><span class="cm-keyword">CREATE</span> <span class="cm-keyword">TRIGGER</span> <span
            class="cm-variable">randomtest_update_trigger</span></pre>
    <pre><span class="cm-variable">AFTER</span> <span class="cm-keyword">DELETE</span> <span class="cm-keyword">ON</span> <span
            class="cm-variable">randomtest</span></pre>
    <pre><span class="cm-keyword">FOR</span> <span class="cm-keyword">EACH</span> <span
            class="cm-variable">ROW</span></pre>
    <pre><span class="cm-variable">BEGIN</span></pre>
    <pre>    <span class="cm-keyword">DELETE</span> <span class="cm-keyword">FROM</span> <span
            class="cm-variable">randomtest_keymapper</span> <span class="cm-keyword">WHERE</span> <span
            class="cm-variable">row_id</span> = <span class="cm-variable">OLD</span>.<span class="cm-variable">id</span>;</pre>
    <pre>    <span class="cm-keyword">UPDATE</span> <span class="cm-variable">randomtest_keymapper</span> <span
            class="cm-keyword">SET</span> <span class="cm-variable">id</span> = <span
            class="cm-variable">id</span> - <span class="cm-variable">1</span> <span
            class="cm-keyword">WHERE</span> <span class="cm-variable">row_id</span> &gt; <span
            class="cm-variable">OLD</span>.<span class="cm-variable">id</span>;</pre>
    <pre><span class="cm-variable">END//</span></pre>
    <pre> </pre>
    <pre><span class="cm-keyword">DROP</span> <span class="cm-keyword">TRIGGER</span> <span class="cm-keyword">IF</span> <span
            class="cm-keyword">EXISTS</span> <span class="cm-variable">randomtest_delete_trigger//</span></pre>
    <pre><span class="cm-keyword">CREATE</span> <span class="cm-keyword">TRIGGER</span> <span
            class="cm-variable">randomtest_delete_trigger</span></pre>
    <pre><span class="cm-variable">AFTER</span> <span class="cm-keyword">UPDATE</span> <span class="cm-keyword">ON</span> <span
            class="cm-variable">randomtest</span></pre>
    <pre><span class="cm-keyword">FOR</span> <span class="cm-keyword">EACH</span> <span
            class="cm-variable">ROW</span></pre>
    <pre><span class="cm-variable">BEGIN</span></pre>
    <pre>    <span class="cm-keyword">UPDATE</span> <span class="cm-variable">randomtest_keymapper</span> <span
            class="cm-keyword">SET</span> <span class="cm-variable">row_id</span> = <span class="cm-variable">NEW</span>.<span
            class="cm-variable">id</span> <span class="cm-keyword">WHERE</span> <span
            class="cm-variable">row_id</span> = <span class="cm-variable">OLD</span>.<span class="cm-variable">id</span>;</pre>
    <pre><span class="cm-keyword">END//</span></pre>
    <pre><span class="cm-keyword">DELIMITER</span> ;</pre>
</div>
<pre>
Ну вот и все. Теперь нам не нужно переживать за целостность данных. За всем будет следить СУБД
</pre>
</div>
<div class="well-lg">
    <span class="h3">Результат</span>
    <ul>
        <?php foreach($variants as $id=>$result): ?>
        <li><?=$id?> . <?=$result?></li>
        <?php endforeach ?>
    </ul>
</div>
</div>
<?php include $template_path . '/_footer.php' ?>