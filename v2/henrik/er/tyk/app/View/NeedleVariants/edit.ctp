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
<?php echo $this->Form->input('needle_id', array('type' => 'hidden', 'value' => $needle_variant['Needle']['id'])); ?>

<!-- id input -->
<?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $needle_variant['NeedleVariant']['id'])); ?>

<!-- product_code input -->
<?php echo $this->Form->input('product_code', array('label' => 'Produktkode', 'value' => $needle_variant['NeedleVariant']['product_code'])); ?>

<!-- stock_quantity input -->
<?php echo $this->Form->input('stock_quantity', array('label' => 'Lager antal', 'value' => $needle_variant['NeedleVariant']['stock_quantity'])); ?>

<!-- item_quantity input -->
<?php echo $this->Form->input('item_quantity', array('label' => 'Produkt antal', 'value' => $needle_variant['NeedleVariant']['item_quantity'])); ?>

<!-- price input -->
<?php echo $this->Form->input('price', array('label' => 'Pris', 'value' => $needle_variant['NeedleVariant']['price'])); ?>

<!-- show_discount input -->
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div style="background:white;" class="well well-sm">
			<span> <?php echo $this->Form->label('Vis discount?', null, array('class' => 'control-label no-margin', 'style' => 'margin:0;')); ?> </span>
			<?php echo $this->Form->checkbox('show_discount', array(
				'class' => 'no-margin',
				'style' =>'vertical-align: middle; margin:0;',
				'checked' => $needle_variant['NeedleVariant']['show_discount']
			));?>

			<!-- display current discount if any -->
			<?php if($needle_variant['NeedleVariant']['discount'] > 0) : ?>
				<p class="help-block">Den nuværende rabat er <?php echo $needle_variant['NeedleVariant']['discount']; ?>%</p>
			<?php else :?>
				<p class="help-block">Der er ingen rabat på dette produkt pt</p>
			<?php endif; ?>
		</div>
	</div>
</div>

<!-- girth input -->
<?php echo $this->Form->input('girth', array('label' => 'Tykkelse', 'value' => $needle_variant['NeedleVariant']['girth'])); ?>
<!-- girth_max input -->
<?php echo $this->Form->input('girth_max', array('label' => 'Tykkelse (max) - valgfri', 'value' => $needle_variant['NeedleVariant']['girth_max'])); ?>
<p class="help-block">Lad dette felt være 0 for at beskrive at du kun har en minimums størelse</p>

<!-- length input -->
<?php echo $this->Form->input('length', array('label' => 'Længde', 'value' => $needle_variant['NeedleVariant']['length'])); ?>

<!-- is_wire input -->
<?php echo $this->Form->input('is_wire', array('label' => 'Længdetype', 'options' => array(
    	'0' => 'Pindelængde',
		'1' => 'Wirelængde'),
		'default' => $needle_variant['NeedleVariant']['is_wire'])
		); ?>

<!-- current uploaded file -->
<?php echo $this->Html->image('/img/needle_variants/'.$needle_variant['NeedleVariant']['id'].'.png', array('style' => 'width:150px', 'class' => 'thumbnail'));?>

<!-- the file -->
<?php echo $this->Form->input('file', array('type' => 'file', 'label' => 'Billede')); ?>

<!-- intern_product_code input -->
<?php echo $this->Form->input('intern_product_code', array('label' => 'Varekode (Intern)', ' value' => $needle_variant['NeedleVariant']['intern_product_code'])); ?>

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