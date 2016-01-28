<h1 class="page-header">Opret forside artikel</h1>

<?php echo $this->Form->create(
	'FrontpageItem', 
	array(
		'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		'label' => false,
		'error' => array('attributes' => array('wrap' => 'span', 'class' => 'label label-danger')),
		),
		'class' => 'well',
		'type' => 'file'
	)
); ?>

<!-- url input -->
<?php echo $this->Form->input('url', array('label' => 'Link')); ?>

<!-- button_text input -->
<?php echo $this->Form->input('button_text', array('label' => 'Knap-tekst')); ?>
<p class="help-block">Den tekst der bliver vist på knappen på en forside artikel</p>

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