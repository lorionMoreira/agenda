<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SubAssunto Entity
 *
 * @property int $id
 * @property string $nome
 * @property int $assunto_id
 * @property string $nome_tab_ligacao
 *
 * @property \App\Model\Entity\Assunto $assunto
 * @property \App\Model\Entity\SolicitacaoAlimento[] $solicitacao_alimentos
 * @property \App\Model\Entity\SolicitacaoAtendimentoCivei[] $solicitacao_atendimento_civeis
 * @property \App\Model\Entity\SolicitacaoCasamento[] $solicitacao_casamentos
 * @property \App\Model\Entity\SolicitacaoCertidaoCasamento[] $solicitacao_certidao_casamentos
 * @property \App\Model\Entity\SolicitacaoCertidaoObito[] $solicitacao_certidao_obitos
 * @property \App\Model\Entity\SolicitacaoComprovarUniaoEstavel[] $solicitacao_comprovar_uniao_estavel
 * @property \App\Model\Entity\SolicitacaoPlanoSaude[] $solicitacao_plano_saude
 * @property \App\Model\Entity\SolicitacaoDesmarcarAgendamento[] $solicitacao_desmarcar_agendamentos
 * @property \App\Model\Entity\SolicitacaoDivorcio[] $solicitacao_divorcios
 * @property \App\Model\Entity\SolicitacaoFazendaPublica[] $solicitacao_fazenda_publica
 * @property \App\Model\Entity\SolicitacaoHeranca[] $solicitacao_herancas
 * @property \App\Model\Entity\SolicitacaoSaude[] $solicitacao_saudes
 * @property \App\Model\Entity\SolicitacaoTrabalhista[] $solicitacao_trabalhistas
 * @property \App\Model\Entity\SolicitacaoUsucapiao[] $solicitacao_usucapiao
 * @property \App\Model\Entity\SolicitacaoViolenciaDomestica[] $solicitacao_violencia_domesticas
 * @property \App\Model\Entity\Solicitaco[] $solicitacoes
 * @property \App\Model\Entity\TipoPedidoSubAssunto[] $tipo_pedido_sub_assuntos
 */
class SubAssunto extends Entity
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
        'assunto_id' => true,
        'nome_tab_ligacao' => true,
        'assunto' => true,
        'solicitacao_alimentos' => true,
        'solicitacao_atendimento_civeis' => true,
        'solicitacao_casamentos' => true,
        'solicitacao_certidao_casamentos' => true,
        'solicitacao_certidao_obitos' => true,
        'solicitacao_comprovar_uniao_estavel' => true,
        'solicitacao_plano_saude' => true,
        'solicitacao_desmarcar_agendamentos' => true,
        'solicitacao_divorcios' => true,
        'solicitacao_fazenda_publica' => true,
        'solicitacao_herancas' => true,
        'solicitacao_saudes' => true,
        'solicitacao_trabalhistas' => true,
        'solicitacao_usucapiao' => true,
        'solicitacao_violencia_domesticas' => true,
        'solicitacoes' => true,
        'solicitacao_adocao' => true,
        'solicitacao_viagem_inter' => true,
        'solicitacao_alvara_heranca' => true,
        'solicitacao_exame_de_dna' => true,
        'solicitacao_idoso' => true,
        'solicitacao_regulamentacao_visita' => true,
        'tipo_pedido_sub_assuntos' => true
    ];
}
