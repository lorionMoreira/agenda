<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacoesHorario Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $horario_id
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\Horario $horario
 */
class SolicitacoesHorario extends Entity
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
        'horario_id' => true,
        'solicitaco' => true,
        'horario' => true
    ];
}
