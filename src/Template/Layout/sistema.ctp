<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Language" content="pt-br">
        <title>
            DPE - BA Agendamento Online:
            <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('icon') ?>

        <?= $this->Html->css('bootstrap.min.css') ?>
        <?= $this->Html->css('home.css') ?>
        <?= $this->Html->css('sistemaAgendamento.css') ?>
        <?= $this->Html->css('open-iconic-bootstrap.min') ?>


        <?= $this->Html->script('jquery-3.2.1.min'); ?>
        <?= $this->Html->script('popper.min'); ?>
        <?= $this->Html->script('bootstrap.min'); ?>
        <?= $this->Html->script('jasny-bootstrap.min') ?>
        <?= $this->Html->script('masks') ?>
        <?= $this->Html->script('timeflash.js'); ?>


        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-126240244-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag(){dataLayer.push(arguments);}
            gtag('js', new Date());

            gtag('config', 'UA-126240244-1');
        </script>
    </head>
    <body>
        <div class="container-fluid mb-5 clearfix">
            <?php echo $this->element('header_logado'); ?>

            <div class="row">
                <?php echo $this->element('menuLateral'); ?>
                <div class="col-md-12 col-lg-10 table-responsive-md" >
                    <!-- Nome tela do sistema localizado parte superior a direita  -->
                     <div class="row justify-content-end">
                        <div class="col-md-4 mr-3 bg-success py-2" style="border-radius: 0 0px 30px 30px ">
                            <p class="h5 text-white text-center"><?= $title ?></p>
                        </div>
                    </div>
                    <!-- Fim Nome tela do sistema  -->
                    <?= $this->Flash->render() ?>

                    <!-- menu para mobile-->
                    <div class="container-fluid mt-3 collapse" id="mobileMenu">
                        <div class="row d-lg-none text-center" style="background-color:#f0f0f0;">
                            <a  href="/users/<?= ($this->request->session()->read('User.sigad_user')) ? "edit" : "add" ?>" class="menuEsquerdo w-50-percent py-3" id="menuAgendamento">
                                <img class="img-fluid" src="/img/icon_editarCad.png" alt="icone">
                                <spam class="mt-2 d-block textoMenu" style="margin-top: 15px">Meus Dados</spam>
                            </a>
                            <a href="/CaixaEntrada" class="menuEsquerdo w-50-percent py-3">
                                <img class="img-fluid "  src="/img/icon_cxEntrada.png" alt="icone">
                                <spam class="mt-2 d-block textoMenu" style="margin-top: 15px">Caixa de Entrada</spam>
                                <span style="height: 2px" id="notification-mb"></span>
                                
                            </a>
                            <a href="/agendamentos/add" class="menuEsquerdo w-50-percent py-3" id="menuAgendamento">
                                <img class="img-fluid" src="/img/icon_novoAgen.png" alt="icone">
                                <spam class="mt-2 d-block textoMenu" style="margin-top: 15px">Solicitar Agendamento</spam>
                            </a>
                            <a href="/agendamentos/solicitacoes" class="menuEsquerdo w-50-percent py-3" id="menuAgendamento">
                                <img class="img-fluid" src="/img/solicitacoes.png" alt="icone">
                                <spam class="mt-2 d-block textoMenu" style="margin-top: 15px">Solicitações</spam>
                            </a>
                            <a href="/agendamentos" class="menuEsquerdo w-50-percent py-3">
                                <img class="img-fluid "  src="/img/icon_acomAgenda.png" alt="icone">
                                <spam class="mt-2 d-block textoMenu" style="margin-top: 15px">Agendamentos</spam>
                            </a>
                        </div>
                    </div>
                    <div class="popup-wrapper popup-termos-disable">
                        <div class="popup">
                            <h3>Termos</h3>
                            <p> Em manifestação livre, informada e certa, conforme o art. 5º, inciso XII, da Lei 13.709/2018,
                            concordo com o tratamento dos meus dados pessoais cadastrado na Defensoria Pública do Estado da Bahia,
                            de acordo com os artigos 7° e 11º da referida legislação.</p>
                            <div class="form-group col-md-12 row checkbox">
                                <?= $this->Form->control("Users[termos]", ['label' => false,'type' => 'checkbox']); ?>
                                <label class="control-label info-termo">Concordo com os termos acima.</label>
                            </div>
                            <button class="btn btn-success" onclick="AtualizarTermos()">OK</button>
                        </div>                        
                    </div>

                    <?= $this->fetch('content') ?>
                </div>
            </div>
       </div>
    </body>
</html>
<script>
    //Verificar se tem alguma menssagem nova na Caixa de entrada
    window.onload = function() {
            Notfication()
            Termos()
        setInterval( () => {
            Notfication()
    }, 120000);
    }
    function Termos(){
        $.get(
            "/api/termos/"+<?php echo $this->request->session()->read('User.sigad_user') ?>,
            function(data){         
                if(data.success)
                {
                    $(".popup-wrapper").removeClass("popup-termos-disable");    
                }
            },
            'json'
        )
    }
    function AtualizarTermos(){
        var termosChecked = $("#users-termos").is(':checked') == true ? 1 : 0
        $.get(
            "/api/atualizarTermos/"+<?php echo $this->request->session()->read('User.sigad_user') ?>+"/"+termosChecked,
            function(data){         
                if(data.success){
                    $(".popup-wrapper").addClass("popup-termos-disable"); 
                }
            },
            'json'
        )
    }
    function Notfication(){
        $.get(
            "/api/notificacao/"+<?php echo $this->request->session()->read('User.sigad_user') ?>,
            function(data){         
                if(data.success)
                {
                    $("#notification-mb").addClass("active-notificacao");     
                    $("#notification-mb").html(data.data)      
                    $("#notification").addClass("active-notificacao");     
                    $("#notification").html(data.data)             
                }else{
                    $("#notification-mb").removeClass("active-notificacao");   
                    $("#notification").removeClass("active-notificacao");                   
                }
            },
            'json'
        )
    }
</script>
