<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Endereco Entity
 *
 * @property int $id
 * @property string $numero
 * @property string $referencia
 * @property string $logradouro_descricao
 * @property string $bairro_descricao
 * @property int $cidade_id
 * @property string $cep
 *
 * @property \App\Model\Entity\Cidade $cidade
 * @property \App\Model\Entity\Pessoa[] $pessoas
 * @property \App\Model\Entity\PlantaoAtendimento[] $plantao_atendimentos
 * @property \App\Model\Entity\Unidade[] $unidades
 * @property \App\Model\Entity\VwAssistido[] $vw_assistidos
 * @property \App\Model\Entity\VwFuncionario[] $vw_funcionarios
 */
class Endereco extends Entity
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
        'numero' => true,
        'referencia' => true,
        'logradouro_descricao' => true,
        'bairro_descricao' => true,
        'cidade_id' => true,
        'cep' => true,
        'cidade' => true,
        'pessoas' => true,
        'plantao_atendimentos' => true,
        'unidades' => true,
        'vw_assistidos' => true,
        'vw_funcionarios' => true
    ];
}
