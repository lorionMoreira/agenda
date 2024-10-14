<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FilaSenhas Model
 *
 * @property \App\Model\Table\FilaTipoPrioridadesTable|\Cake\ORM\Association\BelongsTo $FilaTipoPrioridades
 * @property \App\Model\Table\FuncionariosTable|\Cake\ORM\Association\BelongsTo $Funcionarios
 * @property \App\Model\Table\AssistidosTable|\Cake\ORM\Association\BelongsTo $Assistidos
 * @property \App\Model\Table\UnidadesTable|\Cake\ORM\Association\BelongsTo $Unidades
 * @property \App\Model\Table\FilaPaineisTable|\Cake\ORM\Association\BelongsTo $FilaPaineis
 * @property \App\Model\Table\DefensorsTable|\Cake\ORM\Association\BelongsTo $Defensors
 * @property \App\Model\Table\FilaCategoriasTable|\Cake\ORM\Association\BelongsTo $FilaCategorias
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\BelongsTo $Agendamentos
 *
 * @method \App\Model\Entity\FilaSenha get($primaryKey, $options = [])
 * @method \App\Model\Entity\FilaSenha newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FilaSenha[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FilaSenha|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FilaSenha patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FilaSenha[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FilaSenha findOrCreate($search, callable $callback = null, $options = [])
 */class FilaSenhasTable extends Table
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

        $this->setTable('fila_senhas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('FilaTipoPrioridades', [
            'foreignKey' => 'tipo_prioridade_id'
        ]);
        $this->belongsTo('Funcionarios', [
            'foreignKey' => 'funcionario_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Assistidos', [
            'foreignKey' => 'assistido_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Unidades', [
            'foreignKey' => 'unidade_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('FilaPaineis', [
            'foreignKey' => 'painel_id',
            'joinType' => 'INNER'
        ]);
//        $this->belongsTo('Defensors', [
//            'foreignKey' => 'defensor_id'
//        ]);
//        $this->belongsTo('FilaCategorias', [
//            'foreignKey' => 'categoria_id'
//        ]);
        $this->belongsTo('Agendamentos', [
            'foreignKey' => 'agendamento_id'
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
            ->integer('id')            ->allowEmpty('id', 'create');
        $validator
            ->scalar('senha')            ->allowEmpty('senha');
        $validator
            ->integer('contador')            ->allowEmpty('contador');
        $validator
            ->dateTime('data_hora_emissao')            ->allowEmpty('data_hora_emissao');
        $validator
            ->integer('chamar')            ->requirePresence('chamar', 'create')            ->notEmpty('chamar');
        $validator
            ->requirePresence('espera', 'create')            ->notEmpty('espera');
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
        $rules->add($rules->existsIn(['tipo_prioridade_id'], 'FilaTipoPrioridades'));
        $rules->add($rules->existsIn(['funcionario_id'], 'Funcionarios'));
        $rules->add($rules->existsIn(['assistido_id'], 'Assistidos'));
        $rules->add($rules->existsIn(['unidade_id'], 'Unidades'));
        $rules->add($rules->existsIn(['painel_id'], 'FilaPaineis'));
//        $rules->add($rules->existsIn(['defensor_id'], 'Defensors'));
//        $rules->add($rules->existsIn(['categoria_id'], 'FilaCategorias'));
        $rules->add($rules->existsIn(['agendamento_id'], 'Agendamentos'));

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
