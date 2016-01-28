<h1 class="page-header">Tilføj variant til <?php echo $yarn['Yarn']['name']; ?></h1>

<?php echo $this->Form->create(
	'YarnVariant', 
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

<!-- yarn_id input -->
<?php echo $this->Form->input('yarn_id', array('value' => $yarn['Yarn']['id'], 'type' => 'hidden')); ?>

<!-- color_code input -->
<?php echo $this->Form->input('color_code', array('label' => 'Farvekode')); ?>

<!-- color input -->
<?php echo $this->Form->input('color_id', array('label' => 'Farve',
    'options' => $colors
));?>

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