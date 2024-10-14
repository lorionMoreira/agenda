<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Agendamento $agendamento
 */
?>
<nav class="large-3 medium-4 columns" id="actions-sidebar">
    <ul class="side-nav">
        <li class="heading"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('Edit Agendamento'), ['action' => 'edit', $agendamento->id]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Agendamento'), ['action' => 'delete', $agendamento->id], ['confirm' => __('Are you sure you want to delete # {0}?', $agendamento->id)]) ?> </li>
        <li><?= $this->Html->link(__('List Agendamentos'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Agendamento'), ['action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Assistidos'), ['controller' => 'Assistidos', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Assistido'), ['controller' => 'Assistidos', 'action' => 'add']) ?> </li>
        <li><?= $this->Html->link(__('List Agendamentos'), ['controller' => 'Agendamentos', 'action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Agendamento'), ['controller' => 'Agendamentos', 'action' => 'add']) ?> </li>
    </ul>
</nav>
<div class="agendamentos view large-9 medium-8 columns content">
    <h3><?= h($agendamento->id) ?></h3>
    <table class="vertical-table">
        <tr>
            <th scope="row"><?= __('Assistido') ?></th>
            <td><?= $agendamento->has('assistido') ? $this->Html->link($agendamento->assistido->id, ['controller' => 'Assistidos', 'action' => 'view', $agendamento->assistido->id]) : '' ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Id') ?></th>
            <td><?= $this->Number->format($agendamento->id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipo Acao Id') ?></th>
            <td><?= $this->Number->format($agendamento->tipo_acao_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Agenda Id') ?></th>
            <td><?= $this->Number->format($agendamento->agenda_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Funcionario Id') ?></th>
            <td><?= $this->Number->format($agendamento->funcionario_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Especializada Id') ?></th>
            <td><?= $this->Number->format($agendamento->especializada_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Acao Id') ?></th>
            <td><?= $this->Number->format($agendamento->acao_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Processo Id') ?></th>
            <td><?= $this->Number->format($agendamento->processo_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Atuacao Id') ?></th>
            <td><?= $this->Number->format($agendamento->atuacao_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Agendamento Id') ?></th>
            <td><?= $this->Number->format($agendamento->agendamento_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipo Agendamento Id') ?></th>
            <td><?= $this->Number->format($agendamento->tipo_agendamento_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Comarca Id') ?></th>
            <td><?= $this->Number->format($agendamento->comarca_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Unidade Id') ?></th>
            <td><?= $this->Number->format($agendamento->unidade_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Sala Id') ?></th>
            <td><?= $this->Number->format($agendamento->sala_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Conciliacao Id') ?></th>
            <td><?= $this->Number->format($agendamento->conciliacao_id) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Tipo Atendimento') ?></th>
            <td><?= $this->Number->format($agendamento->tipo_atendimento) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Prioridade') ?></th>
            <td><?= $this->Number->format($agendamento->prioridade) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Prazo') ?></th>
            <td><?= h($agendamento->prazo) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Data Cadastro') ?></th>
            <td><?= h($agendamento->data_cadastro) ?></td>
        </tr>
        <tr>
            <th scope="row"><?= __('Dt Cadastro') ?></th>
            <td><?= h($agendamento->dt_cadastro) ?></td>
        </tr>
    </table>
    <div class="row">
        <h4><?= __('Observacao') ?></h4>
        <?= $this->Text->autoParagraph(h($agendamento->observacao)); ?>
    </div>
    <div class="related">
        <h4><?= __('Related Agendamentos') ?></h4>
        <?php if (!empty($agendamento->agendamentos)): ?>
        <table cellpadding="0" cellspacing="0">
            <tr>
                <th scope="col"><?= __('Id') ?></th>
                <th scope="col"><?= __('Assistido Id') ?></th>
                <th scope="col"><?= __('Tipo Acao Id') ?></th>
                <th scope="col"><?= __('Agenda Id') ?></th>
                <th scope="col"><?= __('Funcionario Id') ?></th>
                <th scope="col"><?= __('Especializada Id') ?></th>
                <th scope="col"><?= __('Observacao') ?></th>
                <th scope="col"><?= __('Acao Id') ?></th>
                <th scope="col"><?= __('Processo Id') ?></th>
                <th scope="col"><?= __('Atuacao Id') ?></th>
                <th scope="col"><?= __('Agendamento Id') ?></th>
                <th scope="col"><?= __('Tipo Agendamento Id') ?></th>
                <th scope="col"><?= __('Comarca Id') ?></th>
                <th scope="col"><?= __('Unidade Id') ?></th>
                <th scope="col"><?= __('Sala Id') ?></th>
                <th scope="col"><?= __('Conciliacao Id') ?></th>
                <th scope="col"><?= __('Prazo') ?></th>
                <th scope="col"><?= __('Tipo Atendimento') ?></th>
                <th scope="col"><?= __('Data Cadastro') ?></th>
                <th scope="col"><?= __('Prioridade') ?></th>
                <th scope="col"><?= __('Dt Cadastro') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
            <?php foreach ($agendamento->agendamentos as $agendamentos): ?>
            <tr>
                <td><?= h($agendamentos->id) ?></td>
                <td><?= h($agendamentos->assistido_id) ?></td>
                <td><?= h($agendamentos->tipo_acao_id) ?></td>
                <td><?= h($agendamentos->agenda_id) ?></td>
                <td><?= h($agendamentos->funcionario_id) ?></td>
                <td><?= h($agendamentos->especializada_id) ?></td>
                <td><?= h($agendamentos->observacao) ?></td>
                <td><?= h($agendamentos->acao_id) ?></td>
                <td><?= h($agendamentos->processo_id) ?></td>
                <td><?= h($agendamentos->atuacao_id) ?></td>
                <td><?= h($agendamentos->agendamento_id) ?></td>
                <td><?= h($agendamentos->tipo_agendamento_id) ?></td>
                <td><?= h($agendamentos->comarca_id) ?></td>
                <td><?= h($agendamentos->unidade_id) ?></td>
                <td><?= h($agendamentos->sala_id) ?></td>
                <td><?= h($agendamentos->conciliacao_id) ?></td>
                <td><?= h($agendamentos->prazo) ?></td>
                <td><?= h($agendamentos->tipo_atendimento) ?></td>
                <td><?= h($agendamentos->data_cadastro) ?></td>
                <td><?= h($agendamentos->prioridade) ?></td>
                <td><?= h($agendamentos->dt_cadastro) ?></td>
                <td class="actions">
                    <?= $this->Html->link(__('View'), ['controller' => 'Agendamentos', 'action' => 'view', $agendamentos->id]) ?>
                    <?= $this->Html->link(__('Edit'), ['controller' => 'Agendamentos', 'action' => 'edit', $agendamentos->id]) ?>
                    <?= $this->Form->postLink(__('Delete'), ['controller' => 'Agendamentos', 'action' => 'delete', $agendamentos->id], ['confirm' => __('Are you sure you want to delete # {0}?', $agendamentos->id)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php endif; ?>
    </div>
</div>
