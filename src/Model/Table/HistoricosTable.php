<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Historicos Model
 *
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\BelongsTo $Agendamentos
 * @property \App\Model\Table\SituacoesTable|\Cake\ORM\Association\BelongsTo $Situacoes
 * @property \App\Model\Table\FuncionariosTable|\Cake\ORM\Association\BelongsTo $Funcionarios
 *
 * @method \App\Model\Entity\Historico get($primaryKey, $options = [])
 * @method \App\Model\Entity\Historico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Historico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Historico|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Historico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Historico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Historico findOrCreate($search, callable $callback = null, $options = [])
 */
class HistoricosTable extends Table
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

        $this->setTable('historicos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Agendamentos', [
            'foreignKey' => 'agendamento_id',
            'joinType' => 'INNER'
        ]);
         $this->belongsTo('Situacoes', [
             'foreignKey' => 'situacao_id',
             'joinType' => 'INNER'
         ]);
        // $this->belongsTo('Funcionarios', [
        //     'foreignKey' => 'funcionario_id'
        // ]);
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
            ->dateTime('data')
            ->requirePresence('data', 'create')
            ->notEmpty('data');

        $validator
            ->scalar('observacao')
            ->requirePresence('observacao', 'create')
            ->notEmpty('observacao');

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
        $rules->add($rules->existsIn(['agendamento_id'], 'Agendamentos'));
        $rules->add($rules->existsIn(['situacao_id'], 'Situacoes'));
        // $rules->add($rules->existsIn(['funcionario_id'], 'Funcionarios'));

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
