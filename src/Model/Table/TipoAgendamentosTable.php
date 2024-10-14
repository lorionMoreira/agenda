<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TipoAgendamentos Model
 *
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\VwAgendamentosTable|\Cake\ORM\Association\HasMany $VwAgendamentos
 *
 * @method \App\Model\Entity\TipoAgendamento get($primaryKey, $options = [])
 * @method \App\Model\Entity\TipoAgendamento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TipoAgendamento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TipoAgendamento|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TipoAgendamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TipoAgendamento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TipoAgendamento findOrCreate($search, callable $callback = null, $options = [])
 */
class TipoAgendamentosTable extends Table
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

        $this->setTable('tipo_agendamentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Agendamentos', [
            'foreignKey' => 'tipo_agendamento_id'
        ]);
        $this->hasMany('VwAgendamentos', [
            'foreignKey' => 'tipo_agendamento_id'
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
            ->allowEmpty('nome');

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
