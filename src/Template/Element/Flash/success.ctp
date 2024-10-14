<?php
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>



<div class="row justify-content-center mt-5 alerta">
    <div class="alert alert-success col-md-5 text-center alert-dismissible fade show" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <img src="/img/icon_sucesso.png" alt="" class="ml-5">
       <p class="h5 ml-5">Sucesso</p>
      <hr>
       <div class="message success h6" onclick="this.classList.add('hidden')"><?= $message ?></div>
    </div>
</div>
