$(document).ready(function() {

    $('#DefinicaoNomeSocial').tooltip();

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


    $('#users-username').change(function(){
        $.get(
            "/users/cpf",
            {cpf: $(this).val()},
            function(data){   
                //console.log(data);             
                if(data.result)
                {
                    if (data.result.ja_tem_usuario) {
                        alert("Você já possui um usuário no sistema.");                        
                       // window.location.replace(window.location.origin+'/users/login?cpf='+data.result.cpf)
                    }
                    if(data.result.email != null){                        
                        alert("Detectamos que você já tem cadastro na defensoria. Informe/confirme seu e-mail.");
                        $("#users-email").val(data.result.email);
                        $("input[name='contato_ajax']").val(data.result.id_contato_email);
                        $("input[name='sigad_user']").val(data.result.id_assistido_ajax);
                        $("input[name='pessoa_user']").val(data.result.pessoa_user);
                    }
                                        
                }
            },
            'json'
        )
    })

    $('#users-email').change(function(){
        var email_informado = $(this).val();
        $.get(
            "/users/email",
            {email: $(this).val()},
            function(data){   
                //console.log(data);             
                if(data.result)
                {
                    if (data.result.ja_tem_email) {        
                        var frase = 'Já existe um cadastro vinculado ao e-mail "'+email_informado+'" no sistema.';                
                        alert(frase); 
                        $("#users-email").val('');     
                        $("#users-email").focus();             
                        
                    }                  
                                        
                }
            },
            'json'
        )
    })

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
