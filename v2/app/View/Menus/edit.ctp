<h1 class="page-header">Rediger menu</h1>

<?php echo $this->Form->create(
	'Menu', 
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
<?php echo $this->Form->input('id', array('value' => $menu['Menu']['id'], 'type' => 'hidden')); ?>

<!-- name input -->
<?php echo $this->Form->input('name', array('label' => 'Navn', 'value' => $menu['Menu']['name'])); ?>

<!-- type input -->
<?php echo $this->Form->input('type', array('label' => 'Type',
    'options' => array(
    	'yarn' => 'Garn',
		'knit' => 'Strikkepinde',
		'crochet' => 'Hæklenåle',
		'surplus_yarn' => 'Rest Garn'),
    'default' => $menu['Menu']['type'],
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