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

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
    </head>
    <body>
        <div class="container-fluid clearfix">
            <?= $this->fetch('content'); ?>
        </div>
    </body>
</html>

<script type="text/javascript">
    $(document).ready(function () {
        window.print();
    });
</script>
