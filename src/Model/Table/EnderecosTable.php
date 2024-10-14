<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Enderecos Model
 *
 * @property \App\Model\Table\CidadesTable|\Cake\ORM\Association\BelongsTo $Cidades
 * @property \App\Model\Table\PessoasTable|\Cake\ORM\Association\HasMany $Pessoas
 * @property \App\Model\Table\PlantaoAtendimentosTable|\Cake\ORM\Association\HasMany $PlantaoAtendimentos
 * @property \App\Model\Table\UnidadesTable|\Cake\ORM\Association\HasMany $Unidades
 * @property \App\Model\Table\VwAssistidosTable|\Cake\ORM\Association\HasMany $VwAssistidos
 * @property \App\Model\Table\VwFuncionariosTable|\Cake\ORM\Association\HasMany $VwFuncionarios
 *
 * @method \App\Model\Entity\Endereco get($primaryKey, $options = [])
 * @method \App\Model\Entity\Endereco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Endereco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Endereco|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Endereco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Endereco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Endereco findOrCreate($search, callable $callback = null, $options = [])
 */
class EnderecosTable extends Table
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

        $this->setTable('enderecos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Pessoas', [
            'foreignKey' => 'endereco_id'
        ]);

        $this->hasMany('Cidades', [
            'foreignKey' => 'cidade_id'
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
            ->scalar('numero')
            ->allowEmpty('numero');

        $validator
            ->scalar('referencia')
            ->allowEmpty('referencia');

        $validator
            ->allowEmpty('logradouro_descricao');

        $validator
            ->scalar('bairro_descricao')
            ->allowEmpty('bairro_descricao');

        $validator
            ->scalar('cep')
            ->allowEmpty('cep');

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
        $rules->add($rules->existsIn(['cidade_id'], 'Cidades'));

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
