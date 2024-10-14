<?php
namespace App\Controller;
use Cake\ORM\TableRegistry;


class CaixaEntradaController extends AppController{

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if (in_array($action, ['index', 'mensagem','add', 'delete', 'cancel', 'solicitacoes', 'cancelSolicitacao', 'comprovante']))
        {
            return true;
        }

        return parent::isAuthorized($user);
    }
    public function index(){
        $this->viewBuilder()->setLayout('sistema');
        $user = $this->request->session()->read('User');
        $title = "Caixa de Entrada";
        $this->loadModel('NotificaAssistidos');
        $notificacao = TableRegistry::get("NotificaAssistidos");
        $menssagens =  $notificacao->find()
            ->select(['NotificaAssistidos.id','NotificaAssistidos.acao_historico_id', 'NotificaAssistidos.assistido_id', 'NotificaAssistidos.created', 'NotificaAssistidos.data_notificacao', 'NotificaAssistidos.email_assistido', 'NotificaAssistidos.funcionario_id', 'NotificaAssistidos.msg_lida', 'NotificaAssistidos.relato', 'ac.tipo_acao_id', 'p.numeracao_unica', 'ac.numero', 'ac.situacao_id',
                'anexos' => ('COALESCE(COUNT(aa.anexo_id), 0)')
            ])
            ->join([
                'table' => 'sigad.acao_historicos',
                'alias' => 'ah',
                'type' => 'LEFT',
                'conditions' => ['NotificaAssistidos.acao_historico_id = ah.id']
            ])
            ->join([
                'table' => 'sigad.acoes',
                'alias' => 'ac',
                'type' => 'LEFT',
                'conditions' => ['ac.id = ah.acao_id']
            ])
            ->join([
                'table' => 'sigad.acoes_anexos',
                'alias' => 'aa',
                'type' => 'LEFT',
                'conditions' => ['ah.acao_id = aa.acao_id']
            ])
            ->join([
                'table' => 'sigad.processos',
                'alias' => 'p',
                'type' => 'LEFT',
                'conditions' => ['ac.processo_id = p.id']
            ])
            ->where(['NotificaAssistidos.assistido_id' => $user['sigad_user']])
            ->group('NotificaAssistidos.id')
            ->order('NotificaAssistidos.msg_lida ASC, NotificaAssistidos.data_notificacao  DESC')->toArray();      
            
        $this->set('menssagens', $menssagens);
        $this->set(compact('title'));
        $this->set('_serialize', ['agendamentos']);
    }

    public function mensagem($id){
        $this->viewBuilder()->setLayout('sistema');
        $user = $this->request->session()->read('User');
        $title = "Caixa de Entrada";
        $this->loadModel('NotificaAssistidos');           


        $notificacao = TableRegistry::get("NotificaAssistidos");
        $mensagem =  $notificacao->find()
            ->select(['NotificaAssistidos.id','NotificaAssistidos.acao_historico_id', 'NotificaAssistidos.assistido_id', 'NotificaAssistidos.created', 'NotificaAssistidos.data_notificacao', 'NotificaAssistidos.email_assistido', 'NotificaAssistidos.funcionario_id', 'NotificaAssistidos.msg_lida', 'NotificaAssistidos.relato', 'ac.tipo_acao_id', 'p.numeracao_unica', 'ac.numero', 'ac.situacao_id', 'si.nome'])
            ->join([
                'table' => 'sigad.acao_historicos',
                'alias' => 'ah',
                'type' => 'LEFT',
                'conditions' => ['NotificaAssistidos.acao_historico_id = ah.id']
            ])
            ->join([
                'table' => 'sigad.acoes',
                'alias' => 'ac',
                'type' => 'LEFT',
                'conditions' => ['ac.id = ah.acao_id']
            ])
            ->join([
                'table' => 'sigad.situacoes',
                'alias' => 'si',
                'type' => 'LEFT',
                'conditions' => ['ac.situacao_id = si.id']
            ])
            ->join([
                'table' => 'sigad.processos',
                'alias' => 'p',
                'type' => 'LEFT',
                'conditions' => ['ac.processo_id = p.id']
            ])
            ->join([
                'table' => 'sigad.acoes_anexos',
                'alias' => 'aa',
                'type' => 'LEFT',
                'conditions' => ['ah.acao_id = aa.acao_id']
            ])
            ->where(['NotificaAssistidos.id = '.$id, 'NotificaAssistidos.assistido_id' => $user['sigad_user']])
            ->first();

        $anexos =  $notificacao->find()
            ->select(['a.caminho_fisico','a.filename', 'aa.anexo_id'])
            ->join([
                'table' => 'sigad.acao_historicos',
                'alias' => 'ah',
                'type' => 'LEFT',
                'conditions' => ['ah.id = NotificaAssistidos.acao_historico_id']
            ])
            ->join([
                'table' => 'sigad.acoes_anexos',
                'alias' => 'aa',
                'type' => 'LEFT',
                'conditions' => ['ah.acao_id = aa.acao_id']
            ])
            ->join([
                'table' => 'sigad.anexos',
                'alias' => 'a',
                'type' => 'LEFT',
                'conditions' => ['aa.anexo_id = a.id']
            ])
            ->where(['NotificaAssistidos.id = '.$id, 'NotificaAssistidos.assistido_id' => $user['sigad_user']])->toArray();
         
        //Atualizar para mensagem lida
        if($mensagem->msg_lida == 0){
            $query = $notificacao->query();
            $res = $query->update()->set(['msg_lida' => 1, 'data_leitura = NOW()'])->where(['id = '.$id])->execute();            
        }

        
        $this->set('mensagem', $mensagem);
        $this->set('id', $id);
        $this->set('anexos', $anexos);
        $this->set(compact('title'));
        $this->set('_serialize', ['agendamentos']);

    }

}