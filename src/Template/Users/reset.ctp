
<!-- inicio tela recuperar senha -->
<div class="container mt-5">
    <div class="row justify-content-md-center ">
        <div class="col-md-4 p-3 bg-dark  rounded-top" >
            <h4 class="text-white" style="color:#bfbfbf">Recuperar senha</h4>
        </div>
    </div>
    <div class="row justify-content-md-center">
        <div class="col-md-4 p-4 bg-white rounded-bottom  bordaTela-login-senha-cadastro ">
            <div class="row">
                <div class="col-12">
                    <?= $this->Flash->render() ?>
                    <?= $this->Form->create() ?>
                    <div class="form-group">
                        <?= $this->Form->input('password', [
                            'required' => true,
                            'autofocus' => true,
                            'class' => 'form-control',
                            'label' => "Nova senha"
                        ]); ?>
                    </div>
                    <div class="form-group">
                        <?= $this->Form->input('confirm_password', [
                            'type' => 'password',
                            'required' => true,
                            'class' => 'form-control',
                            'label' => 'Confirme a nova senha'
                        ]);
                        ?>
                    </div>
                    <?= $this->Form->button(__('Confirmar'), [
                        'class' => 'btn btn-success'
                    ]); ?>
                    <?= $this->Form->end(); ?>
                </div>

            </div>

        </div>
    </div>
</div>
