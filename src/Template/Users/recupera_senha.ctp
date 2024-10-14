<?php
/**
 * @var \App\View\AppView $this
 */
?>

<!-- inicio tela recuperar senha -->
<div class="container mt-5">
   <div class="row justify-content-md-center ">
      <div class="col-md-6 p-3 bg-dark  rounded-top" >
           <h4 class="text-white" style="color:#bfbfbf">Recuperar senha</h4>
      </div>    
   </div>
    <?= $this->Form->create('', ['url' => 'users/recuperaSenha']) ?>
    <div class="row row justify-content-md-center">
        <div class="form-group col-md-6 p-4 bg-white rounded-bottom  bordaTela-login-senha-cadastro ">

            <div class="row">
                <div class="form-group col-md-6">
                    <label>CPF <small>(Somente números)</small></label>
                    <?= $this->Form->control('cpf', [
                        'label' => false,
                        'placeholder' => 'CPF',
                        'class' => 'form-control',
                        'required' => 'required',
                        'pattern' => '\d{11}',
                        'id' => 'cpf',
                        'maxLength' => '11'
                    ]) ?>
                </div>

                <div class="form-group col-md-6" id="colEmail" style="display: none">
                    <label>Informe o email cadastrado</label>
                    <?= $this->Form->control('email', [
                        'id' => 'inputEmail',
                        'label' => false,
                        'placeholder' => 'email@exemplo.com',
                        'class' => 'form-control',
                        'type' => 'email'
                    ]) ?>
                </div>


            </div>

            <div class="row" id="rowCadastroConcluido" style="display: none">
                <div class="form-group col-md-12">
                    <label>Nome da mãe</label>
                    <?= $this->Form->control('PessoaFisicas[nome_mae]', [
                        'label' => false,
                        'placeholder' => 'Nome da mãe',
                        'class' => 'form-control',
                        'id' => 'nomeMae',
                    ]) ?>
                </div>

                <div class="form-group col-md-6">
                    <label>Data de nascimento</label>
                    <?= $this->Form->control('PessoaFisicas[nascimento]', [
                        'label' => false,
                        'placeholder' => "Ex:15/02/1985",
                        'class' => 'form-control data',
                        'id' => 'nascimento',                        
                        'maxlength'=>"10",                        
                        
                    ]) ?>
                </div>
                <div class="form-group col-md-12">
                    <label>Informe o email cadastrado</label>
                    <?= $this->Form->control('email_cad', [
                        'id' => 'email_cad',
                        'label' => false,
                        'placeholder' => 'email@exemplo.com',
                        'class' => 'form-control',
                        'required' => 'required',
                        'type' => 'email'
                    ]) ?>
                </div>
            </div>
                <?php //echo $this->Recaptcha->display() ?> 
                
                <div class="col-md-6 mt-3 p-0">
                <?php //$this->Form->unlockField('g-recaptcha-response'); ?>
                <?= $this->Form->button(__('Recuperar Senha'), ['class' => 'btn btn-success']) ?>
                <a class="btn btn-outline-success" href="/pages/home">Voltar</a>
            <?= $this->Form->end() ?>
            </div>
        </div>
    </div>  
</div>
<!-- fim tela recuperar senha -->

<?= $this->Html->script('recuperacao') ?>
