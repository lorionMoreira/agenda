<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TipoPedido Entity
 *
 * @property int $id
 * @property string $nome
 *
 * @property \App\Model\Entity\SolicitacaoAtendimentoCivei[] $solicitacao_atendimento_civeis
 * @property \App\Model\Entity\SolicitacaoFazendaPublica[] $solicitacao_fazenda_publica
 * @property \App\Model\Entity\SolicitacaoSaude[] $solicitacao_saudes
 * @property \App\Model\Entity\SolicitacaoViolenciaDomestica[] $solicitacao_violencia_domesticas
 * @property \App\Model\Entity\TipoPedidoSubAssunto[] $tipo_pedido_sub_assuntos
 */
class TipoPedido extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'nome' => true,
        'solicitacao_atendimento_civeis' => true,
        'solicitacao_fazenda_publica' => true,
        'solicitacao_saudes' => true,
        'solicitacao_violencia_domesticas' => true,
        'tipo_pedido_sub_assuntos' => true
    ];
}
