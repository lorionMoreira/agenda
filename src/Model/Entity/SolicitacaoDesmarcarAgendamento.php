<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoDesmarcarAgendamento Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property string $nome_agendado
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 */
class SolicitacaoDesmarcarAgendamento extends Entity
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
        'nome_agendado' => true,
        'data_agendada' => true,
        'hora_agendada' => true,
        'solicitaco' => true,
        'sub_assunto' => true
    ];
}
