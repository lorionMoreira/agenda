<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Agendamentos Model
 *
 * @property \App\Model\Table\AssistidosTable|\Cake\ORM\Association\BelongsTo $Assistidos
 * @property \App\Model\Table\TipoAcoesTable|\Cake\ORM\Association\BelongsTo $TipoAcoes
 * @property \App\Model\Table\AgendasTable|\Cake\ORM\Association\BelongsTo $Agendas
  * @property \App\Model\Table\EspecializadasTable|\Cake\ORM\Association\BelongsTo $Especializadas
 * @property \App\Model\Table\AcoesTable|\Cake\ORM\Association\BelongsTo $Acoes
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\BelongsTo $Agendamentos
 * @property \App\Model\Table\TipoAgendamentosTable|\Cake\ORM\Association\BelongsTo $TipoAgendamentos
 * @property \App\Model\Table\ComarcasTable|\Cake\ORM\Association\BelongsTo $Comarcas
 * @property \App\Model\Table\UnidadesTable|\Cake\ORM\Association\BelongsTo $Unidades
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\HasMany $ManyAgendamentos
 *
 * @method \App\Model\Entity\Agendamento get($primaryKey, $options = [])
 * @method \App\Model\Entity\Agendamento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Agendamento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Agendamento|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Agendamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Agendamento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Agendamento findOrCreate($search, callable $callback = null, $options = [])
 */
class AgendamentosTable extends Table
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

        $this->setTable('agendamentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Assistidos', [
            'foreignKey' => 'assistido_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Historicos', [
            'foreignKey' => 'agendamento_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Agendas', [
            'foreignKey' => 'agenda_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Especializadas', [
            'foreignKey' => 'especializada_id'
        ]);
        $this->belongsTo('Agendamentos', [
            'foreignKey' => 'agendamento_id'
        ]);
        $this->belongsTo('Comarcas', [
            'foreignKey' => 'comarca_id'
        ]);
        $this->belongsTo('Unidades', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->belongsTo('Funcionarios', [
            'foreignKey' => 'funcionario_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('TipoAcoes', [
            'foreignKey' => 'tipo_acao_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Acoes', [
            'foreignKey' => 'acao_id'
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
            ->date('prazo')
            ->allowEmpty('prazo');

        $validator
            ->integer('assunto_id')
            ->notEmpty('assunto_id');

        $validator
            ->integer('tipo_atendimento')
            ->requirePresence('tipo_atendimento', 'create')
            ->notEmpty('tipo_atendimento');

        $validator
            ->date('data_cadastro')
            ->allowEmpty('data_cadastro');

        $validator
            ->integer('prioridade')
            ->allowEmpty('prioridade');

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
        $rules->add($rules->existsIn(['assistido_id'], 'Assistidos'));
        $rules->add($rules->existsIn(['agenda_id'], 'Agendas'));
        $rules->add($rules->existsIn(['especializada_id'], 'Especializadas'));
        // $rules->add($rules->existsIn(['acao_id'], 'Acoes'));
        // $rules->add($rules->existsIn(['atuacao_id'], 'Atuacaos'));
        $rules->add($rules->existsIn(['agendamento_id'], 'Agendamentos'));
        // $rules->add($rules->existsIn(['tipo_agendamento_id'], 'TipoAgendamentos'));
        $rules->add($rules->existsIn(['comarca_id'], 'Comarcas'));
        $rules->add($rules->existsIn(['unidade_id'], 'Unidades'));

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

    public function findPendentesPorUsuario(Query $query, array $options)
    {           
        $whereHora = [
                    'Agendas.data > "'.date('Y-m-d').'"',
                    'Agendas.data = "'.date('Y-m-d').'" AND Horas.nome >= "'.date('H:m').'"',
                    ];
        if(isset($options['considerarHora']) == true && $options['considerarHora'] == true){
            $whereHora = [
                    'Agendas.data > "'.date('Y-m-d').'"',
                    'Agendas.data = "'.date('Y-m-d').'"'
                    ];
        }
        
        return $query
            ->contain([
                'Especializadas', 'Agendas' => ['Escalas' => ['Horas']], 'Comarcas', 'Unidades',
                'Historicos','Historicos' => ['Situacoes'],'Funcionarios' => ['Unidades'],
                'Assistidos' => ['Pessoas']
            ])->where([
                'assistido_id' => $options['usuario_sigad'],
                "OR" => $whereHora
            ]);
    }

    public function findFinalizadosPorUsuario(Query $query, array $options)
    {
        return $query
            ->contain([
                'Especializadas', 'Agendas' => ['Escalas' => ['Horas']], 'Comarcas', 'Unidades',
                'Historicos', 'Historicos' => ['Situacoes'], 'Funcionarios' => ['Unidades']
            ])->where([
                'assistido_id' => $options['usuario_sigad'],
                "OR" => [
                    'Agendas.data < "'.date('Y-m-d').'"',
                    'Agendas.data = "'.date('Y-m-d').'" AND Horas.nome < "'.date('H:m').'"',
                ]
            ]);
    }

    public function findComprovante(Query $query, array $options)
    {
        return $query
            ->contain([
                'Especializadas', 'Agendas' => ['Escalas' => ['Horas']], 'Comarcas', 'Unidades',
                'Assistidos' => ['Pessoas'], 'Acoes' => ['AcoesTipoDocumentos' => ['TipoDocumentos']]
            ])->where([
                'Agendamentos.id' => $options['id']
            ])->first();
    }



}
