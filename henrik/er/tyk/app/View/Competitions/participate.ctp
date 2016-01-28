<h1><?php echo $competition['Competition']['title'];?></h1>
<?php echo $this->Html->image('/img/competitions/'.$competition['Competition']['id'].'.png', array('style' => 'width:100%;', 'class' => 'thumbnail'));?>
<p class="lead"><?php echo $competition['Competition']['description'];?></p>

<?php echo $this->Form->create(
    'Participant', 
    array(
        'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control',
        'label' => false,
        'error' => array('attributes' => array('before' => 'span', 'class' => 'label label-danger'))
        ),
        'class' => 'well',
    )
); ?>

<!-- hidden id input -->
<?php echo $this->Form->input('competition_id', array('value' => $competition['Competition']['id'], 'type' => 'hidden')); ?>

<!-- email input -->
<?php echo $this->Form->input('email_address', array('label' => 'Email', 'between' => '<div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-envelope"> </span></div>', 'after' => '</div>')); ?>

<!-- name input -->
<?php echo $this->Form->input('name', array('label' => 'Navn', 'between' => '<div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-user"> </span></div>', 'after' => '</div>')); ?>

<!-- phone_number input -->
<?php echo $this->Form->input('phone_number', array('label' => 'Tlf', 'between' => '<div class="input-group"><div class="input-group-addon"><span class="glyphicon glyphicon-earphone"> </span></div>', 'after' => '</div>')); ?>

<!-- Submit Button -->
<p class="text-center" style="margin:0;">
<?php echo $this->html->tag(
        'button', 
        '<span class="glyphicon glyphicon-floppy-disk"></span> <span style="font-size:1.3em;">Deltag</span>', 
        array(
                'class' => 'btn btn-md btn-primary', 
                'type' => 'submit', 
                'style' => 'width:200px;'
        )
    ); ?>
</p>

<?php echo $this->Form->end(); ?>