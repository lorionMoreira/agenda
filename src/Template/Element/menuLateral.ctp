<!-- Inicio menu lateral do sistema -->
<div class="col-md-2 col-lg-2 p-0 pl-1" style="background-color:#f0f0f0;"  id="menuLateral">
    <ul class="flex-column list-unstyled mt-4">
        <!--
            <li class=" p-0 pb-2">
                <a href="/notificaAssistidos" class="menuEsquerdo">
                    <img class="img-fluid" src="/img/icon_editarCad.png" alt="icone" style=" max-width: 100%; height: auto;">
                    <spam class="mt-2" style="margin-top: 15px">Caixa de Entrada</spam>
                </a>
            </li>

            <hr/>
        -->
        <li class=" p-0 pb-2">
            <a href="/users/<?= ($this->request->session()->read('User.sigad_user')) ? "edit" : "add" ?>" class="menuEsquerdo">
                <img class="img-fluid" src="/img/icon_editarCad.png" alt="icone" style=" max-width: 100%; height: auto;">
                <spam class="mt-2" style="margin-top: 15px">Meus Dados</spam>
            </a>
        </li>
        <hr/>
        <li class="p-0 pb-2">
            <a href="/CaixaEntrada" class="menuEsquerdo">
                <img class="img-fluid" src="/img/icon_cxEntrada.png" alt="icone" style=" max-width: 100%; height: auto;">
                <spam class="mt-2" style="margin-top: 15px">Caixa de Entrada</spam>
                <span style="height: 2px" id="notification"></span>
            </a>
        </li>
        <hr/>
        <li class="p-0 pb-2">
            <a href="/agendamentos/add" class="menuEsquerdo">
                <img class="img-fluid ml-sm"  src="/img/icon_novoAgen.png" alt="icone" style=" max-width: 100%; height: auto;">
                <spam class="mt-2" style="margin-top: 15px">Solicitar Agendamento</spam>
            </a>
        </li>
        <hr/>
        <li class="p-0 pb-2">
            <a href="/agendamentos/solicitacoes" class="menuEsquerdo">
                <img class="img-fluid" src="/img/solicitacoes.png" alt="icone" style=" max-width: 100%; height: auto;">
                <spam class="mt-2" style="margin-top: 15px">Minhas Solicitações</spam>
            </a>
        </li>
        <hr/>
        <li class="p-0 pb-2">
            <a href="/agendamentos" class="menuEsquerdo">
                <img class="img-fluid" src="/img/icon_acomAgenda.png" alt="icone" style=" max-width: 100%; height: auto;">
                <spam class="mt-2" style="margin-top: 15px">Agendamentos</spam>
            </a>
        </li>
        <hr/>
    </ul>
</div>

<!-- fim menu lateral do sistema -->