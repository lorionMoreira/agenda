<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FilaHistorico Entity
 *
 * @property int $id
 * @property \Cake\I18n\FrozenTime $data_hora
 * @property int $senha_id
 * @property int $situacao_id
 * @property int $funcionario_id
 * @property string $observacao
 * @property int $atendente
 *
 * @property \App\Model\Entity\FilaSenha $fila_senha
 * @property \App\Model\Entity\FilaSituaco $fila_situaco
 * @property \App\Model\Entity\Funcionario $funcionario
 */class FilaHistorico extends Entity
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
        'data_hora' => true,
        'senha_id' => true,
        'situacao_id' => true,
        'funcionario_id' => true,
        'observacao' => true,
        'atendente' => true,
        'fila_senha' => true,
        'fila_situaco' => true,
        'funcionario' => true
    ];
}
