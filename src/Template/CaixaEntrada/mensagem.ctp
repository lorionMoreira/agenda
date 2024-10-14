<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Agendamento[]|\Cake\Collection\CollectionInterface
 */
 use Cake\I18n\Time;
 //debug($mensagem);
?>
<div>
    <button class="btn-back" onclick="onBack()">
        <svg xmlns="http://www.w3.org/2000/svg" width="25" height="30" fill="gray" class="bi bi-arrow-left" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
        </svg>
    </button>
    <div class="painel-msg">
        <div class="row">
            <h4 class="title-dpe">Mensagem da Defensoria</h4>
        </div>
        <div class="row">
            <image class="img-icon-dpe" src="../../webroot/img/ic_launcher.png"/>
            <div class="title-mensagem">
                <p class="title-msg">Defensoria Pública</p>
                <p class="sub-title-mensagem">para mim</p>
            </div>
            <div class="data-mensgem">
                <p><?php echo date_format($mensagem->data_notificacao, "d/m/Y") ?></p>
            </div>
        </div>
        <div class="mensagem-cx">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                    <th colspan="1"  >
                        <strong class="registro-msg">N° do Registro:</strong>
                    </th>
                    <td><?php echo $mensagem['id'] ?></td>
                    </tr>
                    <tr>
                    <th scope="row">
                        <strong class="situacao-msg">Situação:</strong> 
                    </th>
                    <td id="situacao"></td>
                    </tr>
                    <tr>
                    <th scope="row">
                        <strong class="situacao-msg">Processo:</strong> 
                    </th>
                    <td colspan="2"><?php echo empty($mensagem->p['numeracao_unica']) ? "Não definido" : $mensagem->p['numeracao_unica']?></td>
                    </tr>
                    <tr>
                        <td colspan="2" scope="row">
                            <p class="mensagem-cx" id="relato"></p></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div >
                <h5>Documentos Anexados</h5>
                <div class="row">
                    <?php foreach($anexos as $anexo) {?>
                        <?php if(!empty($anexo->a['caminho_fisico'])){ ?>
                            <div class="col-6 col-sm-3 anexos-msg">
                                <button onclick="window.open('https://sigad.defensoria.ba.def.br/<?php echo $anexo->a['caminho_fisico']?>', '_blank', 'toolbar=yes,scrollbars=yes,resizable=yes,width=1000,height=800')" class="btn btn-light">
                                    <svg xmlns="http://www.w3.org/2000/svg" color="#28a745" width="60" height="70" fill="currentColor" class="bi bi-filetype-pdf" viewBox="0 0 16 16">
                                        <path fill-rule="evenodd" d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5ZM1.6 11.85H0v3.999h.791v-1.342h.803c.287 0 .531-.057.732-.173.203-.117.358-.275.463-.474a1.42 1.42 0 0 0 .161-.677c0-.25-.053-.476-.158-.677a1.176 1.176 0 0 0-.46-.477c-.2-.12-.443-.179-.732-.179Zm.545 1.333a.795.795 0 0 1-.085.38.574.574 0 0 1-.238.241.794.794 0 0 1-.375.082H.788V12.48h.66c.218 0 .389.06.512.181.123.122.185.296.185.522Zm1.217-1.333v3.999h1.46c.401 0 .734-.08.998-.237a1.45 1.45 0 0 0 .595-.689c.13-.3.196-.662.196-1.084 0-.42-.065-.778-.196-1.075a1.426 1.426 0 0 0-.589-.68c-.264-.156-.599-.234-1.005-.234H3.362Zm.791.645h.563c.248 0 .45.05.609.152a.89.89 0 0 1 .354.454c.079.201.118.452.118.753a2.3 2.3 0 0 1-.068.592 1.14 1.14 0 0 1-.196.422.8.8 0 0 1-.334.252 1.298 1.298 0 0 1-.483.082h-.563v-2.707Zm3.743 1.763v1.591h-.79V11.85h2.548v.653H7.896v1.117h1.606v.638H7.896Z"/>
                                    </svg>
                                    <p style="font-size: 12px;"><?php echo substr($anexo->a["filename"], 0, 35) ?></p>
                                </button>
                            </div>      
                        <?php }else{ ?>        
                            <p style="margin: 25px;">Não existe documento anexado.</p>        
                    <?php  }} ?>
                </div>       
            </div>

        </div>
    </div>

    <div>

    </div>
</div>

<script>
    function onBack(){
        window.location = "/CaixaEntrada"
    }
   
    fixString("<?= preg_replace("/\r?\n|\r/", "",$mensagem->relato) ?>", 1)
    fixString("<?= preg_replace("/\r?\n|\r/", "",$mensagem->si['nome']) ?>", 2)

    function fixString(string, numb) {
        let map = {
            "Âª": "ª",
            "Âº": "º",
            "Ãƒ": "Ã",
            "Ã‚": "Â",
            "Ã‡": "Ç",
            "Ã‰": "É",
            "ÃŠ": "Ê",
            "Ã�": "Í",
            "Ã“": "Ó",
            "Ã”": "Ô",
            "Ãš": "Ú",
            "Ã¡": "á",
            "Ã¢": "â",
            "Ã£": "ã",
            "Ã§": "ç",
            "Ã©": "é",
            "Ãª": "ê",
            "mÃ": "mí",
            "Ã³": "ó",
            "Ã´": "ô",
            "Ãº": "ú",
            "\r": "",
            "\t": "",
            "\n": "",
            "í³": "ó",
            "í­": "í"
        };

        if(numb == 1){
            for (let key in map) {
                string = string.split(key).join(map[key]);
            }
            $("#relato").html(string)
            return string;            
        }else{
            for (let key in map) {
                string = string.split(key).join(map[key]);
            }
            if(string == "")
                $("#situacao").html("Não definido")
            else
                $("#situacao").html(string)
            return string;      
        }

    }
</script>
