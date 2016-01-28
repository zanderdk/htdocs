<h1 class="page-header">Reidger strikkepind</h1>

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

<!-- id input -->
<?php echo $this->Form->input('id', array('value' => $needle['Needle']['id'], 'type' => 'hidden')); ?>

<!-- name input -->
<?php echo $this->Form->input('name', array('label' => 'Navn', 'value' => $needle['Needle']['name'])); ?>

<!-- brand_id input -->
<?php echo $this->Form->input('brand_id', array('label' => 'Mærke',
    'options' => $brands,
    'default' => $needle['Needle']['brand_id']
));?>

<!-- menu_id input -->
<?php echo $this->Form->input('menu_id', array('label' => 'Menu',
    'options' => $menus,
    'default' => $needle['Needle']['menu_id']
));?>

<!-- material_id input -->
<?php echo $this->Form->input('material_id', array('label' => 'Materiale',
    'options' => $materials,
    'default' => $needle['Needle']['material_id']
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