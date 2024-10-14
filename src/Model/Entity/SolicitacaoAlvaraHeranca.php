<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoAlvaraHeranca Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property \Cake\I18n\FrozenDate $data_obito
 * @property string $algum_bem
 * @property string $apenas_valores
 * @property string $nenhum_bem
 * @property string $filho_menor
 * @property string $bens_a_dividir
 *
 * @property \App\Model\Entity\Solicitacao $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 */
class SolicitacaoAlvaraHeranca extends Entity
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
        'data_obito' => true,
        'algum_bem' => true,
        'apenas_valores' => true,
        'nenhum_bem' => true,
        'filho_menor' => true,
        'bens_a_dividir' => true,
        'solicitaco' => true,
        'sub_assunto' => true
    ];
}
