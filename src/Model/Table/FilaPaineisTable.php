<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FilaPaineis Model
 *
 * @property \App\Model\Table\UnidadesTable|\Cake\ORM\Association\BelongsTo $Unidades
 *
 * @method \App\Model\Entity\FilaPainei get($primaryKey, $options = [])
 * @method \App\Model\Entity\FilaPainei newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FilaPainei[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FilaPainei|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FilaPainei patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FilaPainei[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FilaPainei findOrCreate($search, callable $callback = null, $options = [])
 */class FilaPaineisTable extends Table
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

        $this->setTable('fila_paineis');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Unidades', [
            'foreignKey' => 'unidade_id',
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
            ->scalar('nome')            ->requirePresence('nome', 'create')            ->notEmpty('nome');
        $validator
            ->scalar('tipo_multimidia')            ->allowEmpty('tipo_multimidia');
        $validator
            ->integer('qtd_fotos')            ->allowEmpty('qtd_fotos');
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
