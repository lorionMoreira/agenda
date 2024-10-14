var numero=0, cont=0, count=0, count1=0, count2=0, count3=0, count4=0, count5=0, count6=0, count7=0;
var count8=0, count9=0, count10=0, count11=0, count12=0, count13=0,count14=0,count15=0,count16=0,count17=0,count18=0,count19=0;
//Para Exibir o Select do Sub_Assunto, Alterar o Valor da var sub_assunto_X para 2;  
var sub_assunto_viol_domest=1, sub_assunto_alimento=1, sub_assunto_desmarc=1, sub_assunto_adocao=1, sub_assunto_viagem_inter=1,sub_assunto_regulamentacao=1;
var sub_ass_idoso=1, sub_assunto_civel=1, sub_assunto_faz_pub=1, sub_assunt_saude=1, sub_assunt_usucap=1, sub_assunt_trab=1,sub_assunto_exame_dna=1;
//Controle para exibir os Subassuntos
var sa21=0,sa22=0,sa2x=0,sa30=0,sa3x=0,sa30_1=0,sa30_2=0,sa30_x=0,sa37x=0,sa38=0,sa39=0,sa40=0,sa41=0;
var sa32=0,sa38x=0,sa37=0,sa39x=0,sa37_4=0,sa37_x=0,sa37_xx=0,sa36=0,sa40x=0,sa35=0,sa45x=0,sa33=0,sa41x=0,sa59=0,sa11x=0;
var sa34=0,sa43x=0,sa31=0,sa44x=0,sa29=0,sa42x=0,sa51=0,sa01x=0,sa52=0,sa26x=0,sa55=0,sa02x=0,sa57=0,sa05x=0, sa58=0, sa27x=0; 
//Controle para exibir os Assuntos
var assunto36=0,assunto09=0,assunto37=0,assunto38=0,assunto39=0,assunto40=0,assunto45=0,assunto41=0,assunto11=0;
var assunto43=0,assunto44=0,assunto42=0,assunto01=0,assunto26=0,assunto02=0,assunto05=0,assunto27=0,assunto47=0;
//Participação do Assistido no Processo
var partProc03=0, partProc04=0;
var valorPredicao=0, clicouNaIA=0;
var resposta =0;
var RespostaIa = 'vazio';
var valorPredicaoAnterior = 0
var a = document.getElementById('valor_predicao');
var b = document.getElementById('clicou_ia');

a.value = valorPredicao;
b.value = clicouNaIA;

//Verificar se o usuario está usando iphone para ocultar o uso do audio.
if(navigator.userAgent.match(/iPhone|iPad|iPod/i)){
    var audio = document.querySelector('.relato-audio');
    audio.style.display = 'none';
}

function startTimer() {
    let time = 0;
    const timer = document.getElementById("timer");
    let stop = document.getElementById('btnStop');
    var cont = true;

    function updateTime() {
        stop.addEventListener('click', function (ev) {
            cont = false;
        });
        if(cont == true){
            time++;
            const minutes = Math.floor(time / 60);
            const seconds = time % 60;
            timer.innerHTML = `<p id="timeAudio">0${minutes}:${seconds < 10 ? "0" + seconds : seconds}</p>`;
            if(minutes == 2){
                cont = false;
                stop.click()
            }
        }   
    }
    updateTime();
    const intervalId = setInterval(updateTime, 1000);
}

function deleteAudio(){
    var audio = $("#adioPlay")
    $("#adioPlay").remove()
    let del = document.getElementById('btnDelete');
    del.classList.add("none-audio")
    let start = document.getElementById('btnStart');
    start.classList.remove("none-audio")
}
// Stop record
let stop = document.getElementById('btnStop');

function Start(){
    let audioIN = { audio: true };
// Access the permission for use
// the microphone
   navigator.mediaDevices.getUserMedia(audioIN)

  // 'then()' method returns a Promise
  .then(function (mediaStreamObj) {

    let stop = document.getElementById('btnStop');
    stop.classList.remove("none-audio")
    let start = document.getElementById('btnStart');
    let del = document.getElementById('btnDelete');
    start.classList.add("none-audio")

    // 2nd audio tag for play the audio
    let playAudio = document.getElementById('adioPlay');

    // This is the main thing to recorde 
    // the audio 'MediaRecorder' API
    let mediaRecorder = new MediaRecorder(mediaStreamObj, {mimeType: 'audio/webm'});
    // Pass the audio stream 
    // Start event

    mediaRecorder.start();
    startTimer();
    
    // Stop event
    stop.addEventListener('click', function (ev) {
        $("#timeAudio").remove()
        stop.classList.add("none-audio")
        mediaRecorder.stop();
    });

    // If audio data available then push 
    // it to the chunk array
    mediaRecorder.ondataavailable = function (ev) {
        dataArray.push(ev.data);
        const audioBlob = new Blob(dataArray, { type: 'audio/webm' });
        const audioContext = new AudioContext();
        var audio = document.getElementById('url_audio');
        let reader = new FileReader()
        reader.readAsArrayBuffer(audioBlob);
        reader.onloadend = () => {  
            //Converter audio gravado em WEBM para WAV
            audioContext.decodeAudioData(reader.result, function(buffer) {
                const wavBuffer = audioBufferToWav(buffer);
                const wavBlob = new Blob([new DataView(wavBuffer)], { type: "audio/wav" });
                encodeAudioToBase64(URL.createObjectURL(wavBlob), function(audioPlay){
                    audio.value = audioPlay
                    ApiAudioIa(audioPlay)
                    var loading = document.querySelector(".loading-audio");
                    loading.style.display = 'inline-flex'; 
                }) 
                del.classList.remove("none-audio")
          
                $("#audio-relato").append("<audio id='adioPlay' type='audio/wav' src='"+URL.createObjectURL(wavBlob)+"' controls></audio>")  
            })

        }

    }

    let dataArray = [];

    mediaRecorder.onstop = function (ev) {
        //del.classList.remove("none-audio")
        //$("#audio-relato").append("<audio id='adioPlay' src='"+audioSrc+"' controls></audio>")
    }
  })
  .catch(function (err) {
    //console.log(err.name, err.message);
  });
}

function encodeAudioToBase64(url, callback) {
    var xhr = new XMLHttpRequest();
    xhr.open('GET', url, true);
    xhr.responseType = 'blob';
    xhr.onload = function() {
      if (this.status == 200) {
        var reader = new FileReader();
        reader.readAsDataURL(this.response);
        reader.onload = function() {
          var base64 = reader.result.split(',')[1];
          var audio = document.getElementById('url_audio');
          audio.value = base64
          callback(base64);
        };
      }
    };
    xhr.send();
  }

  function gerarCodigoAleatorio() {
    const caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let codigo = '';
    for (let i = 0; i < 10; i++) {
      codigo += caracteres.charAt(Math.floor(Math.random() * caracteres.length));
    }
    return codigo;
  }

function ApiAudioIa(audio){
    const codigo = gerarCodigoAleatorio();
    var data = {
        nome: codigo,
        audiobase64: audio
        }
    var myHeaders = new Headers();
    myHeaders.append("Content-Type", "application/json");
    const options = {
        method: 'POST',
        headers: myHeaders,
        body: JSON.stringify(data),
        redirect: 'follow'
    }                              
    var loading = document.querySelector(".loading-audio");
    var alerta_error = document.getElementById("alerta_error_audio");
    fetch('https://ai.defensoria.ba.def.br/base64', options)
    .then(response => {
        response.json().then(
            data => {
                loading.style.display = 'none'; 
                if(data.Relato != "não entendi"){
                    $("#relato-textarea").val(data.comp_relato); 
                    if(document.getElementById('processo-0').checked){
                        showData_assunto2(data.Predicao, data.Value_pred)
                        bloquear_text_area() 
                        alerta_error.style.display = 'none';
                        //console.log(data)
                    }
                }else{
                    alerta_error.style.display = 'none';
                    mostrar_msg_error_audio('Não conseguimos entender o seu áudio. Grave novamente, ou tente escrever no campo abaixo.')
                    $("#relato-textarea").val(""); 
                    let area = document.getElementById("relato-textarea");
                    area.removeAttribute("readOnly");
                    $('#nome_assunto').prop("disabled", false);
                    limparAssunto();
                    limparSelect("tp_do_assunto");
                }
            }
        )
    })
    .catch(e => {
        loading.style.display = 'none'; 
        mostrar_msg_error_audio('Estamos enfrentando algum problema com nosso tradutor de relatos. Por favor escreva no campo abaixo.') 
    })
}

function blobToBase64(blob, callback) {
    var fileReader = new FileReader();
    fileReader.onload = function(e) { 
      var binary = [].slice.apply(new Uint8Array(fileReader.result));     
      var str = binary.map(function (charCode) {
        return String.fromCharCode(charCode);
      }).join("");  
      callback(btoa(str));
    };
    fileReader.readAsArrayBuffer(blob);
  }

// chama todas as funções após a página ser carregada
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip()
    exibealerta();
    chekBox();
    tem_processo();
});
function filtroAtivado(){
    if(document.getElementById('filtro_assunto').checked){
        var alerta_error = document.getElementById("alerta_error");
        var alerta_ia = document.getElementById("alerta_ia");      
        alerta_error.style.display = 'none';
        alerta_ia.style.display = 'none'; 
        $('#nome_assunto').prop("disabled", false);
        enviar_relato();
        habilitar_text_area();
        limparAssunto();
        limparSelect("tp_do_assunto");
    }
}
function limparFiltro(){
    document.getElementById("filtro_assunto").checked = false;
    limparInput('filtro');
    funcAlternar('#filtro');
}
//[Filtrar Assuntos]++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function onInput() {
    var val = document.getElementById("input").value;
    var opts = document.getElementById('dlist').childNodes;   
    valorPredicao = 0;  

    var a = document.getElementById('valor_predicao');
    a.value = valorPredicao;    
    for (var i = 0; i < opts.length; i++) {
        if (opts[i].value === val) {
            limparAssunto();
            limparFiltro();  
            if((opts[i].value)=='Falecimento de familiar/inventário'){
                $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
                exibirAssunto36();
            }else if((opts[i].value)=='Tratar de herança (Falecimento de familiar/inventário)'){
                $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
                exibirAssunto36();
                $("#ffi").val($("#ffi option[value='21']").val());
                funcFalec_famil();             
            }else if((opts[i].value)=='2ª via da certidão de óbito'){
                $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
                exibirAssunto36();
                $("#ffi").val($("#ffi option[value='22']").val());
                funcFalec_famil();               
            }else if((opts[i].value)=='Retificação da certidão de óbito (erro ou omissão na certidão)'){
                $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
                exibirAssunto36();
                $("#ffi").val($("#ffi option[value='23']").val());
                funcFalec_famil();
            }else if((opts[i].value)=='Óbito não registrado em cartório'){
                $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
                exibirAssunto36();
                $("#ffi").val($("#ffi option[value='24']").val());
                funcFalec_famil();               
            }else if((opts[i].value)=='Transcrição da certidão estrangeira'){
                $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
                exibirAssunto36();
                $("#ffi").val($("#ffi option[value='25']").val());
                funcFalec_famil();               
            }else if((opts[i].value)=='Testamento'){
                $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
                exibirAssunto36();
                $("#ffi").val($("#ffi option[value='26']").val());
                funcFalec_famil();               
            }else if((opts[i].value)=='Declaração de ausência'){
                $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
                exibirAssunto36();
                $("#ffi").val($("#ffi option[value='27']").val());
                funcFalec_famil();               
            }else if((opts[i].value)=='Exumação'){
                $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
                exibirAssunto36();
                $("#ffi").val($("#ffi option[value='28']").val());
                funcFalec_famil();
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Pensão alimentícia'){
                $("#nome_assunto").val($("#nome_assunto option[value='9']").val());
                exibirAssunto09();
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Casamento/união estável/divórcio/separação'){
                $("#nome_assunto").val($("#nome_assunto option[value='37']").val());
                exibirAssunto37();
            }else if((opts[i].value)=='Deseja casar'){
                $("#nome_assunto").val($("#nome_assunto option[value='37']").val());
                exibirAssunto37();
                $("#cuds").val($("#cuds option[value='38']").val());
                funcCasamento_Divorcio();
            }else if((opts[i].value)=='Divórcio/Separação'){
                $("#nome_assunto").val($("#nome_assunto option[value='37']").val());
                exibirAssunto37();
                $("#cuds").val($("#cuds option[value='39']").val());
                funcCasamento_Divorcio();                         
            }else if((opts[i].value)=='Problemas com a certidão de casamento / nascimento'){
                $("#nome_assunto").val($("#nome_assunto option[value='37']").val());
                exibirAssunto37();
                $("#cuds").val($("#cuds option[value='40']").val());
                funcCasamento_Divorcio();                         
            }else if((opts[i].value)=='Documento que comprove a união estável'){
                $("#nome_assunto").val($("#nome_assunto option[value='37']").val());
                exibirAssunto37();
                $("#cuds").val($("#cuds option[value='41']").val());
                funcCasamento_Divorcio();                         
            }else if((opts[i].value)=='Alterar regime de bens'){
                $("#nome_assunto").val($("#nome_assunto option[value='37']").val());
                exibirAssunto37();
                $("#cuds").val($("#cuds option[value='42']").val());
                funcCasamento_Divorcio();                         
            }else if((opts[i].value)=='Realizar escritura de pacto antenupcial'){
                $("#nome_assunto").val($("#nome_assunto option[value='37']").val());
                exibirAssunto37();
                $("#cuds").val($("#cuds option[value='43']").val());
                funcCasamento_Divorcio();                         
            }else if((opts[i].value)=='Cônjuge ou companheiro(a) está se desfazendo do patrimônio'){
                $("#nome_assunto").val($("#nome_assunto option[value='37']").val());
                exibirAssunto37();
                $("#cuds").val($("#cuds option[value='44']").val());
                funcCasamento_Divorcio();
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++                     
            }else if((opts[i].value)=='Violência doméstica'){
                $("#nome_assunto").val($("#nome_assunto option[value='38']").val());
                exibirAssunto38();
            }else if((opts[i].value)=='Abrigo/Casa de Acolhimento (Violência Doméstica)'){
                $("#nome_assunto").val($("#nome_assunto option[value='38']").val());
                if(sub_assunto_viol_domest==2){
                    funcAlternar('#sub_assunto_violencia_domest');
                    habilitarSelect('sub_assunto_violencia_domest');
                }
                $("#id_viol_domest").val($("#id_viol_domest option[value='32']").val());
                funcViolenciaDomestica();
                $("#tp_pedido_id").val($("#tp_pedido_id option[value='1']").val());
                cont++;assunto38=1;
            }else if((opts[i].value)=='Medida protetiva (Violência Doméstica)'){
                $("#nome_assunto").val($("#nome_assunto option[value='38']").val());
                if(sub_assunto_viol_domest==2){
                    funcAlternar('#sub_assunto_violencia_domest');
                    habilitarSelect('sub_assunto_violencia_domest');
                }
                $("#id_viol_domest").val($("#id_viol_domest option[value='32']").val());
                funcViolenciaDomestica();
                $("#tp_pedido_id").val($("#tp_pedido_id option[value='2']").val());
                cont++;assunto38=1;
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Trabalhista'){
                $("#nome_assunto").val($("#nome_assunto option[value='39']").val());
                exibirAssunto39();
            }else if((opts[i].value)=='Servidor público federal'){
                $("#nome_assunto").val($("#nome_assunto option[value='39']").val());
                if(sub_assunt_trab==2){
                    funcAlternar('#sub_assunto_trabalhista');
                    habilitarSelect('sub_assunto_trabalhista');
                }
                $("#id_trabalhista").val($("#id_trabalhista option[value='37']").val());
                funcTrabalhista();
                $("#tipo_vinculo").val($("#tipo_vinculo option[value='1']").val());
                funcVinculoTrabalhista();
                cont++;assunto39=1;
            }else if((opts[i].value)=='Servidor público estadual'){
                $("#nome_assunto").val($("#nome_assunto option[value='39']").val());
                if(sub_assunt_trab==2){
                    funcAlternar('#sub_assunto_trabalhista');
                    habilitarSelect('sub_assunto_trabalhista');
                }
                $("#id_trabalhista").val($("#id_trabalhista option[value='37']").val());
                funcTrabalhista();
                $("#tipo_vinculo").val($("#tipo_vinculo option[value='2']").val());
                funcVinculoTrabalhista();
                cont++;assunto39=1;
            }else if((opts[i].value)=='Servidor público municipal'){
                $("#nome_assunto").val($("#nome_assunto option[value='39']").val());
                if(sub_assunt_trab==2){
                    funcAlternar('#sub_assunto_trabalhista');
                    habilitarSelect('sub_assunto_trabalhista');
                }
                $("#id_trabalhista").val($("#id_trabalhista option[value='37']").val());
                funcTrabalhista();
                $("#tipo_vinculo").val($("#tipo_vinculo option[value='3']").val());
                funcVinculoTrabalhista();
                cont++;assunto39=1;
            }else if((opts[i].value)=='CLT'){
                $("#nome_assunto").val($("#nome_assunto option[value='39']").val());
                if(sub_assunt_trab==2){
                    funcAlternar('#sub_assunto_trabalhista');
                    habilitarSelect('sub_assunto_trabalhista');
                }
                $("#id_trabalhista").val($("#id_trabalhista option[value='37']").val());
                funcTrabalhista();
                $("#tipo_vinculo").val($("#tipo_vinculo option[value='4']").val());
                funcVinculoTrabalhista();
                cont++;assunto39=1;
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Usucapião'){
                $("#nome_assunto").val($("#nome_assunto option[value='40']").val());
                exibirAssunto40();
        // ++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Saúde'){
                $("#nome_assunto").val($("#nome_assunto option[value='45']").val());
                exibirAssunto45();
            }else if((opts[i].value)=='Exame/Cirurgia'){
                $("#nome_assunto").val($("#nome_assunto option[value='45']").val());
                if(sub_assunt_saude==2){
                    funcAlternar('#sub_assunto_saude');
                    habilitarSelect('sub_assunto_saude');
                }
                $("#id_saude").val($("#id_saude option[value='35']").val());
                funcSaude();
                $("#tp_pedido_saude").val($("#tp_pedido_saude option[value='11']").val());
                cont++;assunto45=1;
            }else if((opts[i].value)=='Medicamentos'){
                $("#nome_assunto").val($("#nome_assunto option[value='45']").val());
                if(sub_assunt_saude==2){
                    funcAlternar('#sub_assunto_saude');
                    habilitarSelect('sub_assunto_saude');
                }
                $("#id_saude").val($("#id_saude option[value='35']").val());
                funcSaude();
                $("#tp_pedido_saude").val($("#tp_pedido_saude option[value='12']").val());
                cont++;assunto45=1;
            }else if((opts[i].value)=='Transferência/Regulação'){
                $("#nome_assunto").val($("#nome_assunto option[value='45']").val());
                if(sub_assunt_saude==2){
                    funcAlternar('#sub_assunto_saude');
                    habilitarSelect('sub_assunto_saude');
                }
                $("#id_saude").val($("#id_saude option[value='35']").val());
                funcSaude();
                $("#tp_pedido_saude").val($("#tp_pedido_saude option[value='21']").val());
                cont++;assunto45=1;
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Registros Públicos/Certidões'){
                $("#nome_assunto").val($("#nome_assunto option[value='41']").val());
                exibirAssunto41();
            }else if((opts[i].value)=='Retificação de registro'){
                $("#nome_assunto").val($("#nome_assunto option[value='41']").val());
                if(sub_assunto_civel==2){
                    funcAlternar('#sub_assunto_atend_civel');
                    habilitarSelect('sub_assunto_atend_civel');
                }
                $("#id_atend_civel").val($("#id_atend_civel option[value='33']").val());
                $("#tp_pedido_at_civel_id").val($("#tp_pedido_at_civel_id option[value='5']").val());
                funcAtentim_civel();
                cont++;assunto41=1;
            }else if((opts[i].value)=='Abertura de registro público'){
                $("#nome_assunto").val($("#nome_assunto option[value='41']").val());
                if(sub_assunto_civel==2){
                    funcAlternar('#sub_assunto_atend_civel');
                    habilitarSelect('sub_assunto_atend_civel');
                }
                $("#id_atend_civel").val($("#id_atend_civel option[value='33']").val());
                $("#tp_pedido_at_civel_id").val($("#tp_pedido_at_civel_id option[value='6']").val());
                funcAtentim_civel();
                cont++;assunto41=1;
            }
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            else if((opts[i].value)=='Ações contra o estado/município'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
            }else if((opts[i].value)=='Concurso público'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='7']").val());
            }else if((opts[i].value)=='Mandado de segurança'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='8']").val());
            }else if((opts[i].value)=='IPTU'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='9']").val());
            }else if((opts[i].value)=='Agressão policial'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='13']").val());
            }else if((opts[i].value)=='IPVA'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='14']").val());
            }else if((opts[i].value)=='ICMS'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='15']").val());
            }else if((opts[i].value)=='Dano moral/Dano material (Ações contra o estado/município)'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='16']").val());
            }else if((opts[i].value)=='Erro médico'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='17']").val());
            }else if((opts[i].value)=='Multa de trânsito'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='18']").val());
            }else if((opts[i].value)=='Pensão por morte de servidor público'){
                $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
                exibirAssunto43();
                $("#tipoDeAtend").val($("#tipoDeAtend option[value='19']").val());
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Desmarcar agendamento'){
                $("#nome_assunto").val($("#nome_assunto option[value='44']").val());
                exibirAssunto44();
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Consumidor'){
                $("#nome_assunto").val($("#nome_assunto option[value='42']").val());
                exibirAssunto42();
            }else if((opts[i].value)=='Plano de Saúde'){
                $("#nome_assunto").val($("#nome_assunto option[value='42']").val());
                exibirAssunto42();
                $("#id_cons").val($("#id_cons option[value='29']").val());
                funcPlanoSaude();
            }else if((opts[i].value)=='Banco'){
                $("#nome_assunto").val($("#nome_assunto option[value='42']").val());
                exibirAssunto42();
                $("#id_cons").val($("#id_cons option[value='45']").val());
                funcPlanoSaude();
            }else if((opts[i].value)=='COELBA/EMBASA/Telefonia/Transporte Público'){
                $("#nome_assunto").val($("#nome_assunto option[value='42']").val());
                exibirAssunto42();
                $("#id_cons").val($("#id_cons option[value='46']").val());
                funcPlanoSaude();
            }else if((opts[i].value)=='Consórcio'){
                $("#nome_assunto").val($("#nome_assunto option[value='42']").val());
                exibirAssunto42();
                $("#id_cons").val($("#id_cons option[value='47']").val());
                funcPlanoSaude();
            }else if((opts[i].value)=='Faculdade'){
                $("#nome_assunto").val($("#nome_assunto option[value='42']").val());
                exibirAssunto42();
                $("#id_cons").val($("#id_cons option[value='48']").val());
                funcPlanoSaude();
            }else if((opts[i].value)=='Dano moral/Dano material (Consumidor)'){
                $("#nome_assunto").val($("#nome_assunto option[value='42']").val());
                exibirAssunto42();
                $("#id_cons").val($("#id_cons option[value='60']").val());
                funcPlanoSaude();
            }else if((opts[i].value)=='Seguradora'){
                $("#nome_assunto").val($("#nome_assunto option[value='42']").val());
                exibirAssunto42();
                $("#id_cons").val($("#id_cons option[value='49']").val());
                funcPlanoSaude();
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Adoção'){
                $("#nome_assunto").val($("#nome_assunto option[value='1']").val());
                exibirAssunto01();
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Autorização para viagem internacional'){
                $("#nome_assunto").val($("#nome_assunto option[value='26']").val());
                exibirAssunto26();
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Alvará Judicial'){
                $("#nome_assunto").val($("#nome_assunto option[value='2']").val());
                exibirAssunto02();
            }else if((opts[i].value)=='Cremação de corpo'){
                $("#nome_assunto").val($("#nome_assunto option[value='2']").val());
                exibirAssunto02();
                $("#id_alvara").val($("#id_alvara option[value='53']").val());
                funcAlvara(); 
            }else if((opts[i].value)=='Transplante de órgãos'){
                $("#nome_assunto").val($("#nome_assunto option[value='2']").val());
                exibirAssunto02();
                $("#id_alvara").val($("#id_alvara option[value='54']").val());
                funcAlvara(); 
            }else if((opts[i].value)=='Tratar de herança (Alvará Judicial)'){
                $("#nome_assunto").val($("#nome_assunto option[value='2']").val());
                exibirAssunto02();
                $("#id_alvara").val($("#id_alvara option[value='55']").val());
                funcAlvara();
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++ 
            }else if((opts[i].value)=='Exame de DNA'){
                $("#nome_assunto").val($("#nome_assunto option[value='5']").val());
                exibirAssunto05();
        // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Idosos'){
                $("#nome_assunto").val($("#nome_assunto option[value='27']").val());
                exibirAssunto27();
            }else if((opts[i].value)=='Abrigo/Casa de Acolhimento (Idoso)'){
                $("#nome_assunto").val($("#nome_assunto option[value='27']").val());
                if(sub_ass_idoso==2){
                    funcAlternar('#sub_assunto_idoso');
                    habilitarSelect('sub_assunto_idoso');
                }
                $("#id_idoso").val($("#id_idoso option[value='58']").val());
                funcIdoso();
                $("#tp_pedido_idoso").val($("#tp_pedido_idoso option[value='1']").val());
                cont++;assunto27=1;
            }else if((opts[i].value)=='Medida protetiva (Idoso)'){
                $("#nome_assunto").val($("#nome_assunto option[value='27']").val());
                if(sub_ass_idoso==2){
                    funcAlternar('#sub_assunto_idoso');
                    habilitarSelect('sub_assunto_idoso');
                }
                $("#id_idoso").val($("#id_idoso option[value='58']").val());
                funcIdoso();
                $("#tp_pedido_idoso").val($("#tp_pedido_idoso option[value='2']").val());
                cont++;assunto27=1;
            // +++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
            }else if((opts[i].value)=='Regulamentação de Visita/Direito de convivência'){
                $("#nome_assunto").val($("#nome_assunto option[value='11']").val());
                exibirAssunto11();
            }else if((opts[i].value)=='Locação de imóvel'){
                $("#nome_assunto").val($("#nome_assunto option[value='47']").val());
                exibirAssunto47();
            }
            else if((opts[i].value)=='Despejo'){
                $("#nome_assunto").val($("#nome_assunto option[value='47']").val());
                exibirAssunto47();
                $("#id_locacao").val($("#id_locacao option[value='61']").val());
            }
            else if((opts[i].value)=='Cobrança de aluguel'){
                $("#nome_assunto").val($("#nome_assunto option[value='47']").val());
                exibirAssunto47();
                $("#id_locacao").val($("#id_locacao option[value='62']").val());
            }
            else if((opts[i].value)=='Outros encargos sem despejo'){
                $("#nome_assunto").val($("#nome_assunto option[value='47']").val());
                exibirAssunto47();
                $("#id_locacao").val($("#id_locacao option[value='63']").val());
            }else if((opts[i].value)=='Consignação de aluguel'){
                $("#nome_assunto").val($("#nome_assunto option[value='47']").val());
                exibirAssunto47();
                $("#id_locacao").val($("#id_locacao option[value='64']").val());
            }else if((opts[i].value)=='Renovatória de locação'){
                $("#nome_assunto").val($("#nome_assunto option[value='47']").val());
                exibirAssunto47();
                $("#id_locacao").val($("#id_locacao option[value='65']").val());
            }
            else if((opts[i].value)=='Posse/Propriedade'){
                $("#nome_assunto").val($("#nome_assunto option[value='46']").val());
            }else if((opts[i].value)=='Acidente de trabalho'){
                $("#nome_assunto").val($("#nome_assunto option[value='16']").val());
            }else if((opts[i].value)=='Adi-escolar (agente de desenvolvimento infantil)'){
                $("#nome_assunto").val($("#nome_assunto option[value='23']").val());
            }else if((opts[i].value)=='Ausência de transporte escolar gratuito'){
                $("#nome_assunto").val($("#nome_assunto option[value='25']").val());
            }else if((opts[i].value)=='Destituição do poder familiar (casos de maus tratos, violência sexual, física, psicológica, moral)'){
                $("#nome_assunto").val($("#nome_assunto option[value='24']").val());
            }else if((opts[i].value)=='Guarda'){
                $("#nome_assunto").val($("#nome_assunto option[value='6']").val());
            }else if((opts[i].value)=='Interdição'){
                $("#nome_assunto").val($("#nome_assunto option[value='7']").val());
            }else if((opts[i].value)=='Vaga em creche'){
                $("#nome_assunto").val($("#nome_assunto option[value='19']").val());
            }else if((opts[i].value)=='Vaga no ensino fundamental I e II'){
                $("#nome_assunto").val($("#nome_assunto option[value='20']").val());
            }else if((opts[i].value)=='Vizinhança'){
                $("#nome_assunto").val($("#nome_assunto option[value='17']").val());
            }else if((opts[i].value)=='Direito Real de Laje'){
                $("#nome_assunto").val($("#nome_assunto option[value='48']").val());
            }                                                                                                   
            break;
        }
    }
}
//[Exibe a opção escolhida pelo assistido sobre o processo]+++++++++++++++++++
function tem_processo(){
    var sim = 0, nao = 0;
    var alerta_error = document.getElementById("alerta_error");
    var alerta_ia = document.getElementById("alerta_ia"); 
    $('#processo-1').click(function(){
        if(nao==1){
            $('#procNao').toggle();
            $('#relato').toggle();
            $('#solic_horario').toggle();
            $('#enviar_Form').toggle(); 
            //$('#enviar_relato_ia').toggle();
            limparAssunto();
            limparSelect("tp_do_assunto");
            //habilitarSelect('tp_do_assunto');   
            desabilitarSelect("tp_do_assunto");
            if(document.getElementById('filtro_assunto').checked){
                limparFiltro();
            }
            alerta_error.style.display = 'none';
            alerta_ia.style.display = 'none';
            limparTextarea('relato');
            limparCheckbRadio('textoTabela');
            habilitar_text_area();
            enviar_relato_ia.style.display="none";
            editar_relato_ia.style.display="none";
            nao=0;
        }
        if(sim==0){
            //$('#procNao').toggle();
            $('#procSim').toggle();
            $('#relato').toggle();
           //$('#enviar_relato_ia').toggle();
            $('#solic_horario').toggle();
            $('#enviar_Form').toggle();
            habilitarInput('num_processo');
            habilitarSelect('procSim');      
            //habilitarSelect('tp_do_assunto');       
            sim=1;
        }
    });
    $('#processo-0').click(function(){
        if(sim==1){
            //$('#procNao').toggle();
            $('#procSim').toggle();
            $('#relato').toggle();
            $('#solic_horario').toggle();
            $('#enviar_Form').toggle();
           // $('#enviar_relato_ia').toggle();
            //alerta_ia.style.display = 'none';
            //alerta_error.style.display = 'none';
            //enviar_relato_ia.style.display="none";
            //editar_relato_ia.style.display="none";
            //let area = document.getElementById("relato-textarea");
            //area.removeAttribute("readOnly");
            limparAssunto();
            limparInput('num_processo');
            limparSelect('procSim');
            limparParticipacao();
            limparTextarea('relato');
            limparCheckbRadio('textoTabela');
            desabilitarInput('num_processo');
            desabilitarSelect('procSim');         
            //habilitarSelect('tp_do_assunto');   
            sim=0;
        }
        if(nao==0){
            $('#procNao').toggle();
            $('#relato').toggle();
            $('#solic_horario').toggle();
            $('#enviar_Form').toggle();
            $('#enviar_relato_ia').toggle();
            habilitarSelect('tp_do_assunto');
            nao=1;
        }
    });
}
//[Limpar Participação do Assistido no Processo]++++++++++++++++++++++++++++++
function limparParticipacao(){
    if(count1==1){
        if(partProc03==1){
            $('#trat_parte').toggle();
            limparInput('trat_parte');
            desabilitarInput('trat_parte');
            count1=0;
            partProc03=0;
        }
        else if(partProc04==1){
            $('#trat_famil_preso').toggle();
            limparInput('trat_famil_preso');
            desabilitarInput('trat_famil_preso');
            count1=0;
            partProc04=0;
        }
    }    
}
//[Exibir Participação do Assistido no Processo]++++++++++++++++++++++++++++++
function exibirParticProcesso(){
    limparParticipacao();
    var val = $('#partProc option:selected').val();
    if(val==3){
        funcAlternar('#trat_parte');
        habilitarInput('trat_parte');
        count1++;
        partProc03=1; 
    }else if(val==4){
        funcAlternar('#trat_famil_preso');
        habilitarInput('trat_famil_preso');
        count1++;
        partProc04=1;
    }
}
//[Limpar Assuntos]+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function limparAssunto(){
    if(cont==1){
        if(assunto36==1){
            $('#sub_assunto_falec_famil').toggle();
            limparSubAssuntoFalec_famil();
            limparSelect ('sub_assunto_falec_famil');
            desabilitarSelect('sub_assunto_falec_famil');
            assunto36=0; 
        }
        else if(assunto09==1){
            if(sub_assunto_alimento==2){
                $('#subA_alimentos').toggle();
            }
            limparSubAssuntoAlimento();
            if(sub_assunto_alimento==2){
                limparSelect ('subA_alimentos');
                desabilitarSelect('subA_alimentos');
            }
            assunto09=0;
        }
        else if(assunto37==1){
            $('#sub_assunto_casam_divorcio').toggle();
            limparSubAssuntoCasamento_Divorcio();
            limparSelect('sub_assunto_casam_divorcio');
            desabilitarSelect('sub_assunto_casam_divorcio');
            assunto37=0;
        }
        else if(assunto38==1){
            if(sub_assunto_viol_domest==2){
                $('#sub_assunto_violencia_domest').toggle();
            }
            limparSubAssuntoViolenciaDomestica();
            if(sub_assunto_viol_domest==2){
                limparSelect ('sub_assunto_violencia_domest');
                desabilitarSelect('sub_assunto_violencia_domest');
            }
            assunto38=0;
        }
        else if(assunto39==1){
            if(sub_assunt_trab==2){
                $('#sub_assunto_trabalhista').toggle();
            }
            limparSubAssuntoTrabalhista();
            if(sub_assunt_trab==2){
                limparSelect ('sub_assunto_trabalhista');
                desabilitarSelect('sub_assunto_trabalhista');
            }
            assunto39=0;
        }
        else if(assunto40==1){
            if(sub_assunt_usucap==2){
                $('#sub_assunto_usucapiao').toggle();
            }
            limparSubAssuntoUsucapiao();
            if(sub_assunt_usucap==2){
                limparSelect ('sub_assunto_usucapiao');
                desabilitarSelect('sub_assunto_usucapiao');
            }
            assunto40=0;
        }   
        else if(assunto45==1){
            if(sub_assunt_saude==2){
                $('#sub_assunto_saude').toggle();
            }
            limparSubAssuntoSaude();
            if(sub_assunt_saude==2){
                limparSelect ('sub_assunto_saude');
                desabilitarSelect('sub_assunto_saude');
            }
            assunto45=0;  
        }
        else if(assunto41==1){
            if(sub_assunto_civel==2){
                $('#sub_assunto_atend_civel').toggle();
            }
            limparSubAssuntoAtentim_civel();
            if(sub_assunto_civel==2){
                limparSelect ('sub_assunto_atend_civel');
                desabilitarSelect('sub_assunto_atend_civel');
            }
            assunto41=0;
        }
        else if(assunto42==1){
            $('#sub_assunto_consumidor').toggle();
            limparSubAssuntoPlanoSaude();
            limparSelect ('sub_assunto_consumidor');
            desabilitarSelect('sub_assunto_consumidor');
            assunto42=0; 
        }
        else if(assunto43==1){
            if(sub_assunto_faz_pub==2){
                $('#sub_assunto_fazenda_publica').toggle();
            }
            limparSubAssuntoFazenda_publica();
            if(sub_assunto_faz_pub==2){
                limparSelect ('sub_assunto_fazenda_publica');              
                desabilitarSelect('sub_assunto_fazenda_publica');
            }
            assunto43=0; 
        }        
        else if(assunto44==1){
            if(sub_assunto_desmarc==2){
                $('#sub_assunto_desmarcar_agend').toggle();
            }
            limparSubAssuntoDesmarcar_agendamento();
            if(sub_assunto_desmarc==2){
                limparSelect ('sub_assunto_desmarcar_agend');
                desabilitarSelect('sub_assunto_desmarcar_agend');
            }
            assunto44=0; 
        }        
        else if(assunto26==1){
            if(sub_assunto_viagem_inter==2){
                $('#sub_assunto_viagem_internacional').toggle();
            }
            limparSubAssuntoViagem_inter();
            if(sub_assunto_viagem_inter==2){
                limparSelect('sub_assunto_viagem_internacional');
                desabilitarSelect('sub_assunto_viagem_internacional');
            }
            assunto26=0;
        }       
        else if(assunto27==1){
            if(sub_ass_idoso==2){
                $('#sub_assunto_idoso').toggle();
            }
            limparSubAssuntoIdoso();
            if(sub_ass_idoso==2){
                limparSelect ('sub_assunto_idoso');
                desabilitarSelect('sub_assunto_idoso');
            }
            assunto27=0;
        }        
        else if(assunto01==1){
            if(sub_assunto_adocao==2){
                $('#sub_assunto_adocao').toggle();
            }
            limparSubAssuntoAdocao();
            if(sub_assunto_adocao==2){
                limparSelect('sub_assunto_adocao');
                desabilitarSelect('sub_assunto_adocao');
            }
            assunto01=0;
        }        
        else if(assunto02==1){
            $('#sub_assunto_alvara_judicial').toggle();
            limparSubAssuntoAlvara();
            limparSelect('sub_assunto_alvara_judicial');
            desabilitarSelect('sub_assunto_alvara_judicial');
            assunto02=0;
        }       
        else if(assunto05==1){
            if(sub_assunto_exame_dna==2){
                $('#sub_assunto_exame_dna').toggle();
            }
            limparSubAssuntoExameDna();
            if(sub_assunto_exame_dna==2){
                limparSelect('sub_assunto_exame_dna');
                desabilitarSelect('sub_assunto_exame_dna');
            }
            assunto05=0;
        }       
        else if(assunto11==1){
            if(sub_assunto_regulamentacao==2){
                $('#sub_assunto_regulamentacao_visita').toggle();
            }
            limparSubAssuntoRegulamentacao_visita();
            if(sub_assunto_regulamentacao==2){
                limparSelect ('sub_assunto_regulamentacao_visita');              
                desabilitarSelect('sub_assunto_regulamentacao_visita');
            }
            assunto11=0; 
        }
        else if(assunto47==1){
            $('#sub_assunto_locacao_imovel').toggle();
            limparSelect ('sub_assunto_locacao_imovel');
            desabilitarSelect('sub_assunto_locacao_imovel');
            assunto47=0; 
        }       
        cont=0;
    }
}
//[Exibir Assuntos]+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function exibirAssunto36(){
    funcAlternar('#sub_assunto_falec_famil');
    habilitarSelect('sub_assunto_falec_famil');
    cont++;
    assunto36=1;
}
function exibirAssunto09(){
    if(sub_assunto_alimento==2){
        funcAlternar('#subA_alimentos');
        habilitarSelect('subA_alimentos');
    }
    else{
        $("#id_alimentos").val($("#id_alimentos option[value='30']").val());
        funcAlimentos();
    }
    cont++;assunto09=1;
}
function exibirAssunto37(){
    funcAlternar('#sub_assunto_casam_divorcio');
    habilitarSelect('sub_assunto_casam_divorcio');
    cont++;
    assunto37=1;
}
function exibirAssunto38(){
    if(sub_assunto_viol_domest==2){
        funcAlternar('#sub_assunto_violencia_domest');
        habilitarSelect('sub_assunto_violencia_domest');
    }
    else{
        $("#id_viol_domest").val($("#id_viol_domest option[value='32']").val());
        funcViolenciaDomestica();
    }
    cont++;
    assunto38=1;
}
function exibirAssunto39(){
    if(sub_assunt_trab==2){
        funcAlternar('#sub_assunto_trabalhista');
        habilitarSelect('sub_assunto_trabalhista');
    }
    else{
        $("#id_trabalhista").val($("#id_trabalhista option[value='37']").val());
        funcTrabalhista();
    }
    cont++;
    assunto39=1;
}
function exibirAssunto40(){
    if(sub_assunt_usucap==2){
        funcAlternar('#sub_assunto_usucapiao');
        habilitarSelect('sub_assunto_usucapiao');
    }
    else{
        $("#id_usuc").val($("#id_usuc option[value='36']").val());
        funcUsucapiao();
    }
    cont++;
    assunto40=1;
}
function exibirAssunto45(){
    if(sub_assunt_saude==2){
        funcAlternar('#sub_assunto_saude');
        habilitarSelect('sub_assunto_saude');
    }
    else{
        $("#id_saude").val($("#id_saude option[value='35']").val());
        funcSaude();
    }
    cont++;
    assunto45=1;  
}
function exibirAssunto41(){
    if(sub_assunto_civel==2){
        funcAlternar('#sub_assunto_atend_civel');
        habilitarSelect('sub_assunto_atend_civel');
    }
    else{
        $("#id_atend_civel").val($("#id_atend_civel option[value='33']").val());
        funcAtentim_civel();         
    }
    cont++;
    assunto41=1;
}
function exibirAssunto43(){
    if(sub_assunto_faz_pub==2){
        funcAlternar('#sub_assunto_fazenda_publica');
        habilitarSelect('sub_assunto_fazenda_publica');
    }
    else{
        $("#id_faz_pub").val($("#id_faz_pub option[value='34']").val());
        funcFazenda_publica();            
    }
    cont++;
    assunto43=1;
}
function exibirAssunto44(){
    if(sub_assunto_desmarc==2){
        funcAlternar('#sub_assunto_desmarcar_agend');
        habilitarSelect('sub_assunto_desmarcar_agend');
    }
    else{
        $("#id_desmarcar").val($("#id_desmarcar option[value='31']").val());
        funDesmarcar_agendamento();          
    }
    cont++;
    assunto44=1;  
}
function exibirAssunto42(){
    funcAlternar('#sub_assunto_consumidor');
    habilitarSelect('sub_assunto_consumidor');
    cont++;
    assunto42=1;
}
function exibirAssunto01(){
    if(sub_assunto_adocao==2){
        funcAlternar('#sub_assunto_adocao');
        habilitarSelect('sub_assunto_adocao');
    }
    else{
        $("#id_adocao").val($("#id_adocao option[value='51']").val());
        funAdocao();          
    }
    cont++;  
    assunto01=1;
}
function exibirAssunto26(){
    if(sub_assunto_viagem_inter==2){
        funcAlternar('#sub_assunto_viagem_internacional');
        habilitarSelect('sub_assunto_viagem_internacional');
    }
    else{
        $("#id_viagem_inter").val($("#id_viagem_inter option[value='52']").val());
        funViagem_inter();
    }
    cont++;  
    assunto26=1;
}
function exibirAssunto02(){
    funcAlternar('#sub_assunto_alvara_judicial');
    habilitarSelect('sub_assunto_alvara_judicial');
    cont++;
    assunto02=1;
}
function exibirAssunto05(){
    if(sub_assunto_exame_dna==2){
        funcAlternar('#sub_assunto_exame_dna');
        habilitarSelect('sub_assunto_exame_dna');
    }
    else{
        $("#id_exame_dna").val($("#id_exame_dna option[value='57']").val());
        funExameDna();          
    }
    cont++;  
    assunto05=1;   
}
function exibirAssunto27(){
    if(sub_ass_idoso==2){
        funcAlternar('#sub_assunto_idoso');
        habilitarSelect('sub_assunto_idoso');
    }
    else{
        $("#id_idoso").val($("#id_idoso option[value='58']").val());
        funcIdoso();
    }
    cont++;
    assunto27=1;   
}
function exibirAssunto11(){
    if(sub_assunto_regulamentacao==2){
        funcAlternar('#sub_assunto_regulamentacao_visita');
        habilitarSelect('sub_assunto_regulamentacao_visita');
    }
    else{
        $("#id_regul_visita").val($("#id_regul_visita option[value='59']").val());
        funcRegulamentacao_visita();            
    }
    cont++;
    assunto11=1;    
}
function exibirAssunto47(){
    funcAlternar('#sub_assunto_locacao_imovel');
    habilitarSelect('sub_assunto_locacao_imovel');
    cont++;
    assunto47=1;    
}
//[Exibir Assunto Selecionado]++++++++++++++++++++++++++++++++++++++++++++++++
function exibirAssunto(){

    var alerta_error = document.getElementById("alerta_error");
    var alerta_ia = document.getElementById("alerta_ia");      
    alerta_error.style.display = 'none';
    alerta_ia.style.display = 'none'; 
    enviar_relato(); 
    habilitar_text_area();
    limparAssunto();

    var a = document.getElementById('valor_predicao');
    a.value = valorPredicao;  
    
    if(document.getElementById('filtro_assunto').checked){
        limparFiltro(); 
        habilitar_text_area(); 
    }
    var val = $('#nome_assunto option:selected').val();
    var assunto = $('#nome_assunto option:selected').text();
    //Caso o assistido edite o assunto do relato que a IA escolheu e escolha o mesmo assunto que a Ia selecionou.
    if(assunto == RespostaIa){
        clicouNaIA = 1; 
        valorPredicao = valorPredicaoAnterior;
    }else{
        a.value = 0;
    }
    if(val==36)exibirAssunto36();
    else if(val==9)exibirAssunto09();
    else if(val==37)exibirAssunto37();
    else if(val==38)exibirAssunto38();
    else if(val==39)exibirAssunto39();       
    else if(val==40)exibirAssunto40();             
    else if(val==45)exibirAssunto45();        
    else if(val==41)exibirAssunto41();        
    else if(val==43)exibirAssunto43();
    else if(val==44)exibirAssunto44();
    else if(val==42)exibirAssunto42();
    else if(val==1)exibirAssunto01();
    else if(val==26)exibirAssunto26();
    else if(val==2)exibirAssunto02();
    else if(val==5)exibirAssunto05();
    else if(val==27)exibirAssunto27();
    else if(val==11)exibirAssunto11();
    else if(val==47)exibirAssunto47();
}
//============================================================================
function exibealerta(){
    $('').on(function(){
        $('').show("slow");
    });
}
function chekBox(){
    $('#formulario').submit(function(e){
        if(!$('[type=checkbox]').is(':checked')) {
            e.preventDefault();
            $('#esconderAlerta').fadeIn("slow");
            setTimeout(function () {
            $('#esconderAlerta').fadeOut("slow");
            }, 3000);
        } else {
            $('button[type=submit], input[type=submit]').prop('disabled',true);
        }
    });
}
function countChar(input) {
    var len = input.value.length;
    if (len >= 1000) {
        input.value = input.value.substring(0, 1000);
    }
    $('#charCounter').text(len);
};
//[Alternar valor do Display]+++++++++++++++++++++++++++++++++++++++++++++++++
function funcAlternar(a){
    $(a).toggle("slow");
}
//[Limpar Sub Assunto ++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function limparSubAssuntoFalec_famil(){
    if(count==1){
        if(sa21==1){
            $('#tra_her').toggle();
            limparInput('tra_her');
            limparCheckbRadio('tra_her');
            desabilitarInput('tra_her');
            sa21=0;
        }
        else if(sa22==1){
            $('#seg_via').toggle();
            limparCheckbRadio('seg_via');
            desabilitarInput('seg_via');
            sa22=0;
        }
        else if(sa2x==1){
            sa2x=0;
        }
        count=0;
    }
}
function limparSubAssuntoAlimento_Beneficiario(){
    if(count6==1){
        if(sa30_1==1){
            $('#tra_alimFilhoMenor').toggle("slow");
            limparCheckbRadio('tra_alimFilhoMenor');
            desabilitarInput('tra_alimFilhoMenor');
            sa30_1=0;
        }
        else if(sa30_2==1){
            $('#tra_alimEsposaEsposo').toggle("slow");
            limparCheckbRadio('tra_alimEsposaEsposo');
            desabilitarInput('tra_alimEsposaEsposo');
            sa30_2=0;
        }
        else if(sa30_x==1){
            sa30_x=0;
        }
        count6=0;
    }
}
function limparSubAssuntoAlimento(){
    if(count5==1){
        if(sa30==1){
            $('#tra_alimentos').toggle();
            $('#alim-deter-0').click();
            limparCheckbRadio('id_alim_deter');
            desabilitarInput('id_alim_deter');
            limparSubAssuntoAlimento_Beneficiario();
            limparSelect('tp_do_benef');
            desabilitarSelect('tp_do_benef');
            count5=0;
            sa30=0;
        }
        else if(sa3x==1){
            count5=0;
            sa3x=0;
        }
    }
}
function limparSubAssuntoCasamento_Divorcio(){
    if(count2==1){
        if(sa38==1){
            $('#sub_deseja_casar').toggle();
            $('#noivos-maiores-1').click();
            limparCheckbRadio('tra_casar');
            desabilitarInput('tra_casar');
            count2=0;
            sa38=0;
        }
        else if(sa39==1){
            $('#tra_Divor_Separ').toggle();
            limparCheckbRadio('tra_Divor_Separ');
            desabilitarInput('tra_Divor_Separ');
            count2=0;
            sa39=0;
        }
        else if(sa40==1){
            $('#tra_Problema_certidao_casamento').toggle();
            limparSelect('tpccto');
            limparCheckbRadio('casba');
            desabilitarSelect('tpccto');
            desabilitarInput('casba');
            count2=0;
            sa40=0;
        }
        else if(sa41==1){
            $('#tra_Documento_uniao_estavel').toggle();
            $('#obter-beneficio-0').click();
            limparCheckbRadio('comp_vivo');
            limparCheckbRadio('casal_junto');
            limparCheckbRadio('finalidade_comp');
            desabilitarInput('comp_vivo');
            desabilitarInput('casal_junto');
            desabilitarInput('finalidade_comp');
            count2=0;
            sa41=0;
        }
        else if(sa37x==1){
            count2=0;
            sa37x=0;
        }
    } 
}
function limparSubAssuntoPlanoSaude(){
    if(count4==1){
        if(sa29==1){
            $('#sub_planoSaude').toggle();
            limparInput('sub_planoSaude');
            desabilitarInput('sub_planoSaude');
            count4=0;
            sa29=0;
        }
        else if(sa42x==1){
            count4=0;
            sa42x=0;
        }
    }    
}
function limparSubAssuntoSaude(){
    if(count11==1){
        if(sa35==1){
            $('#tra_saude').toggle();
            $('#plansaud-0').click();
            limparSelect('tp_sde');
            limparCheckbRadio('tpsde');
            desabilitarSelect('tp_sde');
            desabilitarInput('tpsde');
            count11=0;
            sa35=0;
        }
        else if(sa45x==1){
            count11=0;
            sa45x=0;
        }
    }
}
function limparSubAssuntoFazenda_publica(){//[Ações contra o Estado / Municipio]
    if(count10==1){
        if(sa34==1){
            $('#tra_faz_pub').toggle();
            $('#recebeu-intimacao-0').click();
            limparSelect('a_faz_publica');
            limparCheckbRadio('intimacao_processo');
            desabilitarSelect('a_faz_publica');
            desabilitarInput('intimacao_processo');
            count10=0;
            sa34=0;
        }
        else if(sa43x==1){
            count10=0;
            sa43x=0;
        }
    }    
}
function limparSubAssuntoAtentim_civel(){//[Registros Públicos / Certidões]
    if(count9==1){
        if(sa33==1){
            $('#tra_atend_civel').toggle();
            limparSelect('tra_atend_civel');
            desabilitarSelect('tra_atend_civel');
            count9=0;
            sa33=0;
        }
        else if(sa41x==1){
            count9=0;
            sa41x=0;
        }
    }
}
function limparSubAssuntoDesmarcar_agendamento(){
    if(count7==1){
        if(sa31==1){
            $('#tra_desmarcar').toggle();
            limparInput('tra_desmarcar');
            desabilitarInput('tra_desmarcar');
            limparSelect('tra_desmarcar');
            desabilitarSelect('tra_desmarcar');
            count7=0;
            sa31=0;
        }
        else if(sa44x==1){
            count7=0;
            sa44x=0;
        }
    }    
}
function limparSubAssuntoAdocao(){
    if(count14==1){
        if(sa51==1){
            $('#tra_adocao').toggle();
            limparInput('tra_adocao');
            desabilitarInput('tra_adocao');
            count14=0;
            sa51=0;
        }
        else if(sa01x==1){
            count14=0;
            sa01x=0;
        }
    }    
}
function limparSubAssuntoExameDna(){
    if(count17==1){
        if(sa57==1){
            $('#tra_exame_dna').toggle();
            limparCheckbRadio('tra_exame_dna');
            desabilitarInput('tra_exame_dna');
            count17=0;
            sa57=0;
        }
        else if(sa05x==1){
            count17=0;
            sa05x=0;
        }
    }    
}
function limparSubAssuntoViagem_inter(){
    if(count15==1){
        if(sa52==1){
            $('#tra_viagem_inter').toggle();
            limparInput('tra_viagem_inter');
            limparCheckbRadio('tra_viagem_inter');
            desabilitarInput('tra_viagem_inter');
            count15=0;
            sa52=0;
        }
        else if(sa26x==1){
            count15=0;
            sa26x=0;  
        }
    }    
}
function limparSubAssuntoViolenciaDomestica(){
    if(count8==1){
        if(sa32==1){
            $('#tra_violencia').toggle();
            limparSelect('tra_violencia');
            limparCheckbRadio('tra_violencia');
            desabilitarInput('tra_violencia');
            desabilitarSelect('tra_violencia');
            count8=0;
            sa32=0;
        }
        else if(sa38x==1){
            count8=0;
            sa38x=0;
        } 
    }    
}
function limparSubAssuntoIdoso(){
    if(count18==1){
        if(sa58==1){
            $('#tra_idoso').toggle();
            limparSelect('tra_idoso');
            limparCheckbRadio('tra_idoso');
            desabilitarInput('tra_idoso');
            desabilitarSelect('tra_idoso');
            count18=0;
            sa58=0; 
        }
        else if(sa27x==1){
            count18=0;
            sa27x=0;
        }
    }   
}
function limparSubAssuntoUsucapiao(){
    if(count12==1){
        if(sa36==1){
            $('#tra_usucapiao').toggle();
            limparCheckbRadio('tp_usucap');
            desabilitarInput('tp_usucap');
            count12=0;
            sa36=0;
        }
        else if(sa40x==1){
            count12=0;
            sa40x=0; 
        } 
    }    
}
function limparSubAssuntoTrabalhista_vinculo(){
    if(count13==1){
        if(sa37_4==1){
            $('#tra_clt').toggle("slow");
            $('#relato').toggle("slow");
            $('#solic_horario').toggle("slow");
            $('#enviar_Form').toggle("slow");
            count13=0;
            sa37_4=0;
        }
        else if(sa37_x==1){
            $('#sol_vinc_tb').toggle();
            limparCheckbRadio('sol_vinc_tb');
            desabilitarInput('sol_vinc_tb');
            count13=0;
            sa37_x=0;
        }
        else if(sa37_xx==1){
            count13=0;
            sa37_xx=0;
        }
    }
}
function limparSubAssuntoTrabalhista(){
    if(count3==1){
        if(sa37==1){
            $('#tra_trabalhista').toggle();
            limparSubAssuntoTrabalhista_vinculo();
            limparSelect('vinc_trabalhista');
            desabilitarSelect('vinc_trabalhista');
            count3=0;
            sa37=0;
        }
        else if(sa39x==1){
            count3=0;
            sa39x=0; 
        }
    }    
}
function limparSubAssuntoAlvara(){
    if(count16==1){
        if(sa55==1){
            $('#tra_her_alvara').toggle();
            limparInput('tra_her_alvara');
            limparCheckbRadio('tra_her_alvara');
            desabilitarInput('tra_her_alvara');
            count16=0;
            sa55=0;
        }
        else if(sa02x==1){
            count16=0;
            sa02x=0;
        }
    }   
}
function limparSubAssuntoRegulamentacao_visita(){
    if(count19==1){
        if(sa59==1){
            $('#tra_regul_visita').toggle();
            $('#visita-determinada-0').click();
            limparCheckbRadio('tra_regul_visita');
            desabilitarInput('id_visita_determinada');
            desabilitarInput('id_decisao_justica');
            count19=0;
            sa59=0;
        }
        else if(sa11x==1){
            count19=0;
            sa11x=0;
        }
    }    
}
//Exibir Sub Assunto]+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function funcFalec_famil(){
    limparSubAssuntoFalec_famil();
    var val = $('#ffi option:selected').val();
    if (val==21){
        funcAlternar('#tra_her');
        habilitarInput('tra_her');
        count++;
        sa21=1;
    }else if (val==22){
        funcAlternar('#seg_via');
        habilitarInput('seg_via');
        count++;
        sa22=1;
    }else{
        count++;
        sa2x=1;
    }
}
function funcAlimentos(){
    limparSubAssuntoAlimento();
    var val = $('#id_alimentos option:selected').val();
    if (val==30){
        funcAlternar('#tra_alimentos');
        habilitarInput('id_alim_deter');
        habilitarSelect('tp_do_benef');
        count5++;
        sa30=1;
    }
    else{
        count5++;
        sa3x=1;
    }
    $('#alim-deter-1').click(function(){
        if(numero==0){
            funcAlternar('#tra_alimSim');
            habilitarInput('tra_alimSim');
            numero=1;
        }
    });
    $('#alim-deter-0').click(function(){
        if(numero==1){
            funcAlternar('#tra_alimSim');
            limparCheckbRadio('tra_alimSim');
            desabilitarInput('tra_alimSim');
            numero=0;
        }
    });
}
function funcAlimentos_beneficiario(){
    limparSubAssuntoAlimento_Beneficiario();
    var val = $('#nome_beneficiario option:selected').val();
    if (val==1){
        funcAlternar('#tra_alimFilhoMenor');
        habilitarInput('tra_alimFilhoMenor');
        $('#crianca-nasceu-1').click(function(){
            if(numero==1){
                funcAlternar('#tra_mesesGestacao');
                limparInput('tra_mesesGestacao');
                desabilitarInput('tra_mesesGestacao');
                numero=0;
            }else{
                funcAlternar('#tra_mesesGestacao');
                habilitarInput('tra_mesesGestacao');
            }
        });
        $('#crianca-nasceu-0').click(function(){
            if(numero==0){

                funcAlternar('#tra_mesesGestacao');
                habilitarInput('tra_mesesGestacao');
                numero=1;
            }else{
                funcAlternar('#tra_mesesGestacao');
                limparInput('tra_mesesGestacao');
                desabilitarInput('tra_mesesGestacao');
            }
        });
        count6++;
        sa30_1=1;
    }
    else if (val==2){
        funcAlternar('#tra_alimEsposaEsposo');
        habilitarInput('tra_alimEsposaEsposo');
        count6++;
        sa30_2=1;
    }
    else{
        count6++;
        sa30_x=1;        
    }
}
function funcCasamento_Divorcio(){
    limparSubAssuntoCasamento_Divorcio();
    var val = $('#cuds option:selected').val();
    if (val==38){
        funcAlternar('#sub_deseja_casar');
        habilitarInput('tra_casar');
        count2++;
        sa38=1;
    }else if (val==39){
        funcAlternar('#tra_Divor_Separ');
        habilitarInput('tra_Divor_Separ');
        count2++;
        sa39=1;
    }else if (val==40){
        funcAlternar('#tra_Problema_certidao_casamento');
        habilitarSelect('tpccto');
        habilitarInput('casba');
        count2++;
        sa40=1;
    }else if (val==41){
        funcAlternar('#tra_Documento_uniao_estavel');
        habilitarInput('comp_vivo');
        habilitarInput('casal_junto');
        habilitarInput('finalidade_comp');
        count2++;
        sa41=1;
    }else{
        count2++;
        sa37x=1;
    }
    $('#noivos-maiores-0').click(function(){
        if(numero==0){
            funcAlternar('#noivoDeMenor');
            habilitarInput('noivoDeMenor');
            numero=1;
        }
    });
    $('#noivos-maiores-1').click(function(){
        if(numero==1){
            funcAlternar('#noivoDeMenor');
            limparCheckbRadio('noivoDeMenor');
            desabilitarInput('noivoDeMenor');
            numero=0;
        }
    });
    $('#obter-beneficio-1').click(function(){
        if(numero==0){
            funcAlternar('#obter_beneficio_previd');
            habilitarInput('obter_beneficio_previd');
            numero=1;
        }
    });
    $('#obter-beneficio-0').click(function(){
        if(numero==1){
            funcAlternar('#obter_beneficio_previd');
            limparCheckbRadio('obter_beneficio_previd');
            desabilitarInput('obter_beneficio_previd');
            numero=0;
        }
    });
}
function funcPlanoSaude(){
    limparSubAssuntoPlanoSaude();
    var val = $('#id_cons option:selected').val();
    if (val==29){
        funcAlternar('#sub_planoSaude');
        habilitarInput('sub_planoSaude');
        count4++;
        sa29=1;
    }
    else{
        count4++;
        sa42x=1;
    }
}
function funcSaude(){
    limparSubAssuntoSaude();
    var val = $('#id_saude option:selected').val();
    if (val==35){
        funcAlternar('#tra_saude');
        habilitarSelect('tp_sde');
        habilitarInput('tpsde');
        count11++;
        sa35=1;
    }
    else{
        count11++;
        sa45x=1;
    }
    $('#plansaud-1').click(function(){
        if(numero==0){
            funcAlternar('#tra_plano');
            habilitarInput('tra_plano');
            numero=1;
        }
    });
    $('#plansaud-0').click(function(){
        if(numero==1){
            funcAlternar('#tra_plano');
            limparInput('tra_plano');
            desabilitarInput('tra_plano');
            numero=0;
        }
    });
}
function funcFazenda_publica(){//[Ações contra o Estado / Municipio]
    limparSubAssuntoFazenda_publica();
    var val = $('#id_faz_pub option:selected').val();
    if (val==34){
        funcAlternar('#tra_faz_pub');
        habilitarSelect('a_faz_publica');
        habilitarInput('intimacao_processo');
        count10++;
        sa34=1;
    }
    else{
        count10++;
        sa43x=1;
    }
    $('#recebeu-intimacao-1').click(function(){
        if(numero==0){
            funcAlternar('#tra_intima_proc');
            habilitarInput('tra_intima_proc');
            numero=1;
        }
    });
    $('#recebeu-intimacao-0').click(function(){
        if(numero==1){
            funcAlternar('#tra_intima_proc');
            limparInput('tra_intima_proc');
            desabilitarInput('tra_intima_proc');
            numero=0;
        }
    });
}
function funcAtentim_civel(){//[Registros Públicos / Certidões]
    limparSubAssuntoAtentim_civel();
    var val = $('#id_atend_civel option:selected').val();
    if (val==33){
        funcAlternar('#tra_atend_civel');
        habilitarSelect('tra_atend_civel');
        count9++;
        sa33=1;
    }
    else{
        count9++;
        sa41x=1;
    }
}
function funDesmarcar_agendamento(){
    limparSubAssuntoDesmarcar_agendamento();
    var val = $('#id_desmarcar option:selected').val();
    if (val==31){
        funcAlternar('#tra_desmarcar');
        habilitarInput('tra_desmarcar');
        habilitarSelect('tra_desmarcar');
        limparSelect('tra_desmarcar');
        count7++;
        sa31=1;
    }
    else{
        count7++;
        sa44x=1;        
    }
}
function funAdocao(){
    limparSubAssuntoAdocao();
    var val = $('#id_adocao option:selected').val();
    if (val==51){
        funcAlternar('#tra_adocao');
        habilitarInput('tra_adocao');
        count14++;
        sa51=1;
    }
    else{
        count14++;
        sa01x=1;
    }
}
function funExameDna(){
    limparSubAssuntoExameDna();
    var val = $('#id_exame_dna option:selected').val();
    if (val==57){
        funcAlternar('#tra_exame_dna');
        habilitarInput('tra_exame_dna');
        count17++;
        sa57=1;
    }
    else{
        count17++;
        sa05x=1;
    }
}
function funViagem_inter(){
    limparSubAssuntoViagem_inter();
    var val = $('#id_viagem_inter option:selected').val();
    if(val==52){
        funcAlternar('#tra_viagem_inter');
        habilitarInput('tra_viagem_inter');
        count15++;
        sa52=1;
    }
    else{
        count15++;
        sa26x=1;
    }
}
function funcViolenciaDomestica(){
    limparSubAssuntoViolenciaDomestica();
    var val = $('#id_viol_domest option:selected').val();
    if (val==32){
        funcAlternar('#tra_violencia');
        habilitarSelect('tra_violencia');
        habilitarInput('tra_violencia');
        count8++;
        sa32=1;
    }
    else{
        count8++;
        sa38x=1;
    }
}
function funcIdoso(){
    limparSubAssuntoIdoso();
    var val = $('#id_idoso option:selected').val();
    if (val==58){
        funcAlternar('#tra_idoso');
        habilitarSelect('tra_idoso');
        habilitarInput('tra_idoso');
        count18++;
        sa58=1;
    }
    else{
        count18++;
        sa27x=1;
    }
}
function funcUsucapiao(){
    limparSubAssuntoUsucapiao();
    var val = $('#id_usuc option:selected').val();
    if (val==36){
        funcAlternar('#tra_usucapiao');
        habilitarInput('tp_usucap');
        count12++;
        sa36=1;
    }
    else{
        count12++;
        sa40x=1;       
    }
}
function funcTrabalhista(){
    limparSubAssuntoTrabalhista();
    var val = $('#id_trabalhista option:selected').val();
    if (val==37){
        funcAlternar('#tra_trabalhista');
        habilitarSelect('vinc_trabalhista');
        count3++;
        sa37=1;
    }
    else{
        count3++;
        sa39x=1;
    }
}
function funcVinculoTrabalhista(){
    limparSubAssuntoTrabalhista_vinculo();
    var val = $('#tipo_vinculo option:selected').val();
    if(val==4){
        funcAlternar('#tra_clt');
        $('#relato').toggle("slow");
        $('#solic_horario').toggle("slow");
        $('#enviar_Form').toggle("slow");
        count13++;
        sa37_4=1;
    }
    else if (val!==""){
        funcAlternar('#sol_vinc_tb');
        habilitarInput('sol_vinc_tb');
        count13++;
        sa37_x=1;
    }
    else{
        count13++;
        sa37_xx=1;
    }
}
function funcAlvara(){
    limparSubAssuntoAlvara();
    var val = $('#id_alvara option:selected').val();
    if (val==55){
        funcAlternar('#tra_her_alvara');
        habilitarInput('tra_her_alvara');
        count16++;
        sa55=1;
    }else{
        count16++;
        sa02x=1;
    }
}
function funcRegulamentacao_visita(){
    limparSubAssuntoRegulamentacao_visita();
    var val = $('#id_regul_visita option:selected').val();
    if (val==59){
        funcAlternar('#tra_regul_visita');
        habilitarInput('id_visita_determinada');
        habilitarInput('id_decisao_justica');
        count19++;
        sa59=1;
    }
    else{
        count19++;
        sa11x=1;
    }
    $('#visita-determinada-1').click(function(){
        if(numero==0){
            funcAlternar('#tra_visita_determinada');
            habilitarInput('tra_visita_determinada');
            numero=1;
        }
    });
    $('#visita-determinada-0').click(function(){
        if(numero==1){
            funcAlternar('#tra_visita_determinada');
            limparCheckbRadio('tra_visita_determinada');
            desabilitarInput('tra_visita_determinada');
            numero=0;
        }
    });    
}
//[habilitar Campos Formulário]+++++++++++++++++++++++++++++++++++++++++++++++
function habilitarInput(idDiv){
    var form = document.getElementById(idDiv);
    inputs = form.querySelectorAll('input');
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].disabled = false;
    }
}
function habilitarSelect(idDiv){
    var form = document.getElementById(idDiv);
    var selects = form.querySelectorAll('select');
    for (var i = 0; i < selects.length; i++) {
        selects[i].disabled = false;
    }
}
//[Desabilitar Campos Formulário]+++++++++++++++++++++++++++++++++++++++++++++
function desabilitarInput(idDiv){
    var form = document.getElementById(idDiv);
    inputs = form.querySelectorAll('input');
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].disabled = true;
    }
}
function desabilitarSelect(idDiv){
    var form = document.getElementById(idDiv);
    var selects = form.querySelectorAll('select');
    for (var i = 0; i < selects.length; i++) {
        selects[i].disabled = true;
    }
}
//[Limpar Input do Type: text, password, date, etc]+++++++++++++++++++++++++++
function limparInput(idDiv){
    var form = document.getElementById(idDiv);
    var inputs = form.querySelectorAll('input');
    for (var i = 0; i < inputs.length; i++) {
        if ((inputs[i].type != 'checkbox') && (inputs[i].type != 'radio')) {
            inputs[i].value = '';
        }
    }
}
//[Limpar Textarea]+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function limparTextarea(idDiv){
    var form = document.getElementById(idDiv);
    var textarea = form.querySelectorAll('textarea');
    for (var i = 0; i < textarea.length; i++) {
        textarea[i].value = '';
    }
}
//[Limpar Checkboxes e Radios]++++++++++++++++++++++++++++++++++++++++++++++++
function limparCheckbRadio(idDiv){
    var form = document.getElementById(idDiv);
    inputs = form.querySelectorAll('input[type=checkbox], input[type=radio]');
    for (var i = 0; i < inputs.length; i++) {
        inputs[i].checked = false;
    }
}
//[Limpar Selects]++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
function limparSelect (idDiv){
    var form = document.getElementById(idDiv);
    var selects = form.querySelectorAll('select');
    for (var i = 0; i < selects.length; i++) {
        var options = selects[i].querySelectorAll('option');
        if (options.length > 0)selects[i].value = options[0].value;
    }
}

//script do botão sem modal          
$(document).ready(() =>{
    $("#enviar_relato_btn_ia").on("click", (e)=>{
        validar_relato();
        if(resposta == 0){                                      
            if(document.getElementById('filtro_assunto').checked){ 
                limparFiltro();
            }
            var relato = $("#relato-textarea").val();      
            var re = /[^A-zÀ-ú ]/g;                                
            var relato_txt = relato.replace(re, "");
            if(relato_txt == ""){            
            
            }                                
            bloquear_text_area();
                                        
            const options = {
                method: 'POST',
                mode: 'cors',
                cache: 'default'
            }       
            value_pred = 0;                         
    
            fetch(`https://ai.defensoria.ba.def.br/resultado/${relato_txt}`, options)
            .then(response => {
                response.json().then(
                    data => showData_assunto2(data.Predicao, data.Value_pred)
                    )                                    
            })
            .catch(e => mostrar_msg_error_connection())
        }
        else{
            resposta = 0;
        }
    });

    $("#editar_relato_btn_ia").click(function(){
        enviar_relato();
        alerta_ia.style.display = 'none';
        let area = document.getElementById("relato-textarea");
        area.removeAttribute("readOnly");
        $('#nome_assunto').prop("disabled", false);
        limparAssunto();
        limparSelect("tp_do_assunto"); 
    });
});

function validar_relato(){
    var relato = document.getElementById("relato-textarea");
    var len = relato.value.length;
    if (len < 30) {        
        $('#relato-textarea').tooltip('show');
        enviar_relato();
        resposta = 1;
    }
    else{
        $('#relato-textarea').tooltip('disable');
    }
}

function editar_relato(){
    editar_relato_ia.style.display = "block";
    enviar_relato_ia.style.display = "none";
} 
function enviar_relato(){
    editar_relato_ia.style.display = "none";
    enviar_relato_ia.style.display = "block";  

}


const showData_assunto2 = (resultado_api, value_pred) =>{
    if(value_pred <= 65){
        limparAssunto();
        limparSelect("tp_do_assunto");          
        mostrar_msg_error();
        habilitar_text_area();    
        var alerta_ia = document.getElementById("alerta_ia");
        alerta_ia.style.display = 'none';
        enviar_relato();  
    }else{
        editar_relato();
        valorPredicao = value_pred;  
        clicouNaIA = 1;
        var a = document.getElementById('valor_predicao');
        var b = document.getElementById('clicou_ia');

        a.value = valorPredicao;
        b.value = clicouNaIA;      

        var alerta_error = document.getElementById("alerta_error");
        alerta_error.style.display = 'none';        
        mostrar_msg_ia(resultado_api);
        valorPredicaoAnterior = value_pred;
        RespostaIa = resultado_api;
        if(value_pred >= 90){
            //console.log("É maior que 90%")
            $('#nome_assunto').prop("disabled", true);
        }
        limparAssunto();
        if(resultado_api == 'EXAME DE DNA'){
            $("#nome_assunto").val($("#nome_assunto option[value='5']").val());
            $("#selectAssuntoId").val("5");
            exibirAssunto05();
        }
        else if(resultado_api == 'ADOÇÃO'){
            $("#nome_assunto").val($("#nome_assunto option[value='1']").val());
            $("#selectAssuntoId").val("1");
            exibirAssunto01();
        }
        else if(resultado_api == 'ALVARÁ'){
            $("#nome_assunto").val($("#nome_assunto option[value='2']").val());
            $("#selectAssuntoId").val("2");
            exibirAssunto02();
        }
        else if(resultado_api == 'GUARDA'){
            $("#nome_assunto").val($("#nome_assunto option[value='6']").val());
            $("#selectAssuntoId").val("6");
        }
        else if(resultado_api == 'INTERDIÇÃO'){
            $("#nome_assunto").val($("#nome_assunto option[value='7']").val());
            $("#selectAssuntoId").val("7");
            //exibirAssunto07();
        }
        else if(resultado_api == 'PENSÃO ALIMENTÍCIA'){
            $("#nome_assunto").val($("#nome_assunto option[value='9']").val());
            $("#selectAssuntoId").val("9");
            exibirAssunto09();
        }
        else if(resultado_api == 'FALECIMENTO DE FAMILIAR/INVENTÁRIO'){
            $("#nome_assunto").val($("#nome_assunto option[value='36']").val());
            $("#selectAssuntoId").val("36");
            exibirAssunto36();
        }
        else if(resultado_api == 'CASAMENTO/UNIÃO ESTÁVEL/DIVÓRCIO/SEPARAÇÃO'){
            $("#nome_assunto").val($("#nome_assunto option[value='37']").val());
            $("#selectAssuntoId").val("37");
            exibirAssunto37();
        }
        else if(resultado_api == 'VIOLÊNCIA DOMÉSTICA'){
            $("#nome_assunto").val($("#nome_assunto option[value='38']").val());
            $("#selectAssuntoId").val("38");
            exibirAssunto38();
        }
        else if(resultado_api == 'POSSE/PROPRIEDADE'){
            $("#nome_assunto").val($("#nome_assunto option[value='46']").val());
            $("#selectAssuntoId").val("46");
        }
        else if(resultado_api == 'AÇÕES CONTRA O ESTADO/MUNICÍPIO'){
            $("#nome_assunto").val($("#nome_assunto option[value='43']").val());
            $("#selectAssuntoId").val("43");
            exibirAssunto43();
        }
        else if(resultado_api == 'SAÚDE'){
            $("#nome_assunto").val($("#nome_assunto option[value='45']").val());
            $("#selectAssuntoId").val("45");
            exibirAssunto45();
        }
        else if(resultado_api == 'USUCAPIÃO'){
            $("#nome_assunto").val($("#nome_assunto option[value='40']").val());
            $("#selectAssuntoId").val("40");
            exibirAssunto40();
        }
        else if(resultado_api == 'REGISTROS PÚBLICOS / CERTIDÕES'){
            $("#nome_assunto").val($("#nome_assunto option[value='41']").val());
            $("#selectAssuntoId").val("41");
            exibirAssunto41();
        }
        else if(resultado_api == 'CONSUMIDOR'){
            $("#nome_assunto").val($("#nome_assunto option[value='42']").val());
            $("#selectAssuntoId").val("42");
            exibirAssunto42();
        }
        else if(resultado_api == 'ACIDENTE DE TRABALHO'){
            $("#nome_assunto").val($("#nome_assunto option[value='16']").val());
            $("#selectAssuntoId").val("16");
            exibirAssunto16();
        }
        else if(resultado_api == 'DIREITO REAL DE LAJE'){
            $("#nome_assunto").val($("#nome_assunto option[value='48']").val());
            $("#selectAssuntoId").val("48");
            exibirAssunto48();
        }
        else if(resultado_api == 'LOCAÇÃO DE IMÓVEL'){
            $("#nome_assunto").val($("#nome_assunto option[value='47']").val());
            $("#selectAssuntoId").val("47");
            exibirAssunto47();
        }
        else if(resultado_api == 'VIZINHANÇA'){
            $("#nome_assunto").val($("#nome_assunto option[value='17']").val());  
            $("#selectAssuntoId").val("17");         
        }
        else if(resultado_api == 'VAGA EM CRECHE'){
            $("#nome_assunto").val($("#nome_assunto option[value='19']").val());     
            $("#selectAssuntoId").val("19");                                   
        }
        else if(resultado_api == 'VAGA NO ENSINO FUNDAMENTAL I E II'){
            $("#nome_assunto").val($("#nome_assunto option[value='20']").val());
            $("#selectAssuntoId").val("20");
        }
        else if(resultado_api == 'ADI-ESCOLAR (AGENTE DE DESENVOLVIMENTO INFANTIL)'){
            $("#nome_assunto").val($("#nome_assunto option[value='23']").val());
            $("#selectAssuntoId").val("23");
        }
        else if(resultado_api == 'AUSÊNCIA DE TRANSPORTE ESCOLAR GRATUITO'){
            $("#nome_assunto").val($("#nome_assunto option[value='25']").val());
            $("#selectAssuntoId").val("25");
        }
        else if(resultado_api == 'AUTORIZAÇÃO PARA VIAGEM INTERNACIONAL'){
            $("#nome_assunto").val($("#nome_assunto option[value='26']").val());
            $("#selectAssuntoId").val("26");
            exibirAssunto26();
        }
        else if(resultado_api == 'IDOSOS'){
            $("#nome_assunto").val($("#nome_assunto option[value='27']").val());
            $("#selectAssuntoId").val("27");
            exibirAssunto27();
        }
        else if(resultado_api == 'DESTITUIÇÃO DO PODER FAMILIAR'){
            $("#nome_assunto").val($("#nome_assunto option[value='24']").val());
            $("#selectAssuntoId").val("24");
        }
        else if(resultado_api == 'REGULAMENTAÇÃO DE VISITA'){
            $("#nome_assunto").val($("#nome_assunto option[value='11']").val());
            $("#selectAssuntoId").val("11");
            exibirAssunto11();
        }
    }

}

function mostrar_msg_error_audio($msg){    
    $('#alerta_error_audio').html($msg); 

    var alerta_error = document.getElementById("alerta_error_audio");

    if(alerta_error.style.display == 'none') {
        alerta_error.style.display = 'inline-flex';                                    
    } else {                             
        alerta_error.style.display = 'inline-flex';                          
    }                                                             
}

function mostrar_msg_error_connection(){    
    $('#alerta_error').html('Estamos enfrentando algum problema com nosso sistema de ' +
    'classificação, por favor selecione o assunto manualmente no campo informação sobre o assunto abaixo.' ); 

    var alerta_error = document.getElementById("alerta_error");

    habilitar_text_area();
    if(alerta_error.style.display == 'none') {
        alerta_error.style.display = 'inline-flex';                                    
    } else {                             
        alerta_error.style.display = 'inline-flex';                          
    }                                                             
}

function mostrar_msg_error(){    
    $('#alerta_error').html('Não conseguimos entender, explique melhor os acontecimentos '+ 
    'ou escolha o assunto na lista abaixo na seção de informações sobre o assunto. ' ); 

    var alerta_error = document.getElementById("alerta_error");

   
    if(alerta_error.style.display == 'none') {
        alerta_error.style.display = 'inline-flex';                                    
    } else {                             
        alerta_error.style.display = 'inline-flex';                          
    }                                                             
}

function mostrar_msg_ia(assunto){      
    $('#alerta_ia').html('Acreditamos que seja para ' + assunto  +
     ' que voce precisa de ajuda. Caso não seja, selecione manualmente no campo abaixo ou insira mais informações no relato.');

    var alerta_ia = document.getElementById("alerta_ia");

    if(alerta_ia.style.display == 'none') {
        alerta_ia.style.display = 'inline-flex';                                    
    } else {                             
        alerta_ia.style.display = 'inline-flex';                          
    }                                                             
}

function bloquear_text_area(){
    $("#relato-textarea").attr('readOnly','readOnly');  
}

function habilitar_text_area(){
    $("#relato-textarea").removeAttr('readOnly');
}


