<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Comarca Entity
 *
 * @property int $id
 * @property string $nome
 *
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 * @property \App\Model\Entity\AgendamentosTemp[] $agendamentos_temp
 * @property \App\Model\Entity\AtendimentoAtividade[] $atendimento_atividades
 * @property \App\Model\Entity\Atendimento[] $atendimentos
 * @property \App\Model\Entity\Audiencia[] $audiencias
 * @property \App\Model\Entity\Cidade[] $cidades
 * @property \App\Model\Entity\Civei[] $civeis
 * @property \App\Model\Entity\Crime[] $crimes
 * @property \App\Model\Entity\Curadoria[] $curadorias
 * @property \App\Model\Entity\Delegacia[] $delegacias
 * @property \App\Model\Entity\DireitoHumano[] $direito_humanos
 * @property \App\Model\Entity\Familia[] $familias
 * @property \App\Model\Entity\Feriado[] $feriados
 * @property \App\Model\Entity\Fila[] $filas
 * @property \App\Model\Entity\Flagrante[] $flagrantes
 * @property \App\Model\Entity\Funcionario[] $funcionarios
 * @property \App\Model\Entity\Fundiario[] $fundiarios
 * @property \App\Model\Entity\Idoso[] $idosos
 * @property \App\Model\Entity\InstanciaSuperiorCivei[] $instancia_superior_civeis
 * @property \App\Model\Entity\Instituico[] $instituicoes
 * @property \App\Model\Entity\JuizadoCriminai[] $juizado_criminais
 * @property \App\Model\Entity\JuizadoEspecialCivei[] $juizado_especial_civeis
 * @property \App\Model\Entity\JuventudeCivei[] $juventude_civeis
 * @property \App\Model\Entity\Juventude[] $juventudes
 * @property \App\Model\Entity\NucleoMulhere[] $nucleo_mulheres
 * @property \App\Model\Entity\Periodo[] $periodos
 * @property \App\Model\Entity\PopulacaoRua[] $populacao_ruas
 * @property \App\Model\Entity\Processo[] $processos
 * @property \App\Model\Entity\RcEnvio[] $rc_envios
 * @property \App\Model\Entity\Relacionamento[] $relacionamentos
 * @property \App\Model\Entity\Saude[] $saudes
 * @property \App\Model\Entity\UnidadeDefensoriai[] $unidade_defensoriais
 * @property \App\Model\Entity\UnidadeMovei[] $unidade_moveis
 * @property \App\Model\Entity\Unidade[] $unidades
 * @property \App\Model\Entity\VwAtendimento[] $vw_atendimento
 * @property \App\Model\Entity\VwAgendamento[] $vw_agendamentos
 * @property \App\Model\Entity\VwAssistido[] $vw_assistidos
 * @property \App\Model\Entity\VwAtendimento[] $vw_atendimentos
 * @property \App\Model\Entity\VwDesignaco[] $vw_designacoes
 * @property \App\Model\Entity\VwFuncionario[] $vw_funcionarios
 * @property \App\Model\Entity\Especializada[] $especializadas
 */
class Comarca extends Entity
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
        'nome' => true,
        'agendamentos' => true,
        'agendamentos_temp' => true,
        'atendimento_atividades' => true,
        'atendimentos' => true,
        'audiencias' => true,
        'cidades' => true,
        'civeis' => true,
        'crimes' => true,
        'curadorias' => true,
        'delegacias' => true,
        'direito_humanos' => true,
        'familias' => true,
        'feriados' => true,
        'filas' => true,
        'flagrantes' => true,
        'funcionarios' => true,
        'fundiarios' => true,
        'idosos' => true,
        'instancia_superior_civeis' => true,
        'instituicoes' => true,
        'juizado_criminais' => true,
        'juizado_especial_civeis' => true,
        'juventude_civeis' => true,
        'juventudes' => true,
        'nucleo_mulheres' => true,
        'periodos' => true,
        'populacao_ruas' => true,
        'processos' => true,
        'rc_envios' => true,
        'relacionamentos' => true,
        'saudes' => true,
        'unidade_defensoriais' => true,
        'unidade_moveis' => true,
        'unidades' => true,
        'vw_atendimento' => true,
        'vw_agendamentos' => true,
        'vw_assistidos' => true,
        'vw_atendimentos' => true,
        'vw_designacoes' => true,
        'vw_funcionarios' => true,
        'especializadas' => true
    ];
}
