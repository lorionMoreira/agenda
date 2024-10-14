<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FilaTipoPrioridades Model
 *
 * @method \App\Model\Entity\FilaTipoPrioridade get($primaryKey, $options = [])
 * @method \App\Model\Entity\FilaTipoPrioridade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FilaTipoPrioridade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FilaTipoPrioridade|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FilaTipoPrioridade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FilaTipoPrioridade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FilaTipoPrioridade findOrCreate($search, callable $callback = null, $options = [])
 */class FilaTipoPrioridadesTable extends Table
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

        $this->setTable('fila_tipo_prioridades');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
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
            ->scalar('nome')            ->allowEmpty('nome');
        $validator
            ->integer('grau_prioridade')            ->allowEmpty('grau_prioridade');
        return $validator;
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
