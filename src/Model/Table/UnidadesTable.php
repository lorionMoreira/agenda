<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Unidades Model
 *
 * @property \App\Model\Table\ComarcasTable|\Cake\ORM\Association\BelongsTo $Comarcas
 * @property \App\Model\Table\ContatosTable|\Cake\ORM\Association\BelongsTo $Contatos
 * @property \App\Model\Table\EnderecosTable|\Cake\ORM\Association\BelongsTo $Enderecos
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\AssistidosTable|\Cake\ORM\Association\HasMany $Assistidos
 * @property \App\Model\Table\AtendimentosTable|\Cake\ORM\Association\HasMany $Atendimentos
 * @property \App\Model\Table\ComarcasEspecializadasTable|\Cake\ORM\Association\HasMany $ComarcasEspecializadas
 * @property \App\Model\Table\CrimesTable|\Cake\ORM\Association\HasMany $Crimes
 * @property \App\Model\Table\CuradoriasTable|\Cake\ORM\Association\HasMany $Curadorias
 * @property \App\Model\Table\EspecializadaBalanceamentosTable|\Cake\ORM\Association\HasMany $EspecializadaBalanceamentos
 * @property \App\Model\Table\ExecucaoPenaisTable|\Cake\ORM\Association\HasMany $ExecucaoPenais
 * @property \App\Model\Table\FeriadosTable|\Cake\ORM\Association\HasMany $Feriados
 * @property \App\Model\Table\FilaGuichesTable|\Cake\ORM\Association\HasMany $FilaGuiches
 * @property \App\Model\Table\FilaPaineisTable|\Cake\ORM\Association\HasMany $FilaPaineis
 * @property \App\Model\Table\FilaSenhasTable|\Cake\ORM\Association\HasMany $FilaSenhas
 * @property \App\Model\Table\FuncionariosTable|\Cake\ORM\Association\HasMany $Funcionarios
 * @property \App\Model\Table\JuizadoCriminaisTable|\Cake\ORM\Association\HasMany $JuizadoCriminais
 * @property \App\Model\Table\JuventudesTable|\Cake\ORM\Association\HasMany $Juventudes
 * @property \App\Model\Table\SalasTable|\Cake\ORM\Association\HasMany $Salas
 * @property \App\Model\Table\VwAtendimentoTable|\Cake\ORM\Association\HasMany $VwAtendimento
 * @property \App\Model\Table\VwAgendamentosTable|\Cake\ORM\Association\HasMany $VwAgendamentos
 * @property \App\Model\Table\VwAssistidosTable|\Cake\ORM\Association\HasMany $VwAssistidos
 * @property \App\Model\Table\VwAtendimentosTable|\Cake\ORM\Association\HasMany $VwAtendimentos
 * @property \App\Model\Table\VwCrcAtendimentosTable|\Cake\ORM\Association\HasMany $VwCrcAtendimentos
 * @property \App\Model\Table\VwFilaGuichesTable|\Cake\ORM\Association\HasMany $VwFilaGuiches
 * @property \App\Model\Table\VwFuncionariosTable|\Cake\ORM\Association\HasMany $VwFuncionarios
 * @property \App\Model\Table\VwSenhasTable|\Cake\ORM\Association\HasMany $VwSenhas
 * @property \App\Model\Table\EstagiariosTable|\Cake\ORM\Association\BelongsToMany $Estagiarios
 *
 * @method \App\Model\Entity\Unidade get($primaryKey, $options = [])
 * @method \App\Model\Entity\Unidade newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Unidade[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Unidade|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Unidade patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Unidade[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Unidade findOrCreate($search, callable $callback = null, $options = [])
 */
class UnidadesTable extends Table
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

        $this->setTable('unidades');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Comarcas', [
            'foreignKey' => 'comarca_id'
        ]);
        $this->belongsTo('Contatos', [
            'foreignKey' => 'contato_id'
        ]);
        $this->belongsTo('Enderecos', [
            'foreignKey' => 'endereco_id'
        ]);
        $this->hasMany('Agendamentos', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('Assistidos', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('Atendimentos', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('ComarcasEspecializadas', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('Crimes', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('Curadorias', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('EspecializadaBalanceamentos', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('ExecucaoPenais', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('Feriados', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('FilaGuiches', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('FilaPaineis', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('FilaSenhas', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('Funcionarios', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('JuizadoCriminais', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('Juventudes', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('Salas', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('VwAtendimento', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('VwAgendamentos', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('VwAssistidos', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('VwAtendimentos', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('VwCrcAtendimentos', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('VwFilaGuiches', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('VwFuncionarios', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->hasMany('VwSenhas', [
            'foreignKey' => 'unidade_id'
        ]);
        $this->belongsToMany('Estagiarios', [
            'foreignKey' => 'unidade_id',
            'targetForeignKey' => 'estagiario_id',
            'joinTable' => 'estagiarios_unidades'
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
            ->scalar('endereco')
            ->allowEmpty('endereco');

        $validator
            ->integer('situacao')
            ->allowEmpty('situacao');

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
        $rules->add($rules->existsIn(['comarca_id'], 'Comarcas'));
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
