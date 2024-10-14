<!-- Inicio Cabeçalho-->
<style>
  @media screen and (max-width: 950px) {
    #inicio{
      padding-top: 45px
    }
    
  }
</style>
<nav class="row navbar navbar-expand-lg navbar-light bg-dark" id="inicio">
  	<img src="/img/logo2.png" alt="Logo Defensoria" title="Logo Defensoria" class="py-2 img-fluid" id="logoDefensoria">
    <div class="d-block ml-2 col-md-auto">
       	 <p class="h1 mb-0 mt-2 text-white font-weight-bold" id="topicoPrincipal">Agendamento online</p>
         <p class="lead text-light" id="subTopico">Defensoria Pública do Estado da Bahia</p>
    </div>

   <button class="navbar-toggler ml-4 botao" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
<!--    <span class="navbar-toggler-icon"></span>-->
       <img src="/img/menu.png" alt="" class="d-inline-block align-center" id="menu-suspenso"></button>
   </button>

  <div class="collapse navbar-collapse justify-content-end " id="navbarSupportedContent">
       <div class="text-center">
         <ul class="navbar-nav flex-row">
             <a class="text-center px-3 menuNavegacao" href="/pages/home" style="text-decoration: none">
			     <img  class="mt-3" src="/img/icon_home.png" alt="Página Inicial" title="Inico" style=" max-width: 100%; height: auto;">
				 <p class="nav-link text-white h6">Início </p>
			 </a>
             <a class=" text-center px-3 menuNavegacao" target="_blank" href="http://www.defensoria.ba.def.br/fale-conosco" style=" text-decoration: none" >
			    <img class="mt-3" src="/img/icon_contato.png" alt="Contato"  title="Fale Conosco"style=" max-width: 100%; height: auto;">
				<p class="nav-link text-white h6">Fale conosco</p>
			 </a>
            <a class="text-center px-3 menuNavegacao"  href="/pages/home/#secao-ajuda" style="text-decoration: none">
			    <img class="mt-3" src="/img/icon_ajuda.png" alt="Ajuda" title="Ajuda" style=" max-width: 100%; height: auto;">
				<p class="nav-link text-white h6">Ajuda</p>
			</a>
            <?php if($this->request->session()->check('User')): ?>
            <a class="text-center px-3 menuNavegacao"  href="/users/logout" style="text-decoration: none">
			    <img class="mt-3" src="/img/logout.png" alt="Sair" title="Sair" style="max-width: 100%; height: auto;">
				<p class="nav-link text-white h6" style="margin-left: -0.5em">Sair </p>
			</a>
            <?php endif; ?>
         </ul>
       </div>
  </div>
</nav>
<div class="row bg-success pb-3"></div>

<!-- Fim Cabeçalho-->
