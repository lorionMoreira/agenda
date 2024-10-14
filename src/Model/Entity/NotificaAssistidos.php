<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cidade Entity
 *
 * @property int $id
 * @property int $funcionario_id
 * @property int $assistido_id
 * @property string $email_assistido
 * @property string $relato
 * @property date $data_notificacao
 * @property date $created
 * @property date $modified
 * @property string $origem
 * @property int $acao_historico_id
 * @property string $msg_lida
 * 
 *
 */
class NotificaAssistidos extends Entity
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
        'id' => true,
        'funcionario_id' => true,
        'assistido_id' => true,
        'email_assistido' => true,
        'relato' => true,
        'data_notificacao' => true,
        'created' => true,
        'modified' => true,
        'origem' => true,
        'acao_historico_id' => true,
        'msg_lida' => true
    ];
}
