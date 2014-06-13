<?php
/**
 * Created by PhpStorm.
 * User: jasper
 * Date: 6/11/14
 * Time: 8:55 PM
 */
?>
<?php if (1 == $node['pub']) { ?>
<li>
    <?= $node['name'] ?>
    <?php if ($node['nodes'] && count($node['nodes'])) { ?>
        <ul>
            <?php
            foreach ($node['nodes'] as $node) {
                include __FILE__;
            }
            ?>
        </ul>
    <?php } ?>
</li>
<?php } ?>