<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AreaAtuacao Entity
 *
 * @property int $id
 *
 * @property \App\Model\Entity\AcoesRelacionada[] $acoes_relacionada
 * @property \App\Model\Entity\Documento[] $documentos
 * @property \App\Model\Entity\Location[] $location
 */class AreaAtuacao extends Entity
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
        'acoes_relacionada' => true,
        'documentos' => true,
        'location' => true        
    ];
}
