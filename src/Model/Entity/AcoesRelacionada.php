<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AcoesRelacionada Entity
 *
 * @property int $id
 * @property int $area_atuacao_id
 *
 * @property \App\Model\Entity\AreaAtuacao $area_atuacao
 * @property \App\Model\Entity\Duvida[] $duvida
 */class AcoesRelacionada extends Entity
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
        'area_atuacao_id' => true,
        'area_atuacao' => true,
        'duvida' => true,
        'nome' => true
    ];
}
