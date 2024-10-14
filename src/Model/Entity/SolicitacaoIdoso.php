<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoIdoso Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property int $tipo_pedido_id
 * @property string $ocorrência_policial
 * @property string $maus_tratos
 *
 * @property \App\Model\Entity\Solicitacao $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 * @property \App\Model\Entity\TipoPedido $tipo_pedido
 */
class SolicitacaoIdoso extends Entity
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
        'ocorrência_policial' => true,
        'maus_tratos' => true,
        'solicitaco' => true,
        'sub_assunto' => true,
        'tipo_pedido' => true
    ];
}
