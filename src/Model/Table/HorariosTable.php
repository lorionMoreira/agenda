<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Horarios Model
 *
 * @property \App\Model\Table\SolicitacoesTable|\Cake\ORM\Association\BelongsToMany $Solicitacoes
 *
 * @method \App\Model\Entity\Horario get($primaryKey, $options = [])
 * @method \App\Model\Entity\Horario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Horario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Horario|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Horario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Horario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Horario findOrCreate($search, callable $callback = null, $options = [])
 */
class HorariosTable extends Table
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

        $this->setTable('horarios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Solicitacoes', [
            'foreignKey' => 'horario_id',
            'targetForeignKey' => 'solicitaco_id',
            'joinTable' => 'solicitacoes_horarios'
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
            ->scalar('dia')
            ->requirePresence('dia', 'create')
            ->notEmpty('dia');

        $validator
            ->scalar('turno')
            ->requirePresence('turno', 'create')
            ->notEmpty('turno');

        return $validator;
    }
}
