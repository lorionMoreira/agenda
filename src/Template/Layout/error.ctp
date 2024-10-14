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
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta charset="utf-8">
    <title>
        DPE - BA Agendamento Online:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('jasny-bootstrap.min.css') ?>
    <?= $this->Html->css('home.css') ?>

    <?= $this->Html->script('jquery-3.2.1.min'); ?>
    <?= $this->Html->script('popper.min'); ?>
    <?= $this->Html->script('bootstrap.min'); ?>
    <?= $this->Html->script('jasny-bootstrap.min'); ?>
    <?= $this->Html->script('masks'); ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-126240244-1"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'UA-126240244-1');
    </script>
</head>

<body>

<div class="container-fluid clearfix">
    <?= $this->element('header'); ?>
    <?= $this->Flash->render() ?>
    <?= $this->fetch('content') ?>
</div>
<footer>
</footer>
</body>
</html>
