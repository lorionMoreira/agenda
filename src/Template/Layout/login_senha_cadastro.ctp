<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        DPE - BA Agendamento Online:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('open-iconic-bootstrap.min') ?>
    <?= $this->Html->css('jquery-ui.min.css') ?>
    <?= $this->Html->css('home.css') ?>
    <?= $this->Html->css('select2.min.css') ?>


    <?= $this->Html->script('jquery-3.2.1.min'); ?>
    <?= $this->Html->script('select2.min.js'); ?>
    <?= $this->Html->script('popper.min'); ?>
    <?= $this->Html->script('bootstrap.min'); ?>
    <?= $this->Html->script('jasny-bootstrap.min'); ?>
    <?= $this->Html->script('masks'); ?>
    <?= $this->Html->script('jquery-ui.min.js'); ?>
    <?= $this->Html->script('datepicker-pt-BR.js'); ?>
    <?= $this->Html->script('timeflash.js'); ?>


    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>
</head>

<body>

<div class="container-fluid mb-5 clearfix">

    <?= $this->element('header'); ?>
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
    
    <div class="modal"><!-- Place at bottom of page --></div>
</div>

</body>
</html>
