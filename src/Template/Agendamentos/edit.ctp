<?php
/**
 * @var \App\View\AppView $this
 */
?>

<!-- Nome tela do sistema localizado parte superior a direita  -->
   <div class="row justify-content-end">
        <div class="col-md-4 mr-3 bg-success py-2" style="border-radius: 0 0px 30px 30px ">
            <p class="h3 text-white text-center">Editar Agendamento</p>
        </div>
   </div>
<!-- Fim Nome tela do sistema -->

<div class="row  ">
    <div class=" col-md-3 w-25 mt-5 ml-5 ">
        <h4 class="py-3 pl-3  mb-0" id="titulo-agendar-atendimento">Editar atendimento</h4>
    </div>
</div>

<form class=" pt-5 px-5 pb-5 w-75  ml-5" id="formulario">
<?= $this->Form->create($agendamento); ?>
    <div class="row">
        <div class="form-group col-md-6">
            <?= $this->Form->control('comarca_id',[
                'label' => false,
                'empty' => 'Cidade',
                'class' => 'form-control',
                'options' => $comarcas,
                'value' => 256
            ]); ?>
        </div>
        <div class="form-group col-md-6">
            <?= $this->Form->control('assunto_id',[
                'label' => false,
                'empty' => 'Assunto',
                'class' => 'form-control',
                'options' => $especializadas,
                'value' => 48
            ]); ?>
        </div>
    </div>
    <div class="row">
        <div class="form-group col-md-6">
            <?= $this->Form->control('data',[
                'class' => 'form-control',
                'size' => 8,
                'options' => ['18/11/2017', '19/11/2017', '20/11/2017', '21/11/2017'],
                'value' => 0
            ]); ?>
        </div>
        <div class="form-group col-md-6">
            <?= $this->Form->control('hora',[
                'class' => 'form-control',
                'size' => 8,
                'options' => ['08:00', '08:30', '09:00', '09:30'],
                'value' => 1
            ]); ?>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            <?= $this->Form->control('observacao',[
                'label' => false,
                'placeholder' => 'Relate seu caso',
                'class' => 'form-control',
                'value' => 'Relato de um caso'
            ]); ?>
        </div>
    </div>



    <?= $this->Form->button(__('Agendar'), ['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>

</form>
