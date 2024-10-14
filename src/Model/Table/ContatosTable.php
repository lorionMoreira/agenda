<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Contatos Model
 *
 * @property \App\Model\Table\PessoasTable|\Cake\ORM\Association\HasMany $Pessoas
 * @property \App\Model\Table\UnidadesTable|\Cake\ORM\Association\HasMany $Unidades
 * @property \App\Model\Table\VwAssistidosTable|\Cake\ORM\Association\HasMany $VwAssistidos
 * @property \App\Model\Table\VwFuncionariosTable|\Cake\ORM\Association\HasMany $VwFuncionarios
 *
 * @method \App\Model\Entity\Contato get($primaryKey, $options = [])
 * @method \App\Model\Entity\Contato newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Contato[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Contato|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contato patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Contato[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Contato findOrCreate($search, callable $callback = null, $options = [])
 */
class ContatosTable extends Table
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

        $this->setTable('contatos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Pessoas', [
            'foreignKey' => 'contato_id'
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
            ->scalar('celular');

        $validator
            ->scalar('residencial')
            ->allowEmpty('residencial');

        $validator
            ->scalar('comercial')
            ->allowEmpty('comercial');

        $validator
            ->scalar('recado')
            ->allowEmpty('recado');

        $validator
            ->email('email');

        $validator
            ->scalar('email_alternativo')
            ->allowEmpty('email_alternativo');

        $validator
            ->scalar('responsavel')
            ->allowEmpty('responsavel');

        $validator
            ->scalar('whatsapp')
            ->allowEmpty('whatsapp');
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
        //$rules->add($rules->isUnique(['email']));

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
