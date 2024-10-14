<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoAlimento Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property int $tipo_beneficiario_id
 * @property string $alimentos_determinados
 * @property string $cobrar_parcelas
 * @property string $mudar_valor
 * @property string $crianca_nascida
 * @property string $crianca_registrada
 * @property string $pai_vivo
 * @property string $pai_ofereceu_pensao
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 * @property \App\Model\Entity\TipoBeneficiario $tipo_beneficiario
 */
class SolicitacaoAlimento extends Entity
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
        'solicitacao_id' => true,
        'sub_assunto_id' => true,
        'tipo_beneficiario_id' => true,
        'acao_divorcio' => true,
        'alimentos_determinados' => true,
        'cobrar_parcelas' => true,
        'mudar_valor' => true,
        'crianca_nascida' => true,
        'crianca_registrada' => true,
        'pai_vivo' => true,
        'pai_ofereceu_pensao' => true,
        'solicitaco' => true,
        'sub_assunto' => true,
        'tipo_beneficiario' => true
    ];
}
