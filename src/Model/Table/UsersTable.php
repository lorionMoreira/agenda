<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Users Model
 *
 * @method \App\Model\Entity\User get($primaryKey, $options = [])
 * @method \App\Model\Entity\User newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\User[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\User|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\User patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\User[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\User findOrCreate($search, callable $callback = null, $options = [])
 */
class UsersTable extends Table
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

        $this->setTable('users');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Assistidos', [
            'foreignKey' => 'sigad_user'
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
            ->scalar('username')
            ->requirePresence('username', 'create')
            ->notEmpty('username')
            ->add('username','numeric', [
                    'rule' => 'numeric',
                    'message' => 'Cpf deve conter somente números' 
            ])
            ->lengthBetween('username', [10, 12], 'Cpf deve conter entre 10 e 12 digitos');

        $validator
            ->scalar('password')
            ->requirePresence('password', 'create')
            ->notEmpty('password')
            ->lengthBetween('password', [8, 30]) //tamanho da senha
            ->add('password', 'custom', [
                'rule' => function ($value, $context) {
                    $flag_tem_minusculo = false;
                    $flag_tem_maiusculo = false;
                    $flag_tem_numero = false;
                    
                    if (preg_match('/[a-z]/', $value))
                        $flag_tem_minusculo= true;
                    
                    if (preg_match('/[A-Z]/', $value))
                        $flag_tem_maiusculo= true;

                    if (preg_match('/[0-9]/', $value))
                        $flag_tem_numero= true;


                    if ($flag_tem_minusculo && $flag_tem_maiusculo && $flag_tem_numero) //a senha DEVE ter letra min., mai. e num.
                        return true;
                    else
                        return false;
                },
                'message' => 'Sua senha não respeita os critérios estabelecidos.'
            ]);

        $validator
            ->integer('sigad_user')
            ->allowEmpty('sigad_user');

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
        $rules->add($rules->isUnique(['username'], 'Cpf já cadastrado'));

        return $rules;
    }

    public function findUsuario($username, $email)
    {        
        $r = $this->find()            
            ->where(['username' => $username, 'email' => $email])
            ->first();        
        
        return $r;
    }
}
