<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Agendamento[]|\Cake\Collection\CollectionInterface $agendamentos
 */
 use Cake\I18n\Time;
?>

<div class="row justify-content-md-center pt-4">
    <div class="alert alert-info col-md-11" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="alert-heading">ATENÇÃO!</h4>
        <p>Antes de fazer a solicitação clique em <strong>Meus Dados</strong> (no menu) e verifique se o seu cadastro está correto e completo. 
            É muito importante que seu cadastro esteja correto para que nossos atendentes respondam ao atendimento através do e-mail.</p>
    </div>
</div>
<!-- <div class="noPrint" style="text-align: center; background-color: #FFFF33; font-weight: bold;  font-family: 'Time new Roman'; clear: both; margin-top: 10px; ">
        <span>
            ATENÇÃO! Antes de fazer a solicitação clique em "Meus Dados" (no menu) e verifique se o seu cadastro está correto e completo. <br>É muito importante que seu cadastro esteja correto para que nossos atendentes respondam ao atendimento através do e-mail.
        </span>
</div> -->

<!-- Topico tabela  -->
<div class="row">
    <div class=" col-md-auto  mt-2">
        <p class="h5 py-3 px-3  mb-0 titulosTabela">Acompanhamento de Agendamentos</p>
    </div>
</div>

<!-- Inico tabela agendamentos pendentes -->
<div class="table-responsive">
    <table class="table table-hover ">
        <thead class="corTexto-cabecalho-tabela">
            <tr>
                <th scope="col "> Data do agendamento </th>
                <th scope="col"> Assunto </th>
                <th scope="col"> Cidade </th>
                <th scope="col"> Unidade </th>
				<th scope="col"> Situação </th>
                <th scope="col" class="actions"><?= __('Ações') ?></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($agendamentos as $agendamento): ?>
            <?php
				
                $imprime = true;
                foreach($agendamento->historicos as $historico)
                {				
				if($historico->situacao_id == 43)
                    {
                        $imprime = false;
                        break;
                    }
                }
            ?>

            <?php if($imprime): ?>
                <tr>
                    <td><?= $agendamento->agenda->data->i18nFormat('dd/M/Y') . " " . $agendamento->agenda->escala->hora->nome ?></td>
                    <td><?= $agendamento->especializada->nome ?></td>
                    <td><?= $agendamento->comarca->nome ?></td>
                    <td><?= __($agendamento->funcionario->unidade->nome) ?></td>					
					<td><?= $historico->situaco->nome ?></td>					
                    <td class="actions">
                        <?= $this->Html->link(
                            $this->Html->image('extrato_table.png', ['alt' => 'Comprovante']),
                            ['action' => 'comprovante', $agendamento->id],
                            ['escape' => false, 'target' => '_blank']
                        ) ?>
                        <?php $this->Html->link(
                            $this->Html->image('historico_table.png', ['alt' => 'Histórico']),
                            ['action' => 'historico', $agendamento->id],
                            ['escape' => false]
                        ) ?>
                        <?php
                            if($agendamento->especializada->id == 15){
                               
                                echo '';
                            }else{
                                echo $this->Form->postLink(
                                    $this->Html->image('cancelar_table.png', ['alt' => 'Cancelar']),
                                    ['action' => 'cancel', $agendamento->id],
                                    [
                                        'confirm' => __('Tem certeza que deseja cancelar seu agendamento?', $agendamento->id),
                                        'escape' => false
                                    ]
                                );
                            } 
                        ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Fim tabela agendamentos pendentes -->

<div class="row">
    <div class=" col-md-auto  mt-5">
        <p class="h5 py-3 px-3  mb-0 titulosTabela">Agendamentos Passados</p>
    </div>
</div>
<!-- Inico tabela agendamentos Finalizados -->
<div class="table-responsive">
    <table class="table table table-hover">
        <thead class="corTexto-cabecalho-tabela">
            <tr>
                <th> Data do agendamento </th>
                <th> Assunto </th>
                <th> Cidade </th>
                <th> Unidade </th>
                <!-- <th class="actions"><?= __('Ações') ?></th> -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($agendamentos_finalizados as $agendamento): ?>
            <tr>
                <td><?= $agendamento->agenda->data->i18nFormat('dd/M/Y') ." ". $agendamento->agenda->escala->hora->nome?></td>
                <td><?= $agendamento->especializada->nome ?></td>
                <td><?= $agendamento->comarca->nome ?></td>
                <td><?= __($agendamento->funcionario->unidade->nome) ?></td>
                <!-- <td class="actions">
                    <?= $this->Html->link(
                        $this->Html->image('historico_table.png', ['alt' => 'Histórico']),
                        ['action' => 'historico', $agendamento->id],
                        ['escape' => false]
                    ) ?>
                </td> -->
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<!-- Fim tabela agendamentos Finalizados -->
