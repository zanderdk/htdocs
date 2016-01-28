<h1 class="page-header">Opret materiale</h1>

<?php echo $this->Form->create(
	'Material', 
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

<!-- name input -->
<?php echo $this->Form->input('name', array('label' => 'Navn')); ?>

<!-- type input -->
<?php echo $this->Form->input('type', array('label' => 'Type',
    'options' => array(
    	'yarn' => 'Garn',
		'needle' => 'Strikkepinde/Hælkenåle')
));?>

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