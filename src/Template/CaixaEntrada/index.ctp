<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Agendamento[]|\Cake\Collection\CollectionInterface
 */
 use Cake\I18n\Time;
?>

<!-- Inico tabela Caixa de Entrada -->
<div class="table-responsive">
    <div class="row">
        <div class="form-group col-md-6">
            <label class="control-label">Relato</label>
            <?= $this->Form->control('relato', [
                'label' => false,
                'placeholder' => 'Relato',
                'class' => 'form-control',
                'style' => "text-transform:uppercase",
                'id' => 'relato',
                'pattern' => '\w+ \w+.*',
            ]) ?>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label">Período da mensagem</label>
            <?= $this->Form->control('data_msg', [
                'label' => false,
                'placeholder' => 'dd/mm/aaaa',
                'class' => 'form-control data',
                'id' => "data_msg",
                'autocomplete' => 'off',
                'pattern' => '(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d', 
            ]) ?>
        </div>
        <div class="form-group col-md-6">
            <label class="control-label">N° do Processo</label>
            <?= $this->Form->control('n_processo', [
                'label' => false,
                'placeholder' => 'Numero do processo',
                'class' => 'form-control',
                'style' => "text-transform:uppercase",
                'id' => 'n_processo',
                'pattern' => '\w+ \w+.*',
            ]) ?>
        </div>
        <div class="form-group col-md-4">
            <label class="control-label">Pesquisar</label>
            <?= $this->Form->select('pesquisar', [
                1 => 'Mensagem lidas',
                0 => 'Mensagem não lidas',
            ],[
                'label' => false,
                'empty' => 'Selecione',
                'class' => 'form-control',
                'id' => 'pesquisar'
            ]) ?>
        </div>
        <div class="form-group col-md-4">
            <button class="btn btn-primary" id="buttonLimpar">Limpar Filtro</button>
            <button class="btn btn-success" id="button">Filtrar</button>
        </div>
    </div>
    <table class="table table-hover ">
        <thead class="corTexto-cabecalho-tabela">
            <tr>
                <th scope="col">N° Registro</th>
                <th scope="col">Mensagem da Defensoria</th>
                <th scope="col">Data da Mensagem</th>
                <th scope="col">Processo Vinculado</th>
                <th scope="col">Documento</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody class="list">

        </tbody>
    </table>
    <div class="row justify-content-md-center">
        <image src="../../webroot/img/loading-gif.gif" id="spinner" style="height: 30px; widht: 30px; display: none;" />
    </div>
</div>
<script>

    var list = []
    var total = 7;
    <?php foreach ($menssagens as $s): #debug($assuntos) ?>
        list.push(<?php echo $s ?>)
    <?php endforeach; ?>
    
    
    $("#button").on("click", function(){
        List($('#relato').val(), $('#data_msg').val(), $('#n_processo').val(), $('#pesquisar option:selected').val())
    })

    $("#buttonLimpar").on("click", function(){
        $('#relato').val("")
        $('#data_msg').val("")
        $('#n_processo').val("")
        $('#pesquisar').prop("selectedIndex", 0)
        List($('#relato').val(), $('#data_msg').val(), $('#n_processo').val(), $('#pesquisar option:selected').val())
    })



    $(function(){
       List()
 
       
    });

    window.addEventListener('scroll', () =>{
        const {scrollTop, scrollHeight, clientHeight} = document.documentElement
        if(scrollTop + clientHeight >= scrollHeight - total){
            if(total < list.length){
                Loading()
            }
            
        }
    })
    
    function Loading(){
        $("#spinner").addClass("spinner")
        setTimeout(() => {
            $("#spinner").removeClass("spinner")
            setTimeout(() => {
                total+=5
                List($('#relato').val(), $('#data_msg').val(), $('#n_processo').val(), $('#pesquisar option:selected').val())
            }, 300)            
        }, 1000);

    }

    function List(relato = '', data = '', processo = '', lida = ''){
        $(".list").html('')
        var list = []
        
        var count = 1;
        <?php foreach ($menssagens as $s): #debug($assuntos) ?>
            list.push(<?php echo $s ?>)
        <?php endforeach; ?>

        if(relato){
            relato = relato.toLowerCase()
            list = list.filter((f) => f.relato.toLowerCase().includes(relato))
        }
        if(data){
            list = list.filter((f) => f.data_notificacao.substr(8,2)+"/"+f.data_notificacao.substr(5,2)+"/"+f.data_notificacao.substr(0,4) == data)
        }
        if(processo){
            list = list.filter((f) => f.ac.processo_id == processo)
        }
        if(lida){
           list = list.filter((f) => f.msg_lida == lida)
        }
        list.map((l) => {
            if(total >= count){
                $(".list")
                    .append($("<tr id='msg-id' class='"+l.id+"' onclick='mensagem("+l.id+")'>")
                    .append($("<td>")
                    .html(l.id))
                    .append($("<td>")
                    .html(decode_utf8(l.relato).substring(0,52)+(l.relato.length >= 26 ? "..." : "")))
                    .append($("<td>")
                    .html(l.data_notificacao.substr(8,2)+"/"+l.data_notificacao.substr(5,2)+"/"+l.data_notificacao.substr(0,4)))
                    .append($("<td>")
                    .html(l.p.numeracao_unica))
                    .append($("<td>")
                    .html(l.anexos))
                    .append($("<td>")
                    .html(l.msg_lida == 1 ? "Visualizada" : "Não Lida"))
                );    
                if(l.msg_lida == 1){
                    $('.'+l.id).addClass('table-active')

                } 
                count++; 
            }     
         
        })
   
    }

    function decode_utf8(string) {
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

        for (let key in map) {
            string = string.split(key).join(map[key]);
        }

        return string;
    }

    function mensagem(id){
        window.location = "CaixaEntrada/mensagem/"+id
    }
    

</script>
