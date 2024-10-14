<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Solicitacoes Model
 *
 * @property \App\Model\Table\HorariosTable|\Cake\ORM\Association\BelongsToMany $Horarios
 * @property \App\Model\Table\ComarcasTable|\Cake\ORM\Association\BelongsTo $Comarca
 * @property \App\Model\Table\AssuntosTable|\Cake\ORM\Association\BelongsTo $Assunto
 * @property \App\Model\Table\AgendamentosTable|\Cake\ORM\Association\BelongsTo $Agendamento
 *
 * @method \App\Model\Entity\Solicitaco get($primaryKey, $options = [])
 * @method \App\Model\Entity\Solicitaco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Solicitaco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Solicitaco|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Solicitaco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Solicitaco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Solicitaco findOrCreate($search, callable $callback = null, $options = [])
 */
class SolicitacoesTable extends Table
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

        $this->setTable('solicitacoes');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Horarios', [
            'foreignKey' => 'solicitacao_id',
            'targetForeignKey' => 'horario_id',
            'joinTable' => 'solicitacoes_horarios',
            'dependent' => true,
            'cascadeCallbacks' => true,
        ]);

        $this->belongsTo('Assuntos', [
            'foreignKey' => 'assunto_id',
            'joinTable' => 'assuntos',
            'joinType' => 'LEFT'
        ]);

//        $this->belongsTo('Assuntos', [
//            'foreignKey' => 'assunto_id',
//            'className' => 'Assuntos'
//            #'joinTable' => 'sigad.assuntos',
//            #'joinType' => 'LEFT'
//        ]);
//
//        $this->belongsTo('Comarcas', [
//            'propertyName' => "Comarca",
//            'foreignKey' => 'comarca',
//            'className' => 'Comarcas'
//            #'joinTable' => 'sigad.comarcas',
//            #'joinType' => 'INNER'
//        ]);
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
            ->integer('comarca')
            ->requirePresence('comarca', 'create')
            ->notEmpty('comarca');

        $validator
            ->scalar('relato')
            ->requirePresence('relato', 'create')
            ->notEmpty('relato');

        $validator
            ->requirePresence('processo', 'create')
            ->notEmpty('processo');

        $validator
            ->scalar('numero_processo')
            ->allowEmpty('numero_processo');

        $validator
            ->integer('assunto_id')
            ->notEmpty('assunto_id');

        return $validator;
    }

    public function existsAgendamento($dia, $sigadUser, $assuntoId)
    {        
        $p = $this->exists([
            'sigad_user' => $sigadUser,
            'assunto_id' => $assuntoId,
            'status' => '1']);        
        
        return $p;
    }

    public function proUsuario($sigadUser, $idSolicitacao)
    {
        $r = $this->find()
             ->where(['sigad_user' => $sigadUser, 'id' => $idSolicitacao])
             ->first();
        
        return $r;
    }

}
