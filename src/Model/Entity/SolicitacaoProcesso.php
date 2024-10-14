<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoProcesso Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $processo_motivo_contato_id
 * @property int $processo_tipo_parte_id
 * @property string $nome_parte_representada
 * @property string $nome_preso
 * @property string $rg_preso
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\ProcessoMotivoContato $processo_motivo_contato
 * @property \App\Model\Entity\ProcessoTipoParte $processo_tipo_parte
 */
class SolicitacaoProcesso extends Entity
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
        'processo_motivo_contato_id' => true,
        'processo_tipo_parte_id' => true,
        'nome_parte_representada' => true,
        'nome_preso' => true,
        'rg_preso' => true,
        'solicitaco' => true,
        'processo_motivo_contato' => true,
        'processo_tipo_parte' => true
    ];
}
