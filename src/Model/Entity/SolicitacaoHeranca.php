<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoHeranca Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property string $deixou_bens
 * @property string $deixou_valores
 * @property string $sem_bens
 * @property \Cake\I18n\FrozenDate $data_obito
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 */
class SolicitacaoHeranca extends Entity
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
        'deixou_bens' => true,
        'deixou_valores' => true,
        'sem_bens' => true,
        'data_obito' => true,
        'filho_menor' => true,
        'solicitaco' => true,
        'sub_assunto' => true
    ];
}
