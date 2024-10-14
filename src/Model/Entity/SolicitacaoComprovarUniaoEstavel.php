<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoComprovarUniaoEstavel Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property string $companheiro_vivo
 * @property string $casal_vive_junto
 * @property string $obter_beneficio
 * @property string $inss
 * @property string $inst_estadual
 * @property string $inst_municipal
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 */
class SolicitacaoComprovarUniaoEstavel extends Entity
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
        'companheiro_vivo' => true,
        'casal_vive_junto' => true,
        'obter_beneficio' => true,
        'inss' => true,
        'inst_estadual' => true,
        'inst_municipal' => true,
        'solicitaco' => true,
        'sub_assunto' => true
    ];
}
