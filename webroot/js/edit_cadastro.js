$(document).ready(function() {
    // $( "#nascimento" ).datepicker();

    $('#nascimento').on('change', function () {
        if (!isValidDate($(this).val())) {
            this.setCustomValidity("Informe uma data válida");
        } else {
            this.setCustomValidity('');
        }
    });


    function isValidDate(dateString) {
        var d = new Date(dateString.split('/').reverse().join('-'));
        return !isNaN(d.getDay());
    }

    $('#users-username').change(function(){
        $.get(
            "/users/cpf",
            {cpf: $(this).val()},
            function(data){
                if(data.result)
                {
                    if (data.result.ja_tem_usuario) {
                        alert("Você já possui um usuário no sistema.")
                        window.location.replace(window.location.origin+'/users/login?cpf='+data.result.cpf)
                    }
                }
            },
            'json'
        )
    })


    $('#confirmEmail').on('change', function () {
        if ($(this).val() !== $('#users-email').val()) {
            this.setCustomValidity("Emails digitados diferentes");
        } else {
            this.setCustomValidity('');
        }
    });

    $('#users-username').on('change', function () {
        if (!validaCPF(this.value)) {
            this.setCustomValidity("Informe um CPF válido!");
        } else {
            this.setCustomValidity('');
        }
    });

    $('#repetirSenha').on('change', function () {
        var senha = $('#senha').val();
        var repetirSenha = $('#repetirSenha').val();
        if (senha !== repetirSenha) {
            this.setCustomValidity("Senhas digitadas diferentes");
        } else {
            this.setCustomValidity('');
        }
    });

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


function validaCPF(cpf) {
    cpf = cpf.replace(/[^\d]+/g,'');
    if(cpf == '') return false;
    // Elimina CPFs invalidos conhecidos
    if (cpf.length != 11 ||
        cpf == "00000000000" ||
        cpf == "11111111111" ||
        cpf == "22222222222" ||
        cpf == "33333333333" ||
        cpf == "44444444444" ||
        cpf == "55555555555" ||
        cpf == "66666666666" ||
        cpf == "77777777777" ||
        cpf == "88888888888" ||
        cpf == "99999999999")
        return false;
    // Valida 1o digito
    add = 0;
    for (i=0; i < 9; i ++)
        add += parseInt(cpf.charAt(i)) * (10 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11)
        rev = 0;
    if (rev != parseInt(cpf.charAt(9)))
        return false;
    // Valida 2o digito
    add = 0;
    for (i = 0; i < 10; i ++)
        add += parseInt(cpf.charAt(i)) * (11 - i);
    rev = 11 - (add % 11);
    if (rev == 10 || rev == 11)
        rev = 0;
    if (rev != parseInt(cpf.charAt(10)))
        return false;
    return true;
}