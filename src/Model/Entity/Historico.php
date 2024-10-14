<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Historico Entity
 *
 * @property int $id
 * @property int $agendamento_id
 * @property int $situacao_id
 * @property \Cake\I18n\FrozenTime $data
 * @property string $observacao
 * @property int $funcionario_id
 *
 * @property \App\Model\Entity\Agendamento $agendamento
 * @property \App\Model\Entity\Situaco $situaco
 * @property \App\Model\Entity\Funcionario $funcionario
 */
class Historico extends Entity
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
        'agendamento_id' => true,
        'situacao_id' => true,
        'data' => true,
        'observacao' => true,
        'funcionario_id' => true,
        'agendamento' => true,
        'situaco' => true,
        'funcionario' => true
    ];
}
