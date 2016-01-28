<h1 class="page-header">Opret strikkepind</h1>

<?php echo $this->Form->create(
	'Needle', 
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

<!-- brand_id input -->
<?php echo $this->Form->input('brand_id', array('label' => 'Mærke',
    'options' => $brands
));?>

<!-- menu_id input -->
<?php echo $this->Form->input('menu_id', array('label' => 'Menu',
    'options' => $menus
));?>

<!-- material_id input -->
<?php echo $this->Form->input('material_id', array('label' => 'Materiale',
    'options' => $materials
));?>

<br/>

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