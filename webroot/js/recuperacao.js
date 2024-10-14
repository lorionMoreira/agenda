$(function () {
    /*
    $('form').submit(function(event){
        if(! (grecaptcha && grecaptcha.getResponse().length !== 0)){
            alert('você deve marcar o captcha para recuperar a senha');
            event.preventDefault();
        }        
    });*/

    $('#cpf').change(function(){
        $.get(
            "/users/cpf",
            {cpf: $(this).val()},
            function(data){
                console.log(data);
                if(data.result)
                {
                    if (data.result.ja_tem_usuario) {
                        $('#cpf').attr('readonly', 'readonly');
                        if (data.result.concluiu_cadastro) {
                            $('#rowCadastroConcluido').show();
                            $('#nascimento').attr('required', 'required');
                            $('#nomeMae').attr('required', 'required');
                        } else {
                            $('#inputEmail').attr('required', 'required');
                            $('#colEmail').show();
                        }
                    } else {
                        var cadastrar = confirm("Não há cadastro com este CPF no sistema. Deseja realizar um cadastro agora?")
                        if (cadastrar) {
                            window.location.replace(window.location.origin+'/users/login?cpf='+data.result.cpf)
                        }
                    }
                }
            },
            'json'
        )
    })

    $('#nascimento').on('change', function () {
        if (!isValidDate($(this).val())) {
            this.setCustomValidity("Informe uma data válida (Exemplo: 30/06/1985)");
        } else {
            this.setCustomValidity('');
        }
    });

    function isValidDate(dateString) {
        var d = new Date(dateString.split('/').reverse().join('-'));
        return !isNaN(d.getDay());
    }
});