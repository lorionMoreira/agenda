<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Documento Entity
 *
 *
 * @property \App\Model\Entity\Acoes[] $acao
 */class Acoes extends Entity
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
        'tipo_acao_id' => true,
        'processo_id' => true,
        'assistido_id' => true,
        'data' => true,
        'numero' => true,
        'situacao_id' => true,
        'data_cadastro' => true,
        'social_id' => true,
        'especializada_id' => true,
        'subespecializada_id' => true,
        'funcionario_id' => true,
        'substituicao' => true,
        'vinculo_id' => true,
        'comentario_observacao' => true,
    ];
}
