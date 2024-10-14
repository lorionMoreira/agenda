<?php
/**
 * @var \App\View\AppView $this
 */

//debug($contato);
?>

<?= 
$this->Flash->render();
$this->Html->css('tooltip.css');
?>

<div class="container mt-5 mb-5">
    <?= $this->Form->create('', ['url' => 'users/edit', 'class'=>'was-validated']) ?>
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs col-md-12 border-0" role="tablist">
                <li class="nav-item col-md-auto  p-0">
                    <a class="nav-link active menuCadastro " id="dados-pessoais-tab" data-toggle="tab" href="#dados-pessoais" role="tab" aria-controls="dados-pessoais" style="border:solid #d7e1ec 1px"> <img class="" src="/img/dadosPessoais.png" alt=""> <span class="mt-1 ml-2">Dados pessoais</span> </a>
                </li>

                <li class="nav-item col-md-auto  p-0">
                    <a class="nav-link menuCadastro " id="contato-tab" data-toggle="tab" href="#contato" role="tab" aria-controls="contato" style="border:solid #d7e1ec 1px"><img class="" src="/img/contato.png" alt=>  <span class="mt-1 ml-2">Contato</span></a>
                </li>

                <li class="nav-item col-md-auto p-0">
                    <a class="nav-link menuCadastro " id="endereco-tab" data-toggle="tab" href="#endereco" role="tab" aria-controls="endereco" style="border:solid #d7e1ec 1px"> <img class="" src="/img/home.png" alt="">  <span class="mt-1 ml-3">Endereço</span></a>
                </li>
            </ul>

            <div class="rounded-bottom p-4 bg-white bordaTela-login-senha-cadastro ">
                <div class="tab-content">
                    <div class="tab-pane active" id="dados-pessoais" role="tabpanel" aria-labelledby="dados-pessoais-tab">
                        
                        <div class="row ">
                            <div class="form-group col-md-6">
                                <label>Nome completo<b class="text-danger"> *</b></label>
                                <?= $this->Form->control('Pessoas[nome]', [
                                    'label' => false,
                                    'placeholder' => 'Nome completo',
                                    'class' => 'form-control',
                                    'style' => "text-transform:uppercase",
                                    'id' => 'nome',
                                    'pattern' => '\w+ \w+.*',
                                    'title' => 'É preciso cadastrar o nome completo.',
                                    'value' => $pessoa->nome,
                                    'required'
                                    
                                ]) ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Nome social</label>                                
                                <a  id="DefinicaoNomeSocial" style="cursor: help;"  href="#" data-toggle="tooltip" title="Nome social é o nome que pessoas transgêneros e travestis preferem ser chamadas, e possui a mesma proteção concedida ao nome de registro. É assegurada pela ADI n. 4.275, do Supremo Tribunal Federal."><i style="font-size: 13px; color: white; background-color: #343b42 ;">(Nome que pessoas transgêneros e travestis preferem ser chamadas)</i></a>
                                                             
                                <?= $this->Form->control('Pessoas[nome_social]', [
                                    'label' => false,
                                    'placeholder' => 'Nome social - este campo não é obrigatório',
                                    'class' => 'form-control',
                                    'style' => "text-transform:uppercase",
                                    'id' => 'nomeSocial',
                                    'pattern' => '\w+ \w+.*',
                                    'value' => $pessoa->nome_social
                                ]) ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Nome da mãe<b class="text-danger"> *</b></label>
                                <?= $this->Form->control('PessoaFisicas[nome_mae]', [
                                    'label' => false,
                                    'placeholder' => 'Nome da mãe',
                                    'class' => 'form-control',
                                    'style' => "text-transform:uppercase",
                                    'id' => 'nomeMae',
                                    'value' => $pessoaFisica->nome_mae,
                                    'required'
                                ]) ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Nome do pai</label>
                                <?= $this->Form->control('PessoaFisicas[nome_pai]', [
                                    'label' => false,
                                    'placeholder' => 'Nome do pai',
                                    'style' => "text-transform:uppercase",
                                    'value' => $pessoaFisica->nome_pai,
                                    'class' => 'form-control'
                                    
                                ]) ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label class="control-label">Opção de gênero</label>
                                <?= $this->Form->select('PessoaFisicas[opcao_genero_id]', [
                                    1 => 'Feminino',
                                    2 => 'Masculino',
                                    3 => 'Nao binário',
                                    4 => 'Outros'
                                ],[
                                    'label' => false,
                                    'empty' => 'Selecione',
                                    'value' => $pessoaFisica->opcao_genero_id,
                                    'class' => 'form-control'
                                ]) ?>
                            </div> 

                            <div class="form-group col-md-4">
                                <label class="control-label">Orientação sexual</label>
                                <?= $this->Form->select('PessoaFisicas[orientacao_sexual_id]', [
                                    1 => 'Heterossexual',
                                    2 => 'Homossexual',
                                    3 => 'Bissexual',
                                    4 => 'Assexual',
                                    5 => 'Outros'
                                ],[
                                    'label' => false,
                                    'empty' => 'Selecione',
                                    'value' => $pessoaFisica->orientacao_sexual_id,
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                            <?php if($pessoaFisica->nascimento != null){ ?>
                            <div class="form-group col-md-4">
                                <label>Data de nascimento<b class="text-danger"> *</b><small>(dia/mês/ano)</small></label>
                                <?= $this->Form->control('PessoaFisicas[nascimento]', [
                                    'label' => false,
                                    'placeholder' => 'dd/mm/aaaa',
                                    'class' => 'form-control data',
                                    'id' => "nascimento",
                                    'autocomplete' => 'off',
                                    'pattern' => '(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d',
                                    'value' => $pessoaFisica->nascimento->i18nFormat('dd/MM/Y'),
                                    'required'
                                ]) ?>
                            </div>
                        <?php }else{ ?>
                            <div class="form-group col-md-4">
                                <label>Data de nascimento<b class="text-danger"> *</b><small>(dia/mês/ano)</small></label>
                                <?= $this->Form->control('PessoaFisicas[nascimento]', [
                                    'label' => false,
                                    'placeholder' => 'dd/mm/aaaa',
                                    'class' => 'form-control data',
                                    'id' => "nascimento",
                                    'autocomplete' => 'off',
                                    'pattern' => '(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d',
                                    'value' => '',
                                    'required'
                                ]) ?>
                            </div>

                        <?php } ?>

                            <div class="form-group col-md-4">
                                <label>Nacionalidade</label>
                                <?= $this->Form->control('PessoaFisicas[nacionalidade]', [
                                    'label' => false,
                                    'placeholder' => 'Nacionalidade',
                                    'class' => 'form-control',
                                    'value' => $pessoaFisica->nacionalidade
                                    
                                ]) ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Naturalidade</label>
                                <?= $this->Form->control('PessoaFisicas[naturalidade]', [
                                    'label' => false,
                                    'placeholder' => 'Naturalidade',
                                    'class' => 'form-control',
                                    'value' => $pessoaFisica->naturalidade
                                ]) ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Estado civil</label>
                                <?= $this->Form->select('PessoaFisicas[estado_civil_id]', [
                                    "1" => "CASADO (A)",
                                    "2" => "COMPANHEIRO (A)",
                                    "4" => "DIVORCIADO (A)",
                                    "5" => "OUTROS",
                                    "6" => "SEPARADO (A)",
                                    "7" => "SOLTEIRO (A)",
                                    "8" => "UNIÃO ESTÁVEL ",
                                    "9" => "VIUVO (A)"
                                ],[
                                    'label' => false,
                                    'empty' => 'Estado civil',
                                    'class' => 'form-control',
                                    'value' => $pessoaFisica->estado_civil_id
                                ]) ?>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="contato" role="tabpanel" aria-labelledby="contato-tab">
                        <div class="row">
                            <div class="form-group col-md-4">
                                <label class="control-label">WhatsApp</label>
                                <?= $this->Form->control('Contatos[whatsapp]', [
                                    'label' => false,
                                    'placeholder' => 'WhatsApp',
                                    'class' => 'form-control cel',
                                    'maxLength' => '15'
                                ]) ?>
                            </div>

                            <!--        <div class="form-group col-md-3">-->
                            <!--            <input class="form-control" type="email" id="confirmEmail" placeholder="Confirme seu email" required>-->
                            <!--            <span id="confirmEmailAlert" class="text-danger small" style="display: none">Confirme seu email</span>-->
                            <!--        </div>-->

                            <div class="form-group col-md-4">
                                <label>Celular (caso seja diferente do whatsapp)<b class="text-danger"> *</b></label>
                                <?= $this->Form->control('Contatos[celular]', [
                                    'label' => false,
                                    'placeholder' => 'Celular',
                                    'maxLength' => '15',
                                    'class' => 'form-control cel',
                                    'value' => $contato->celular,
                                    'required'
                                ]) ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Email<b class="text-danger"> *</b></label>
                                <?= $this->Form->control('Contatos[email]', [
                                    'label' => false,
                                    'type' => 'email',
                                    'placeholder' => 'Email',
                                    'class' => 'form-control',
                                    'value' => $contato->email,
                                    'required'
                                ]) ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Telefone fixo</label>
                                <?= $this->Form->control('Contatos[residencial]', [
                                    'label' => false,
                                    'placeholder' => 'Telefone',
                                    'maxLength' => '15',
                                    'value' => $contato->residencial,
                                    'class' => 'form-control tel'
                                ]) ?>
                            </div>

                            <div class="form-group col-md-4">
                                <label>Telefone comercial</label>
                                <?= $this->Form->control('Contatos[comercial]', [
                                    'label' => false,
                                    'placeholder' => 'Comercial',
                                    'maxLength' => '15',
                                    'value' => $contato->comercial,
                                    'class' => 'form-control tel'
                                ]) ?>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="endereco" role="tabpanel" aria-labelledby="endereco-tab">
                        <div class="row">

                            <div class="form-group col-md-3">
                                <label>CEP</label>
                                <?= $this->Form->control('Enderecos[cep]', [
                                    'label' => false,
                                    'placeholder' => 'CEP',
                                     'id' => 'cep',
                                    'value' => $endereco->cep,
                                    'class' => 'form-control cep'
                                ]) ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Estado</label>
                                <?= $this->Form->control('Cidades[estado_id]', [
                                    'label' => false,
                                    'empty' => 'Estado',
                                    'id' => 'estado',
                                    'class' => 'add_cadastro form-control',
                                    'required',
                                    'placeholder' => 'UF',
                                    'value' => $cidade->estado_id,                                   
                                    'options' => $estados
                                ]) ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Cidade<b class="text-danger"> *</b></label>
                                <?= $this->Form->control('Enderecos[cidade_id]', [
                                    'label' => false,
                                    'empty' => 'Cidade',
                                    'id' => 'cidade',
                                    'class' => 'add_cadastro form-control',
                                    'required',
                                    'value' => $endereco->cidade_id,
                                    'options' => $cidades
                                ]) ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Bairro</label>
                                <?= $this->Form->control('Enderecos[bairro_descricao]', [
                                    'label' => false,
                                    'placeholder' => 'Bairro',
                                    'value' => $endereco->bairro_descricao,
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Logradouro</label>
                                <?= $this->Form->control('Enderecos[logradouro_descricao]', [
                                    'label' => false,
                                    'placeholder' => 'Logradouro',
                                    'value' => $endereco->logradouro_descricao,
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                            <div class="form-group col-md-3">
                                <label>Número</label>
                                <?= $this->Form->control('Enderecos[numero]', [
                                    'label' => false,
                                    'placeholder' => 'Número',
                                    'value' => $endereco->numero,
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                            <div class="form-group col-md-6">
                                <label>Referência</label>
                                <?= $this->Form->control('Enderecos[referencia]', [
                                    'label' => false,
                                    'placeholder' => 'Referência',
                                    'value' => $endereco->referencia,
                                    'class' => 'form-control'
                                ]) ?>
                            </div>
                        </div>
                    </div>

                </div>
                <?= $this->Form->button(__('Salvar'), ['class' => 'btn btn-success validacaoTab']) ?>

            </div>

        </div>
    </div>


    <?= $this->Form->end() ?>



</div>


<?= $this->Html->script('cadastro');
    $this->Html->script('masks') ; 

?>
