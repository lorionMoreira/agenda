<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Comarcas Model
 *
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\UnidadeDefensoriaisTable|\Cake\ORM\Association\HasMany $UnidadeDefensoriais
 * @property \App\Model\Table\EspecializadasTable|\Cake\ORM\Association\BelongsToMany $Especializadas
 *
 * @method \App\Model\Entity\Comarca get($primaryKey, $options = [])
 * @method \App\Model\Entity\Comarca newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Comarca[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Comarca|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Comarca patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Comarca[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Comarca findOrCreate($search, callable $callback = null, $options = [])
 */
class ComarcasTable extends Table
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

        $this->setTable('comarcas');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('Agendamentos', [
            'foreignKey' => 'comarca_id'
        ]);
        $this->hasMany('UnidadeDefensoriais', [
            'foreignKey' => 'comarca_id'
        ]);
        $this->belongsToMany('Especializadas', [
            'foreignKey' => 'comarca_id',
            'targetForeignKey' => 'especializada_id',
            'joinTable' => 'comarcas_especializadas'
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

    public function findComAgendamentoServidor(Query $query, Array $options)
    {
        $comarcas = $query->select(['Comarcas.id', 'Comarcas.nome'])
            ->join([
                'ud' => [
                    'table' => 'unidade_defensoriais',
                    'type' => 'INNER',
                    'conditions' => 'ud.comarca_id = Comarcas.id',
                ],
                'eud' => [
                    'table' => 'especializadas_unidade_defensoriais',
                    'type' => 'INNER',
                    'conditions' => 'ud.id = eud.unidade_defensorial_id',
                ],
                'e' => [
                    'table' => 'especializadas',
                    'type' => 'INNER',
                    'conditions' => [
                        'e.id = eud.especializada_id',
                        'e.agd_servidor' => 1
                    ],
                ]
            ])
            ->hydrate(false);
        dd($comarcas);
        $return = [];
        foreach ($comarcas as $comarca)
        {
            $return[$comarca['id']] = $comarca['nome'];
        }

        return $return;
    }
}
