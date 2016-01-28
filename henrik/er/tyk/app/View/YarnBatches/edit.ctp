<h1 class="page-header">Rediger garnparti</h1>

<?php echo $this->Form->create(
	'YarnBatch', 
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

<!-- yarn_variant_id input -->
<?php echo $this->Form->input('yarn_variant_id', array('type' => 'hidden', 'value' => $yarn_batch['YarnVariant']['id'])); ?>

<!-- id input -->
<?php echo $this->Form->input('id', array('value' => $yarn_batch['YarnBatch']['id'], 'type' => 'hidden')); ?>

<!-- batch_code input -->
<?php echo $this->Form->input('batch_code', array('label' => 'Partinummer', 'value' => $yarn_batch['YarnBatch']['batch_code'])); ?>

<!-- stock_quantity input -->
<?php echo $this->Form->input('stock_quantity', array('label' => 'Lager antal', 'value' => $yarn_batch['YarnBatch']['stock_quantity'])); ?>

<!-- stock_quantity input -->
<?php echo $this->Form->input('item_quantity', array('label' => 'Produkt antal', 'value' => $yarn_batch['YarnBatch']['item_quantity'])); ?>

<!-- price input -->
<?php echo $this->Form->input('price', array('label' => 'Pris', 'value' => $yarn_batch['YarnBatch']['price'])); ?>

<!-- show_discount input -->
<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div style="background:white;" class="well well-sm">
			<span> <?php echo $this->Form->label('Vis discount?', null, array('class' => 'control-label no-margin', 'style' => 'margin:0;')); ?> </span>
			<?php echo $this->Form->checkbox('show_discount', array(
				'class' => 'no-margin',
				'style' =>'vertical-align: middle; margin:0;',
				'checked' => $yarn_batch['YarnBatch']['show_discount']
			));?>

			<!-- display current discount if any -->
			<?php if($yarn_batch['YarnBatch']['discount'] > 0) : ?>
				<p class="help-block">Den nuværende rabat er <?php echo $yarn_batch['YarnBatch']['discount']; ?>%</p>
			<?php else :?>
				<p class="help-block">Der er ingen rabat på dette produkt pt</p>
			<?php endif; ?>
		</div>
	</div>
</div>



<!-- intern_product_code input -->
<?php echo $this->Form->input('intern_product_code', array('label' => 'Varekode (intern)', 'value' => $yarn_batch['YarnBatch']['intern_product_code'])); ?>

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