<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Especializadas Model
 *
 * @property \App\Model\Table\EspecializadasTable|\Cake\ORM\Association\BelongsTo $ParentEspecializadas
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\AgendamentosTempTable|\Cake\ORM\Association\HasMany $AgendamentosTemp
 * @property \App\Model\Table\AtendimentoAtividadesTable|\Cake\ORM\Association\HasMany $AtendimentoAtividades
 * @property \App\Model\Table\AudienciasTable|\Cake\ORM\Association\HasMany $Audiencias
 * @property \App\Model\Table\EscalasFuncionariosTable|\Cake\ORM\Association\HasMany $EscalasFuncionarios
 * @property \App\Model\Table\EspecializadaBalanceamentosTable|\Cake\ORM\Association\HasMany $EspecializadaBalanceamentos
 * @property \App\Model\Table\EspecializadasTable|\Cake\ORM\Association\HasMany $ChildEspecializadas
 * @property \App\Model\Table\PerfisTable|\Cake\ORM\Association\HasMany $Perfis
 * @property \App\Model\Table\PeriodosTable|\Cake\ORM\Association\HasMany $Periodos
 * @property \App\Model\Table\RcEspecializadasTipoAtividadesTable|\Cake\ORM\Association\HasMany $RcEspecializadasTipoAtividades
 * @property \App\Model\Table\RcEspecializadasTipoAtividadesCopyTable|\Cake\ORM\Association\HasMany $RcEspecializadasTipoAtividadesCopy
 * @property \App\Model\Table\RelacionamentosTable|\Cake\ORM\Association\HasMany $Relacionamentos
 * @property \App\Model\Table\VwAgendamentosTable|\Cake\ORM\Association\HasMany $VwAgendamentos
 * @property \App\Model\Table\AtuacoesTable|\Cake\ORM\Association\BelongsToMany $Atuacoes
 * @property \App\Model\Table\ComarcasTable|\Cake\ORM\Association\BelongsToMany $Comarcas
 * @property \App\Model\Table\FuncionariosTable|\Cake\ORM\Association\BelongsToMany $Funcionarios
 * @property \App\Model\Table\TipoAtividadesTable|\Cake\ORM\Association\BelongsToMany $TipoAtividades
 * @property \App\Model\Table\UnidadeDefensoriaisTable|\Cake\ORM\Association\BelongsToMany $UnidadeDefensoriais
 *
 * @method \App\Model\Entity\Especializada get($primaryKey, $options = [])
 * @method \App\Model\Entity\Especializada newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Especializada[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Especializada|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Especializada patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Especializada[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Especializada findOrCreate($search, callable $callback = null, $options = [])
 */
class EspecializadasTable extends Table
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

        $this->setTable('especializadas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('ParentEspecializadas', [
            'className' => 'Especializadas',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Agendamentos', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('AgendamentosTemp', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('AtendimentoAtividades', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('Audiencias', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('EscalasFuncionarios', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('EspecializadaBalanceamentos', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('ChildEspecializadas', [
            'className' => 'Especializadas',
            'foreignKey' => 'parent_id'
        ]);
        $this->hasMany('Perfis', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('Periodos', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('RcEspecializadasTipoAtividades', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('RcEspecializadasTipoAtividadesCopy', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('Relacionamentos', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->hasMany('VwAgendamentos', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->belongsToMany('Atuacoes', [
            'foreignKey' => 'especializada_id',
            'targetForeignKey' => 'atuaco_id',
            'joinTable' => 'atuacoes_especializadas'
        ]);
        $this->belongsToMany('Comarcas', [
            'foreignKey' => 'especializada_id',
            'targetForeignKey' => 'comarca_id',
            'joinTable' => 'comarcas_especializadas'
        ]);
        $this->belongsToMany('Funcionarios', [
            'foreignKey' => 'especializada_id',
            'targetForeignKey' => 'funcionario_id',
            'joinTable' => 'especializadas_funcionarios'
        ]);
        $this->belongsToMany('TipoAtividades', [
            'foreignKey' => 'especializada_id',
            'targetForeignKey' => 'tipo_atividade_id',
            'joinTable' => 'especializadas_tipo_atividades'
        ]);
        $this->belongsToMany('UnidadeDefensoriais', [
            'foreignKey' => 'especializada_id',
            'targetForeignKey' => 'unidade_defensoriai_id',
            'joinTable' => 'especializadas_unidade_defensoriais'
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

        $validator
            ->scalar('nome')
            ->requirePresence('nome', 'create')
            ->notEmpty('nome');

        $validator
            ->scalar('sigla')
            ->allowEmpty('sigla');

        $validator
            ->integer('agd_servidor')
            ->allowEmpty('agd_servidor');

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
        $rules->add($rules->existsIn(['parent_id'], 'ParentEspecializadas'));

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
