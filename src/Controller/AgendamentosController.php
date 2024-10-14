<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\ConnectionManager;
use App\Model\Table\AssuntosTable;
use App\Model\Table\SubAssuntosTable;
use App\Model\Table\ProcessoMotivoContatoTable;
use App\Model\Table\ProcessoTipoPartesTable;
use App\Model\Table\TipoBeneficiariosTable;
use App\Model\Table\VinculoTrabalhistasTable;
use App\Model\Table\SolicitacoesTable;
use Cake\Error\Debugger;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use DateTimeImmutable;

/**
 * Agendamentos Controller
 *
 * @property \App\Model\Table\AgendamentosTable $Agendamentos
 *
 * @method \App\Model\Entity\Agendamento[] paginate($object = null, array $settings = [])
 */
class AgendamentosController extends AppController{
    public function isAuthorized($user)
    {
        $action = $this->request->getParam('action');

        if (in_array($action, ['index', 'add', 'delete', 'cancel', 'solicitacoes', 'cancelSolicitacao', 'comprovante']))
        {
            return true;
        }

        return parent::isAuthorized($user);
    }

    public function initialize(){
        parent::initialize();

        $this->loadModel('Solicitacoes');
        $this->loadModel('SolicitacoesHorarios');

        $this->loadModel('SolicitacaoProcesso');
        $this->loadModel('SolicitacaoCertidaoObitos');
        $this->loadModel('SolicitacaoHerancas');
        $this->loadModel('SolicitacaoPlanoSaude');
        $this->loadModel('SolicitacaoAlimentos');
        $this->loadModel('SolicitacaoDesmarcarAgendamentos');
        $this->loadModel('SolicitacaoViolenciaDomesticas');
        $this->loadModel('SolicitacaoAtendimentoCiveis');
        $this->loadModel('SolicitacaoFazendaPublica');
        $this->loadModel('SolicitacaoSaudes');
        $this->loadModel('SolicitacaoUsucapiao');
        $this->loadModel('SolicitacaoTrabalhistas');
        $this->loadModel('SolicitacaoCasamentos');
        $this->loadModel('SolicitacaoDivorcios');
        $this->loadModel('SolicitacaoCertidaoCasamentos');
        $this->loadModel('SolicitacaoComprovarUniaoEstavel');
        $this->loadModel('SolicitacaoAdocao');
        $this->loadModel('SolicitacaoViagemInter');
        $this->loadModel('SolicitacaoAlvaraHeranca');
        $this->loadModel('SolicitacaoExameDeDna');
        $this->loadModel('SolicitacaoIdoso');
        $this->loadModel('SolicitacaoRegulamentacaoVisita');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|void
     */
    public function index(){
        if (!$this->request->session()->read('User.sigad_user')) {
            $this->Flash->error(__('Para realizar agendamentos é necessário concluir seu cadastro.'));
            return $this->redirect("users/add");
        }

        $this->viewBuilder()->setLayout('sistema');
        $user = $this->request->session()->read('User');
        $title = "Agendamentos";

        $user['sigad_user'] = 179284;

        $agendamentos = $this->Agendamentos->find('pendentesPorUsuario', [
            'usuario_sigad' => $user['sigad_user']
        ]);
        
        $agendamentos_finalizados = $this->Agendamentos->find('finalizadosPorUsuario', [
            'usuario_sigad' => $user['sigad_user']
        ]);

        $this->set(compact('agendamentos', 'agendamentos_finalizados', 'title'));
        $this->set('_serialize', ['agendamentos']);
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */

    public function add()
    {
        $pos_expediente_bloqueio = '';        
        $agora = date('d/m/Y'); 
       
        // Verificar data de nascimento para habilitar somente para alguns usuarios 
        $this->loadModel('Assistidos');
        $this->loadModel('PessoaFisicas');
        $pessoa_id = $this->Assistidos->get($this->request->session()->read('User.sigad_user')); 
        $bloqueioAudio = $this->PessoaFisicas->findByPessoaId($pessoa_id->pessoa_id)->first();
        //var_dump($bloqueioAudio);
        $nascimento = substr($bloqueioAudio->nascimento, 0, 1);
        $idAssistido = $bloqueioAudio->id;
        $this->set(compact('idAssistido', 'idAssistido'));
        $this->set(compact('nascimento', 'nascimento'));
        $exibeaviso = false;
        
        $exibeaviso = $this->VerificaBloqueio();
        $validarAgendamento = $this->validarHorario();     



        if (!$this->request->session()->read('User.sigad_user')) {
            $this->Flash->error(__('Para realizar agendamentos é necessário concluir seu cadastro.'));
            return $this->redirect("users/add");
        }

        $this->viewBuilder()->setLayout('sistema');
        $solicitacao = $this->Solicitacoes->newEntity();
        $title = "Solicitar Agendamento";
        
        if ($this->request->is('post')){
            $solicitacao->comarca = $this->request->getData('comarca');
            $solicitacao->processo = $this->request->getData('processo');
            $solicitacao->sigad_user = $this->request->session()->read('User.sigad_user');
            $solicitacao->relato = $this->request->getData('relato-textarea');
            $solicitacao->status = 1; // aberta
            $solicitacao->origem_solicitacao = 'Site'; // aberta
            $solicitacao->valor_predicao = $this->request->getData('valor_predicao');
            $solicitacao->clicou_ia = $this->request->getData('clicou_no_btn_relato');
            if(!empty($this->request->getData("url_audio"))){
                $datahora = date('YmdHis');
                $uniqid = uniqid();
                $filename = $uniqid."-".$datahora;    
                //$base64String = substr($this->request->getData("url_audio"), strpos($this->request->getData("url_audio"), ',') + 1);
                $base = base64_decode($this->request->getData("url_audio"));
                $filePath = WWW_ROOT.'repositorio/audios/'.$filename.'.wav';
                $file = new File($filePath, true);
                $file->write($base);
                $file->close();
                $solicitacao->caminho_audio = 'repositorio/audios/'.$filename.'.wav';      
            }
            if($solicitacao->processo == 1){
                $solicitacao->numero_processo = $this->request->getData('numero_processo');
                $solicitacao->assunto_id = 13;
            }
            else{
                if($this->request->getData('assunto_id') == null){
                    $solicitacao->assunto_id = $this->request->getData('select_assunto');
                }else{
                    $solicitacao->assunto_id = $this->request->getData('assunto_id');
                }
                if($solicitacao->assunto_id == 36){
                    $solicitacao->sub_assunto_id = $this->request->getData('s_assunto');                    
                }
                else if ($solicitacao->assunto_id == 42){
                    $solicitacao->sub_assunto_id = $this->request->getData('s_ass_cons');
                }
                else if ($solicitacao->assunto_id == 9){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 30;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('sub_ass_alim');
                    }
                }
                else if ($solicitacao->assunto_id == 44){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 31;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_descmar');
                    }
                }
                else if ($solicitacao->assunto_id == 38){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 32;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_viol_domest');
                    }
                }
                else if ($solicitacao->assunto_id == 41){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 33;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_atend_civel');
                    }
                }
                else if ($solicitacao->assunto_id == 43){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 34;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_faz_pub');
                    } 
                }
                else if ($solicitacao->assunto_id == 45){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 35;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_saude');
                    }
                }
                else if ($solicitacao->assunto_id == 40){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 36;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_usuc');
                    } 
                }
                else if ($solicitacao->assunto_id == 39){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 37;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_trab');
                    }
                }
                else if ($solicitacao->assunto_id == 1){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 51;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_adocao');
                    }
                }
                else if ($solicitacao->assunto_id == 26){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 52;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_id_viagem_inter');
                    }
                }
                else if ($solicitacao->assunto_id == 37){
                    $solicitacao->sub_assunto_id = $this->request->getData('s_assunto_casam');
                }
                else if ($solicitacao->assunto_id == 2){
                    $solicitacao->sub_assunto_id = $this->request->getData('s_ass_alvara_judicial');
                }
                else if ($solicitacao->assunto_id == 5){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 57;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_exame_dna');
                    }
                }
                else if ($solicitacao->assunto_id == 27){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 58;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_idoso');
                    }
                }
                else if ($solicitacao->assunto_id == 11){
                    if(empty($solicitacao->sub_assunto_id)) {
                        $solicitacao->sub_assunto_id = 59;
                    }else {
                        $solicitacao->sub_assunto_id = $this->request->getData('s_ass_regul_visita');
                    }
                }
                else if ($solicitacao->assunto_id == 47){
                    $solicitacao->sub_assunto_id = $this->request->getData('s_ass_locacao');
                }
            }

            $existsAgendamentoDia = $this->Solicitacoes->existsAgendamento(date("Y-m-d"), $solicitacao->sigad_user, $solicitacao->assunto_id);
            //Verifica se está no recesso de fim de ano antes de inserir
            if($exibeaviso == false && $validarAgendamento){           
                if ($existsAgendamentoDia){
                    $this->Flash->error(__('Uma solicitação com este mesmo assunto já foi realizada. Em breve entraremos em contato. Fique atento ao seu email. Você também pode ficar sabendo a data do seu agendamento aqui no site! Consulte o site com frequência para saber o dia de comparecer na Defensoria Pública!'));
                }
                else 
                    if ($this->Solicitacoes->save($solicitacao)){
                        if($solicitacao->processo == 1){
                            $solic_proc = $this->SolicitacaoProcesso->newEntity();
                            $solic_proc->solicitacao_id = $solicitacao->id;
                            $solic_proc->processo_motivo_contato_id = $this->request->getData('processo_motivo_contato_id');
                            $solic_proc->processo_tipo_parte_id = $this->request->getData('processo_tipo_parte_id');
                            if($solic_proc->processo_tipo_parte_id==3){
                                $solic_proc->nome_parte_representada = $this->request->getData('nome_parte_representada'); 
                            }
                            else if($solic_proc->processo_tipo_parte_id==4){
                                $solic_proc->nome_preso =  $this->request->getData('nome_preso');
                                $solic_proc->rg_preso = $this->request->getData('rg_preso');
                            }
                            $this->SolicitacaoProcesso->save($solic_proc);    
                        }
                        else{
                            if($solicitacao->assunto_id == 36){
                                if($solicitacao->sub_assunto_id == 21){
                                    $s_her = $this->SolicitacaoHerancas->newEntity();
                                    $s_her->solicitacao_id = $solicitacao->id;
                                    $s_her->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    foreach ($this->request->getData('heranca') as $heranca){
                                        if((int)$heranca == 1){
                                            $s_her->deixou_bens = 1;
                                            $s_her->deixou_valores = 0;
                                            $s_her->sem_bens = 0;
                                        }
                                        else if((int)$heranca == 2){
                                            $s_her->deixou_bens = 0;
                                            $s_her->deixou_valores = 1;
                                            $s_her->sem_bens = 0;
                                        }
                                        else{
                                            $s_her->deixou_bens = 0;
                                            $s_her->deixou_valores = 0;
                                            $s_her->sem_bens = 1;
                                        }
                                    }
                                    $s_her->data_obito = Time::createFromFormat('d/m/Y', $this->request->getData('data_obito'));
                                    $s_her->filho_menor = $this->request->getData('f_menor');
                                    $this->SolicitacaoHerancas->save($s_her);
                                }
                                if($solicitacao->sub_assunto_id == 22){                          
                                    $s_cert_obito = $this->SolicitacaoCertidaoObitos->newEntity();
                                    $s_cert_obito->solicitacao_id = $solicitacao->id;
                                    $s_cert_obito->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $s_cert_obito->registrado_na_bahia = $this->request->getData('registrado_na_bahia');
                                    $this->SolicitacaoCertidaoObitos->save($s_cert_obito);
                                }
                            }
                            else if($solicitacao->assunto_id == 42){
                                if($solicitacao->sub_assunto_id == 29){
                                    $s_plano_saude = $this->SolicitacaoPlanoSaude->newEntity();
                                    $s_plano_saude->solicitacao_id = $solicitacao->id;
                                    $s_plano_saude->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $s_plano_saude->nome_plano = $this->request->getData('nome_plano');
                                    $this->SolicitacaoPlanoSaude->save($s_plano_saude);
                                }
                            }
                            else if($solicitacao->assunto_id == 9){
                                if($solicitacao->sub_assunto_id == 30){
                                    $s_alimentos = $this->SolicitacaoAlimentos->newEntity();
                                    $s_alimentos->solicitacao_id = $solicitacao->id;
                                    $s_alimentos->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $s_alimentos->tipo_beneficiario_id = $this->request->getData('tipo_beneficiario');
                                    if($s_alimentos->tipo_beneficiario_id == 1){
                                        $s_alimentos->crianca_nascida = $this->request->getData('crianca_nasceu');
                                        if($s_alimentos->crianca_nascida == 0){
                                            $s_alimentos->tempo_gestacao = $this->request->getData('meses_gestao');
                                        }
                                        $s_alimentos->crianca_registrada = $this->request->getData('registrado_pai');
                                        $s_alimentos->pai_vivo = $this->request->getData('pai_vivo');
                                        $s_alimentos->pai_ofereceu_pensao = $this->request->getData('oferecer_pensao');
                                    }else if($s_alimentos->tipo_beneficiario_id == 2){
                                        $s_alimentos->acao_divorcio = $this->request->getData('acao_divorcio');
                                    }
                                    $s_alimentos->alimentos_determinados = $this->request->getData('alim_deter');
                                    if($s_alimentos->alimentos_determinados == 1){
                                        $s_alimentos->cobrar_parcelas = $this->request->getData('cobrar_parcelas');
                                        $s_alimentos->mudar_valor = $this->request->getData('mudar_valor');
                                    }
                                    $this->SolicitacaoAlimentos->save($s_alimentos);
                                }
                            }
                            else if($solicitacao->assunto_id == 44){
                                if($solicitacao->sub_assunto_id == 31){
                                    $s_desm_agend = $this->SolicitacaoDesmarcarAgendamentos->newEntity();
                                    $s_desm_agend->solicitacao_id = $solicitacao->id;
                                    $s_desm_agend->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $s_desm_agend->nome_agendado = $this->request->getData('nome_agendado');
                                    $s_desm_agend->data_agendada = Time::createFromFormat('d/m/Y', $this->request->getData('data_agendam'));
                                    $h = $this->request->getData('hora_agendam');
                                    $hora = $h['hour'];
                                    $minuto = $h['minute'];
                                    $horario = "{$hora}h {$minuto}m 00s";
                                    $s_desm_agend->hora_agendada = Time::createFromFormat('H\h i\m s\s',$horario)->format('H:i:s');
                                    $this->SolicitacaoDesmarcarAgendamentos->save($s_desm_agend);
                                }
                            }
                            else if($solicitacao->assunto_id == 38){
                                if($solicitacao->sub_assunto_id == 32){
                                    $s_violencia_domest = $this->SolicitacaoViolenciaDomesticas->newEntity();
                                    $s_violencia_domest->solicitacao_id = $solicitacao->id;
                                    $s_violencia_domest->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $s_violencia_domest->tipo_pedido_id = $this->request->getData('tipo_pedido');
                                    $s_violencia_domest->ocorrencia_policial = $this->request->getData('ocorrencia_policial');
                                    $s_violencia_domest->risco_fisico_emocional = $this->request->getData('risco_fisico_emocional');
                                    $s_violencia_domest->medida_protetiva_urgencia = $this->request->getData('medida_protetiva_urgencia');
                                    $s_violencia_domest->situacao_violencia = $this->request->getData('situacao_violencia');
                                    $s_violencia_domest->ultima_violencia_sofrida = $this->request->getData('ultima_violencia_sofrida');                          
                                    $this->SolicitacaoViolenciaDomesticas->save($s_violencia_domest);
                                }
                            }
                            else if($solicitacao->assunto_id == 41){
                                if($solicitacao->sub_assunto_id == 33){
                                    $solic_atend_civel = $this->SolicitacaoAtendimentoCiveis->newEntity();
                                    $solic_atend_civel->solicitacao_id = $solicitacao->id;
                                    $solic_atend_civel->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $solic_atend_civel->tipo_pedido_id = $this->request->getData('tipo_pedido_at_civel');
                                    $this->SolicitacaoAtendimentoCiveis->save($solic_atend_civel);                            
                                }
                            }
                            else if($solicitacao->assunto_id == 43){
                                if($solicitacao->sub_assunto_id == 34){
                                    $solic_fz_publica = $this->SolicitacaoFazendaPublica->newEntity();
                                    $solic_fz_publica->solicitacao_id = $solicitacao->id;
                                    $solic_fz_publica->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $solic_fz_publica->tipo_pedido_id = $this->request->getData('tipo_pedido_faz_pub');
                                    $solic_fz_publica->recebeu_intimacao = $this->request->getData('recebeu_intimacao');
                                    if($solic_fz_publica->recebeu_intimacao == 1){
                                        $solic_fz_publica->numero = $this->request->getData('numero');
                                    }
                                    $this->SolicitacaoFazendaPublica->save($solic_fz_publica);                            
                                }
                            }
                            else if($solicitacao->assunto_id == 45){
                                if($solicitacao->sub_assunto_id == 35){
                                    $solic_sde = $this->SolicitacaoSaudes->newEntity();
                                    $solic_sde->solicitacao_id = $solicitacao->id;
                                    $solic_sde->sub_assunto_id = $solicitacao->sub_assunto_id;                            
                                    $solic_sde->tipo_pedido_id = $this->request->getData('tipo_pedido_sde');
                                    $solic_sde->plano_de_saude = $this->request->getData('planSaud');
                                    if($solic_sde->plano_de_saude == 1){
                                        $solic_sde->nome_plano = $this->request->getData('n_pl');
                                    }
                                    $this->SolicitacaoSaudes->save($solic_sde);                              
                                }
                            }
                            else if($solicitacao->assunto_id == 40){
                                if($solicitacao->sub_assunto_id == 36){
                                    $solic_usuc = $this->SolicitacaoUsucapiao->newEntity();
                                    $solic_usuc->solicitacao_id = $solicitacao->id;
                                    $solic_usuc->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    foreach ($this->request->getData('tp_usucapiao') as $tp_usucapiao){
                                        if((int)$tp_usucapiao==1){
                                            $solic_usuc->primeiro_atendimento = 1;
                                            $solic_usuc->retornar_ao_nucleo = 0;
                                        }
                                        else{
                                            $solic_usuc->primeiro_atendimento = 0;
                                            $solic_usuc->retornar_ao_nucleo = 1;
                                        }
                                    }
                                    $this->SolicitacaoUsucapiao->save($solic_usuc);
                                }
                            }
                            else if($solicitacao->assunto_id == 39){
                                if($solicitacao->sub_assunto_id == 37){
                                    $solic_trab = $this->SolicitacaoTrabalhistas->newEntity();
                                    $solic_trab->solicitacao_id = $solicitacao->id;
                                    $solic_trab->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $solic_trab->vinculo_trabalhista_id = $this->request->getData('tipo_vinc');
                                    $solic_trab->revisao_beneficio = $this->request->getData('tra_solic_vtb');
                                    $this->SolicitacaoTrabalhistas->save($solic_trab);
                                }
                            }
                            else if($solicitacao->assunto_id == 37){
                                if($solicitacao->sub_assunto_id == 38){
                                    $solic_casamento = $this->SolicitacaoCasamentos->newEntity();
                                    $solic_casamento->solicitacao_id = $solicitacao->id;
                                    $solic_casamento->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $solic_casamento->noivos_maiores = $this->request->getData('noivos_maiores');
                                    if($solic_casamento->noivos_maiores ==0){
                                        $solic_casamento->pais_de_acordo = $this->request->getData('pais_de_acordo');
                                    }
                                    $this->SolicitacaoCasamentos->save($solic_casamento);
                                }
                                else if($solicitacao->sub_assunto_id == 39){
                                    $solic_divorcio = $this->SolicitacaoDivorcios->newEntity();
                                    $solic_divorcio->solicitacao_id = $solicitacao->id;
                                    $solic_divorcio->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $solic_divorcio->casado = $this->request->getData('casado');
                                    $solic_divorcio->filho_menor = $this->request->getData('filho_menor');
                                    $solic_divorcio->possui_bens = $this->request->getData('possui_bens');
                                    $solic_divorcio->divorcio_amigavel = $this->request->getData('tipo_divorcio');
                                    $solic_divorcio->medida_protetiva = $this->request->getData('medida_protetiva');
                                    $solic_divorcio->ocorrencia_conjuges = $this->request->getData('ocorrencia_conjuge');
                                    $solic_divorcio->ocorrencia_filhos = $this->request->getData('ocorrencia_filho');
                                    $this->SolicitacaoDivorcios->save($solic_divorcio);
                                }
                                else if($solicitacao->sub_assunto_id == 40){
                                    $solic_cert_casam = $this->SolicitacaoCertidaoCasamentos->newEntity();
                                    $solic_cert_casam->solicitacao_id = $solicitacao->id;
                                    $solic_cert_casam->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $solic_cert_casam->realizado_na_bahia = $this->request->getData('realizado_bahia');
                                    foreach ($this->request->getData('tipo_atend') as $tipo_atend){
                                        if((int)$tipo_atend == 1){
                                            $solic_cert_casam->retificacao = 1;
                                            $solic_cert_casam->averbacao_nome_casada = 0;
                                            $solic_cert_casam->averbacao_obito = 0;
                                            $solic_cert_casam->transcricao_casamento = 0;
                                        }
                                        else if((int)$tipo_atend == 2){
                                            $solic_cert_casam->retificacao = 0;
                                            $solic_cert_casam->averbacao_nome_casada = 1;
                                            $solic_cert_casam->averbacao_obito = 0;
                                            $solic_cert_casam->transcricao_casamento = 0;
                                        }
                                        else if((int)$tipo_atend == 3){
                                            $solic_cert_casam->retificacao = 0;
                                            $solic_cert_casam->averbacao_nome_casada = 0;
                                            $solic_cert_casam->averbacao_obito = 1;
                                            $solic_cert_casam->transcricao_casamento = 0;
                                        }
                                        else{
                                            $solic_cert_casam->retificacao = 0;
                                            $solic_cert_casam->averbacao_nome_casada = 0;
                                            $solic_cert_casam->averbacao_obito = 0;
                                            $solic_cert_casam->transcricao_casamento = 1;
                                        }
                                    }
                                    $this->SolicitacaoCertidaoCasamentos->save($solic_cert_casam);
                                }
                                else if($solicitacao->sub_assunto_id == 41){
                                    $solic_uniao_estavel = $this->SolicitacaoComprovarUniaoEstavel->newEntity();
                                    $solic_uniao_estavel->solicitacao_id = $solicitacao->id;
                                    $solic_uniao_estavel->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $solic_uniao_estavel->companheiro_vivo = $this->request->getData('companheiro_vivo');
                                    $solic_uniao_estavel->casal_vive_junto = $this->request->getData('casal_vive_junto');
                                    $solic_uniao_estavel->obter_beneficio = $this->request->getData('obter_beneficio');
                                    if($solic_uniao_estavel->obter_beneficio == 1){
                                        foreach ($this->request->getData('tipo_instituto') as $tipo_instituto){
                                            if((int)$tipo_instituto == 1){
                                                $solic_uniao_estavel->inss = 1;
                                                $solic_uniao_estavel->inst_estadual = 0;
                                                $solic_uniao_estavel->inst_municipal = 0;
                                            }
                                            else if((int)$tipo_instituto == 2){
                                                $solic_uniao_estavel->inss = 0;
                                                $solic_uniao_estavel->inst_estadual = 1;
                                                $solic_uniao_estavel->inst_municipal = 0;
                                            } 
                                            else{
                                                $solic_uniao_estavel->inss = 0;
                                                $solic_uniao_estavel->inst_estadual = 0;
                                                $solic_uniao_estavel->inst_municipal = 1;
                                            }
                                        }
                                    }
                                    $this->SolicitacaoComprovarUniaoEstavel->save($solic_uniao_estavel);
                                }
                            }
                            else if($solicitacao->assunto_id == 1){
                                if($solicitacao->sub_assunto_id == 51){
                                    $s_adocao = $this->SolicitacaoAdocao->newEntity();
                                    $s_adocao->solicitacao_id = $solicitacao->id;
                                    $s_adocao->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $s_adocao->idade_adotado = $this->request->getData('idade_adotado');
                                    $this->SolicitacaoAdocao->save($s_adocao);
                                }
                            }
                            else if($solicitacao->assunto_id == 26){
                                if($solicitacao->sub_assunto_id == 52){
                                    $s_vg_inter = $this->SolicitacaoViagemInter->newEntity();
                                    $s_vg_inter->solicitacao_id = $solicitacao->id;
                                    $s_vg_inter->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $s_vg_inter->inicio_viagem = Time::createFromFormat('d/m/Y', $this->request->getData('inicio_viagem'));
                                    $s_vg_inter->fim_viagem = Time::createFromFormat('d/m/Y', $this->request->getData('fim_viagem'));
                                    $s_vg_inter->previsao_retorno = $this->request->getData('previsao_retorno');
                                    $this->SolicitacaoViagemInter->save($s_vg_inter);
                                }
                            }
                            else if($solicitacao->assunto_id == 2){
                                if($solicitacao->sub_assunto_id == 55){
                                    $s_her_avara = $this->SolicitacaoAlvaraHeranca->newEntity();
                                    $s_her_avara->solicitacao_id = $solicitacao->id;
                                    $s_her_avara->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    foreach ($this->request->getData('heranca_alvara') as $herancaAlvara){
                                        if((int)$herancaAlvara == 1){
                                            $s_her_avara->algum_bem = 1;
                                            $s_her_avara->apenas_valores = 0;
                                            $s_her_avara->nenhum_bem = 0;
                                        }
                                        else if((int)$herancaAlvara == 2){
                                            $s_her_avara->algum_bem = 0;
                                            $s_her_avara->apenas_valores = 1;
                                            $s_her_avara->nenhum_bem = 0;
                                        }
                                        else{
                                            $s_her_avara->algum_bem = 0;
                                            $s_her_avara->apenas_valores = 0;
                                            $s_her_avara->nenhum_bem = 1;
                                        }
                                    }
                                    $s_her_avara->data_obito = Time::createFromFormat('d/m/Y', $this->request->getData('data_obito_alvara'));
                                    $s_her_avara->filho_menor = $this->request->getData('f_menor_alvara');
                                    $s_her_avara->bens_a_dividir = $this->request->getData('bens_dividir_alvara');
                                    $this->SolicitacaoAlvaraHeranca->save($s_her_avara);
                                }
                                if($solicitacao->sub_assunto_id == 22){                          
                                    $s_cert_obito = $this->SolicitacaoCertidaoObitos->newEntity();
                                    $s_cert_obito->solicitacao_id = $solicitacao->id;
                                    $s_cert_obito->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $s_cert_obito->registrado_na_bahia = $this->request->getData('registrado_na_bahia');
                                    $this->SolicitacaoCertidaoObitos->save($s_cert_obito);
                                }
                            }
                            else if($solicitacao->assunto_id == 5){
                                if($solicitacao->sub_assunto_id == 57){
                                    $s_exame_de_dna = $this->SolicitacaoExameDeDna->newEntity();
                                    $s_exame_de_dna->solicitacao_id = $solicitacao->id;
                                    $s_exame_de_dna->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $s_exame_de_dna->pai_registro = $this->request->getData('pai_registro');
                                    $this->SolicitacaoExameDeDna->save($s_exame_de_dna);
                                }
                            }
                            else if($solicitacao->assunto_id == 27){
                                if($solicitacao->sub_assunto_id == 58){
                                    $sass_idoso = $this->SolicitacaoIdoso->newEntity();
                                    $sass_idoso->solicitacao_id = $solicitacao->id;
                                    $sass_idoso->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $sass_idoso->tipo_pedido_id = $this->request->getData('tipo_pedido_idoso');
                                    $sass_idoso->ocorrencia_policial = $this->request->getData('ocorrencia_policial_idoso');
                                    $sass_idoso->maus_tratos = $this->request->getData('mau_trato_idoso');
                                    $this->SolicitacaoIdoso->save($sass_idoso);
                                }
                            }
                            else if($solicitacao->assunto_id == 11){
                                if($solicitacao->sub_assunto_id == 59){
                                    $solic_Regulam_visita = $this->SolicitacaoRegulamentacaoVisita->newEntity();
                                    $solic_Regulam_visita->solicitacao_id = $solicitacao->id;
                                    $solic_Regulam_visita->sub_assunto_id = $solicitacao->sub_assunto_id;
                                    $solic_Regulam_visita->visita_determinada = $this->request->getData('visita_determinada');
                                    if($solic_Regulam_visita->visita_determinada == 1){
                                        $solic_Regulam_visita->modificar = $this->request->getData('modificar');
                                    }
                                    $solic_Regulam_visita->cumprir_decisao = $this->request->getData('decisao_justica');
                                    $this->SolicitacaoRegulamentacaoVisita->save($solic_Regulam_visita);                            
                                }
                            }
                        }
                        foreach ($this->request->getData('preferencia') as $preferencia){
                            $s_h = $this->SolicitacoesHorarios->newEntity();
                            $s_h->solicitacao_id = $solicitacao->id;
                            $s_h->horario_id = (int)$preferencia;
                            $this->SolicitacoesHorarios->save($s_h);                    
                        }       
                        $this->Flash->success(__('A solicitação será analisada por nossa equipe e em breve entraremos em contato. Fique atento ao seu e-mail! Você também pode ficar sabendo a data do seu agendamento no site! Consulte o site do Agendamento on line da DPE com frequência para saber o dia de comparecer na Defensoria Pública!'));
                        return $this->redirect(['action' => 'solicitacoes?suces=true']);                
                    }
                    else{
                        $this->Flash->error(__('A solicitação não pôde ser salva. Por favor, tente novamente.'));
                    }
            }
            else{
/*                
                $this->Flash->error(__('Desculpem os transtornos, estamos procedendo a atualização do sistema para melhor atendê-los.'));
*/
                
                $this->Flash->error(__('Este canal está suspenso temporariamente. Somente os casos urgentes serão atendidos pela nossa central de atendimento telefônico.'));



            }            
        }
        //Atribui o valor da variável $email ao campo
        $email = $this->request->session()->read('User.email');
        $this->set(compact('email'));
        $this->set(compact('solicitacao', 'title','exibeaviso'));
        $this->set('_serialize', ['solicitacao']);

        $assuntos = TableRegistry::get("Assuntos");
        $a = $assuntos->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'nome','conditions' => ['chat_bot' => 0, 'estado' => 1]])->toArray();
        $this->set('assuntos', $a);

        $this->loadModel('Assistidos');
        $this->loadModel('Pessoas');
        $this->loadModel('PessoaFisicas');
        $this->loadModel('Contatos');
        $this->loadModel('Users');

        // ATUALIZA E-MAIL DO ASSISTIDO CASO ELE NÃO POSSUA NA TABELA DE CONTATOS
        $id = $this->Auth->user('id');
        $user = $this->Users->get($id);
        $assistido = $this->Assistidos->get($user->sigad_user);        
        $pessoa = $this->Pessoas->get($assistido->pessoa_id);
        $pessoaFisica = $this->PessoaFisicas->findByPessoaId($pessoa->id)->first();
        $emailContato = $pessoa->contato->email;
        $this->set(compact('emailContato'));
        if(empty($emailContato)){
            $contato = $this->Contatos->get($pessoa->contato_id);
            $data['Contatos']['email'] = $email;
            $contato = $this->Contatos->patchEntity($contato, $data);
            //print_r($contato);
            $this->Contatos->save($contato);
        }           
        $sub_assunto_ffi = TableRegistry::get("SubAssuntos");
        $s_ffi = $sub_assunto_ffi->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 36 ]])->toArray();
        $this->set('sub_assunto_ffi', $s_ffi);

        $sub_assunto_cuds = TableRegistry::get("SubAssuntos");
        $s_cuds = $sub_assunto_cuds->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 37 ]])->toArray();
        $this->set('sub_assunto_cuds', $s_cuds);

        $sub_assunto_consumidor = TableRegistry::get("SubAssuntos");
        $s_ac = $sub_assunto_consumidor->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 42 ]])->toArray();
        $this->set('sub_assunto_consumidor', $s_ac);

        $sub_assunto_alimentos = TableRegistry::get("SubAssuntos");
        $s_alim = $sub_assunto_alimentos->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 9 ]])->toArray();
        $this->set('sub_assunto_alimentos', $s_alim);

        $sub_assunto_desmarcar = TableRegistry::get("SubAssuntos");
        $s_ass_desmarcar = $sub_assunto_desmarcar->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 44 ]])->toArray();
        $this->set('sub_assunto_desmarcar', $s_ass_desmarcar);

        $sub_assunto_violencia = TableRegistry::get("SubAssuntos");
        $s_ass_viol = $sub_assunto_violencia->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 38 ]])->toArray();
        $this->set('sub_assunto_violencia', $s_ass_viol);

        $sub_assunto_at_civel = TableRegistry::get("SubAssuntos");
        $s_ass_at_civel = $sub_assunto_at_civel->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 41 ]])->toArray();
        $this->set('sub_assunto_at_civel', $s_ass_at_civel);

        $sub_assunto_faz_pub = TableRegistry::get("SubAssuntos");
        $s_ass_fz_pub = $sub_assunto_faz_pub->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 43 ]])->toArray();
        $this->set('sub_assunto_faz_pub', $s_ass_fz_pub);

        $sub_ass_saude = TableRegistry::get("SubAssuntos");
        $s_ass_sde = $sub_ass_saude->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 45 ]])->toArray();
        $this->set('sub_ass_saude', $s_ass_sde);

        $sub_ass_usuc = TableRegistry::get("SubAssuntos");
        $s_ass_us = $sub_ass_usuc->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 40 ]])->toArray();
        $this->set('sub_ass_usuc', $s_ass_us);

        $sub_assunto_trab = TableRegistry::get("SubAssuntos");
        $s_ass_tb = $sub_assunto_trab->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 39 ]])->toArray();
        $this->set('sub_assunto_trab', $s_ass_tb);

        $processo_motivo_contato = TableRegistry::get("ProcessoMotivoContato");
        $b = $processo_motivo_contato->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id'])->toArray();
        $this->set('processo_motivo_contato', $b);

        $processo_tipo_partes = TableRegistry::get("ProcessoTipoPartes");
        $c = $processo_tipo_partes->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id'])->toArray();
        $this->set('processo_tipo_partes', $c);

        $beneficiario_pensao = TableRegistry::get("TipoBeneficiarios");
        $b_pensao = $beneficiario_pensao->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id'])->toArray();
        $this->set('beneficiario_pensao', $b_pensao);

        $vinculo_trabalhista = TableRegistry::get("VinculoTrabalhistas");
        $vinc_trab = $vinculo_trabalhista->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id'])->toArray();
        $this->set('vinculo_trabalhista', $vinc_trab);

        $sub_assunto_adocao = TableRegistry::get("SubAssuntos");
        $s_as_adocao = $sub_assunto_adocao->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 1 ]])->toArray();
        $this->set('sub_assunto_adocao', $s_as_adocao);

        $sub_assunto_viagem_inter = TableRegistry::get("SubAssuntos");
        $s_as_viagem_inter = $sub_assunto_viagem_inter->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 26 ]])->toArray();
        $this->set('sub_assunto_viagem_inter', $s_as_viagem_inter);

        $sub_assunto_alvara_judicial = TableRegistry::get("SubAssuntos");
        $s_as_alvara = $sub_assunto_alvara_judicial->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 2 ]])->toArray();
        $this->set('sub_assunto_alvara_judicial', $s_as_alvara);

        $sub_assunto_exame_dna = TableRegistry::get("SubAssuntos");
        $s_as_exame_dna = $sub_assunto_exame_dna->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 5 ]])->toArray();
        $this->set('sub_assunto_exame_dna', $s_as_exame_dna);

        $sub_assunto_idoso = TableRegistry::get("SubAssuntos");
        $sa_idoso = $sub_assunto_idoso->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 27 ]])->toArray();
        $this->set('sub_assunto_idoso', $sa_idoso);

        $sub_assunto_regul_visita = TableRegistry::get("SubAssuntos");
        $sa_regul_visita = $sub_assunto_regul_visita->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 11 ]])->toArray();
        $this->set('sub_assunto_regul_visita', $sa_regul_visita);

        $sub_assunto_locacao = TableRegistry::get("SubAssuntos");
        $sa_locacao = $sub_assunto_locacao->find("list", ['keyField' => 'id', 'valueField' => 'nome', 'order' => 'id', 'conditions' => ['assunto_id' => 47 ]])->toArray();
        $this->set('sub_assunto_locacao', $sa_locacao);
    }

    public function solicitacoes() {
        if (!$this->request->session()->read('User.sigad_user')){
            $this->Flash->error(__('Para realizar agendamentos é necessário concluir seu cadastro.'));
            return $this->redirect("users/add");
        }

        $this->viewBuilder()->setLayout('sistema');
        $this->set('title', "Minhas solicitações");
        
        $sigad_user = $this->request->session()->read('User.sigad_user');

        $sigad_user = 520167;
        $solicitacoes = $this->Solicitacoes->find('all', [
            'conditions' => [
                'sigad_user' => $sigad_user,
                'agendado' => 0
            ],
            'order' => ['id DESC']
        ]);



        $this->set('solicitacoes', $solicitacoes);
        $assuntos = TableRegistry::get("Assuntos");
        $a = $assuntos->find("list", ['keyField' => 'id', 'valueField' => 'nome','conditions' => ['chat_bot' => 0]])->toArray();
        $this->set('assuntos', $a);
        $status = TableRegistry::get("StatusSolicitacoes");
        $status_descricao = $status->find("list", ['keyField' => 'id', 'valueField' => 'nome'])->toArray();
        $this->set('status_descricao', $status_descricao);
    }

    /**
     * Delete method
     *
     * @param string|null $id Agendamento id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */

    public function delete($id = null){
        $this->request->allowMethod(['post', 'delete']);
        $agendamento = $this->Agendamentos->get($id);

        if ($this->Agendamentos->delete($agendamento)) {
            $this->Flash->success(__('The agendamento has been deleted.'));
        }
        else{
            $this->Flash->error(__('The agendamento could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function comprovante($id){
        $this->viewBuilder()->setLayout('impressao');
        $user = $this->request->session()->read('User');
        $agendamento = $this->Agendamentos->find('comprovante', [
            'id' => $id
        ]);       
     

        $this->set(compact('agendamento'));
    }

    public function cancel($id){
        $this->request->allowMethod(['post', 'delete']);
        $this->loadModel('Historicos');
        
        $historico = $this->Historicos->newEntity();
        $historico->agendamento_id = $id;
        $historico->situacao_id = 43; //id para cancelado
        $historico->data = date('Y-m-d H:i:s');
        $historico->observacao = "Cancelado pelo assistido - Agendamento Online";

        if ($this->Historicos->save($historico)){
            $this->Flash->success(__('Agendamento cancelado com sucesso.'));
        }else{
            $this->Flash->error(__('O agendamento não pôde ser cancelado, por favor tente novamente.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    public function cancelSolicitacao($id){
        $this->request->allowMethod(['post', 'delete']);
        try {
            $s = $this->Solicitacoes->get($id);
            if ($s->agendado) {
                $this->Flash->error(__('A Solicitação não pôde ser cancelada pois já foi agendada.'));
            }
            else{
                $r = $this->Solicitacoes->delete($s);
                if ($r){
                    $this->Flash->success(__('Solicitação cancelada com sucesso.'));
                }
                else {
                    $this->Flash->error(__('A Solicitação não pôde ser cancelada, por favor tente novamente.'));
                }
            }
        } catch (\Exception $e) {

        }
        return $this->redirect(['action' => 'index']);
    }

    // - tranca sexta às 15h 
    //- abre segunda as 07h 
    //- tranca feriado
    public function VerificaBloqueio(){
        $dia_semana = date('w');
        $hora_atual = date('H:i');        
        $exibe = false;
        $agoraSigad = date('Y-m-d');
        $agora = date('Y/m/d');
        $bloq = 0;

        $conn = ConnectionManager::get('sigad');
        $dados = $conn
        ->newQuery()
        ->select('*')
        ->from('bloqueio_solicitacao')->execute()->fetchAll('assoc');
        foreach($dados as $datas){
            $dateInicial = new DateTimeImmutable($datas['data_inicio']); 
            $dateFinal = new DateTimeImmutable($datas['data_fim']);
            
            if($agoraSigad >= $dateInicial->format('Y-m-d') && $agoraSigad <= $dateFinal->format('Y-m-d')){
                $exibe = true;   
                if($dateInicial->format('Y-m-d') === $dateFinal->format('Y-m-d')){
                    if($hora_atual >= $datas['hora_inicio'] && $hora_atual < $datas['hora_fim']){
                        $exibe = true;
                    }else{
                        $exibe = false;
                    }
                }else{
                    if($agoraSigad === $dateInicial->format('Y-m-d')){
                        if($hora_atual >= $datas['hora_inicio']){
                            $exibe = true;
                        }else{
                            $exibe = false;
                        }
                    }
                    else if($agoraSigad === $dateFinal->format('Y-m-d')){
                        if($hora_atual < $datas['hora_fim']){
                            $exibe = true;
                        }else{
                            $exibe = false;
                        }
                    }                    
                }
            }
        }

        if($bloq == 1){

            if($dia_semana >= 1 and $dia_semana <= 4){
                //segunda a quinta
                /*
                if($dia_semana == 1 and $hora_atual < '07:00'){ 
                    if($hora_atual < '07:00' or $hora_atual > '18:00'){            
                        
                        $exibe = true;
                                        
                    }else{                    
                        $exibe = false;
                    }
                } 
                */
                 $exibe = false;                          
            }elseif($dia_semana == 5){
                //sexta
                if($hora_atual < '07:00' or $hora_atual > '15:00'){
                    $exibe = true;
                }else{
                    $exibe = false;
                }
            }else{
                //fim de semana
                $exibe = true;
            } 
        }

        //Bloquear uma data específica
        $data_bloqueada = ["01/05/2023"];
        $encontrou_data = in_array($agora, $data_bloqueada);             
        if($encontrou_data){
            $exibe = true;
        }        
        return $exibe;       

    }

    private function validarHorario()
    {
        $horaAtual = date("H:i:s");
        $diaSemana = date('N');

        if($horaAtual >= '08:00:00' && $horaAtual <= '17:00:00' && $diaSemana >= 1 && $diaSemana <= 5)
        {
            return true;
        }

        return false;
    }
}
