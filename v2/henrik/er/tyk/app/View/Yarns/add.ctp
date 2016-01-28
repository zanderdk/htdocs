<h1 class="page-header">Opret garnkvalitet</h1>

<?php echo $this->Form->create(
	'Yarn', 
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
<?php echo $this->Form->input('name', array('label' => 'Navn')
); ?>

<!-- weight input -->
<?php echo $this->Form->input('weight', array('label' => 'Vægt i gram')
); ?>

<!-- length input -->
<?php echo $this->Form->input('length', array('label' => 'Løbelængde pr. 50g')
); ?>

<!-- gauge_masks input -->
<?php echo $this->Form->input('gauge_masks', array('label' => 'Strikkefasthed (masker)')
); ?>

<!-- gauge_rows input -->
<?php echo $this->Form->input('gauge_rows', array('label' => 'Strikkefasthed (rækker)')
); ?>

<!-- needle_size_min input -->
<?php echo $this->Form->input('needle_size_min', array('label' => 'Nålestørelse (minimum)')
); ?>

<!-- needle_size_max input -->
<?php echo $this->Form->input('needle_size_max', array('label' => 'Nålestørelse (maximum)')
); ?>

<!-- brand_id input -->
<?php echo $this->Form->input('brand_id', array('label' => 'Mærke',
    'options' => $brands
));?>

<!-- menu_id input -->
<?php echo $this->Form->input('menu_id', array('label' => 'Menu',
    'options' => $menus
));?>

<!-- price input (color sample price) --> 
<?php echo $this->Form->input('price', array('label' => 'Pris for farveprøve')); ?>

<!-- care_label input -->
<?php echo $this->Form->label('Vaskemærker', null); ?>
<div class="row">
	<?php foreach($care_labels as $i => $care_label) : ?>
		<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
			<div style="background:white;" class="well well-sm">

					<!-- image for the care_label -->
					<?php echo $this->Html->image('/img/care_labels/'.$i.'.png', 
						array('style' => 'width:50px; display:inline; margin:0;', 'class' => 'thumbnail')
					);?>
					
					<!-- id for the care_label -->
                    <?php 
                    echo $this->Form->checkbox('CareLabel.'.$i, array('type' => 'checkbox', 'value' => $i));
                    ?>

					<!-- name of the care_label -->
					<span> 
						<?php echo $this->Form->label($care_label, null, array('class' => 'control-label no-margin', 'style' => 'margin:0;')); ?> 
					</span>
			</div>
		</div>
	<?php endforeach; ?>
</div>

<!-- part input -->
<?php echo $this->Form->label('Bestanddele', null); ?>
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<?php foreach($materials as $i => $material) : ?>

			<?php echo $this->Form->input('YarnPart.'.$i.'.material_id', array('type' => 'hidden', 'value' => $i)); ?>
			<?php echo $this->Form->input('YarnPart.'.$i.'.percentage', array(
				'before' => '<span class="input-group-addon"><div style="min-width:140px;">'.$material.'</div></span>',
				'div' => 'input-group',
				'after' => '<span class="input-group-addon">%</span>',
				'required' => false,
				'value' => null
				 )
			); ?>

		<?php endforeach; ?>
	</div>
</div>

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