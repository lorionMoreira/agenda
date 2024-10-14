<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Agendamento[]|\Cake\Collection\CollectionInterface $agendamentos
 */
 use Cake\I18n\Time;
?>

<?php

    if(isset($_GET["suces"])){
?>
        <div class="noPrint" style="text-align: center; color: white; background-color: #32CD32; font-weight: bold; font-size: 15; font-family: 'Time new Roman'; clear: both; margin-top: 10px; ">
            <span>
                SOLICITAÇÃO ENVIADA. <br>FIQUE ATENTO(A) AO SEU E-MAIL!<br> 
            </span>
        </div>
        
<?php
    }
?>

<div class="noPrint" style="text-align: center; color: white; background-color: #32CD32; font-weight: bold; font-size: 16; font-family: 'Arial'; clear: both; margin-top: 10px; ">
                Você também pode ficar sabendo a data do seu agendamento aqui no site! Consulte o site com frequência para saber o dia de comparecer na Defensoria Pública!            
</div>


<!-- Topico tabela  -->
<div class="row">
    <div class=" col-md-auto  mt-5">
        <p class="h5 py-3 px-3  mb-0 titulosTabela">Solicitações</p>
    </div>
</div>

<!-- Inico tabela agendamentos pendentes -->
<div class="table-responsive">
    <table class="table table-hover ">
        <thead class="corTexto-cabecalho-tabela">
            <tr>
                <th scope="col "> Data da solicitação </th>
                <th scope="col"> Assunto </th>
                <th scope="col"> Status </th>
                <th scope="col"> Nº Processo </th>
                <th scope="col" class="actions"><?= __('Ações') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($solicitacoes as $s): #debug($assuntos) /*debug($s);*/ ?>
            <tr>
                <td><?= $s->data_cadastro->i18nFormat('dd/MM/Y') ?></td>
                <?php if($s->processo == 1){?>
                <td>USUÁRIO JÁ POSSUI PROCESSO</td>
                <?php } else{?>
                <td><?= $assuntos[$s->assunto_id] #$s->comarca->nome ?></td>
                <?php } ?>
                <td><?= $status_descricao[$s->status]. $s->id; ?></td>
                <td><?= $s->numero_processo ?></td>
                
                <td class="actions">
                    <?= $this->Form->postLink(
                        $this->Html->image('cancelar_table.png', ['alt' => 'Cancelar']),
                        ['action' => 'cancelSolicitacao', $s->id],
                        [
                            'confirm' => __('Tem certeza que deseja cancelar sua solicitação?', $s->id),
                            'escape' => false
                        ]
                    ) ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Fim tabela agendamentos pendentes -->
