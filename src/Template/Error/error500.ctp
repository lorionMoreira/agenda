<?php
use Cake\Core\Configure;
use Cake\Error\Debugger;

$this->layout = 'error';

if (Configure::read('debug')) :
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error500.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?php Debugger::dump($error->params) ?>
<?php endif; ?>
<?php if ($error instanceof Error) : ?>
        <strong>Error in: </strong>
        <?= sprintf('%s, line %s', str_replace(ROOT, 'ROOT', $error->getFile()), $error->getLine()) ?>
<?php endif; ?>
<?php
    echo $this->element('auto_table_warning');

    if (extension_loaded('xdebug')) :
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h2 class="h2 mt-5">Desculpe, houve um erro interno. </h2>
            <h5>
                Já fomos notificados desse problema e estamos trabalhando para soluciona-lo.<br>
                Tente novamente mais tarde ou entre em contato através do portal
                <a href="http://www.defensoria.ba.def.br/fale-conosco"> fale conosco</a>.
            </h5>

            <a href="/pages/home" class="btn btn-success text-white">
                Voltar para Página Inicial
            </a>
        </div>
    </div>
</div>
