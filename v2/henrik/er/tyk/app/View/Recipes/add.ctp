<h1 class="page-header">Opret ny opskrift</h1>

<?php echo $this->Form->create(
	'Recipe', 
	array(
		'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		'label' => false,
		'error' => array('attributes' => array('before' => 'span', 'class' => 'label label-danger')),
		),
		'class' => 'well',
		'type' => 'file',
	)
); ?>

<!-- name input -->
<?php echo $this->Form->input('name', array('label' => 'Navn')); ?>

<!-- price input -->
<?php echo $this->Form->input('price', array('label' => 'Pris for print')); ?>

<!-- category input -->
<?php echo $this->Form->input('category', array('label' => 'Kategori',
    'options' => array(
    	'none' => 'Ingen',
    	'females' => 'Damer',
    	'babies' => 'Baby',
    	'children' => 'Børn',
		)
));?>

<!-- intern productcode -->
<?php echo $this->Form->input('intern_product_code', array('label' => 'Intern produktkode')); ?>

<!-- the image -->
<?php echo $this->Form->input('image', array('type' => 'file', 'label' => 'Billede', 'required' => true)); ?>

<!-- the file -->
<?php echo $this->Form->input('pdf', array('type' => 'file', 'label' => 'PDF-fil', 'required' => true)); ?>

<!-- care_label input -->
<?php echo $this->Form->label('Garnkvaliteter', null); ?>
<div class="row">
	<?php foreach($yarns as $i => $yarn) : ?>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div style="background:white;" class="well well-sm">

					<!-- id for the care_label -->
                    <?php 
                    echo $this->Form->checkbox('Yarn.'.$i, array('type' => 'checkbox', 'value' => $i));
                    ?>

					<!-- name of the care_label -->
					<span> 
						<?php echo $this->Form->label($yarn, null, array('class' => 'control-label no-margin', 'style' => 'margin:0;')); ?> 
					</span>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<!-- care_label input -->
<?php echo $this->Form->label('Opskriftskategorier', null); ?>
<div class="row">
	<?php foreach($categories as $i => $category) : ?>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div style="background:white;" class="well well-sm">

					<!-- id for the care_label -->
                    <?php 
                    echo $this->Form->checkbox('Category.'.$i, array('type' => 'checkbox', 'value' => $i));
                    ?>

					<!-- name of the care_label -->
					<span> 
						<?php echo $this->Form->label($category, null, array('class' => 'control-label no-margin', 'style' => 'margin:0;')); ?> 
					</span>
			</div>
		</div>
	<?php endforeach; ?>
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