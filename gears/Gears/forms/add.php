<?php

return array(
    '#name' => 'gears.add',
    '#class' => 'form form-horizontal',
    'info' => array(
        '#type' => 'div',
        '#class' => 'alert alert-info',
        '#label' => t('Вы можете загрузить одну или несколько шестерёнок в одном архиве. Система автоматически установит их.'),
    ),
    'field' => array(
        '#type' => 'fieldset',
        'file' => array(
            '#label' => t('С диска'),
            '#type' => 'file',
            '#allowed_types' => array('zip'),
            '#maxsize' => 3072,
            '#path' => UPLOADS . DS . 'gears',
            '#overwrite' => TRUE,
        ),
        'or' => array(
            '#type' => 'div',
            '#value' => '<h2>' . t('ИЛИ') . '</h2>',
        ),
        'url' => array(
            '#type' => 'file_url',
            '#label' => t('С Интернета'),
            '#class' => 'input-xxxlarge',
            '#allowed_types' => array('zip'),
            '#maxsize' => 3072,
            '#path' => UPLOADS . DS . 'gears',
            '#overwrite' => TRUE,
            '#validators' => array('Url'),
        ),
    ),
    'actions' => array(
        '#class' => 't_c',
        'submit' => array(
            '#class' => 'btn btn-primary btn-large',
            '#label' => t('Загрузить'),
        )
    )
);