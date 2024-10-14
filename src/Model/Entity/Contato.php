<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Contato Entity
 *
 * @property int $id
 * @property string $celular
 * @property string $residencial
 * @property string $comercial
 * @property string $recado
 * @property string $email
 * @property string $email_alternativo
 * @property string $responsavel
 *
 * @property \App\Model\Entity\Pessoa[] $pessoas
 * @property \App\Model\Entity\Unidade[] $unidades
 * @property \App\Model\Entity\VwAssistido[] $vw_assistidos
 * @property \App\Model\Entity\VwFuncionario[] $vw_funcionarios
 */
class Contato extends Entity
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
        'celular' => true,
        'residencial' => true,
        'comercial' => true,
        'recado' => true,
        'email' => true,
        'email_alternativo' => true,
        'responsavel' => true,
        'pessoas' => true,
        'unidades' => true,
        'vw_assistidos' => true,
        'vw_funcionarios' => true
    ];
}
