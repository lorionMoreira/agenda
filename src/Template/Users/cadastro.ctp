<?php
/**
 * @var \App\View\AppView $this
 */
?>
<style>
    .select2 {
        width:100%!important;
    }
</style>

<?= $this->Flash->render() ?>

<div class="container mt-5 ">
    <div class="row">
        <div class="col-md-12">
            <?= $this->Form->create('', ['url' => 'users/cadastro', 'class'=>'was-validated']) ?>
                <!-- ########    tabs      #######   -->
                
                <?= $this->Form->hidden('contato_ajax') ?>
                <?= $this->Form->unlockField('contato_ajax');?>

                <?= $this->Form->hidden('sigad_user') ?>
                <?= $this->Form->unlockField('sigad_user');?>

                <?= $this->Form->hidden('pessoa_user') ?>
                <?= $this->Form->unlockField('pessoa_user');?>
                
                <ul class="nav nav-tabs col-md-12 border-0" role="tablist">
                    <li class="nav-item col-md-auto p-0  ">
                        <a class="nav-link active menuCadastro" id="usuario-tab" data-toggle="tab" href="#usuario" role="tab" aria-controls="usuario" aria-expanded="true" style="border:solid #d7e1ec 1px"> <img class="" src="/img/usuario.png" alt=""><span class="mt-1 ml-2">Cadastro</span></a>
                    </li>
                </ul>
                <div class="rounded-bottom p-4 bg-white bordaTela-login-senha-cadastro ">
                    <div class="tab-content">
                        <!-- ########    usuario      #######   -->
                        <div class="tab-pane fade show active " id="usuario" role="tabpanel" aria-labelledby="usuario-tab">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label class="control-label">CPF</label>
                                    <?= $this->Form->control('Users[username]', [
                                        'label' => false,
                                        'placeholder' => 'Digite somente os números',
                                        'class' => 'form-control',
                                        'maxLength'=> 11,
                                        'minLength'=> 11,
                                        'autocomplete'=> 'off',
                                        'required'
                                    ]) ?>
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="control-label">Email</label>
                                    <?= $this->Form->control('Users[email]', [
                                        'label' => false,
                                        'type' => 'email',
                                        'placeholder' => 'Email',
                                        'class' => 'form-control',
                                        'required'
                                    ]) ?>
                                </div>

                                <div class="form-group col-md-3">
                                    <label class="control-label">Confirme seu email</label>
                                    <input class="form-control" type="email" id="confirmEmail" placeholder="Confirme seu email" required>
                                    <span id="confirmEmailAlert" class="text-danger small" style="display: none">Confirme seu email</span>
                                </div>

                                <div class="form-group col-md-3">
                                    <label class="control-label">Senha</label>
                                    <a  id="Senha" style="cursor: help;"  href="#" data-toggle="tooltip" title="A senha deve ter uma letra minúscula, uma maiúscula, um número e ter pelo menos 8 caracteres."><i style="font-size: 14px; color: #D60624; background-color: #0000 ;">(?)</i></a>
                                    <?= $this->Form->password('Users[password]', [
                                        'placeholder' => 'Senha',
                                        'class' => 'form-control',
                                        'autocomplete' => 'off',
                                        'minLength'=> 8,
                                        'id' => 'senha',
                                         'required'
                                    ]) ?>
                                    <!-- A senha deve ter uma letra minúscula, uma maiúscula, um número e ter pelo menos 8 caracteres. -->
                                </div>
                                <div class="form-group col-md-3">
                                    <label class="control-label">Confirme a senha</label>
                                    <?= $this->Form->password('Users[password2]', [
                                        'label' => false,
                                        'placeholder' => 'Confirme a senha',
                                        'class' => 'form-control ',
                                        'onclick' => 'verificar_senha()',
                                        'id' => 'repetirSenha',
                                        'minLength'=> 8,
                                        'autocomplete' => 'off',
                                        'required'
                                    ]) ?>
                                </div>
                            </div>
                        </div>
                        <script>
                            function verificar_senha()
                            {
                                var senha = document.getElementById("senha").value;
                                    //teste minusculo/         teste maiusculo/           teste numerico/          teste tamanho
                                if ((/[a-z]/.test(senha)) && (/[A-Z]/.test(senha)) && (/[0-9]/.test(senha)) && (senha.length >= 8))
                                {}
                                else
                                {
                                    alert ("A senha deve ter uma letra minúscula, uma maiúscula, um número e ter pelo menos 8 caracteres.");
                                    $("#senha").val('');
                                }
                            }
                        </script>

                    </div>
                    <?= $this->Form->button(__('Cadastrar'), ['class' => 'btn btn-success validacaoTab']) ?>
                    <a class="btn btn-outline-success" href="/pages/home">Voltar</a>
                </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>


<?= $this->Html->script('cadastro') ?>
