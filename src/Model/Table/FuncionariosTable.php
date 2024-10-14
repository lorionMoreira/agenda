<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Funcionarios Model
 *
 * @property \App\Model\Table\SituacoesTable|\Cake\ORM\Association\BelongsTo $Situacoes
 * @property \App\Model\Table\PessoasTable|\Cake\ORM\Association\BelongsTo $Pessoas
 * @property \App\Model\Table\ComarcasTable|\Cake\ORM\Association\BelongsTo $Comarcas
 * @property \App\Model\Table\NivelsTable|\Cake\ORM\Association\BelongsTo $Nivels
 * @property \App\Model\Table\UnidadesTable|\Cake\ORM\Association\BelongsTo $Unidades
 * @property \App\Model\Table\AcaoHistoricosTable|\Cake\ORM\Association\HasMany $AcaoHistoricos
 * @property \App\Model\Table\AfastamentosTable|\Cake\ORM\Association\HasMany $Afastamentos
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\AgendamentosTempTable|\Cake\ORM\Association\HasMany $AgendamentosTemp
 * @property \App\Model\Table\AnexosTable|\Cake\ORM\Association\HasMany $Anexos
 * @property \App\Model\Table\AssistidosTable|\Cake\ORM\Association\HasMany $Assistidos
 * @property \App\Model\Table\AtendimentosTable|\Cake\ORM\Association\HasMany $Atendimentos
 * @property \App\Model\Table\AtividadeExtrasTable|\Cake\ORM\Association\HasMany $AtividadeExtras
 * @property \App\Model\Table\AtuacoesDesignacoesTable|\Cake\ORM\Association\HasMany $AtuacoesDesignacoes
 * @property \App\Model\Table\AudienciasTable|\Cake\ORM\Association\HasMany $Audiencias
 * @property \App\Model\Table\CiveisTable|\Cake\ORM\Association\HasMany $Civeis
 * @property \App\Model\Table\CondicoesTable|\Cake\ORM\Association\HasMany $Condicoes
 * @property \App\Model\Table\CrimesTable|\Cake\ORM\Association\HasMany $Crimes
 * @property \App\Model\Table\CuradoriasTable|\Cake\ORM\Association\HasMany $Curadorias
 * @property \App\Model\Table\DesignacoesTable|\Cake\ORM\Association\HasMany $Designacoes
 * @property \App\Model\Table\DireitoHumanosTable|\Cake\ORM\Association\HasMany $DireitoHumanos
 * @property \App\Model\Table\DocumentosTable|\Cake\ORM\Association\HasMany $Documentos
 * @property \App\Model\Table\EstagiariosTable|\Cake\ORM\Association\HasMany $Estagiarios
 * @property \App\Model\Table\ExecucaoPenaisTable|\Cake\ORM\Association\HasMany $ExecucaoPenais
 * @property \App\Model\Table\ExecucaoPenaisProcessosTable|\Cake\ORM\Association\HasMany $ExecucaoPenaisProcessos
 * @property \App\Model\Table\FamiliasTable|\Cake\ORM\Association\HasMany $Familias
 * @property \App\Model\Table\FilaGuichesTable|\Cake\ORM\Association\HasMany $FilaGuiches
 * @property \App\Model\Table\FilaHistoricosTable|\Cake\ORM\Association\HasMany $FilaHistoricos
 * @property \App\Model\Table\FilaSenhasTable|\Cake\ORM\Association\HasMany $FilaSenhas
 * @property \App\Model\Table\FilasTable|\Cake\ORM\Association\HasMany $Filas
 * @property \App\Model\Table\FlagrantesTable|\Cake\ORM\Association\HasMany $Flagrantes
 * @property \App\Model\Table\FundiariosTable|\Cake\ORM\Association\HasMany $Fundiarios
 * @property \App\Model\Table\HistoricosTable|\Cake\ORM\Association\HasMany $Historicos
 * @property \App\Model\Table\IdososTable|\Cake\ORM\Association\HasMany $Idosos
 * @property \App\Model\Table\InstanciaSuperiorCiveisTable|\Cake\ORM\Association\HasMany $InstanciaSuperiorCiveis
 * @property \App\Model\Table\JuizadoCriminaisTable|\Cake\ORM\Association\HasMany $JuizadoCriminais
 * @property \App\Model\Table\JuizadoEspecialCiveisTable|\Cake\ORM\Association\HasMany $JuizadoEspecialCiveis
 * @property \App\Model\Table\JuventudeCiveisTable|\Cake\ORM\Association\HasMany $JuventudeCiveis
 * @property \App\Model\Table\JuventudesTable|\Cake\ORM\Association\HasMany $Juventudes
 * @property \App\Model\Table\MedidasTable|\Cake\ORM\Association\HasMany $Medidas
 * @property \App\Model\Table\NucleoMulheresTable|\Cake\ORM\Association\HasMany $NucleoMulheres
 * @property \App\Model\Table\PaHistoricosTable|\Cake\ORM\Association\HasMany $PaHistoricos
 * @property \App\Model\Table\PlantoesTable|\Cake\ORM\Association\HasMany $Plantoes
 * @property \App\Model\Table\PopulacaoRuasTable|\Cake\ORM\Association\HasMany $PopulacaoRuas
 * @property \App\Model\Table\SociaisTable|\Cake\ORM\Association\HasMany $Sociais
 * @property \App\Model\Table\TitularidadesTable|\Cake\ORM\Association\HasMany $Titularidades
 * @property \App\Model\Table\TrabalhosTable|\Cake\ORM\Association\HasMany $Trabalhos
 * @property \App\Model\Table\VwAtendimentoTable|\Cake\ORM\Association\HasMany $VwAtendimento
 * @property \App\Model\Table\VwAfastamentosTable|\Cake\ORM\Association\HasMany $VwAfastamentos
 * @property \App\Model\Table\VwAgendamentosTable|\Cake\ORM\Association\HasMany $VwAgendamentos
 * @property \App\Model\Table\VwAssistidosTable|\Cake\ORM\Association\HasMany $VwAssistidos
 * @property \App\Model\Table\VwAtendimentosTable|\Cake\ORM\Association\HasMany $VwAtendimentos
 * @property \App\Model\Table\VwCrcAtendimentosTable|\Cake\ORM\Association\HasMany $VwCrcAtendimentos
 * @property \App\Model\Table\VwDesignacoesTable|\Cake\ORM\Association\HasMany $VwDesignacoes
 * @property \App\Model\Table\VwFilaGuichesTable|\Cake\ORM\Association\HasMany $VwFilaGuiches
 * @property \App\Model\Table\VwSenhasTable|\Cake\ORM\Association\HasMany $VwSenhas
 * @property \App\Model\Table\EscalasTable|\Cake\ORM\Association\BelongsToMany $Escalas
 * @property \App\Model\Table\EspecializadasTable|\Cake\ORM\Association\BelongsToMany $Especializadas
 * @property \App\Model\Table\EstagiariosTable|\Cake\ORM\Association\BelongsToMany $Estagiarios
 * @property \App\Model\Table\UnidadeMoveisTable|\Cake\ORM\Association\BelongsToMany $UnidadeMoveis
 *
 * @method \App\Model\Entity\Funcionario get($primaryKey, $options = [])
 * @method \App\Model\Entity\Funcionario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Funcionario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Funcionario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Funcionario findOrCreate($search, callable $callback = null, $options = [])
 */
class FuncionariosTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('funcionarios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Pessoas', [
            'foreignKey' => 'pessoa_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Comarcas', [
            'foreignKey' => 'comarca_id'
        ]);
        $this->belongsTo('Unidades', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('Agendamentos', [
            'foreignKey' => 'funcionario_id'
        ]);
        $this->hasMany('Assistidos', [
            'foreignKey' => 'funcionario_id'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmpty('id', 'create');

        // $validator
        //     ->integer('registro')
        //     ->allowEmpty('registro');

        // $validator
        //     ->date('data_inicio')
        //     ->allowEmpty('data_inicio');
        //
        // $validator
        //     ->date('data_fim')
        //     ->allowEmpty('data_fim');
        //
        // $validator
        //     ->integer('tipo_funcionario')
        //     ->requirePresence('tipo_funcionario', 'create')
        //     ->notEmpty('tipo_funcionario');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['pessoa_id'], 'Pessoas'));
        $rules->add($rules->existsIn(['comarca_id'], 'Comarcas'));
        $rules->add($rules->existsIn(['unidade_id'], 'Unidades'));

        return $rules;
    }

    /**
     * Returns the database connection name to use by default.
     *
     * @return string
     */
    public static function defaultConnectionName()
    {
        return 'sigad';
    }
}
