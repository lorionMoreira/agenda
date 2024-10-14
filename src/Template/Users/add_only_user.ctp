<?php
/**
 * @var \App\View\AppView $this
 */
?>

<?= $this->Flash->render() ?>

<div class="container mt-5 ">
    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->create('', ['url' => 'users/add_only_user', 'class'=>'was-validated']) ?>
                <!-- ########    tabs      #######   -->
                <ul class="nav nav-tabs col-md-12 border-0" role="tablist">
                    <li class="nav-item col-md-auto p-0">
                        <a class="nav-link active menuCadastro" id="usuario-tab" data-toggle="tab" href="#usuario" role="tab" aria-controls="usuario" aria-expanded="true" style="border:solid #d7e1ec 1px"> <img class="" src="/img/usuario.png" alt=""><span class="mt-1 ml-2">Usuário</span></a>
                    </li>
                </ul>
                <div class="rounded-bottom p-4 bg-white bordaTela-login-senha-cadastro ">
                    <div class="tab-content">
                        <!-- ########    usuario      #######   -->
                        <div class="tab-pane fade show active" id="usuario" role="tabpanel" aria-labelledby="usuario-tab">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <?= $this->Form->hidden('PessoaFisicas[cpf]', [
                                        'value' => $_GET['cpf']
                                    ]); ?>
                                    <?= $this->Form->control('cpf', [
                                        'label' => false,
                                        'placeholder' => 'CPF',
                                        'value' => $_GET['cpf'],
                                        'class' => 'form-control cpf',
                                        'required',
                                        'disabled'
                                    ]) ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <?= $this->Form->control('Usuarios[email]', [
                                        'label' => false,
                                        'type' => 'email',
                                        'placeholder' => 'Email',
                                        'class' => 'form-control',
                                        'required'
                                    ]) ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <?= $this->Form->control('Usuarios[celular]', [
                                        'label' => false,
                                        'placeholder' => 'Celular',
                                        'class' => 'form-control cel',
                                        'required'
                                    ]) ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <?= $this->Form->password('Usuarios[senha]', [
                                        'label' => false,
                                        'placeholder' => 'Senha',
                                        'class' => 'form-control',
                                        'pattern' => '.{6,}',
                                        'title' => 'Precisa conter no mínimo 6 caracteres.',
                                        'required'
                                    ]) ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <?= $this->Form->password('Usuarios[senha2]', [
                                        'label' => false,
                                        'placeholder' => 'Repetir a senha',
                                        'class' => 'form-control',
                                        'pattern' => '.{6,}',
                                        'title' => 'Precisa conter no mínimo 6 caracteres.',
                                        'required'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                    <?= $this->Form->button(__('Cadastrar'), ['class' => 'btn btn-success validacaoTab']) ?>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

<?= $this->Html->script('cadastro') ?>
