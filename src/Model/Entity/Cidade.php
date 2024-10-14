<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cidade Entity
 *
 * @property int $id
 * @property int $estado_id
 * @property string $nome
 * @property int $ordem
 * @property int $comarca_id
 *
 * @property \App\Model\Entity\Estado $estado
 * @property \App\Model\Entity\Comarca $comarca
 * @property \App\Model\Entity\Bairro[] $bairros
 * @property \App\Model\Entity\Endereco[] $enderecos
 * @property \App\Model\Entity\VwAssistido[] $vw_assistidos
 */
class Cidade extends Entity
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
        'estado_id' => true,
        'nome' => true,
        'ordem' => true,
        'comarca_id' => true,
        'estado' => true,
        'comarca' => true,
        'bairros' => true,
        'enderecos' => true,
        'vw_assistidos' => true
    ];
}
