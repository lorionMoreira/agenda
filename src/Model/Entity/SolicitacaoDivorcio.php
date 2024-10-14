<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * SolicitacaoDivorcio Entity
 *
 * @property int $id
 * @property int $solicitacao_id
 * @property int $sub_assunto_id
 * @property string $casado
 * @property string $filho_menor
 * @property string $possui_bens
 * @property string $divorcio_amigavel
 * @property string $medida_protetiva
 * @property string $ocorrencia_conjuges
 * @property string $ocorrencia_filhos
 *
 * @property \App\Model\Entity\Solicitaco $solicitaco
 * @property \App\Model\Entity\SubAssunto $sub_assunto
 */
class SolicitacaoDivorcio extends Entity
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
        'casado' => true,
        'filho_menor' => true,
        'possui_bens' => true,
        'divorcio_amigavel' => true,
        'medida_protetiva' => true,
        'ocorrencia_conjuges' => true,
        'ocorrencia_filhos' => true,
        'solicitaco' => true,
        'sub_assunto' => true
    ];
}
