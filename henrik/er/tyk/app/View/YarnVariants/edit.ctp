<h1 class="page-header">Rediger garnvariant</h1>

<?php echo $this->Form->create(
	'YarnVariant', 
	array(
		'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		'label' => false,
		'error' => array('attributes' => array('before' => 'span', 'class' => 'label label-danger')),
		),
		'class' => 'well',
		'type' => 'file'
	)
); ?>

<!-- yarn_id input -->
<?php echo $this->Form->input('yarn_id', array('value' => $yarn_variant['Yarn']['id'], 'type' => 'hidden')); ?>

<!-- id input -->
<?php echo $this->Form->input('id', array('value' => $yarn_variant['YarnVariant']['id'], 'type' => 'hidden')); ?>

<!-- color_code input -->
<?php echo $this->Form->input('color_code', array('label' => 'Farvekode', 'value' => $yarn_variant['YarnVariant']['color_code'])); ?>

<!-- color input -->
<?php echo $this->Form->input('color_id', array('label' => 'Farve',
    'options' => $colors,
    'default' => $yarn_variant['Color']['id']
));?>

<!-- current uploaded file -->
<?php echo $this->Html->image('/img/yarn_variants/'.$yarn_variant['YarnVariant']['id'].'.png', array('style' => 'width:150px', 'class' => 'thumbnail'));?>

<!-- the file -->
<?php echo $this->Form->input('file', array('type' => 'file', 'label' => 'Billede')); ?>

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

<h4 class="page-header">Partier</h4>
<!-- Links to yarn_batches -->
<div class="row">
<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
	<!-- Edit -->
	<?php echo $this->Html->link(
	    '<span class="glyphicon glyphicon-plus"></span> ',
	    array('controller' => 'yarn_batches', 'action' => 'add', $yarn_variant['YarnVariant']['id']),
	    array('class' => 'btn btn-success btn-sm', 'style' => 'width:100%;', 'escapeTitle' => false,)
    );?>
</div>
<?php foreach ($yarn_variant['YarnBatch'] as $key => $yarn_batch) : ?>
	<?php if($yarn_batch['is_active']) : ?>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
		<label style="line-height:30px; margin:0;"><?php echo $yarn_batch['batch_code']; ?></label>
		
	    <!-- Delete -->
	    <?php echo $this->Html->link(
		    '<span class="glyphicon glyphicon-trash"></span> ',
		    array('controller' => 'yarn_batches', 'action' => 'delete', $yarn_batch['id']),
		    array('class' => 'btn btn-danger btn-sm pull-right', 'escapeTitle' => false,),
		    'Er du sikker på du vil slette dette garnparti'
	    );?>

	    <!-- Edit -->
		<?php echo $this->Html->link(
		    '<span class="glyphicon glyphicon-pencil"></span> ',
		    array('controller' => 'yarn_batches', 'action' => 'edit', $yarn_batch['id']),
		    array('class' => 'btn btn-default btn-sm pull-right', 'style' => 'margin-right:10px;', 'escapeTitle' => false,)
	    );?>
	    </div>
	<?php endif; ?>
<?php endforeach; ?>
</div>

<?php echo $this->Form->end(); ?>
