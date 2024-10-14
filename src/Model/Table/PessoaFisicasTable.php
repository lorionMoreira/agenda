<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PessoaFisicas Model
 *
 * @method \App\Model\Entity\PessoaFisica get($primaryKey, $options = [])
 * @method \App\Model\Entity\PessoaFisica newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PessoaFisica[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PessoaFisica|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PessoaFisica patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PessoaFisica[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PessoaFisica findOrCreate($search, callable $callback = null, $options = [])
 */
class PessoaFisicasTable extends Table
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

        $this->setTable('pessoa_fisicas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Pessoas', [
            'foreignKey' => 'pessoa_id',
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

        $validator
            ->scalar('cpf')
            ->requirePresence('cpf', 'create')
            ->notEmpty('cpf');

        $validator
            ->scalar('numero_documento')
            ->allowEmpty('numero_documento');

        $validator
            ->scalar('nome_pai')
            ->allowEmpty('nome_pai');

        $validator
            ->scalar('nome_mae')
            ->allowEmpty('nome_mae');

        $validator
            ->scalar('naturalidade')
            ->allowEmpty('naturalidade');

        $validator
            ->scalar('nacionalidade')
            ->allowEmpty('nacionalidade');

        $validator
            ->integer('sexo')
            ->allowEmpty('sexo');

        $validator
            ->date('nascimento')
            ->allowEmpty('nascimento');

        $validator
            ->scalar('orgao_expedidor')
            ->allowEmpty('orgao_expedidor');

        $validator
            ->scalar('outro_documento')
            ->allowEmpty('outro_documento');

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
        $rules->add($rules->existsIn(['pessoa_id'], 'Pessoas'));

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

    public function buscaRegistro($username)
    {  
        $r = '
                SELECT
                    user.username, user.sigad_user, user.email, 
                    pf.cpf
                FROM agenda.users user
                LEFT JOIN sigad.assistidos a on (a.id = user.sigad_user)
                LEFT JOIN sigad.pessoas p on (p.id = a.pessoa_id)
                LEFT JOIN sigad.pessoa_fisicas pf on (pf.pessoa_id = p.id)
            ';

       /*$r = $this->find()            
            ->where(['cpf' => $username]);            //->first();     */   
        
        return $r;

    } 

}
