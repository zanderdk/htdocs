<h1 class="page-header">Rediger forside artikel</h1>

<?php echo $this->Form->create(
	'FrontpageItem', 
	array(
		'inputDefaults' => array(
		'div' => 'form-group',
		'wrapInput' => false,
		'class' => 'form-control',
		'label' => false,
		'error' => array('attributes' => array('wrap' => 'span', 'class' => 'label label-danger')),
		),
		'class' => 'well',
		'type' => 'file'
	)
); ?>

<!-- id input -->
<?php echo $this->Form->input('id', array('value' => $frontpage_item['FrontpageItem']['id'], 'type' => 'hidden')); ?>

<!-- url input -->
<?php echo $this->Form->input('url', array('label' => 'Link', 'value' => $frontpage_item['FrontpageItem']['url'])); ?>

<!-- button_text input -->
<?php echo $this->Form->input('button_text', array('label' => 'Knap-tekst', 'value' => $frontpage_item['FrontpageItem']['button_text'])); ?>
<p class="help-block">Den tekst der bliver vist på knappen på en forside artikel</p>

<!-- description input -->
<?php echo $this->Form->input('description', array('label' => 'Beskrivelse', 'value' => $frontpage_item['FrontpageItem']['description'])); ?>

<!-- current uploaded file -->
<?php echo $this->Html->image('/img/frontpage_items/'.$frontpage_item['FrontpageItem']['id'].'.png', array('style' => 'width:150px', 'class' => 'thumbnail'));?>

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

<?php echo $this->Form->end(); ?>

