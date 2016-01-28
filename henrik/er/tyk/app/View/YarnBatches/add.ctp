<h1 class="page-header">Opret garnparti</h1>

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
<?php echo $this->Form->input('yarn_variant_id', array('type' => 'hidden', 'value' => $yarn_variant['YarnVariant']['id'])); ?>

<!-- name input -->
<?php echo $this->Form->input('batch_code', array('label' => 'Partinummer')); ?>

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

<!-- intern_product_code input -->
<?php echo $this->Form->input('intern_product_code', array('label' => 'Varekode (intern)')); ?>

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