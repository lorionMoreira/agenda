<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FilaPainei Entity
 *
 * @property int $id
 * @property int $unidade_id
 * @property string $nome
 * @property string $tipo_multimidia
 * @property int $qtd_fotos
 *
 * @property \App\Model\Entity\Unidade $unidade
 */class FilaPainei extends Entity
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
        'unidade_id' => true,
        'nome' => true,
        'tipo_multimidia' => true,
        'qtd_fotos' => true,
        'unidade' => true
    ];
}
