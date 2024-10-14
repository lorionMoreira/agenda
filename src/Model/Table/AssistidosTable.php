<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Assistidos Model
 *
 * @property \App\Model\Table\PessoasTable|\Cake\ORM\Association\BelongsTo $Pessoas
 * @property \App\Model\Table\FuncionariosTable|\Cake\ORM\Association\BelongsTo $Funcionarios
 * @property \App\Model\Table\UnidadesTable|\Cake\ORM\Association\BelongsTo $Unidades
 * @property \App\Model\Table\AcoesTable|\Cake\ORM\Association\HasMany $Acoes
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\HasMany $Agendamentos
 * @property \App\Model\Table\AssistenciaPresosTable|\Cake\ORM\Association\HasMany $AssistenciaPresos
 * @property \App\Model\Table\AtendimentosTable|\Cake\ORM\Association\HasMany $Atendimentos
 * @property \App\Model\Table\AudienciasTable|\Cake\ORM\Association\HasMany $Audiencias
 * @property \App\Model\Table\CiveisTable|\Cake\ORM\Association\HasMany $Civeis
 * @property \App\Model\Table\ConciliacoesTable|\Cake\ORM\Association\HasMany $Conciliacoes
 * @property \App\Model\Table\CrimesTable|\Cake\ORM\Association\HasMany $Crimes
 * @property \App\Model\Table\CuradoriasTable|\Cake\ORM\Association\HasMany $Curadorias
 * @property \App\Model\Table\DireitoHumanosTable|\Cake\ORM\Association\HasMany $DireitoHumanos
 * @property \App\Model\Table\DocumentosTable|\Cake\ORM\Association\HasMany $Documentos
 * @property \App\Model\Table\ExecucaoPenaisTable|\Cake\ORM\Association\HasMany $ExecucaoPenais
 * @property \App\Model\Table\FamiliasTable|\Cake\ORM\Association\HasMany $Familias
 * @property \App\Model\Table\FilaSenhasTable|\Cake\ORM\Association\HasMany $FilaSenhas
 * @property \App\Model\Table\FilasTable|\Cake\ORM\Association\HasMany $Filas
 * @property \App\Model\Table\FlagrantesTable|\Cake\ORM\Association\HasMany $Flagrantes
 * @property \App\Model\Table\FundiariosTable|\Cake\ORM\Association\HasMany $Fundiarios
 * @property \App\Model\Table\IdososTable|\Cake\ORM\Association\HasMany $Idosos
 * @property \App\Model\Table\InstanciaSuperiorCiveisTable|\Cake\ORM\Association\HasMany $InstanciaSuperiorCiveis
 * @property \App\Model\Table\JuizadoCriminaisTable|\Cake\ORM\Association\HasMany $JuizadoCriminais
 * @property \App\Model\Table\JuizadoEspecialCiveisTable|\Cake\ORM\Association\HasMany $JuizadoEspecialCiveis
 * @property \App\Model\Table\JuventudeCiveisTable|\Cake\ORM\Association\HasMany $JuventudeCiveis
 * @property \App\Model\Table\JuventudesTable|\Cake\ORM\Association\HasMany $Juventudes
 * @property \App\Model\Table\NucleoMulheresTable|\Cake\ORM\Association\HasMany $NucleoMulheres
 * @property \App\Model\Table\PaAcolhidosTable|\Cake\ORM\Association\HasMany $PaAcolhidos
 * @property \App\Model\Table\PaAcolhimentosTable|\Cake\ORM\Association\HasMany $PaAcolhimentos
 * @property \App\Model\Table\PaHistoricosTable|\Cake\ORM\Association\HasMany $PaHistoricos
 * @property \App\Model\Table\PopulacaoRuasTable|\Cake\ORM\Association\HasMany $PopulacaoRuas
 * @property \App\Model\Table\ProcessoAdministrativosTable|\Cake\ORM\Association\HasMany $ProcessoAdministrativos
 * @property \App\Model\Table\ProcessosTable|\Cake\ORM\Association\HasMany $Processos
 * @property \App\Model\Table\RelacionamentosTable|\Cake\ORM\Association\HasMany $Relacionamentos
 * @property \App\Model\Table\SaudesTable|\Cake\ORM\Association\HasMany $Saudes
 * @property \App\Model\Table\SociaisTable|\Cake\ORM\Association\HasMany $Sociais
 * @property \App\Model\Table\UnidadeMoveisTable|\Cake\ORM\Association\HasMany $UnidadeMoveis
 * @property \App\Model\Table\VwAtendimentoTable|\Cake\ORM\Association\HasMany $VwAtendimento
 * @property \App\Model\Table\VwAgendamentosTable|\Cake\ORM\Association\HasMany $VwAgendamentos
 * @property \App\Model\Table\VwAtendimentosTable|\Cake\ORM\Association\HasMany $VwAtendimentos
 * @property \App\Model\Table\VwCrcAtendimentosTable|\Cake\ORM\Association\HasMany $VwCrcAtendimentos
 * @property \App\Model\Table\VwSenhasTable|\Cake\ORM\Association\HasMany $VwSenhas
 * @property \App\Model\Table\VwextratoTable|\Cake\ORM\Association\HasMany $Vwextrato
 * @property \App\Model\Table\DependentesTable|\Cake\ORM\Association\BelongsToMany $Dependentes
 * @property \App\Model\Table\FundiariosTable|\Cake\ORM\Association\BelongsToMany $Fundiarios
 * @property \App\Model\Table\PartesTable|\Cake\ORM\Association\BelongsToMany $Partes
 * @property \App\Model\Table\PlantaoAtendimentosTable|\Cake\ORM\Association\BelongsToMany $PlantaoAtendimentos
 * @property \App\Model\Table\TipoDoencasTable|\Cake\ORM\Association\BelongsToMany $TipoDoencas
 *
 * @method \App\Model\Entity\Assistido get($primaryKey, $options = [])
 * @method \App\Model\Entity\Assistido newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Assistido[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Assistido|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Assistido patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Assistido[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Assistido findOrCreate($search, callable $callback = null, $options = [])
 */
class AssistidosTable extends Table
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

        $this->setTable('assistidos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Pessoas', [
            'foreignKey' => 'pessoa_id'
        ]);

        $this->hasMany('Agendamentos', [
            'foreignKey' => 'assistido_id'
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
            ->scalar('numero_triagem')
            ->requirePresence('numero_triagem', 'create')
            ->notEmpty('numero_triagem');

        $validator
            ->integer('dados_completos')
            ->requirePresence('dados_completos', 'create')
            ->notEmpty('dados_completos');

        $validator
            ->dateTime('dt_cadastro')
            ->requirePresence('dt_cadastro', 'create')
            ->notEmpty('dt_cadastro');

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
        //$rules->add($rules->existsIn(['funcionario_id'], 'Funcionarios'));
        //$rules->add($rules->existsIn(['unidade_id'], 'Unidades'));

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

    public function dtNascimento($idAssitido, $dtNascimento)
    {    
        $r = $this->find()          
            ->matching('Pessoas.PessoaFisicas', function(Query $q) 
                use($dtNascimento){
                    return $q->where(['nascimento' => $dtNascimento]);
            })            
            ->where(['Assistidos.id' => $idAssitido])
            ->first();
        
        return $r;        
    }
}
