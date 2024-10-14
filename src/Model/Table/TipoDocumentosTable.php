<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * TipoAcoes Model
 *
 * @property \App\Model\Table\TipoDocumentosTable|\Cake\ORM\Association\BelongsToMany $TipoDocumentos
 * @property \App\Model\Table\UnidadeMoveisTable|\Cake\ORM\Association\BelongsToMany $UnidadeMoveis
 *
 * @method \App\Model\Entity\TipoAco get($primaryKey, $options = [])
 * @method \App\Model\Entity\TipoAco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\TipoAco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\TipoAco|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\TipoAco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\TipoAco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\TipoAco findOrCreate($search, callable $callback = null, $options = [])
 */
class TipoDocumentosTable extends Table
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

        $this->setTable('tipo_documentos');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('AcoesTipoDocumentos', [
            'foreignKey' => 'tipo_documento_id',
            'targetForeignKey' => 'tipo_documento_id',
            'joinTable' => 'acao_tipo_documentos'
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
}
