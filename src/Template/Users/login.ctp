
<!-- Inicio da tela login-->

<div class="container mt-5">
    <div class="row justify-content-md-center">
        <div class="col-md-6 p-3 bg-dark  rounded-top">
            <h4 class="" style="color:#bfbfbf"> Acessar Sistema</h4>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col-md-6 p-4 bg-white  rounded-bottom bordaTela-login-senha-cadastro">

            <?= $this->Form->create() ?>
            <div class="form-group">
                <label>CPF <small>(somente números)</small></label>
                <?= $this->Form->control('username', [
                    'label' => false,
                    'value' => isset($_GET['cpf']) ? $_GET['cpf'] : "",
                    'placeholder' => 'Digite somente os números',
                    'minLength' => 11,
                    'maxLength' => 11,
                    'class' => 'form-control',
                    ]) ?>
            </div>
            <div class="form-group">
                <label>Senha</label>
                <?= $this->Form->control('password', [
                    'label' => false,
                    'placeholder' => 'Senha',
                    'class' => 'form-control'
                    ]) ?>
            </div>
            <?= $this->Form->button(__('Entrar'), [
                'class' => 'btn btn-success btn-block t'
            ]); ?>
            <?= $this->Form->end() ?><br/>
            <a href="/users/cadastro" style="text-decoration:none">
                <button type="button" class="btn btn-primary btn-block">Cadastre-se</button>
            </a>
            <a href="/users/recupera_senha">
                <button type="button" class="btn btn-link float-right">Esqueceu sua senha?</button>
            </a>
        </div>
    </div>
</div>


<!-- Fim da tela login-->
