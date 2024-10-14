<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoRegulamentacaoVisita Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property string $visita_determinada
 * @property string $modificar
 * @property string $cumprir_decisao
 *
 * @property \App\Model\Entity\Solicitacao $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 */
class SolicitacaoRegulamentacaoVisita extends Entity
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
        'visita_determinada' => true,
        'modificar' => true,
        'cumprir_decisao' => true,
        'solicitaco' => true,
        'sub_assunto' => true
    ];
}
