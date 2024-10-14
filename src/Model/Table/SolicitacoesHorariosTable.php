<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SolicitacoesHorarios Model
 *
 * @property \App\Model\Table\SolicitacoesTable|\Cake\ORM\Association\BelongsTo $Solicitacoes
 * @property \App\Model\Table\HorariosTable|\Cake\ORM\Association\BelongsTo $Horarios
 *
 * @method \App\Model\Entity\SolicitacoesHorario get($primaryKey, $options = [])
 * @method \App\Model\Entity\SolicitacoesHorario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\SolicitacoesHorario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\SolicitacoesHorario|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\SolicitacoesHorario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\SolicitacoesHorario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\SolicitacoesHorario findOrCreate($search, callable $callback = null, $options = [])
 */
class SolicitacoesHorariosTable extends Table
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

        $this->setTable('solicitacoes_horarios');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Solicitacoes', [
            'foreignKey' => 'solicitacao_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Horarios', [
            'foreignKey' => 'horario_id',
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
            ->integer('id')
            ->allowEmpty('id', 'create');

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
        $rules->add($rules->existsIn(['solicitacao_id'], 'Solicitacoes'));
        $rules->add($rules->existsIn(['horario_id'], 'Horarios'));

        return $rules;
    }
}
