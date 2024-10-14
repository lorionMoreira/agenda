$(document).ready(function(){

    $('.cpf').inputmask({
         mask: '999.999.999-99'
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

    $('.data').inputmask({
        mask: '99/99/9999'
    });

    $('#numProc').inputmask({
        mask: '9999999-99.9999.9.99.9999'
    });
    //$( ".data" ).datepicker($.datepicker.regional[ "pt-BR" ]);
})

$(document).ajaxStart(function() {
    $("body").addClass("loading")
});

$(document).ajaxStop(function() {
    $("body").removeClass("loading")
});
