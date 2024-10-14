<?php
/**
 * @var \App\View\AppView $this
 */ 
?>
<script src="https://cdn.webrtc-experiment.com/RecordRTC.js"></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="https://unpkg.com/audiobuffer-to-wav"></script>
<?php if($exibeaviso){ ?>
    <!-- <div class="row justify-content-center mt-5">
        <div class="alert alert-warning col-md-12 text-center alert-dismissible show " role="alert">
            <img src="/img/icon_alerta.png" alt="" class="ml-5">
            <p class="h5 ml-5">SUSPENSÃO TEMPORÁRIA DE ATENDIMENTO NO AGENDAMENTO ONLINE</p>
            <hr>
            <div class="h6 text-left">
                <p>
                    <center> 
                       
                        Desculpem os transtornos, estamos procedendo a atualização do sistema para melhor atendê-los.


                    </center>
                </p>
            </div>
        </div>
    </div> -->
    <div class="row justify-content-center mt-5">
        <div class="alert alert-warning col-md-12 text-center alert-dismissible show " role="alert">
            <img src="/img/icon_alerta.png" alt="" class="ml-5">
            <p class="h5 ml-5">AGENDAMENTO ONLINE - HORÁRIO DE ATENDIMENTO</p>
            <hr>
            <div class="h6 text-left">
                <p>
                    <center> 
                       
                    Prezado Assistido(a), 

                        Nosso Horário de Atendimento das Solicitações Online é de Segunda a Sexta-feira das 08:00h às 17:00h. 
                        Retorne em breve, será um prazer atender sua solicitação! 


                    </center>
                </p>
            </div>
        </div>
    </div>
    
<?php } else{ ?>
    <div class="row justify-content-md-center pt-4">
        <div class="alert alert-info col-md-10" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="alert-heading">ATENÇÃO!</h4>
            <p>Antes de fazer a solicitação clique em <strong>Meus Dados</strong> (no menu) e verifique se o seu cadastro está correto e completo. 
                É muito importante que seu cadastro esteja correto para que nossos atendentes respondam ao atendimento através do e-mail.</p>
        </div>
    </div>
    <!-- Solicitar Agendamento -->
    <div class="row">
        <div class="col-md-auto mt-2" id="topicoAgendarAtendimento">
            <p class="h5 py-3 px-3  mb-0 tituloFormulario">Solicitar Agendamento</p>
        </div>
    </div>

    <!-- Formulário -->
    <div class="row justify-content-md-center pb-5">
        <?= $this->Form->create($solicitacao,[
            'class' => 'pt-4 pb-4 px-5 cor-labelformulario col-md-9 was-validated',
            'id' => 'formulario'
            ]);
        ?>
        <div class="row border border-secondary">
            <div class="col-12">
                <!-- 1º Bloco - [Informação Geral]-->
                <div class="row">
                    <div class="col">
                        <!-- [Título do Bloco] -->
                        <div class="row rotuloForm text-white bg-secondary">
                            Informação geral
                        </div>
                        <!-- [Corpo do Bloco] -->
                        <div class="row align-items-center pt-3 pb-3">
                            <!-- [Cidade] -->              
                            <label class="col-sm col-form-label" for="NomeCidade">Selecione a Cidade:</label>
                            <div class="col">
                                <?= $this->Form->control(
                                    'comarca',[
                                        'id' => 'NomeCidade',
                                        'label' => false,
                                        'empty' => 'Selecione...',
                                        'class' => 'form-control',
                                        'required',
                                        'options' => [
                                            256 => 'Salvador',
                                            3 => 'Alagoinhas', 
                                            5 => 'Amargosa', 
                                            25 => 'Barreiras', 
                                            31 => 'Bom Jesus da Lapa',
                                            35 => 'Brumado',
                                            44 => 'Camaçari',
                                            50 => 'Candeias',
                                            80 => 'Cruz das Almas',
                                            87 => 'Esplanada',
                                            88 => 'Euclides da Cunha',
                                            89 => 'Eunápolis',
                                            90 => 'Feira de Santana',
                                            99 => 'Guanambi',
                                            399 => 'Ipiaú',
                                            400 => 'Ipirá',
                                            116 => 'Ilhéus',
                                            125 => 'Irecê',
                                            127 => 'Itaberaba',
                                            128 => 'Itabuna',
                                            142 => 'Itaparica',
                                            145 => 'Itapetinga',
                                            160 => 'Jacobina',
                                            166 => 'Jequié',
                                            172 => 'Juazeiro',
                                            176 => 'Lauro de Freitas',
                                            206 => 'Nazaré',
                                            470 => 'Paripiranga',
                                            226 => 'Paulo Afonso',
                                            237 => 'Porto Seguro',
                                            492 => 'Ribeira do Pombal',
                                            289 => 'Serrinha',
                                            266 => 'Santo Amaro',
                                            267 => 'Santo Antônio de Jesus',
                                            285 => 'Senhor do Bonfim',
                                            291 => 'Simões Filho',
                                            297 => 'Teixeira de Freitas',
                                            312 => 'Valença',
                                            315 => 'Vitória da Conquista'
                                        ],
                                    ]);
                                ?>
                            </div>
                            <!-- [Sobre o processo] -->
                            <label class="col-sm col-form-label">Já tem processo?</label>
                            <div class="col form-inline">
                                <div class="form-check-inline">
                                    <?=$this->Form->radio('processo',[1 => 'Sim'],['hiddenField' => false]);?>
                                </div>
                                <div class="form-check-inline">
                                    <?=$this->Form->radio('processo',[0 => 'Não'],['hiddenField' => false,'value' => '']);?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 2º Bloco - [Informação sobre o processo ]-->
                <div class="row">

                    <div class="col" style="display: none" id="procSim">
                        <!-- [Título do Bloco] -->
                        <div class="row rotuloForm text-white bg-secondary">
                            Informação sobre o processo
                        </div>
                        <!-- [Corpo do Bloco] -->
                        <div class="row pt-2 pb-2">
                            <!-- [Número do processo] -->
                            <div class="col-12 pt-1 pb-1" id="num_processo">
                                <div class="row align-items-center">
                                    <label class="col-4 col-form-label" for="numProc">Número do processo:</label>
                                    <div class="col-6">
                                        <?=$this->Form->text('numero_processo',[
                                            'class'=>'form-control',
                                            'id' => 'numProc',
                                            'required',
                                            'disabled'
                                            ]);
                                        ?>
                                        <?=$this->Form->unlockField('numero_processo');?> 
                                    </div>
                                </div>
                            </div>
                            <!-- [Motivo do contato] -->
                            <div class="col-12 pt-1 pb-1 borda-topo">
                                <div class="row align-items-center">
                                    <label class="col-4 col-form-label" for="motCont">Motivo do seu contato:</label>
                                    <div class="col-6">
                                        <?= $this->Form->control(
                                                'processo_motivo_contato_id',[
                                                'label' => false,
                                                'empty' => 'Selecione...',
                                                'options' => $processo_motivo_contato,
                                                'class' => 'form-control',
                                                'id' => 'motCont',
                                                'required',
                                                'disabled'
                                            ]);
                                        ?>
                                        <?=$this->Form->unlockField('processo_motivo_contato_id');?>
                                    </div>
                                </div>
                            </div>
                            <!-- [Participação no processo] -->
                            <div class="col-12 pt-1 pb-1 borda-topo">
                                <div class="row align-items-center">
                                    <label class="col-4 col-form-label" for="partProc">Sua participação no processo:</label>         
                                    <div class="col-6">
                                        <?= $this->Form->control(
                                            'processo_tipo_parte_id',[
                                                'label' => false,
                                                'onchange'=> 'exibirParticProcesso()',
                                                'empty' => 'Selecione...',
                                                'options' => $processo_tipo_partes,
                                                'class' => 'form-control',
                                                'id' => 'partProc',
                                                'required',
                                                'disabled'
                                            ]);
                                        ?>
                                        <?=$this->Form->unlockField('processo_tipo_parte_id');?>
                                    </div>
                                </div>
                            </div>
                            <!-- [Participação no processo: Representa uma das partes] -->
                            <div class="col-12" id="trat_parte" style="display:none">
                                <!-- [Nome completo] -->
                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                    <label class="col-4 col-form-label" for="nomeComp">Nome completo da parte representada:</label>
                                    <div class="col-6">
                                        <?=$this->Form->text('nome_parte_representada',[
                                            'class'=>'form-control',
                                            'id' => 'nomeComp',
                                            'required',
                                            'disabled'
                                            ]);
                                        ?>
                                        <?=$this->Form->unlockField('nome_parte_representada');?>
                                    </div>
                                </div>
                            </div>
                            <!-- [Participação no processo: Familiar de pessoa presa] -->
                            <div class="col-12" id="trat_famil_preso" style="display:none">
                                <!-- [Nome do Preso] -->        
                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                    <label class="col-4 col-form-label" for="nomepreso" >Nome do(a) preso(a):</label>
                                    <div class="col-6">
                                        <?=$this->Form->text('nome_preso',[
                                            'class'=>'form-control',
                                            'id' => 'nomepreso',
                                            'required',
                                            'disabled'
                                            ]);
                                        ?>
                                        <?=$this->Form->unlockField('nome_preso');?>
                                    </div>
                                </div>
                                <!-- [RG do Preso] --> 
                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                    <label class="col-4 col-form-label" for="RgPres" >RG do(a) preso(a):</label>
                                    <div class="col-6">
                                        <?=$this->Form->text('rg_preso',[
                                            'class'=>'form-control',
                                            'id' => 'RgPres',
                                            'required',
                                            'disabled'
                                            ]);
                                        ?>
                                        <?=$this->Form->unlockField('rg_preso');?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 3º Bloco - [Relatório]-->
                <div class="row">
                <div class="col" style="display:none" id="relato">
                    <!-- [Título do Bloco] -->
                    <div class="row rotuloForm text-white bg-secondary">
                        Relatório
                    </div>
                    <!-- [Corpo do Bloco] -->
                    <!-- [Relato em áudio] -->
                    <div class="pt-2 pb-2">
                        <div style="margin: 15px;" class="relato-audio">
                            <label> Relate seu caso de forma objetiva por áudio </label>  
                            <a style="font-size: 12px;">(áudio tem um limite de até 2 minutos)</a>    
                            <div class="row pb-1" style="justify-content: center;">
                                <button type="button" onclick="Start()" class="btn btn-success btn-audio" id="btnStart" >
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-mic-fill" viewBox="0 0 16 16">
                                        <path d="M5 3a3 3 0 0 1 6 0v5a3 3 0 0 1-6 0V3z"/>
                                        <path d="M3.5 6.5A.5.5 0 0 1 4 7v1a4 4 0 0 0 8 0V7a.5.5 0 0 1 1 0v1a5 5 0 0 1-4.5 4.975V15h3a.5.5 0 0 1 0 1h-7a.5.5 0 0 1 0-1h3v-2.025A5 5 0 0 1 3 8V7a.5.5 0 0 1 .5-.5z"/>
                                    </svg>
                                </button>     
                                <button type="button" class="btn btn-danger btn-audio none-audio" id="btnStop">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-stop-fill" viewBox="0 0 16 16">
                                        <path d="M5 3.5h6A1.5 1.5 0 0 1 12.5 5v6a1.5 1.5 0 0 1-1.5 1.5H5A1.5 1.5 0 0 1 3.5 11V5A1.5 1.5 0 0 1 5 3.5z"/>
                                    </svg>
                                </button>                 
                                <!-- [Tempo] -->
                                <a id="timer"></a>  
                                <div class="row center">
                                    <div id="audio-relato"></div>    
                                    <button type="button" onclick="deleteAudio()" class="btn btn-danger btn-audio none-audio" id="btnDelete">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"  fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                            <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                        </svg>
                                    </button>                                
                                </div>    
                            </div>
                        </div>
                        <div class="erro-audio">
                            <div id ="alerta_error_audio" animation="true" name="alerta_error_audio" class="p-3 mb-2 bg-primary text-white" style="display:none"> </div>   
                        </div>
                        <!-- [Relato] -->
                        <div class="col-12 pt-1">
                            <?= $this->Form->control('relato-textarea',[                                    
                                'type' => 'textarea',
                                'label' => 'Relate seu caso de forma objetiva',
                                'placeholder' => 'Relate seu caso, com no mínimo 30 e no máximo 1000 caracteres',
                                'maxlength' => '1000',
                                'minlength' => '30',
                                'onkeyup' => 'countChar(this)',
                                'class' => 'form-control',
                                'spellcheck'=> 'true',
                                'required'=>"required",
                                "title"=> "Seu relato deve ter no mínimo 30 caracteres.",
                                "data-placement" => "bottom"
                                ]);
                            ?>
                        </div>
                        <!-- [Contagem de caracteres] -->
                        <div class="col-12 text-right pb-1">
                            <span id="charCounter">0</span>/1000
                        </div>
                        
                        <!-- Botão de enviar relato -->
                        <div class="col-md-12 "  id="enviar_relato_ia" style="display:none">                                
                            <button type="button" onclick="validar_relato()" class ="btn btn-success btn-block" id="enviar_relato_btn_ia"><b>CLIQUE AQUI</b> PARA A ANÁLISE DO RELATO</button>                                    
                        </div>

                        <!-- Botão de editar relato -->
                        <div class="col-md-12 text-right" id="editar_relato_ia" style="display:none">                                
                            <button type="button" class ="btn btn-success" id="editar_relato_btn_ia">Editar relato</button>                                    
                        </div> 
                    <script src="https://unpkg.com/sweetalert2@7.19.3/dist/sweetalert2.all.js"></script>                                                    
                    </div>

                    <div id ="alerta_ia" name="alerta_ia" class="p-3 mb-2 bg-success text-white" style="display:none"></div>
                   
                    <div id ="alerta_error" animation = "true" name="alerta_error" class="p-3 mb-2 bg-primary text-white" style="display:none"> </div>   
                   
                    </div>                    
        </div>
                
           

                
                <!-- 3º Bloco - [Informação sobre o Assunto ]-->
                <div class="row">
                    <div class="col" style="display:none" id="procNao">
                        <!-- [Título do Bloco] -->
                        <div class="row rotuloForm text-white bg-secondary">
                            Informação sobre o assunto                        
                        </div>
                        <!-- [Corpo do Bloco] -->
                        <div class="row pb-1">
                            <!-- [Escolher o Assunto] -->
                            <div class="col-12 alinhar pt-1 pb-1" id="tp_do_assunto">
                                <?= $this->Form->control('assunto_id',[
                                    'label' => 'Qual o motivo do seu contato?',
                                    'onchange'=> 'exibirAssunto()',
                                    'class' => 'form-control',
                                    'options' => $assuntos,
                                    'id'=>'nome_assunto',
                                    'empty' => 'Selecione...',
                                    'disabled'
                                    ]);
                                ?>
                                <?=$this->Form->unlockField('assunto_id');?>
                            </div>
                            <!-- [Pesquisar Assunto] -->
                            <div class="col-12 alinhar">
                                <div class="row align-items-center">
                                    <div class="col-3" id="pesquisar_assunto">                                   
                                        <input type="checkbox" id="filtro_assunto" onchange="(funcAlternar('#filtro'), filtroAtivado())">
                                        <label for="filtro_assunto">Pesquisar Assunto</label>                                   
                                    </div>
                                    <!-- [Filtrar] -->
                                    <div class="col pt-1 pb-1" id="filtro" style="display:none">  
                                        <input type="text" class="form-control" oninput="onInput()" id="input" placeholder="Buscar..." list='dlist'>
                                        <datalist id='dlist'>
                                            <option value="2ª via da certidão de óbito"></option>
                                            <option value="Abertura de registro público"></option>
                                            <option value="Abrigo/Casa de Acolhimento (Idoso)"></option>
                                            <option value="Abrigo/Casa de Acolhimento (Violência Doméstica)"></option>
                                            <option value="Acidente de trabalho"></option>
                                            <option value="Ações contra o estado/município"></option>
                                            <option value="Adi-escolar (agente de desenvolvimento infantil)"></option>
                                            <option value="Adoção"></option>
                                            <option value="Agressão policial"></option>
                                            <option value="Alterar regime de bens"></option>
                                            <option value="Alvará Judicial"></option>
                                            <option value="Ausência de transporte escolar gratuito"></option>
                                            <option value="Autorização para viagem internacional"></option>
                                            <option value="Banco"></option>
                                            <option value="Casamento/união estável/divórcio/separação"></option>
                                            <option value="CLT"></option>
                                            <option value="Cobrança de aluguel"></option>
                                            <option value="COELBA/EMBASA/Telefonia/Transporte Público"></option>
                                            <option value="Concurso público"></option>
                                            <option value="Cônjuge ou companheiro(a) está se desfazendo do patrimônio"></option>
                                            <option value="Consignação de aluguel"></option>
                                            <option value="Consórcio"></option>
                                            <option value="Consumidor"></option>
                                            <option value="Cremação de corpo"></option>
                                            <option value="Dano moral/Dano material (Ações contra o estado/município)"></option>
                                            <option value="Dano moral/Dano material (Consumidor)"></option>                                        
                                            <option value="Declaração de ausência"></option>
                                            <option value="Deseja casar"></option>
                                            <option value="Desmarcar agendamento"></option>
                                            <option value="Despejo"></option>
                                            <option value="Destituição do poder familiar (casos de maus tratos, violência sexual, física, psicológica, moral)"></option>
                                            <option value="Direito Real de Laje"></option>
                                            <option value="Divórcio/Separação"></option>
                                            <option value="Documento que comprove a união estável"></option>
                                            <option value="Erro médico"></option>
                                            <option value="Exame de DNA"></option>
                                            <option value="Exame/Cirurgia"></option>
                                            <option value="Exumação"></option>
                                            <option value="Faculdade"></option>
                                            <option value="Falecimento de familiar/inventário"></option>
                                            <option value="Guarda"></option>
                                            <option value="ICMS"></option>
                                            <option value="Idosos"></option>
                                            <option value="Interdição"></option>
                                            <option value="IPTU"></option>
                                            <option value="IPVA"></option>
                                            <option value="Locação de imóvel"></option>
                                            <option value="Mandado de segurança"></option>
                                            <option value="Medicamentos"></option>
                                            <option value="Medida protetiva (Idoso)"></option>
                                            <option value="Medida protetiva (Violência Doméstica)"></option>
                                            <option value="Multa de trânsito"></option>
                                            <option value="Óbito não registrado em cartório"></option>
                                            <option value="Outros encargos sem despejo"></option>  
                                            <option value="Pensão alimentícia"></option>
                                            <option value="Plano de Saúde"></option>
                                            <option value="Posse/Propriedade"></option>
                                            <option value="Pensão por morte de servidor público"></option>
                                            <option value="Problemas com a certidão de casamento / nascimento"></option>
                                            <option value="Realizar escritura de pacto antenupcial"></option>
                                            <option value="Registros Públicos/Certidões"></option>
                                            <option value="Renovatória de locação"></option>
                                            <option value="Regulamentação de Visita/Direito de convivência"></option>
                                            <option value="Retificação da certidão de óbito (erro ou omissão na certidão)"></option>  
                                            <option value="Retificação de registro"></option>
                                            <option value="Saúde"></option>
                                            <option value="Seguradora"></option>
                                            <option value="Servidor público estadual"></option>
                                            <option value="Servidor público federal"></option>
                                            <option value="Servidor público municipal"></option>
                                            <option value="Testamento"></option>
                                            <option value="Trabalhista"></option>
                                            <option value="Transcrição da certidão estrangeira"></option>  
                                            <option value="Transferência/Regulação"></option>
                                            <option value="Transplante de órgãos"></option>
                                            <option value="Tratar de herança (Alvará Judicial)"></option>
                                            <option value="Tratar de herança (Falecimento de familiar/inventário)"></option>
                                            <option value="Usucapião"></option>
                                            <option value="Vaga em creche"></option>
                                            <option value="Vaga no ensino fundamental I e II"></option>
                                            <option value="Violência doméstica"></option>
                                            <option value="Vizinhança"></option>                        
                                        </datalist>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Falecimento de familiar/inventário] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_falec_famil" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">
                                            <label class="col-5 col-form-label" for="ffi">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_assunto',[
                                                    'label' => false,
                                                    'onchange'=> 'funcFalec_famil()',
                                                    'class' => 'form-control',
                                                    'options' => $sub_assunto_ffi,
                                                    'id'=>'ffi',
                                                    'empty' => 'Selecione...',
                                                    'required',
                                                    'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_assunto');?>
                                            </div>
                                        </div>
                                    </div>                       
                                    <!-- [Sub Assunto - Herança]  -->
                                    <div class="col-12" id="tra_her" style="display:none">
                                        <div class="row">
                                            <!-- [Data do óbito] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="date-input">Informe a data do óbito <small>(dia/mês/ano)</small></label>
                                                    <div class="col-3">
                                                        <?=$this->Form->control('data_obito',[
                                                            'label' => false,
                                                            'placeholder' => 'dd/mm/aaaa',
                                                            'class' => 'form-control',
                                                            'id' => "date-input",
                                                            'pattern' => '(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('data_obito');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Bens do falecido] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">O(a) falecido(a) deixou:</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('heranca[]',[1 => 'Algum bem'],['title'=>'Imóvel, Carro, Outros','disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('heranca[]',[2 => 'Apenas valores'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('heranca[]',[3 => 'Nenhum bem'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('heranca');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Filho Menor] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">                 
                                                    <label class="col-5 col-form-label">Há filhos menores?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('f_menor',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('f_menor',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('f_menor');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - 2ª via da certidão de óbito]  -->
                                    <div class="col-12" id="seg_via" style="display:none">
                                        <div class="row">
                                            <!-- [Registro da certidão] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-6 col-form-label">A certidão foi registrada no estado da Bahia?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('registrado_na_bahia',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('registrado_na_bahia',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('registrado_na_bahia');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Alimentos] -->
                            <div class="col-12 alinhar">
                                <div class = "row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="subA_alimentos" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">
                                            <label class="col-5 col-form-label" for="id_alimentos">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('sub_ass_alim', [
                                                    'label' => false,
                                                    'class' => 'form-control',
                                                    'onchange'=> 'funcAlimentos()',
                                                    'options' => $sub_assunto_alimentos,
                                                    'id'=>'id_alimentos',
                                                    'empty' => 'Selecione...',
                                                    'required',
                                                    'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('sub_ass_alim');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto Alimento] -->
                                    <div class="col-12" id="tra_alimentos" style="display:none">
                                        <div class="row">                                
                                            <!-- [Alimento determinado] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Já tem alimentos determinado?</label>
                                                    <div class="col form-inline" id="id_alim_deter">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('alim_deter',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('alim_deter',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('alim_deter');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Alimento determinado: Sim]  -->
                                            <div class="col-12" id="tra_alimSim" style="display:none">
                                                <!-- [Parcelas atrasadas] -->
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">Deseja cobrar parcelas atrasadas?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('cobrar_parcelas',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('cobrar_parcelas',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('cobrar_parcelas');?>
                                                    </div>
                                                </div>
                                                <!-- [Mudar valor] -->
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">Deseja mudar o valor dos alimentos?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('mudar_valor',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('mudar_valor',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('mudar_valor');?>
                                                    </div>
                                                </div>
                                            </div>                             
                                            <!-- [Beneficiário da pensão] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Quem será o beneficiário da pensão?</label>               
                                                    <div class="col" id="tp_do_benef">
                                                        <?= $this->Form->control('tipo_beneficiario',
                                                            [
                                                                'label' => false,
                                                                'class' => 'form-control',
                                                                'onchange'=> 'funcAlimentos_beneficiario()',
                                                                'options' => $beneficiario_pensao,
                                                                'id'=> 'nome_beneficiario',
                                                                'empty' => 'Selecione...',
                                                                'required',
                                                                'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('tipo_beneficiario');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Beneficiário da pensão: Filho menor]  -->
                                            <div class="col-12" id="tra_alimFilhoMenor" style="display:none">
                                                <!-- [Criança Nasceu] -->           
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">A Criança já nasceu?</label>                        
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('crianca_nasceu',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('crianca_nasceu',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('crianca_nasceu');?>
                                                    </div>
                                                </div>       
                                                 <!-- [Tempo de Gestação] --> 
                                                <div class="row align-items-center pt-1 pb-1 borda-topo" id="tra_mesesGestacao" style="display:none">
                                                    <label class="col-5 col-form-label" for="meses_gt">Quanto tempo de gestação a mãe está?</label>                     
                                                    <div class="col">
                                                        <?=$this->Form->text('meses_gestao',[
                                                            'class'=>'form-control',
                                                            'id' => 'meses_gt',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('meses_gestao');?>
                                                    </div>
                                                </div>
                                                <!-- [Pai Registrou] --> 
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">Foi registrado pelo pai? </label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('registrado_pai',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('registrado_pai',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('registrado_pai');?>
                                                    </div>
                                                </div>
                                                <!-- [Pai vivo] -->
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">Pai é vivo?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('pai_vivo',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('pai_vivo',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('pai_vivo');?>
                                                    </div>
                                                </div>
                                                <!-- [Oferecer a pensão] -->
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">É o pai que deseja oferecer a pensão?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('oferecer_pensao',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('oferecer_pensao',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('oferecer_pensao');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Beneficiário da pensão: Esposa/Esposo/Companheiro/Companheira]  -->
                                            <div class="col-12" id="tra_alimEsposaEsposo" style="display:none">
                                                <!-- [Ação de divórcio] -->           
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">Já possui ação de divórcio ou reconhecimento de união estável?</label>                        
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('acao_divorcio',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('acao_divorcio',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('acao_divorcio');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Casamento/União Estável/ Divórcio/ Separação] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_casam_divorcio" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">
                                            <label class="col-5 col-form-label" for="cuds">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?=$this->Form->control('s_assunto_casam', [
                                                    'label' => false,
                                                    'class' => 'form-control',
                                                    'onchange'=> 'funcCasamento_Divorcio()',
                                                    'options' => $sub_assunto_cuds,
                                                    'id'=>'cuds',
                                                    'empty' => 'Selecione...',
                                                    'required',
                                                    'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_assunto_casam');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Deseja Casar] -->
                                    <div class="col-12" id="sub_deseja_casar" style="display:none">
                                        <div class="row">
                                            <!-- [Idade dos Noivos] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center" id="tra_casar">
                                                    <label class="col-5 col-form-label" >Os noivos são maiores de 18 anos?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('noivos_maiores',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('noivos_maiores',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('noivos_maiores');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Idade dos Noivos: Noivo de Menor]  -->               
                                            <div class="col-12" id="noivoDeMenor" style="display:none">
                                                <!-- [Pais de acordo] -->
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">Os pais estão de acordo?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('pais_de_acordo',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('pais_de_acordo',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('pais_de_acordo');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Divorcio/Separação]  -->
                                    <div class="col-12" id="tra_Divor_Separ" style="display:none">
                                        <div class="row">
                                            <!-- [Casado] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">É oficialmente casado(a) ou vive em união estável?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('casado',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('casado',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('casado');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Filho menor] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">                 
                                                    <label class="col-5 col-form-label">Há filhos menores?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('filho_menor',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('filho_menor',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('filho_menor');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Bens para dividir] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Há bens a dividir?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('possui_bens',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('possui_bens',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('possui_bens');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [tipo do divórcio] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Como será o divórcio?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('tipo_divorcio',[1 => 'Amigável'],['title'=>'Há acordo entre as partes sobre todas as cláusulas, ambos os cônjuges deverão comparecer ao atendimento','disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('tipo_divorcio',[0 => 'Litigioso'],['title'=>'Sem acordo entre as partes','disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('tipo_divorcio');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Medida protetiva] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Há medida protetiva em face do ex cônjuge?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('medida_protetiva',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('medida_protetiva',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('medida_protetiva');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Registro de ocorrência cônjuge] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Há registro de ocorrência de um dos cônjuges contra o outro?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('ocorrencia_conjuge',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('ocorrencia_conjuge',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('ocorrencia_conjuge');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Registro de ocorrência filhos] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Há registro de ocorrência que diga respeito a filhos do casal e/ou enteados?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('ocorrencia_filho',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('ocorrencia_filho',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('ocorrencia_filho');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Problemas com a certidão de casamento / 2ª via] -->
                                    <div class="col-12" id="tra_Problema_certidao_casamento" style="display:none">
                                        <div class="row">
                                            <!-- [Tipo do atendimento] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="SobreAtend">Qual o tipo do atendimento?</label>
                                                    <div class="col" id="tpccto">
                                                        <?= $this->Form->control('tipo_atend[]',[
                                                                'options' => [
                                                                    1 => 'Retificação da certidão de casamento',
                                                                    2 => 'Averbação do nome de casada na certidão de nascimento dos filhos',
                                                                    3 => 'Averbação de óbito na certidão de casamento (viúvo(a))',
                                                                    4 => 'Transcrição de certidão de casamento realizado no estrangeiro'
                                                                ],
                                                                'id' => 'SobreAtend',
                                                                'label' => false,
                                                                'empty' => 'Selecione...',
                                                                'class' => 'form-control',
                                                                'required',
                                                                'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('tipo_atend');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Realização do casamento] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-6 col-form-label">O casamento foi realizado no Estado da Bahia?</label>
                                                    <div class="col form-inline" id="casba">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('realizado_bahia',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('realizado_bahia',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('realizado_bahia');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Documento que comprove a união estável] -->
                                    <div class="col-12" id="tra_Documento_uniao_estavel" style="display:none">
                                        <div class="row">
                                            <!-- [Companheiro vivo] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">O(A) companheiro(a) é vivo(a)?</label>
                                                    <div class="col form-inline" id="comp_vivo">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('companheiro_vivo',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('companheiro_vivo',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('companheiro_vivo');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Casal junto] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">O casal ainda está junto?</label>
                                                    <div class="col form-inline" id="casal_junto">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('casal_vive_junto',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('casal_vive_junto',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('casal_vive_junto');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Finalidade da comprovação] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Qual a finalidade da comprovação?</label>
                                                    <div class="col form-inline" id="finalidade_comp">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('obter_beneficio',[1 => 'Obter benefício previdenciário'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('obter_beneficio',[0 => 'Outros'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('obter_beneficio');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Finalidade da comprovação: Obter benefício previdenciário] -->               
                                            <div class="col-12" id="obter_beneficio_previd" style="display:none">
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">Qual o instituto previdenciário?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('tipo_instituto[]',[1 => 'INSS'],['title'=>'Instituto Nacional do Seguro Social','disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('tipo_instituto[]',[2 => 'IPE'],['title'=>'Instituto Previdenciário Estadual','disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('tipo_instituto[]',[3 => 'IPM'],['title'=>'Instituto Previdenciário Municipal','disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('tipo_instituto');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Violência Doméstica] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_violencia_domest" style="display:none">
                                        <div class="row align-items-center borda-topo pt-1 pb-1">
                                            <label class="col-5 col-form-label" for="id_viol_domest">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_viol_domest', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funcViolenciaDomestica()',
                                                        'options' => $sub_assunto_violencia,
                                                        'id'=>'id_viol_domest',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_viol_domest');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto violência doméstica] -->
                                    <div class="col-12" id="tra_violencia" style="display:none">
                                        <div class="row">
                                            <!-- [Tipo do Atendimento] --> 
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="tp_pedido_id">Qual o tipo do atendimento?</label>
                                                    <div class="col">
                                                        <?= $this->Form->control('tipo_pedido',[
                                                                'options' => [
                                                                    1 => 'Abrigo/Casa de Acolhimento',
                                                                    2 => 'Medida protetiva'
                                                                ],
                                                                'id' => 'tp_pedido_id',
                                                                'label' => false,
                                                                'empty' => 'Selecione...',
                                                                'class' => 'form-control',
                                                                'required',
                                                                'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('tipo_pedido');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Registro de ocorrência policial] --> 
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Já possui registro de ocorrência policial?</label>               
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('ocorrencia_policial',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('ocorrencia_policial',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('ocorrencia_policial');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Risco (físico ou emocional)] --> 
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Você se sente em risco (físico ou emocional) atualmente? </label>               
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('risco_fisico_emocional',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('risco_fisico_emocional',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('risco_fisico_emocional');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Medida protetiva de urgência] --> 
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Você precisa de medida protetiva de urgência? </label>               
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('medida_protetiva_urgencia',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('medida_protetiva_urgencia',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('medida_protetiva_urgencia');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Situação de violência (física ou psicológica)] --> 
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Você está em situação de violência (física ou psicológica)?</label>               
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('situacao_violencia',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('situacao_violencia',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('situacao_violencia');?>
                                                    </div>
                                                </div>
                                            </div>            
                                            <!-- [Última situação de violência sofrida] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="violencia_sofrida">Quando foi a última situação de violência sofrida?</label>
                                                    <div class="form-inline col">
                                                        <?=$this->Form->text('ultima_violencia_sofrida',[
                                                            'class'=>'form-control',
                                                            'id' => 'violencia_sofrida',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('ultima_violencia_sofrida');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Trabalhista] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_trabalhista" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">
                                            <label class="col-5 col-form-label" for="id_trabalhista">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_trab', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funcTrabalhista()',
                                                        'options' => $sub_assunto_trab,
                                                        'id'=>'id_trabalhista',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_trab');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto Trabalhista] -->
                                    <div class="col-12" id="tra_trabalhista" style="display:none">
                                        <div class="row">
                                            <!-- [Vínculo trabalhista] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Qual seu vínculo trabalhista?</label>
                                                    <div class="col" id="vinc_trabalhista">
                                                        <?= $this->Form->control('tipo_vinc',[
                                                            'label' => false,
                                                            'class' => 'form-control',
                                                            'onchange'=> 'funcVinculoTrabalhista()',
                                                            'options' => $vinculo_trabalhista,
                                                            'id'=> 'tipo_vinculo',
                                                            'empty' => 'Selecione...',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('tipo_vinc');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Vínculo trabalhista: Tipo de Servidor] -->
                                            <div class="col-12" id="sol_vinc_tb" style="display:none">
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-6 col-form-label">A solicitação versa sobre recebimento ou revisão de benefício por acidente de trabalho?</label>                       
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('tra_solic_vtb',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('tra_solic_vtb',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('tra_solic_vtb');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Vínculo trabalhista: CLT] -->
                                            <div class="col-12" id="tra_clt" style="display:none">
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <div class="col alert-warning" role="alert">
                                                        A Defensoria Pública do Estado da Bahia não tem atribuição para atuar na área trabalhista. Para obter assistência gratuita nestes casos, dirija-se ao sindicato da sua categoria ou aos escritórios modelos de Faculdades de Direito
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Usucapião] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_usucapiao" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">
                                            <label class="col-5 col-form-label" for="id_usuc">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_usuc', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funcUsucapiao()',
                                                        'options' => $sub_ass_usuc,
                                                        'id'=>'id_usuc',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_usuc');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto Usucapiao] -->
                                    <div class="col-12" id="tra_usucapiao" style="display:none">
                                        <div class="row" id="tp_usucap">
                                            <!-- [Tipo do atendimento] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Qual o tipo do atendimento?</label>
                                                    <div class="col">
                                                        <?=$this->Form->radio('tp_usucapiao[]',[1 => '1º atendimento para iniciar ação de usucapião'],['disabled','hiddenField' => false]);?>
                                                        <?=$this->Form->radio('tp_usucapiao[]',[2 => 'Retorno para entregar a planta do imóvel ou outros documentos para elaboração da petição inicial'],['disabled','hiddenField' => false]);?>
                                                        <?=$this->Form->unlockField('tp_usucapiao');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Saúde] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_saude" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">
                                            <label class="col-5 col-form-label" for="id_saude">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_saude', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funcSaude()',
                                                        'options' => $sub_ass_saude,
                                                        'id'=>'id_saude',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_saude');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Saúde] -->
                                    <div class="col-12" id="tra_saude" style="display:none">
                                        <div class="row">
                                            <!-- [Tipo do atendimento] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo" id="tp_sde">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="tp_pedido_saude">Qual o tipo do atendimento?</label>
                                                    <div class="col">
                                                        <?= $this->Form->control('tipo_pedido_sde',[
                                                            'options' => [
                                                                11 => 'Exame/Cirurgia',
                                                                12 => 'Medicamentos',
                                                                21 => 'Transferência/Regulação',
                                                                20 => 'Outros'

                                                            ],
                                                            'id' => 'tp_pedido_saude',
                                                            'label' => false,
                                                            'empty' => 'Selecione...',
                                                            'class' => 'form-control',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('tipo_pedido_sde');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Plano de saúde] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Tem plano de saúde?</label>
                                                    <div class="col form-inline" id="tpsde">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('planSaud',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('planSaud',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('planSaud');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Plano de saúde: Nome do Plano] -->
                                            <div class="col-12" id="tra_plano" style="display:none">
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label" for="n_p">Qual é o nome do plano?</label>                     
                                                    <div class="col">
                                                        <?=$this->Form->text('n_pl',[
                                                            'class'=>'form-control',
                                                            'id' => 'n_p',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('n_pl');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Registros Públicos / Certidões (Atendimento cível)] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_atend_civel" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">
                                            <label class="col-5 col-form-label" for="id_atend_civel">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_atend_civel', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funcAtentim_civel()',
                                                        'options' => $sub_assunto_at_civel,
                                                        'id'=>'id_atend_civel',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_atend_civel');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Registros Públicos / Certidões (Atendimento cível)] -->
                                    <div class="col-12" id="tra_atend_civel" style="display:none">
                                        <div class="row">
                                            <!-- [Tipo do atendimento] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="tp_pedido_at_civel_id">Qual o tipo do atendimento?</label>
                                                    <div class="col">
                                                        <?= $this->Form->control('tipo_pedido_at_civel',[
                                                                'options' => [                                                                
                                                                    5 => 'Retificação de registro',
                                                                    6 => 'Abertura de registro público'
                                                                ],
                                                                'id' => 'tp_pedido_at_civel_id',
                                                                'label' => false,
                                                                'empty' => 'Selecione...',
                                                                'class' => 'form-control',
                                                                'required',
                                                                'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('tipo_pedido_at_civel');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Consumidor] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_consumidor" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">                 
                                            <label class="col-5 col-form-label" for="id_cons">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_cons', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange' => 'funcPlanoSaude()',
                                                        'options' => $sub_assunto_consumidor,
                                                        'id'=>'id_cons',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_cons');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Plano de Saúde]  -->
                                    <div class="col-12" id="sub_planoSaude" style="display:none">
                                        <div class="row">
                                            <!-- [Nome do Plano de Saúde]  -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="n_plano">Qual é o nome do plano?</label>
                                                    <div class="col">
                                                        <?=$this->Form->text('nome_plano',[
                                                            'class'=>'form-control',
                                                            'id' => 'n_plano',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('nome_plano');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Ações contra o estado/município (Ações de fazenda Publica)] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_fazenda_publica" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo"> 
                                            <label class="col-5 col-form-label" for="id_faz_pub">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_faz_pub', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funcFazenda_publica()',
                                                        'options' => $sub_assunto_faz_pub,
                                                        'id'=>'id_faz_pub',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_faz_pub');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Ações contra o estado/município]  -->
                                    <div class="col-12" id="tra_faz_pub" style="display:none">
                                        <div class="row">
                                            <!-- [Tipo do atendimento] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo" id="a_faz_publica">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="tipoDeAtend">Qual o tipo do atendimento?</label>
                                                    <div class="col">
                                                        <?= $this->Form->control('tipo_pedido_faz_pub',[
                                                                'options' => [
                                                                    7 => 'Concurso público',
                                                                    8 => 'Mandado de segurança',
                                                                    9 => 'IPTU',
                                                                    13 => 'Agressão policial',
                                                                    14 => 'IPVA',
                                                                    15 => 'ICMS',
                                                                    16 => 'Dano moral / Dano material',
                                                                    17 => 'Erro médico',
                                                                    18 => 'Multa de trânsito',
                                                                    19 => 'Pensão por morte de servidor público',
                                                                    20 => 'Outros'
                                                                ],
                                                                'id' => 'tipoDeAtend',
                                                                'label' => false,
                                                                'empty' => 'Selecione...',
                                                                'class' => 'form-control',
                                                                'required',
                                                                'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('tipo_pedido_faz_pub');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Intimação de processo] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Recebeu alguma intimação de processo?</label>
                                                    <div class="col form-inline" id="intimacao_processo">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('recebeu_intimacao',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('recebeu_intimacao',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('recebeu_intimacao');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Intimação de processo: Número] -->
                                            <div class="col-12" id="tra_intima_proc" style="display:none">
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">Qual o número?</label>
                                                    <div class="col">
                                                        <?=$this->Form->text('numero',[
                                                            'class'=>'form-control',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('numero');?> 
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Desmarcar agendamento] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_desmarcar_agend" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">       
                                            <label class="col-5 col-form-label" for="id_desmarcar">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_descmar', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funDesmarcar_agendamento()',
                                                        'options' => $sub_assunto_desmarcar,
                                                        'id'=>'id_desmarcar',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_descmar');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Desmarcar Agendamento]  -->
                                    <div class="col-12" id="tra_desmarcar" style="display:none">
                                        <div class="row">
                                            <!-- [Nome de quem é o agendamento] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="desm_agend">Favor nos informar em nome de quem é o agendamento que pretende desmarcar</label>
                                                    <div class="form-inline col">
                                                        <?=$this->Form->text('nome_agendado',[
                                                            'class'=>'form-control',
                                                            'id' => 'desm_agend',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('nome_agendado');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Data do agendamento] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="date_agend">Data do agendamento que deseja cancelar <small>(dia/mês/ano)</small></label>
                                                    <div class="col-3">
                                                        <?=$this->Form->control('data_agendam',[
                                                            'label' => false,
                                                            'placeholder' => 'dd/mm/aaaa',
                                                            'class' => 'form-control',
                                                            'id' => "date_agend",
                                                            'pattern' => '(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('data_agendam');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Hora agendada] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="hora_agend">Hora agendada <small>(hora/minuto)</small></label>
                                                    <div class="col-3">
                                                        <?=$this->Form->control('hora_agendam',[
                                                            'label' => false,
                                                            'type' => 'time',
                                                            'class' => 'form-control',
                                                            'id' => "hora_agend",
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('hora_agendam');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Adoção] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_adocao" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">       
                                            <label class="col-5 col-form-label" for="id_adocao">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_adocao', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funAdocao()',
                                                        'options' => $sub_assunto_adocao,
                                                        'id'=>'id_adocao',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_adocao');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Adocao]  -->
                                    <div class="col-12" id="tra_adocao" style="display:none">
                                        <div class="row">
                                            <!-- [Idade da pessoa adotada] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="idade_adocao">Informe a idade de quem será adotado</label>
                                                    <div class="form-inline col">
                                                        <?=$this->Form->text('idade_adotado',[
                                                            'class'=>'form-control',
                                                            'id' => 'idade_adocao',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('idade_adotado');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Autorização para viagem internacional] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_viagem_internacional" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">       
                                            <label class="col-5 col-form-label" for="id_viagem_inter">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_id_viagem_inter', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funViagem_inter()',
                                                        'options' => $sub_assunto_viagem_inter,
                                                        'id'=>'id_viagem_inter',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_id_viagem_inter');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Autorização para viagem internacional]  -->
                                    <div class="col-12" id="tra_viagem_inter" style="display:none">
                                        <div class="row">
                                            <!-- [Previsão de início da viagem] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="id_inicio_viagem">Previsão de início da viagem <small>(dia/mês/ano)</small></label>
                                                    <div class="col-3">
                                                        <?=$this->Form->control('inicio_viagem',[
                                                            'label' => false,
                                                            'placeholder' => 'dd/mm/aaaa',
                                                            'class' => 'form-control',
                                                            'id' => "id_inicio_viagem",
                                                            'pattern' => '(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('inicio_viagem');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Previsão de Fim da viagem] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="id_fim_viagem">Previsão de fim da viagem <small>(dia/mês/ano)</small></label>
                                                    <div class="col-3">
                                                        <?=$this->Form->control('fim_viagem',[
                                                            'label' => false,
                                                            'placeholder' => 'dd/mm/aaaa',
                                                            'class' => 'form-control',
                                                            'id' => "id_fim_viagem",
                                                            'pattern' => '(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('fim_viagem');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Previsão de retorno] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Há previsão de retorno?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('previsao_retorno',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('previsao_retorno',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('previsao_retorno');?>
                                                    </div>
                                                </div>
                                            </div>                                
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Alvará judicial] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_alvara_judicial" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">                 
                                            <label class="col-5 col-form-label" for="id_alvara">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_alvara_judicial', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange' => 'funcAlvara()',
                                                        'options' => $sub_assunto_alvara_judicial,
                                                        'id'=>'id_alvara',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_alvara_judicial');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Tratar de Herança]  -->
                                    <div class="col-12" id="tra_her_alvara" style="display:none">
                                        <div class="row">
                                            <!-- [Data do óbito] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="date_input_alvara">Informe a data do óbito <small>(dia/mês/ano)</small></label>
                                                    <div class="col-3">
                                                        <?=$this->Form->control('data_obito_alvara',[
                                                            'label' => false,
                                                            'placeholder' => 'dd/mm/aaaa',
                                                            'class' => 'form-control',
                                                            'id' => "date_input_alvara",
                                                            'pattern' => '(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d',
                                                            'required',
                                                            'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('data_obito_alvara');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Bens do falecido] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">O(a) falecido(a) deixou:</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('heranca_alvara[]',[1 => 'Algum bem'],['title'=>'Imóvel, Carro, Outros','disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('heranca_alvara[]',[2 => 'Apenas valores'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('heranca_alvara[]',[3 => 'Nenhum bem'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('heranca_alvara');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Filho Menor] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">                 
                                                    <label class="col-5 col-form-label">Há filhos menores?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('f_menor_alvara',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('f_menor_alvara',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('f_menor_alvara');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Bens a dividir] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">                 
                                                    <label class="col-5 col-form-label">Há bens a dividir?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('bens_dividir_alvara',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('bens_dividir_alvara',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('bens_dividir_alvara');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Exame de DNA] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_exame_dna" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">       
                                            <label class="col-5 col-form-label" for="id_exame_dna">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_exame_dna', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funExameDna()',
                                                        'options' => $sub_assunto_exame_dna,
                                                        'id'=>'id_exame_dna',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_exame_dna');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Exame de DNA]  -->
                                    <div class="col-12" id="tra_exame_dna" style="display:none">
                                        <div class="row">
                                            <!-- [Pai no Registro] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">                 
                                                    <label class="col-5 col-form-label">Já tem pai no registro?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('pai_registro',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('pai_registro',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('pai_registro');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Idoso] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_idoso" style="display:none">
                                        <div class="row align-items-center borda-topo pt-1 pb-1">
                                            <label class="col-5 col-form-label" for="id_idoso">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_idoso', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funcIdoso()',
                                                        'options' => $sub_assunto_idoso,
                                                        'id'=>'id_idoso',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_idoso');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto Idoso] -->
                                    <div class="col-12" id="tra_idoso" style="display:none">
                                        <div class="row">
                                            <!-- [Tipo do Atendimento] --> 
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label" for="tp_pedido_idoso">Qual o tipo do atendimento?</label>
                                                    <div class="col">
                                                        <?= $this->Form->control('tipo_pedido_idoso',[
                                                                'options' => [
                                                                    1 => 'Abrigo/Casa de Acolhimento',
                                                                    2 => 'Medida protetiva'
                                                                ],
                                                                'id' => 'tp_pedido_idoso',
                                                                'label' => false,
                                                                'empty' => 'Selecione...',
                                                                'class' => 'form-control',
                                                                'required',
                                                                'disabled'
                                                            ]);
                                                        ?>
                                                        <?=$this->Form->unlockField('tipo_pedido_idoso');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Registro de ocorrência policial] --> 
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Já possui registro de ocorrência policial?</label>               
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('ocorrencia_policial_idoso',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('ocorrencia_policial_idoso',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('ocorrencia_policial_idoso');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Maus tratos Idoso] --> 
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">O idoso sofre maus tratos? </label>               
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('mau_trato_idoso',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('mau_trato_idoso',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('mau_trato_idoso');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Regulamentação de Visita/Direito de convivência] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_regulamentacao_visita" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo"> 
                                            <label class="col-5 col-form-label" for="id_regul_visita">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_regul_visita', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'onchange'=> 'funcRegulamentacao_visita()',
                                                        'options' => $sub_assunto_regul_visita,
                                                        'id'=>'id_regul_visita',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_regul_visita');?>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- [Sub Assunto - Regulamentação de Visita/Direito de convivência]  -->
                                    <div class="col-12" id="tra_regul_visita" style="display:none">
                                        <div class="row">
                                            <!-- [visitas determinadas?] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Já tem visitas determinadas?</label>
                                                    <div class="col form-inline" id="id_visita_determinada">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('visita_determinada',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('visita_determinada',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('visita_determinada');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Visitas determinadas: Deseja modificar?] -->
                                            <div class="col-12" id="tra_visita_determinada" style="display:none">
                                                <div class="row align-items-center pt-1 pb-1 borda-topo">
                                                    <label class="col-5 col-form-label">Deseja modificar?</label>
                                                    <div class="col form-inline">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('modificar',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('modificar',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('modificar');?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- [Quer cumprir a decisão da justiça?] -->
                                            <div class="col-12 pt-1 pb-1 borda-topo">
                                                <div class="row align-items-center">
                                                    <label class="col-5 col-form-label">Quer cumprir a decisão da justiça?</label>
                                                    <div class="col form-inline" id="id_decisao_justica">
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('decisao_justica',[1 => 'Sim'],['disabled','hiddenField' => false]);?>
                                                        </div>
                                                        <div class="form-check-inline">
                                                            <?=$this->Form->radio('decisao_justica',[0 => 'Não'],['disabled','hiddenField' => false,'value' => '']);?>
                                                        </div>
                                                        <?=$this->Form->unlockField('decisao_justica');?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- [Assunto - Locação de imóvel] -->
                            <div class="col-12 alinhar">
                                <div class="row">
                                    <!-- [Escolher o Sub Assunto] -->
                                    <div class="col-12" id="sub_assunto_locacao_imovel" style="display:none">
                                        <div class="row align-items-center pt-1 pb-1 borda-topo">                 
                                            <label class="col-5 col-form-label" for="id_locacao">Você deseja atendimento sobre o quê?</label>
                                            <div class="col">
                                                <?= $this->Form->control('s_ass_locacao', [
                                                        'label' => false,
                                                        'class' => 'form-control',
                                                        'options' => $sub_assunto_locacao,
                                                        'id'=>'id_locacao',
                                                        'empty' => 'Selecione...',
                                                        'required',
                                                        'disabled'
                                                    ]);
                                                ?>
                                                <?=$this->Form->unlockField('s_ass_locacao');?>
                                            </div>
                                        </div>
                                    </div>                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- 5º Bloco - [Preferência de horário]-->
                <div class="row">
                    <div class="col" style="display:none" id="solic_horario">
                        <!-- [Título do Bloco] -->
                        <div class="row rotuloForm text-white bg-secondary">
                            Preferência de horário
                        </div>
                        <!-- [Corpo do Bloco] -->
                        <div class="row pt-2">
                            <!-- [Informação sobre o horario] -->
                            <div class="col-md-auto"><img src="/img/icon_info.png" alt="" id="iconeInfo"></div>
                            <div class="col-md-10">
                                <p style="font-size:0.8em" class="text-justify">Olá, no dia e turno escolhido, a equipe da Defensoria Pública da Bahia fará contato com você através do email para confirmar o seu atendimento!</p>
                            </div>                              
                            <!-- [Aviso] -->
                            <div class="col-12 justify-content-center alerta alert alert-warning text-center alert-dismissible fade show" id="esconderAlerta" role="alert">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <img src="/img/icon_alerta.png" alt="" class="ml-5">
                                <p class="h5 ml-5">Atenção</p>
                                <hr>
                                <div class="h6">
                                    <p>É necessário selecionar pelo menos um horário abaixo. </p>
                                </div>
                            </div>
                            <!-- [Tabela de horário] -->
                            <div class="col-12 cor-labelformulario table-responsive table-condensed table-responsive-sm" id="textoTabela" style="overflow-y: hidden">
                                <table class="table text-center">
                                    <tr>
                                        <td></td>
                                        <td>SEG</td>
                                        <td>TER</td>
                                        <td>QUA</td>
                                        <td>QUI</td>
                                        <td>SEX</td>
                                    </tr>
                                    <tr>
                                        <td>MANHÃ</td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 1,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 3,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 5,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 7,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 9,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>TARDE</td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 2,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 4,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 6,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 8,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                        <td>
                                            <?= $this->Form->checkbox('preferencia[]', [
                                                'value' => 10,
                                                'hiddenField' => false,
                                            ]); ?>
                                        </td>
                                    </tr>
                                </table>
                            </div>                
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="loading-audio " style="display: none;">
            <div class="loading-audio-text">
                <div></div>
                <image src="/img/loading-gif.gif" id="spinner" style="height: 30px; widht: 20%;" />    
                <p>Aguarde enquanto analisamos seu áudio.</p>                 
            </div>

        </div>
        <!-- Campos para inserir a acuracia e click do usuário no momento da solicitação -->
        <?= $this->Form->hidden('valor_predicao',['id'=> 'valor_predicao', 'value' => '']) ?>
        <?=$this->Form->unlockField('valor_predicao');?>
        <?= $this->Form->hidden('clicou_no_btn_relato',['id'=> 'clicou_ia','value' => '']) ?>
        <?=$this->Form->unlockField('clicou_no_btn_relato');?>
        <?= $this->Form->hidden('select_assunto',['id'=> 'selectAssuntoId','value' => '']) ?>
        <?=$this->Form->unlockField('select_assunto');?>
        <?= $this->Form->hidden('url_audio',['id'=> 'url_audio','value' => '']) ?>
        <?=$this->Form->unlockField('url_audio');?>


        <!-- [Enviar Formulário]-->
        <div class="row">
            <div class="col text-right" id="enviar_Form" style="display:none">
                <?= $this->Form->button(__('Solicitar Agendamento'),[
                    'class' => 'mt-3 btn btn-success'
                    ]);
                ?>
            </div>
        </div>
        <?= $this->Form->end() ?>
    </div>
<?php } ?>
<?= $this->Html->script('exibicaoInput.js');?>
<?= $this->Html->script('mask.js');?>
