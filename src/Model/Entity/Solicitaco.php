<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Solicitaco Entity
 *
 * @property int $id
 * @property int $comarca
 * @property string $relato
 * @property int $processo
 * @property string $numero_processo
 * @property int $assunto_id
 *
 * @property \App\Model\Entity\Horario[] $horarios
 * @property \App\Model\Entity\Assunto $assunto
 */
class Solicitaco extends Entity
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
        'comarca' => true,
        'relato' => true,
        'processo' => true,
        'numero_processo' => true,
        'horarios' => true,
        'assunto_id' => true,
        'solicitacoes_horarios' => true,
        'sigad_user' => true,
        'agendamento_id' => true,
        'sub_assunto_id' => true
    ];
}
