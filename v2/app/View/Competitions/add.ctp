<h1 class="page-header">Opret konkurrence</h1>

<?php echo $this->Form->create(
    'Competition', 
    array(
        'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control',
        'label' => false,
        'error' => array('attributes' => array('before' => 'span', 'class' => 'label label-danger')),
        ),
        'class' => 'well',
        'type' => 'file'
    )
); ?>

<!-- title input -->
<?php echo $this->Form->input('title', array('label' => 'Title')); ?>

<!-- description input -->
<?php echo $this->Form->input('description', array('label' => 'Beskrivelse')); ?>

<!-- the file -->
<?php echo $this->Form->input('file', array('type' => 'file', 'label' => 'Billede', 'required' => true)); ?>

<!-- Submit Button -->
<p class="text-center" style="margin:0;">
<?php echo $this->html->tag(
        'button', 
        '<span class="glyphicon glyphicon-floppy-disk"></span> <span style="font-size:1.3em;">Gem</span>', 
        array(
                'class' => 'btn btn-md btn-primary', 
                'type' => 'submit', 
                'style' => 'width:200px;'
        )
    ); ?>
</p>

<?php echo $this->Form->end(); ?>