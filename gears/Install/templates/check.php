<p class="alert alert-info"><?php echo
t('Прежде чем приступить к установке системы, ваш сервер должен быть проверен на соответствие с требованиями системы. <br/>Обратите внимание на таблицу ниже и следуйте инстуркциям.') ?></p>
<?
$success = TRUE;
?>
<table id="requirements" class="table table-bordered table-striped">
    <thead>
    <th>#</th>
    <th><?php echo  t('Параметр') ?></th>
    <th><?php echo  t('Проверка') ?></th>
    <th><?php echo  t('Требование') ?></th>
    <th><?php echo  t('Тестирование') ?></th>
</thead>
<tbody>
    <?
    $success = FALSE;
    $php_version = phpversion();
    $passed = version_compare($php_version, '5.2.6', '>=');
    $success = $passed ? TRUE : FALSE;
    ?>
    <tr class="<?php echo  $passed ? 'success' : 'failure' ?>">
        <td>0.</td>
        <td><?php echo  t('Версия PHP') ?></td><td>
            <?php echo  $php_version ?>
        </td><td>
            5.2.6
        </td>
        <td >
            <span class="label label-<?php echo  $passed ? 'success' : 'important' ?>"><?php echo  $passed ? t('Успешно') : t('Ошибка') ?></span>
        </td>
    </tr>

    <?
    $passed = function_exists('spl_autoload_register');
    $success = $passed ? TRUE : FALSE;
    ?>
    <tr class="<?php echo  $passed ? 'success' : 'failure' ?>">
        <td>1.</td>
        <td colspan="3"><?php echo  t('SPL-библиотека') ?></td>
        <td>
            <span class="label label-<?php echo  $passed ? 'success' : 'important' ?>"><?php echo  $passed ? t('Успешно') : t('Ошибка') ?></span>
        </td>
    </tr>
    <?
    $passed = class_exists('ReflectionClass');
    $success = $passed ? TRUE : FALSE;
    ?>
    <tr class="<?php echo  $passed ? 'success' : 'failure' ?>">
        <td>2.</td>
        <td colspan="3"><?php echo  t('Reflections (Отражения)') ?></td>
        <td >
            <span class="label label-<?php echo  $passed ? 'success' : 'important' ?>"><?php echo  $passed ? t('Успешно') : t('Ошибка') ?></span>
        </td>
    </tr>
    <?
    $passed = function_exists('filter_list');
    $success = $passed ? TRUE : FALSE;
    ?>
    <tr class="<?php echo  $passed ? 'success' : 'failure' ?>">
        <td>3.</td>
        <td colspan="3"><?php echo  t('Фильтры') ?></td>
        <td >
            <span class="label label-<?php echo  $passed ? 'success' : 'important' ?>"><?php echo  $passed ? t('Успешно') : t('Ошибка') ?></span>
        </td>
    </tr>
    <?
    $passed = extension_loaded('iconv');
    $success = $passed ? TRUE : FALSE;
    ?>
    <tr class="<?php echo  $passed ? 'success' : 'failure' ?>">
        <td>4.</td>
        <td colspan="3"><?php echo  t('Iconv-расширение') ?></td>
        <td >
            <span class="label label-<?php echo  $passed ? 'success' : 'important' ?>"><?php echo  $passed ? t('Успешно') : t('Ошибка') ?></span>
        </td>
    </tr>

    <?
    if (isset($_SERVER['REQUEST_URI']) OR isset($_SERVER['PHP_SELF']) OR isset($_SERVER['PATH_INFO'])) {
        $passed = TRUE;
    }
    else {
        $passed = FALSE;
    }
    $success = $passed ? TRUE : FALSE;
    ?>
    <tr class="<?php echo  $passed ? 'success' : 'failure' ?>">
        <td>5.</td>
        <td colspan="3"><?php echo  t('Перезапись URL') ?></td>
        <td >
            <span class="label label-<?php echo  $passed ? 'success' : 'important' ?>"><?php echo  $passed ? t('Успешно') : t('Ошибка') ?></span>
        </td>
    </tr>
</tbody>
</table>
<? if ($success): ?>
    <p align="center">
        <a href="<?php echo  l('install/site') ?>" class="btn btn-primary"><?php echo  t('Продолжить') ?></a>
    </p>
    <?else:?>
    <?php echo error(t('Некоторые из требования не были удовлетворены.'))?>
    <a href="javascript:window.reload()" class="btn btn-warning"><?php echo t('Обновить')?></a>
<? endif; ?>