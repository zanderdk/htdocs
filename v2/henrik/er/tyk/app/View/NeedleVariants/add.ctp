<h1 class="page-header">Opret strikkepindsvariant</h1>

<?php echo $this->Form->create(
	'NeedleVariant', 
	array(
		'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		'label' => false,
		),
		'class' => 'well',
		'type' => 'file'
	)
); ?>

<!-- needle_id input -->
<?php echo $this->Form->input('needle_id', array('type' => 'hidden', 'value' => $needle['Needle']['id'])); ?>

<!-- product_code input -->
<?php echo $this->Form->input('product_code', array('label' => 'Produktkode')); ?>

<!-- stock_quantity input -->
<?php echo $this->Form->input('stock_quantity', array('label' => 'Lager antal')); ?>

<!-- item_quantity input -->
<?php echo $this->Form->input('item_quantity', array('label' => 'Produkt antal', 'value' => 1)); ?>

<!-- price input -->
<?php echo $this->Form->input('price', array('label' => 'Pris')); ?>

<!-- show_discount input -->
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div style="background:white;" class="well well-sm">
			<span> <?php echo $this->Form->label('Vis discount?', null, array('class' => 'control-label no-margin', 'style' => 'margin:0;')); ?> </span>
			<?php echo $this->Form->checkbox('show_discount', array(
				'class' => 'no-margin',
				'style' =>'vertical-align: middle; margin:0;',
			));?>
		</div>
	</div>
</div>

<!-- girth input -->
<?php echo $this->Form->input('girth', array('label' => 'Tykkelse')); ?>

<!-- girth_max input -->
<?php echo $this->Form->input('girth_max', array('label' => 'Tykkelse (max) - valgfri', 'value' => 0)); ?>
<p class="help-block">Lad dette felt være 0 for at beskrive at du kun har en minimums størelse</p>

<!-- length input -->
<?php echo $this->Form->input('length', array('label' => 'Længde')); ?>

<!-- is_wire input -->
<?php echo $this->Form->input('is_wire', array('label' => 'Længdetype', 'options' => array(
    	'0' => 'Pindelængde',
		'1' => 'Wirelængde'))); ?>

<?php echo $this->Form->input('file', array('type' => 'file', 'label' => 'Billede', 'required' => true)); ?>

<!-- intern_product_code input -->
<?php echo $this->Form->input('intern_product_code', array('label' => 'Varekode (Intern)')); ?>

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