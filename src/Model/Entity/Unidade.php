<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Unidade Entity
 *
 * @property int $id
 * @property string $nome
 * @property int $comarca_id
 * @property int $situacao
 * @property int $contato_id
 * @property int $endereco_id
 *
 * @property \App\Model\Entity\Endereco $endereco
 * @property \App\Model\Entity\Comarca $comarca
 * @property \App\Model\Entity\Contato $contato
 * @property \App\Model\Entity\Agendamento[] $agendamentos
 * @property \App\Model\Entity\Assistido[] $assistidos
 * @property \App\Model\Entity\Atendimento[] $atendimentos
 * @property \App\Model\Entity\ComarcasEspecializada[] $comarcas_especializadas
 * @property \App\Model\Entity\Crime[] $crimes
 * @property \App\Model\Entity\Curadoria[] $curadorias
 * @property \App\Model\Entity\EspecializadaBalanceamento[] $especializada_balanceamentos
 * @property \App\Model\Entity\ExecucaoPenai[] $execucao_penais
 * @property \App\Model\Entity\Feriado[] $feriados
 * @property \App\Model\Entity\FilaGuich[] $fila_guiches
 * @property \App\Model\Entity\FilaPainei[] $fila_paineis
 * @property \App\Model\Entity\FilaSenha[] $fila_senhas
 * @property \App\Model\Entity\Funcionario[] $funcionarios
 * @property \App\Model\Entity\JuizadoCriminai[] $juizado_criminais
 * @property \App\Model\Entity\Juventude[] $juventudes
 * @property \App\Model\Entity\Sala[] $salas
 * @property \App\Model\Entity\VwAtendimento[] $vw_atendimento
 * @property \App\Model\Entity\VwAgendamento[] $vw_agendamentos
 * @property \App\Model\Entity\VwAssistido[] $vw_assistidos
 * @property \App\Model\Entity\VwAtendimento[] $vw_atendimentos
 * @property \App\Model\Entity\VwCrcAtendimento[] $vw_crc_atendimentos
 * @property \App\Model\Entity\VwFilaGuich[] $vw_fila_guiches
 * @property \App\Model\Entity\VwFuncionario[] $vw_funcionarios
 * @property \App\Model\Entity\VwSenha[] $vw_senhas
 * @property \App\Model\Entity\Estagiario[] $estagiarios
 */
class Unidade extends Entity
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
        'comarca_id' => true,
        'endereco' => true,
        'situacao' => true,
        'contato_id' => true,
        'endereco_id' => true,
        'comarca' => true,
        'contato' => true,
        'agendamentos' => true,
        'assistidos' => true,
        'atendimentos' => true,
        'comarcas_especializadas' => true,
        'crimes' => true,
        'curadorias' => true,
        'especializada_balanceamentos' => true,
        'execucao_penais' => true,
        'feriados' => true,
        'fila_guiches' => true,
        'fila_paineis' => true,
        'fila_senhas' => true,
        'funcionarios' => true,
        'juizado_criminais' => true,
        'juventudes' => true,
        'salas' => true,
        'vw_atendimento' => true,
        'vw_agendamentos' => true,
        'vw_assistidos' => true,
        'vw_atendimentos' => true,
        'vw_crc_atendimentos' => true,
        'vw_fila_guiches' => true,
        'vw_funcionarios' => true,
        'vw_senhas' => true,
        'estagiarios' => true
    ];
}
