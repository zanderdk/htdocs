<h1 class="page-header">Rediger opskrift</h1>

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

<?php echo $this->Form->input('id', array('type' => 'hidden', 'value' => $recipe['Recipe']['id'])); ?>

<!-- name input -->
<?php echo $this->Form->input('name', array('label' => 'Navn', 'value' => $recipe['Recipe']['name'])); ?>

<!-- price input -->
<?php echo $this->Form->input('price', array('label' => 'Pris for print' , 'value' => $recipe['Recipe']['price'])); ?>

<!-- category input -->
<?php echo $this->Form->input('category', array('label' => 'Kategori',
    'options' => array(
        'none' => 'Ingen',
        'females' => 'Damer',
        'babies' => 'Baby',
        'children' => 'Børn',
        ),
    'default' => $recipe['Recipe']['category'])
);?>

<!-- intern productcode -->
<?php echo $this->Form->input('intern_product_code', array('label' => 'Intern produktkode', 'value' => $recipe['Recipe']['intern_product_code'])); ?>

<!-- the image -->
<?php echo $this->Form->input('image', array('type' => 'file', 'label' => 'Billede')); ?>
<?php echo $this->Html->image('/img/recipes/'.$recipe['Recipe']['id'].'.png', array('style' => 'width:100px', 'class' => 'thumbnail'));?>


<!-- the file -->
<label>PDF-fil (<?php echo $this->Html->link('se fil',array('controller' => 'recipes', 'action' => 'view_pdf', $recipe['Recipe']['id']),array('target' => '_blank'));?>)</label>
<?php echo $this->Form->input('pdf', array('type' => 'file')); ?>

<br/>
<br/>
<!-- care_label input -->
<?php echo $this->Form->label('Garnkvaliteter', null); ?>
<div class="row">
    <?php foreach($yarns as $i => $yarn) : ?>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div style="background:white;" class="well well-sm">

                    <!-- id for the care_label -->
                    <?php echo $this->Form->checkbox('Yarn.'.$i, array(
                        'class' => 'no-margin',
                        'style' =>'vertical-align: middle; margin:0;',
                        'value' => $i,
                        'checked' => $yarn['checked']
                    ));?>

                    <!-- name of the care_label -->
                    <span> 
                        <?php echo $this->Form->label($yarn['name'], null, array('class' => 'control-label no-margin', 'style' => 'margin:0;', 'checked' => $yarn['checked'])); ?> 
                    </span>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php echo $this->Form->label('Opskriftskategorier', null); ?>
<div class="row">
    <?php foreach($categories as $i => $category) : ?>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <div style="background:white;" class="well well-sm">

                    <!-- id for the care_label -->
                    <?php echo $this->Form->checkbox('Category.'.$i, array(
                        'class' => 'no-margin',
                        'style' =>'vertical-align: middle; margin:0;',
                        'value' => $i,
                        'checked' => $category['checked']
                    ));?>

                    <!-- name of the care_label -->
                    <span> 
                        <?php echo $this->Form->label($category['name'], null, array('class' => 'control-label no-margin', 'style' => 'margin:0;', 'checked' => $category['checked'])); ?> 
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
