<h1 class="page-header">Rediger beholdningskategori</h1>

<?php echo $this->Form->create(
	'AvailabilityCategory', 
	array(
		'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		'label' => false,
		'error' => array('attributes' => array('before' => 'span', 'class' => 'label label-danger')),
		),
		'class' => 'well',
	)
); ?>

<!-- id input -->
<?php echo $this->Form->input('id', array('value' => $availability_category['AvailabilityCategory']['id'], 'type' => 'hidden')); ?>

<!-- name input -->
<?php echo $this->Form->input('name', array('label' => 'Navn', 'value' => $availability_category['AvailabilityCategory']['name'])); ?>

<!-- color input -->
<?php echo $this->Form->input('color', array('label' => 'Farve',
    'options' => array(
    	'primary' => 'Blå',
		'success' => 'Grøn',
		'supreme' => 'Mørke Grøn',
		'default' => 'Grå',
		'info' => 'Lyseblå',
		'warning' => 'Orange',
		'danger' => 'Rød'),
	'default' => $availability_category['AvailabilityCategory']['color'],
));?>

<!-- Display options for color -->
<p class="text-center">
<span class="label label-primary">Blå</span>
<span class="label label-success">Grøn</span>
<span class="label label-supreme">Mørke grøn</span>
<span class="label label-default">Grå</span>
<span class="label label-warning">Orange</span>
<span class="label label-danger">Rød</span>
</p>

<!-- type input -->
<?php echo $this->Form->input('type', array('label' => 'Type',
    'options' => array(
    	'yarn' => 'Garn',
		'needle' => 'Strikkepinde/Hælkenåle'),
    'default' => $availability_category['AvailabilityCategory']['type'],
));?>

<!-- is_below input -->
<?php echo $this->Form->input('is_below', array('label' => 'Grænse', 'value' => $availability_category['AvailabilityCategory']['is_below'])); ?>

<!-- show_amount input -->
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div style="background:white;" class="well well-sm">
			<span> <?php echo $this->Form->label('Vis Antal?', null, array('class' => 'control-label no-margin', 'style' => 'margin:0;')); ?> </span>
			<?php echo $this->Form->checkbox('show_amount', array(
				'class' => 'no-margin',
				'style' =>'vertical-align: middle; margin:0;',
				'checked' => $availability_category['AvailabilityCategory']['show_amount']
			));?>
		</div>
	</div>
</div>

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