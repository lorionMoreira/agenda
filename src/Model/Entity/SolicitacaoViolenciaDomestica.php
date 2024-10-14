<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoViolenciaDomestica Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property int $tipo_pedido_id
 * @property string $ocorrencia_policial
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 * @property \App\Model\Entity\TipoPedido $tipo_pedido
 */
class SolicitacaoViolenciaDomestica extends Entity
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
        'solicitacao_id' => true,
        'sub_assunto_id' => true,
        'tipo_pedido_id' => true,
        'ocorrencia_policial' => true,
        'risco_fisico_emocional' => true,
        'medida_protetiva_urgencia' => true,
        'situacao_violencia' => true,
        'ultima_violencia_sofrida' => true,
        'solicitaco' => true,
        'sub_assunto' => true,
        'tipo_pedido' => true
    ];
}
