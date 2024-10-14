<?php
namespace App\Controller;

use App\Controller\AppController;
use App\Model\Table\AssuntosTable;
use App\Model\Table\SolicitacoesTable;
use Cake\Error\Debugger;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * Agendamentos Controller
 *
 * @property \App\Model\Table\AgendamentosTable $Agendamentos
 *
 * @method \App\Model\Entity\Agendamento[] paginate($object = null, array $settings = [])
 */
class NotificaAssistidosController extends AppController
{

    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if (in_array($action, ['index', 'add', 'delete', 'cancel', 'solicitacoes', 'cancelSolicitacao', 'comprovante']))
        {
            return true;
        }

        return parent::isAuthorized($user);
    }

    public function initialize()
    {
        parent::initialize();

        $this->loadModel('Solicitacoes');
        $this->loadModel('SolicitacoesHorarios');
        $this->loadModel('NotificaAssistidos');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index()
    {
        if (!$this->request->session()->read('User.sigad_user')) {
            $this->Flash->error(__('Para realizar agendamentos é necessário concluir seu cadastro.'));
            return $this->redirect("users/add");
        }

        $this->viewBuilder()->setLayout('sistema');
        $user = $this->request->session()->read('User');
        $title = "Mensagens";

        $mensagens = $this->NotificaAssistidos->find('pendentesPorUsuario', [
            'usuario_sigad' => $user['sigad_user']
        ]);
		
		
        $mensagens_lidas = $this->NotificaAssistidos->find('finalizadosPorUsuario', [
            'usuario_sigad' => $user['sigad_user']
        ]);

        $this->set(compact('mensagens', 'mensagens_lidas', 'title'));
        $this->set('_serialize', ['mensagens']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        /*$agora = date('d/m/Y');

        if($agora >= strtotime('20/12/2020') and $agora <= strtotime('06/01/2021')){            
            $exibeaviso = true;
        }else{
            $exibeaviso = false;
        }*/

        //$this->set(compact('exibeaviso', 'exibeaviso'));
        
        if (!$this->request->session()->read('User.sigad_user')) {
            $this->Flash->error(__('Para realizar agendamentos é necessário concluir seu cadastro.'));
            return $this->redirect("users/add");
        }

        $this->viewBuilder()->setLayout('sistema');
        $solicitacao = $this->Solicitacoes->newEntity();
        $title = "Solicitar Agendamento";

        if ($this->request->is('post'))
        {

            $solicitacao = $this->Solicitacoes->patchEntity(
                $solicitacao, $this->request->getData()
            );
            $solicitacao->status = 1; // aberta
            $solicitacao->sigad_user = $this->request->session()->read('User.sigad_user');

            $existsAgendamentoDia = $this->Solicitacoes->existsAgendamento(date("Y-m-d"), $solicitacao->sigad_user, $solicitacao->assunto_id);
            //Verifica se está no recesso de fim de ano antes de inserir
            //if($exibeaviso == false){
            
                if ($existsAgendamentoDia) {
                    $this->Flash->error(__('Uma solicitação com este mesmo assunto já foi realizada. Em breve entraremos em contato. Fique atento ao seu email.'));
                } else if ($this->Solicitacoes->save($solicitacao)) {
                    foreach ($this->request->getData('preferencia') as $preferencia)
                    {
                        $s_h = $this->SolicitacoesHorarios->newEntity();
                        $s_h->solicitacao_id = $solicitacao->id;
                        $s_h->horario_id = (int)$preferencia;
                        $this->SolicitacoesHorarios->save($s_h);					
                    }
                    				
    				$this->Flash->success(__('A solicitação será analisada por nossa equipe e em breve entraremos em contato. Fique atento ao seu e-mail!'));
                    return $this->redirect(['action' => 'solicitacoes']);
    								
                } else {
                    $this->Flash->error(__('A solicitação não pôde ser salva. Por favor, tente novamente.'));
                }
            /*}else{
                $this->Flash->error(__('Caras(os) cidadãs(aos)<br>

                    Devido ao recesso de final de ano da Defensoria Pública do Estado da Bahia este canal estará suspenso de<br> <b>20 de dezembro de 2020 a 6 de janeiro de 2021.</b> <br>Neste período apenas casos urgentes serão atendidos.<br><br>

                    Se o seu caso é urgente, ligue 129 ou 0800 071 3121 nos DIAS UTEIS, das 8 às 17h.<br><br>

                    Nos FERIADOS E FINAIS DE SEMANA, esses são os contatos do plantão para casos urgentes:<br>
                    CAPITAL - 71 - 99913-9108<br>
                    plantao@defensoria.ba.def.br<br>
                    INTERIOR - 71 - 99650-1669<br>
                    plantao.regional@defensoria.ba.def.br'));                
            }*/
        }
			
		//Atribui o valor da variável $email ao campo
		$email = $this->request->session()->read('User.email');
		$this->set(compact('email', 'email'));
        $this->set(compact('solicitacao', 'title'));
        $this->set('_serialize', ['solicitacao']);

        $assuntos = TableRegistry::get("Assuntos");
        $a = $assuntos->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'nome','conditions' => [
                'chat_bot' => 0
            ]
            ])->toArray();
        $this->set('assuntos', $a);
    }

    public function solicitacoes() {
        if (!$this->request->session()->read('User.sigad_user')) {
            $this->Flash->error(__('Para realizar agendamentos é necessário concluir seu cadastro.'));
            return $this->redirect("users/add");
        }

        $this->viewBuilder()->setLayout('sistema');
        $this->set('title', "Minhas solicitações");

        $sigad_user = $this->request->session()->read('User.sigad_user');

        $solicitacoes = $this->Solicitacoes->find('all', [
            'conditions' => [
                'sigad_user' => $sigad_user,
                'agendado' => 0
            ]
        ]);

        $this->set('solicitacoes', $solicitacoes);
        $assuntos = TableRegistry::get("Assuntos");
        $a = $assuntos->find("list", ['keyField' => 'id', 'valueField' => 'nome','conditions' => [
                'chat_bot' => 0
            ]
                                    ])->toArray();
        $this->set('assuntos', $a);
    }

    /**
     * Delete method
     *
     * @param string|null $id Agendamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $agendamento = $this->Agendamentos->get($id);
        if ($this->Agendamentos->delete($agendamento)) {
            $this->Flash->success(__('The agendamento has been deleted.'));
        } else {
            $this->Flash->error(__('The agendamento could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function comprovante($id)
    {
        $this->viewBuilder()->setLayout('impressao');
        $user = $this->request->session()->read('User');

        $agendamento = $this->Agendamentos->find('comprovante', [
            'id' => $id
        ]);

        $this->set(compact('agendamento'));
    }

    public function cancel($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        $this->loadModel('Historicos');
        $historico = $this->Historicos->newEntity();

        $historico->agendamento_id = $id;
        $historico->situacao_id = 43; //id para cancelado
        $historico->data = date('Y-m-d H:i:s');
        $historico->observacao = "Cancelado pelo assistido - Agendamento Online";

        if ($this->Historicos->save($historico))
        {
            $this->Flash->success(__('Agendamento cancelado com sucesso.'));
        }else{
            $this->Flash->error(
                __('O agendamento não pôde ser cancelado, por favor tente novamente.')
            );
        }

        return $this->redirect(['action' => 'index']);
    }

    public function cancelSolicitacao($id)
    {
        $this->request->allowMethod(['post', 'delete']);
        try {
            $s = $this->Solicitacoes->get($id);

            if ($s->agendado) {
                $this->Flash->error(
                    __('A Solicitação não pôde ser cancelada pois já foi agendada.')
                );
            } else {
                $r = $this->Solicitacoes->delete($s);

                if ($r) {
                    $this->Flash->success(__('Solicitação cancelada com sucesso.'));
                } else {
                    $this->Flash->error(
                        __('A Solicitação não pôde ser cancelada, por favor tente novamente.')
                    );
                }
            }
        } catch (\Exception $e) {

        }


        return $this->redirect(['action' => 'index']);
    }
}
