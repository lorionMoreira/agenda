<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoCertidaoCasamento Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property string $realizado_na_bahia
 * @property string $retificacao
 * @property string $averbacao_nome_casada
 * @property string $averbacao_obito
 * @property string $transcricao_casamento
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 */
class SolicitacaoCertidaoCasamento extends Entity
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
        'realizado_na_bahia' => true,
        'retificacao' => true,
        'averbacao_nome_casada' => true,
        'averbacao_obito' => true,
        'transcricao_casamento' => true,
        'solicitaco' => true,
        'sub_assunto' => true
    ];
}
