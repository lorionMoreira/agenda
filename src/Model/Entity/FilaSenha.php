<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FilaSenha Entity
 *
 * @property int $id
 * @property string $senha
 * @property int $tipo_prioridade_id
 * @property int $funcionario_id
 * @property int $assistido_id
 * @property int $contador
 * @property \Cake\I18n\FrozenTime $data_hora_emissao
 * @property int $unidade_id
 * @property int $chamar
 * @property int $espera
 * @property int $painel_id
 * @property int $defensor_id
 * @property int $categoria_id
 * @property int $agendamento_id
 *
 * @property \App\Model\Entity\FilaTipoPrioridade $fila_tipo_prioridade
 * @property \App\Model\Entity\Funcionario $funcionario
 * @property \App\Model\Entity\Assistido $assistido
 * @property \App\Model\Entity\Unidade $unidade
 * @property \App\Model\Entity\FilaPainei $fila_painei
 * @property \App\Model\Entity\Defensor $defensor
 * @property \App\Model\Entity\FilaCategoria $fila_categoria
 * @property \App\Model\Entity\Agendamento $agendamento
 */class FilaSenha extends Entity
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
        'senha' => true,
        'tipo_prioridade_id' => true,
        'funcionario_id' => true,
        'assistido_id' => true,
        'contador' => true,
        'data_hora_emissao' => true,
        'unidade_id' => true,
        'chamar' => true,
        'espera' => true,
        'painel_id' => true,
        'defensor_id' => true,
        'categoria_id' => true,
        'agendamento_id' => true,
        'fila_tipo_prioridade' => true,
        'funcionario' => true,
        'assistido' => true,
        'unidade' => true,
        'fila_painei' => true,
        'defensor' => true,
        'fila_categoria' => true,
        'agendamento' => true
    ];
}
