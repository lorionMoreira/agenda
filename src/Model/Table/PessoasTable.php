<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Pessoas Model
 *
 * @property \App\Model\Table\ContatosTable|\Cake\ORM\Association\BelongsTo $Contatos
 * @property \App\Model\Table\EnderecosTable|\Cake\ORM\Association\BelongsTo $Enderecos
 * @property \App\Model\Table\AssistidosTable|\Cake\ORM\Association\HasMany $Assistidos
 * @property \App\Model\Table\PessoaFisicasTable|\Cake\ORM\Association\HasMany $PessoaFisicas
 *
 * @method \App\Model\Entity\Pessoa get($primaryKey, $options = [])
 * @method \App\Model\Entity\Pessoa newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Pessoa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Pessoa|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Pessoa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Pessoa[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Pessoa findOrCreate($search, callable $callback = null, $options = [])
 */
class PessoasTable extends Table
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

        $this->setTable('pessoas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Contatos', [
            'foreignKey' => 'contato_id'
        ]);
        $this->belongsTo('Enderecos', [
            'foreignKey' => 'endereco_id'
        ]);
        $this->hasMany('Assistidos', [
            'foreignKey' => 'pessoa_id'
        ]);
        $this->hasMany('PessoaFisicas', [
            'foreignKey' => 'pessoa_id'
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
            ->scalar('tipo')
            ->requirePresence('tipo', 'create')
            ->notEmpty('tipo');

        $validator
            ->dateTime('data_cadastro')
            ->allowEmpty('data_cadastro');

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
        $rules->add($rules->existsIn(['contato_id'], 'Contatos'));
        $rules->add($rules->existsIn(['endereco_id'], 'Enderecos'));

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
