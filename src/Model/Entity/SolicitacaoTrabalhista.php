<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoTrabalhista Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property int $vinculo_trabalhista_id
 * @property string $revisao_beneficio
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 * @property \App\Model\Entity\VinculoTrabalhista $vinculo_trabalhista
 */
class SolicitacaoTrabalhista extends Entity
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
        'sub_assunto_id' => true,
        'vinculo_trabalhista_id' => true,
        'revisao_beneficio' => true,
        'solicitaco' => true,
        'sub_assunto' => true,
        'vinculo_trabalhista' => true
    ];
}
