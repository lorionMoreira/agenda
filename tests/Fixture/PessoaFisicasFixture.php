<?php
namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * PessoaFisicasFixture
 *
 */
class PessoaFisicasFixture extends TestFixture
{

    /**
     * Fields
     *
     * @var array
     */
    // @codingStandardsIgnoreStart
    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'autoIncrement' => true, 'precision' => null],
        'escolaridade_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'raca_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'profissao_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'religiao_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'pessoa_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => false, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'tipo_documento_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'cpf' => ['type' => 'string', 'length' => 14, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'numero_documento' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'nome_pai' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'nome_mae' => ['type' => 'string', 'length' => 50, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'naturalidade' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'nacionalidade' => ['type' => 'string', 'length' => 15, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'renda_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'quantidade_filho_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'sexo' => ['type' => 'integer', 'length' => 1, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'estado_civil_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'nascimento' => ['type' => 'date', 'length' => null, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null],
        'apelido' => ['type' => 'string', 'length' => 100, 'null' => false, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'nucleo_familiar_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'tipo_residencia_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'tipo_deficiencia_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'situacao_profissional_id' => ['type' => 'integer', 'length' => 11, 'unsigned' => false, 'null' => true, 'default' => null, 'comment' => '', 'precision' => null, 'autoIncrement' => null],
        'orgao_expedidor' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        'outro_documento' => ['type' => 'string', 'length' => 20, 'null' => true, 'default' => null, 'collate' => 'utf8_unicode_ci', 'comment' => '', 'precision' => null, 'fixed' => null],
        '_indexes' => [
            'escolaridade_id' => ['type' => 'index', 'columns' => ['escolaridade_id'], 'length' => []],
            'raca_id' => ['type' => 'index', 'columns' => ['raca_id'], 'length' => []],
            'religiao_id' => ['type' => 'index', 'columns' => ['religiao_id'], 'length' => []],
            'profissao_id' => ['type' => 'index', 'columns' => ['profissao_id'], 'length' => []],
            'pessoa_id' => ['type' => 'index', 'columns' => ['pessoa_id'], 'length' => []],
            'tipo_documento_id' => ['type' => 'index', 'columns' => ['tipo_documento_id'], 'length' => []],
            'quantidade_filho_id' => ['type' => 'index', 'columns' => ['quantidade_filho_id'], 'length' => []],
            'renda_id' => ['type' => 'index', 'columns' => ['renda_id'], 'length' => []],
            'estado_civil_id' => ['type' => 'index', 'columns' => ['estado_civil_id'], 'length' => []],
            'nucleo_familiar_id' => ['type' => 'index', 'columns' => ['nucleo_familiar_id'], 'length' => []],
            'tipo_residencia_id' => ['type' => 'index', 'columns' => ['tipo_residencia_id'], 'length' => []],
            'tipo_deficiencia_id' => ['type' => 'index', 'columns' => ['tipo_deficiencia_id'], 'length' => []],
            'fk_pessoa_fisicas_situacoes1' => ['type' => 'index', 'columns' => ['situacao_profissional_id'], 'length' => []],
            'idx_numero_documento' => ['type' => 'index', 'columns' => ['numero_documento'], 'length' => []],
            'idx_cpf' => ['type' => 'index', 'columns' => ['cpf'], 'length' => []],
            'idx_nascimento' => ['type' => 'index', 'columns' => ['nascimento'], 'length' => []],
            'idx_outro_documento' => ['type' => 'index', 'columns' => ['outro_documento'], 'length' => []],
        ],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
            'fk_pessoa_fisicas_situacoes1' => ['type' => 'foreign', 'columns' => ['situacao_profissional_id'], 'references' => ['situacoes', 'id'], 'update' => 'noAction', 'delete' => 'noAction', 'length' => []],
            'pessoa_fisicas_ibfk_1' => ['type' => 'foreign', 'columns' => ['escolaridade_id'], 'references' => ['escolaridades', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_10' => ['type' => 'foreign', 'columns' => ['nucleo_familiar_id'], 'references' => ['quantidade_filhos', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_11' => ['type' => 'foreign', 'columns' => ['tipo_residencia_id'], 'references' => ['tipo_residencias', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_12' => ['type' => 'foreign', 'columns' => ['tipo_deficiencia_id'], 'references' => ['tipo_deficiencias', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_2' => ['type' => 'foreign', 'columns' => ['raca_id'], 'references' => ['racas', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_3' => ['type' => 'foreign', 'columns' => ['profissao_id'], 'references' => ['profissoes', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_4' => ['type' => 'foreign', 'columns' => ['religiao_id'], 'references' => ['religioes', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_5' => ['type' => 'foreign', 'columns' => ['pessoa_id'], 'references' => ['pessoas', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_6' => ['type' => 'foreign', 'columns' => ['tipo_documento_id'], 'references' => ['tipo_documentos', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_7' => ['type' => 'foreign', 'columns' => ['quantidade_filho_id'], 'references' => ['quantidade_filhos', 'id'], 'update' => 'restrict', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_8' => ['type' => 'foreign', 'columns' => ['estado_civil_id'], 'references' => ['estado_civis', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
            'pessoa_fisicas_ibfk_9' => ['type' => 'foreign', 'columns' => ['renda_id'], 'references' => ['rendas', 'id'], 'update' => 'cascade', 'delete' => 'restrict', 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_unicode_ci'
        ],
    ];
    // @codingStandardsIgnoreEnd

    /**
     * Records
     *
     * @var array
     */
    public $records = [
        [
            'id' => 1,
            'escolaridade_id' => 1,
            'raca_id' => 1,
            'profissao_id' => 1,
            'religiao_id' => 1,
            'pessoa_id' => 1,
            'tipo_documento_id' => 1,
            'cpf' => 'Lorem ipsum ',
            'numero_documento' => 'Lorem ipsum dolor ',
            'nome_pai' => 'Lorem ipsum dolor sit amet',
            'nome_mae' => 'Lorem ipsum dolor sit amet',
            'naturalidade' => 'Lorem ipsum d',
            'nacionalidade' => 'Lorem ipsum d',
            'renda_id' => 1,
            'quantidade_filho_id' => 1,
            'sexo' => 1,
            'estado_civil_id' => 1,
            'nascimento' => '2017-10-06',
            'apelido' => 'Lorem ipsum dolor sit amet',
            'nucleo_familiar_id' => 1,
            'tipo_residencia_id' => 1,
            'tipo_deficiencia_id' => 1,
            'situacao_profissional_id' => 1,
            'orgao_expedidor' => 'Lorem ipsum dolor ',
            'outro_documento' => 'Lorem ipsum dolor '
        ],
    ];
}
