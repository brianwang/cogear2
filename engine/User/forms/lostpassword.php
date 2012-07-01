<?php

return array(
    'name' => 'user-lostpassword',
    'class' => 'form-horizontal',
    'elements' => array(
        'email' => array(
            'label' => t('E-Mail', 'User'),
            'type' => 'text',
            'validators' => array('Email'),
        ),
        'or' => array(
            'label' => t('OR', 'User'),
            'type' => 'div',
            'class' => 'or',
        ),
        'login' => array(
            'label' => t('Login', 'User'),
            'type' => 'text',
            'validators' => array('Login'),
        ),
        'buttons' => array(
            'type' => 'group',
            'class' => 'form-actions',
            'elements' => array(
                'submit' => array(
                    'type' => 'submit',
                    'label' => t('Renew password'),
                    'class' => 'btn btn-primary',
                )
            ),
        ),
    )
);