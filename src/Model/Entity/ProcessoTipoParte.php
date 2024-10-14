<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProcessoTipoParte Entity
 *
 * @property int $id
 * @property string $nome
 * @property string $cod_parte_integra_pje
 *
 * @property \App\Model\Entity\SolicitacaoProcesso[] $solicitacao_processo
 */
class ProcessoTipoParte extends Entity
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
        'cod_parte_integra_pje' => true,
        'solicitacao_processo' => true
    ];
}
