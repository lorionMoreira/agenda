<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Especializada Entity
 *
 * @property int $id
 * @property int $parent_id
 * @property string $nome
 * @property string $sigla
 * @property int $agd_servidor
 *
 * @property \App\Model\Entity\ParentEspecializada $parent_especializada
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 * @property \App\Model\Entity\AgendamentosTemp[] $agendamentos_temp
 * @property \App\Model\Entity\AtendimentoAtividade[] $atendimento_atividades
 * @property \App\Model\Entity\Audiencia[] $audiencias
 * @property \App\Model\Entity\EscalasFuncionario[] $escalas_funcionarios
 * @property \App\Model\Entity\EspecializadaBalanceamento[] $especializada_balanceamentos
 * @property \App\Model\Entity\ChildEspecializada[] $child_especializadas
 * @property \App\Model\Entity\Perfi[] $perfis
 * @property \App\Model\Entity\Periodo[] $periodos
 * @property \App\Model\Entity\RcEspecializadasTipoAtividade[] $rc_especializadas_tipo_atividades
 * @property \App\Model\Entity\RcEspecializadasTipoAtividadesCopy[] $rc_especializadas_tipo_atividades_copy
 * @property \App\Model\Entity\Relacionamento[] $relacionamentos
 * @property \App\Model\Entity\VwAgendamento[] $vw_agendamentos
 * @property \App\Model\Entity\Atuaco[] $atuacoes
 * @property \App\Model\Entity\Comarca[] $comarcas
 * @property \App\Model\Entity\Funcionario[] $funcionarios
 * @property \App\Model\Entity\TipoAtividade[] $tipo_atividades
 * @property \App\Model\Entity\UnidadeDefensoriai[] $unidade_defensoriais
 */
class Especializada extends Entity
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
        'parent_id' => true,
        'nome' => true,
        'sigla' => true,
        'agd_servidor' => true,
        'parent_especializada' => true,
        'agendamentos' => true,
        'agendamentos_temp' => true,
        'atendimento_atividades' => true,
        'audiencias' => true,
        'escalas_funcionarios' => true,
        'especializada_balanceamentos' => true,
        'child_especializadas' => true,
        'perfis' => true,
        'periodos' => true,
        'rc_especializadas_tipo_atividades' => true,
        'rc_especializadas_tipo_atividades_copy' => true,
        'relacionamentos' => true,
        'vw_agendamentos' => true,
        'atuacoes' => true,
        'comarcas' => true,
        'funcionarios' => true,
        'tipo_atividades' => true,
        'unidade_defensoriais' => true
    ];
}
