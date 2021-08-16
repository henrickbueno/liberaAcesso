$(function() {
    // FUNCAO DE CLICK PARA RECEBER O ID DO PROPRIO BOTAO CLICADO PELO METODO $(THIS);
    // TAMBEM CHAMA A FUNCAO PEGANDO POR PARAMETRO A VARIAVEL CONTENDO O ID, FAZENDO UM OPERADOR TERNARIO PARA ACTION E INFORMANDO O BOTAO PELO METODO $(THIS);
    $('.liberarAcesso, .bloquearAcesso').click(function () {
        var id = $(this).attr('data-id');
        liberaBloqueia(id, $(this).hasClass('bloquearAcesso')? 'bloquear' : 'liberar', $(this));
    })



    function liberaBloqueia(id, action, btn) {
        $.ajax({
            url: 'index.php',
            type: 'post',
            dataType: 'json',
            data: {id, action},
            beforeSend : function(){
                if (btn.hasClass('bloquearAcesso')) {
                    btn.html('BLOQUEANDO...')
                    btn.attr("disabled", true)
                }else{
                    btn.html('LIBERANDO...')
                    btn.attr("disabled", true)
                }
            },
            success: function (response) {
                if (response.resultado == true) {
                    alert("Acao realizada com sucesso!");
                    location.reload();
                }else{
                    alert('Nao foi possível completar o processo');
                }
                console.log(response);
            },
            error: function() {
                alert('nao funcionou');
            },
            complete: function(){
                if (btn.hasClass('bloquearAcesso')) {
                    btn.html('BLOQUEAR')
                    btn.attr("disabled", false)
                }else{
                    btn.html('LIBERAR')
                    btn.attr("disabled", false)
                }   
            }
        });
    }

    // ADICIONAR SETE DIAS 

    $('.adicionaSete').click(function () {
        var id = $(this).attr('data-id');
        adcSeteDias(id, $(this).hasClass('adicionaSete') ? 'adicionar' : 'desativar', $(this))
    })
});

    function adcSeteDias(id, action, btn) {
        $.ajax({
            url : 'index.php',
            type: 'post',
            dataType: 'json',
            data : {id, action},
            beforeSend : function () {
                if (btn.hasClass('adicionaSete')) {
                    btn.html('Liberando...')
                    btn.attr("disabled", true) 
                }         
            },
            success : function (response) {
                if (response.resultado == true) {
                    alert('Acao realizada com sucesso!');
                }else{
                    alert('Nao foi possivel adicionar mais dias!');
                }
                location.reload();
            },

            error : function () {
                alert('Algo errado aconteceu, tente novamente!');
            },
            complete : function () {
                if(btn.hasClass('adicionaSete')){
                    btn.html('Liberar Teste')
                    btn.attr("disabled", false); 
                } 
            }
        })


    }



    