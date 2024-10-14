<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use JeremyHarris\LazyLoad\ORM\LazyLoadEntityTrait;
/**
 * PessoaFisica Entity
 *
 * @property int $id
 * @property int $escolaridade_id
 * @property int $raca_id
 * @property int $profissao_id
 * @property int $religiao_id
 * @property int $pessoa_id
 * @property int $tipo_documento_id
 * @property string $cpf
 * @property string $numero_documento
 * @property string $nome_pai
 * @property string $nome_mae
 * @property string $nome_social
 * @property string $naturalidade
 * @property string $nacionalidade
 * @property int $renda_id
 * @property int $quantidade_filho_id
 * @property int $sexo
 * @property int $opcao_genero_id
 * @property int $orientacao_sexual_id
 * @property int $estado_civil_id
 * @property \Cake\I18n\FrozenDate $nascimento
 * @property string $apelido
 * @property int $nucleo_familiar_id
 * @property int $tipo_residencia_id
 * @property int $tipo_deficiencia_id
 * @property int $situacao_profissional_id
 * @property string $orgao_expedidor
 * @property string $outro_documento
 *
 * @property \App\Model\Entity\Escolaridade $escolaridade
 * @property \App\Model\Entity\Raca $raca
 * @property \App\Model\Entity\Profisso $profisso
 * @property \App\Model\Entity\Religio $religio
 * @property \App\Model\Entity\Pessoa $pessoa
 * @property \App\Model\Entity\TipoDocumento $tipo_documento
 * @property \App\Model\Entity\Renda $renda
 * @property \App\Model\Entity\QuantidadeFilho $quantidade_filho
 * @property \App\Model\Entity\EstadoCivi $estado_civi
 * @property \App\Model\Entity\TipoResidencia $tipo_residencia
 * @property \App\Model\Entity\TipoDeficiencia $tipo_deficiencia
 * @property \App\Model\Entity\Situaco $situaco
 */
class PessoaFisica extends Entity
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
        'escolaridade_id' => true,
        'raca_id' => true,
        'profissao_id' => true,
        'religiao_id' => true,
        'pessoa_id' => true,
        'tipo_documento_id' => true,
        'cpf' => true,
        'numero_documento' => true,
        'nome_pai' => true,
        'nome_mae' => true,
        'nome_social' => true,
        'naturalidade' => true,
        'nacionalidade' => true,
        'renda_id' => true,
        'quantidade_filho_id' => true,
        'sexo' => true,
        'opcao_genero_id' => true,
        'orientacao_sexual_id' => true,
        'estado_civil_id' => true,
        'nascimento' => true,
        'apelido' => true,
        'nucleo_familiar_id' => true,
        'tipo_residencia_id' => true,
        'tipo_deficiencia_id' => true,
        'situacao_profissional_id' => true,
        'orgao_expedidor' => true,
        'outro_documento' => true,
        'escolaridade' => true,
        'raca' => true,
        'profisso' => true,
        'religio' => true,
        'pessoa' => true,
        'tipo_documento' => true,
        'renda' => true,
        'quantidade_filho' => true,
        'estado_civi' => true,
        'tipo_residencia' => true,
        'tipo_deficiencia' => true,
        'situaco' => true
        
    ];
}
