<?php use Cake\I18n\Time; ?>

<div class="row mt-5 justify-content-md-center ">
    <div class="col-md-6 text-center">
        <img src="/img/logodef.png" alt="" class="">
        <p class="display-4" style="font-size: 2em">Comprovante de Atendimento</p>
        <hr>
    </div>
</div>

<div class="row justify-content-md-center ">
    <div class="col-md-6">
        <ul class="list-unstyled">
            
            <li>
                <p class="mb-0">
                    <spam class="font-weight-bold">Nº de triagem:</spam>
                    <?= $agendamento->assistido->numero_triagem ?>
                </p>
            </li>
            <li>
                <p class="mb-0">
                    <spam class="font-weight-bold">Tipo de atendimento: </spam>
                    <?= $agendamento->especializada->nome ?>
                </p>
            </li>
        </ul>
    </div>
</div>

<div class="row justify-content-md-center ">
    <div class="col-md-6">
        <ul class="list-unstyled">
            <li>
                <p class="mb-0">
                    <spam class="font-weight-bold">Nome:</spam>
                    <?= $agendamento->assistido->pessoa->nome ?>
                </p>
            </li>
            <li>
                <p class="mb-0">
                    <spam class="font-weight-bold">Data atendimento: </spam>
                    <?= $agendamento->agenda->data->i18nFormat('dd/M/Y') . " " .
                    $agendamento->agenda->escala->hora->nome ?></p>
            </li>
            <?php //debug($agendamento); ?>
            <?php if($agendamento->especializada->atendimento_remoto == 1){?>                
                <li>
                    <p class="mb-0">
                        <spam class="font-weight-bold">Local: </spam>
                        <?= 'O ATENDIMENTO SERÁ REMOTO ATRAVÉS DE CONTATO TELEFÔNICO.' ?>
                    </p>
                </li>
            <?php }else{ ?>

                <li>
                    <p class="mb-0">
                        <spam class="font-weight-bold">Local: </spam>
                        <?= utf8_decode($agendamento->funcionario->unidade->nome) ?>
                    </p>
                </li>
                <li>
                    <p class="mb-0">
                        <spam class="font-weight-bold">Endereço:</spam>
                        <?= utf8_decode($agendamento->funcionario->unidade->endereco) ?>
                    </p>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>


<div class="row justify-content-md-center ">
    <div class="col-md-6 mt-3">    
        <?php if (!empty($agendamento->aco->acoes_tipo_documentos)){ ?>    
        <p class="h6 font-weight-bold">Documentos Necessários</p>
        <ul class="list-unstyled pb-2">
            <?php foreach ($agendamento->aco->acoes_tipo_documentos as $documentos): ?>
                
                <li>
                    <p class="mb-0">* <?php echo utf8_decode($documentos->tipo_documento->nome) ?></p>
                </li>
                           
            <?php endforeach; ?>
        </ul> 
        <?php } ?>
        <p class="h6">* Fique atento ao seu e-mail e em caso de dúvida ligue para 129. Você também pode ficar sabendo a data do seu agendamento no site! Consulte o site do Agendamento on line da DPE com frequência para saber o dia de comparecer na Defensoria Pública! </p>
        <hr>
    </div>
</div>

