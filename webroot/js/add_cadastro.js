$(document).ready(function() {

    $('#DefinicaoNomeSocial').tooltip();

    $("#cidade").select2({
        placeholder: "Cidade",
        allowClear: true,
        width : 'style'
    });

    $('.data').inputmask({
        mask: '99/99/9999'
    });

    $('.cel').inputmask({
        mask: '(99) 99999-9999'
    });

    $('.tel').inputmask({
        mask: '(99) 9999-9999'
    });

    $('.cep').inputmask({
        mask: '99999-999'
    });


    validacaoTabs();
    validacaoSelect();

    $(".termos").click(function(){
        // Criar um elemento div que irá conter o popup
      const popup = document.createElement("div");
      const popupw = document.getElementById("popupw");
      popupw.classList.add("popup-wrapper")
  // Definir o conteúdo do popup
      popup.innerHTML = "<h3>Termos</h3> "+
      "<p> Em manifestação livre, informada e certa, conforme o art. 5º, inciso XII, da Lei 13.709/2018,"+
      " concordo com o tratamento dos dados pessoais acima listados pela Defensoria Pública do Estado da Bahia,"+
      "de acordo com os artigos 7° e 11º da referida legislação.</p>";
  // Estilizar o popup
      popup.style.position = "fixed";
      popup.style.top = "50%";
      popup.style.left = "50%";
      popup.style.transform = "translate(-50%, -50%)";
      popup.style.backgroundColor = "#fff";
      popup.style.padding = "70px";
      popup.style.width = "80%";
      popup.style.justifyContent = "center";
      popup.style.border = "1px solid rgb(208,212,212)";
      popup.style.borderRadius = "8px"
      popup.style.zIndex = 10 

      document.body.appendChild(popup);

      const closeButton = document.createElement("button");

      closeButton.innerHTML = "OK";
      
      closeButton.style.marginLeft = "10px";
      closeButton.style.width = "50px";
      closeButton.style.padding = "5px";
      closeButton.style.border = "1px solid #28a745";
      closeButton.style.borderRadius = "8px"
      closeButton.style.backgroundColor = "#28a745";
      closeButton.style.color = "#fff";
      closeButton.style.cursor = "pointer";
      closeButton.onclick = function() {
          popup.remove();
          popupw.classList.remove("popup-wrapper")
      };
      popup.appendChild(closeButton);
  })
    $('#enderecos-cep').change(function(){
        $.get(
            "/users/cep",
            {cep: $('#enderecos-cep').val()},
            function(data){
                if (data.result.error == undefined) {
                    var endereco  = data.result

                    $('#enderecos-estado').val(endereco.uf)
                    // $('#cidade').val(endereco.city).change()
                    if (endereco.city !== undefined) $('#cidade option').each(function (i, e) {
                        if ($(e).html().trim().toLowerCase() == endereco.city.toLowerCase()) {
                            $('#cidade').val(e.value).change();
                        }
                    });
                    $('#enderecos-bairro-descricao').val(endereco.district)
                    $('#enderecos-logradouro-descricao').val(endereco.street)
                }
            },
            'json'
        )
    })

    $('#nascimento').on('change', function () {
        if (!isValidDate($(this).val())) {
            this.setCustomValidity("Informe uma data válida");
        } else {
            this.setCustomValidity('');
        }
    });

    $('#contatos-celular').on('change', function () {
        var cel = $('#contatos-celular').val();        
        var tamanho = cel.length;
        
        if (tamanho != 15) {
            this.setCustomValidity("Informe o número completo do celular");
        } else {
            this.setCustomValidity('');
        }
    });

    $('#contatos-residencial').on('change', function () {
        var tel = $('#contatos-residencial').val();
        var tamanho = tel.length;
        //alert(tamanho);
        
        if (tamanho != 14) {
            this.setCustomValidity("Informe o número completo do telefone residencial");
        } else {
            this.setCustomValidity('');
        }
    });

    $('#contatos-comercial').on('change', function () {
        var com = $('#contatos-comercial').val();
        var tamanho = com.length;
        
        if (tamanho != 14) {
            this.setCustomValidity("Informe o número completo do telefone comercial");
        } else {
            this.setCustomValidity('');
        }
    });
});


function validacaoTabs() {
    $('.validacaoTab').on('click', function () {
        //armazenando valores dos inputs tab dados pessoais
        var nascimento = $('#nascimento').val();

        //armazenando valores dos inputs tab contato
        var email = $('#contatos-email').val();
        var cel = $('#contatos-celular').val();

        //armazenando valores dos inputs tab endereço
        var cidade = $('#cidade').val();

        if (!$('#nome')[0].checkValidity() || !$('#nomeMae')[0].checkValidity() || !isValidDate(nascimento)) {
            $('#dados-pessoais-tab').tab('show');
        } else if (email == "" || cel == "") {
            $('#contato-tab').tab('show');
        } else if (cidade == "") {
            $('#endereco-tab').tab('show');
        }
    });
}

function validacaoSelect() {
    $('.select2-selection').css({"border": "solid red 1px"});

    $("#cidade").change(function() {
        if($('#cidade option:selected').val()==""){
            $('.select2-selection').css({"border": "solid red 1px"});
        }else{
            $('.select2-selection').css({"border": "solid #28a745 1px"});
        }
    });
}

function isValidDate(dateString) {
    var regex = /^(0[1-9]|[12][0-9]|3[01])[/](0[1-9]|1[012])[/](19|20)\d\d$/;
    var d = new Date(dateString.split('/').reverse().join('-'));
    return regex.test(dateString) && !isNaN(d.getDay());
}
