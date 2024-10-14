<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoCasamento Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property string $noivos_maiores
 * @property string $pais_de_acordo
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 */
class SolicitacaoCasamento extends Entity
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
        'noivos_maiores' => true,
        'pais_de_acordo' => true,
        'solicitaco' => true,
        'sub_assunto' => true
    ];
}
