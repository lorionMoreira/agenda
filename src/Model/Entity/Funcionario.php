<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;

/**
 * Funcionario Entity
 *
 * @property int $id
 * @property int $registro
 * @property \Cake\I18n\FrozenDate $data_inicio
 * @property \Cake\I18n\FrozenDate $data_fim
 * @property int $tipo_funcionario
 * @property int $situacao_id
 * @property int $pessoa_id
 * @property int $comarca_id
 * @property int $nivel_id
 * @property int $unidade_id
 *
 * @property \App\Model\Entity\Situaco $situaco
 * @property \App\Model\Entity\Pessoa $pessoa
 * @property \App\Model\Entity\Comarca $comarca
 * @property \App\Model\Entity\Nivel $nivel
 * @property \App\Model\Entity\Unidade $unidade
 * @property \App\Model\Entity\AcaoHistorico[] $acao_historicos
 * @property \App\Model\Entity\Afastamento[] $afastamentos
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 * @property \App\Model\Entity\AgendamentosTemp[] $agendamentos_temp
 * @property \App\Model\Entity\Anexo[] $anexos
 * @property \App\Model\Entity\Assistido[] $assistidos
 * @property \App\Model\Entity\Atendimento[] $atendimentos
 * @property \App\Model\Entity\AtividadeExtra[] $atividade_extras
 * @property \App\Model\Entity\AtuacoesDesignaco[] $atuacoes_designacoes
 * @property \App\Model\Entity\Audiencia[] $audiencias
 * @property \App\Model\Entity\Civei[] $civeis
 * @property \App\Model\Entity\Condico[] $condicoes
 * @property \App\Model\Entity\Crime[] $crimes
 * @property \App\Model\Entity\Curadoria[] $curadorias
 * @property \App\Model\Entity\Designaco[] $designacoes
 * @property \App\Model\Entity\DireitoHumano[] $direito_humanos
 * @property \App\Model\Entity\Documento[] $documentos
 * @property \App\Model\Entity\Estagiario[] $estagiarios
 * @property \App\Model\Entity\ExecucaoPenai[] $execucao_penais
 * @property \App\Model\Entity\ExecucaoPenaisProcesso[] $execucao_penais_processos
 * @property \App\Model\Entity\Familia[] $familias
 * @property \App\Model\Entity\FilaGuich[] $fila_guiches
 * @property \App\Model\Entity\FilaHistorico[] $fila_historicos
 * @property \App\Model\Entity\FilaSenha[] $fila_senhas
 * @property \App\Model\Entity\Fila[] $filas
 * @property \App\Model\Entity\Flagrante[] $flagrantes
 * @property \App\Model\Entity\Fundiario[] $fundiarios
 * @property \App\Model\Entity\Historico[] $historicos
 * @property \App\Model\Entity\Idoso[] $idosos
 * @property \App\Model\Entity\InstanciaSuperiorCivei[] $instancia_superior_civeis
 * @property \App\Model\Entity\JuizadoCriminai[] $juizado_criminais
 * @property \App\Model\Entity\JuizadoEspecialCivei[] $juizado_especial_civeis
 * @property \App\Model\Entity\JuventudeCivei[] $juventude_civeis
 * @property \App\Model\Entity\Juventude[] $juventudes
 * @property \App\Model\Entity\Medida[] $medidas
 * @property \App\Model\Entity\NucleoMulhere[] $nucleo_mulheres
 * @property \App\Model\Entity\PaHistorico[] $pa_historicos
 * @property \App\Model\Entity\Planto[] $plantoes
 * @property \App\Model\Entity\PopulacaoRua[] $populacao_ruas
 * @property \App\Model\Entity\Sociai[] $sociais
 * @property \App\Model\Entity\Titularidade[] $titularidades
 * @property \App\Model\Entity\Trabalho[] $trabalhos
 * @property \App\Model\Entity\VwAtendimento[] $vw_atendimento
 * @property \App\Model\Entity\VwAfastamento[] $vw_afastamentos
 * @property \App\Model\Entity\VwAgendamento[] $vw_agendamentos
 * @property \App\Model\Entity\VwAssistido[] $vw_assistidos
 * @property \App\Model\Entity\VwAtendimento[] $vw_atendimentos
 * @property \App\Model\Entity\VwCrcAtendimento[] $vw_crc_atendimentos
 * @property \App\Model\Entity\VwDesignaco[] $vw_designacoes
 * @property \App\Model\Entity\VwFilaGuich[] $vw_fila_guiches
 * @property \App\Model\Entity\VwSenha[] $vw_senhas
 * @property \App\Model\Entity\Escala[] $escalas
 * @property \App\Model\Entity\Especializada[] $especializadas
 * @property \App\Model\Entity\UnidadeMovei[] $unidade_moveis
 */
class Funcionario extends Entity
{
    use LazyLoadEntityTrait;
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
        'registro' => true,
        'data_inicio' => true,
        'data_fim' => true,
        'tipo_funcionario' => true,
        'situacao_id' => true,
        'pessoa_id' => true,
        'comarca_id' => true,
        'nivel_id' => true,
        'unidade_id' => true,
        'situaco' => true,
        'pessoa' => true,
        'comarca' => true,
        'nivel' => true,
        'unidade' => true,
        'acao_historicos' => true,
        'afastamentos' => true,
        'agendamentos' => true,
        'agendamentos_temp' => true,
        'anexos' => true,
        'assistidos' => true,
        'atendimentos' => true,
        'atividade_extras' => true,
        'atuacoes_designacoes' => true,
        'audiencias' => true,
        'civeis' => true,
        'condicoes' => true,
        'crimes' => true,
        'curadorias' => true,
        'designacoes' => true,
        'direito_humanos' => true,
        'documentos' => true,
        'estagiarios' => true,
        'execucao_penais' => true,
        'execucao_penais_processos' => true,
        'familias' => true,
        'fila_guiches' => true,
        'fila_historicos' => true,
        'fila_senhas' => true,
        'filas' => true,
        'flagrantes' => true,
        'fundiarios' => true,
        'historicos' => true,
        'idosos' => true,
        'instancia_superior_civeis' => true,
        'juizado_criminais' => true,
        'juizado_especial_civeis' => true,
        'juventude_civeis' => true,
        'juventudes' => true,
        'medidas' => true,
        'nucleo_mulheres' => true,
        'pa_historicos' => true,
        'plantoes' => true,
        'populacao_ruas' => true,
        'sociais' => true,
        'titularidades' => true,
        'trabalhos' => true,
        'vw_atendimento' => true,
        'vw_afastamentos' => true,
        'vw_agendamentos' => true,
        'vw_assistidos' => true,
        'vw_atendimentos' => true,
        'vw_crc_atendimentos' => true,
        'vw_designacoes' => true,
        'vw_fila_guiches' => true,
        'vw_senhas' => true,
        'escalas' => true,
        'especializadas' => true,
        'unidade_moveis' => true
    ];
}
