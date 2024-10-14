<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Support\RelacionamentoInterface;

/**
 * AreaAtuacao Model
 *
 * @property \App\Model\Table\AcoesRelacionadaTable|\Cake\ORM\Association\HasMany $AcoesRelacionada
 * @property \App\Model\Table\DocumentosTable|\Cake\ORM\Association\HasMany $Documentos
 * @property \App\Model\Table\LocationTable|\Cake\ORM\Association\BelongsToMany $Location
 *
 * @method \App\Model\Entity\AreaAtuacao get($primaryKey, $options = [])
 * @method \App\Model\Entity\AreaAtuacao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AreaAtuacao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AreaAtuacao|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AreaAtuacao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AreaAtuacao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AreaAtuacao findOrCreate($search, callable $callback = null, $options = [])
 */
class AreaAtuacaoTable extends Table
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

        $this->setTable('area_atuacao');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->hasMany('AcoesRelacionada', [            
            'foreignKey' => 'area_atuacao_id'
        ]);
        
        $this->hasMany('Documentos', [
            'foreignKey' => 'area_atuacao_id'
        ]);
        
        $this->belongsToMany('Documentos', [
            'foreignKey' => 'area_atuacao_id',
            'targetForeignKey' => 'documento_id',
            'joinTable' => 'area_atuacao_documentos'
        ]);
        
        $this->belongsToMany('Location', [
            'foreignKey' => 'area_atuacao_id',
            'targetForeignKey' => 'location_id',
            'joinTable' => 'area_atuacao_location'
        ]);
    
        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'created_at' => 'new',
                    'updated_at' => 'always',
                 ]
            ]
        ]);

        $this->addBehavior('Josegonzalez/Upload.Upload', [
            'nome_arquivo' => [
                'path' => 'webroot{DS}files{DS}upload{DS}{model}{DS}',
                'pathProcessor' => 'App\CakephpUpload\AreaAtuacaoNameFileProcessor',                
                'keepFilesOnDelete' => false,
                'deleteOnUpdate' => true, 
                'extensions'=> array('png')             
            ]
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
        $validator->integer('id')
            ->allowEmpty('id', 'create');
        return $validator;
    }

    public function allAreaAtuacao(RelacionamentoInterface $relacionamento)
    {                
        $relacionamentos = $relacionamento->contain();        
        
        return $this->find()
            ->contain($relacionamentos)            
            ->all();
    }


}
