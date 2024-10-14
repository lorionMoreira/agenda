<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FilaHistoricos Model
 *
 * @property \App\Model\Table\FilaSenhasTable|\Cake\ORM\Association\BelongsTo $FilaSenhas
 * @property \App\Model\Table\FilaSituacoesTable|\Cake\ORM\Association\BelongsTo $FilaSituacoes
 * @property \App\Model\Table\FuncionariosTable|\Cake\ORM\Association\BelongsTo $Funcionarios
 *
 * @method \App\Model\Entity\FilaHistorico get($primaryKey, $options = [])
 * @method \App\Model\Entity\FilaHistorico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FilaHistorico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FilaHistorico|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FilaHistorico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FilaHistorico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FilaHistorico findOrCreate($search, callable $callback = null, $options = [])
 */class FilaHistoricosTable extends Table
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

        $this->setTable('fila_historicos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('FilaSenhas', [
            'foreignKey' => 'senha_id',
            'joinType' => 'INNER'
        ]);
//        $this->belongsTo('FilaSituacoes', [
//            'foreignKey' => 'situacao_id',
//            'joinType' => 'INNER'
//        ]);
        $this->belongsTo('Funcionarios', [
            'foreignKey' => 'funcionario_id',
            'joinType' => 'INNER'
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
            ->dateTime('data_hora')            ->allowEmpty('data_hora');
        $validator
            ->scalar('observacao')            ->allowEmpty('observacao');
        $validator
            ->integer('atendente')            ->allowEmpty('atendente');
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
        $rules->add($rules->existsIn(['senha_id'], 'FilaSenhas'));
//        $rules->add($rules->existsIn(['situacao_id'], 'FilaSituacoes'));
        $rules->add($rules->existsIn(['funcionario_id'], 'Funcionarios'));

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
