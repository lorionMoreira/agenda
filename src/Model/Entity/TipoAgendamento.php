<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * TipoAgendamento Entity
 *
 * @property int $id
 * @property string $nome
 *
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 * @property \App\Model\Entity\VwAgendamento[] $vw_agendamentos
 */
class TipoAgendamento extends Entity
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
        'agendamentos' => true,
        'vw_agendamentos' => true
    ];
}
