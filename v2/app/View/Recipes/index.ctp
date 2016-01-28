<h1 class="page-header">
<span><?php echo $title; ?></span>
<?php if($logged_in) : ?>
	<span class="pull-right">
		<!-- Add -->
		<?php echo $this->Html->link(
		'<span class="glyphicon glyphicon-plus"></span>',
		array('controller' => 'recipes', 'action' => 'add'),
		array('class' => 'btn btn-success btn-sm', 'escapeTitle' => false,)
		);?>
	</span>
<?php endif; ?>
</h1>

	<span onclick="window.location = '<?php echo $this->Html->url(array('controller' => 'recipes', 'action' => 'index')); ?>';" style="font-size:2em; cursor:pointer;" class="label label-danger">Alle</span>
	
<?php foreach ($categories as $key => $category) : ?>

	<span onclick="window.location = '<?php echo $this->Html->url(array('controller' => 'recipes', 'action' => 'index', $key)); ?>';" style="font-size:2em; cursor:pointer;" class="label label-danger"><?php echo $category; ?></span>

<?php endforeach; ?>

<?php if($categories != null) : ?>
	<hr/>
<? endif; ?>

<div class="row">
<?php foreach ($recipes as $key => $recipe) : ?>
	<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:20px; cursor:pointer;"
	onclick="window.location = '<?php echo $this->Html->url(array('controller' => 'recipes', 'action' => 'view', $recipe['Recipe']['id'])); ?>';">

		<div id="recipe_image<?php echo $recipe['Recipe']['id'];?>" class="thumbnail product_container" style="width:100%;">
			<div style="width:100%; height:100%;background: url(<?php echo $this->Html->url('/img/recipes/'.$recipe['Recipe']['id'].'.png'); ?>); background-repeat:no-repeat; background-position: center; background-size:cover;">

				<?php if($logged_in) : ?>
					<div style="padding:10px;">
						<!-- Edit -->
						<?php echo $this->Html->link(
			            '<span class="glyphicon glyphicon-pencil"></span>',
			            array('controller' => 'recipes', 'action' => 'edit', $recipe['Recipe']['id']),
			            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
			            );?>

			            <!-- Delete -->
						<?php echo $this->Html->link(
							'<span class="glyphicon glyphicon-trash"></span>',
							array('controller' => 'recipes', 'action' => 'delete', $recipe['Recipe']['id']),
							array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
							'Er du sikker på du vil slette denne opskrift?'
						);?>
					</div>
				<?php endif; ?>

				<h4 id="recipe_title<?php echo $recipe['Recipe']['id'];?>" class="product_title ">
						<?php echo $recipe['Recipe']['name']; ?>
				</h4>
			</div>
		</div>
	</div>

	<!-- Script to handle the scaling -->
	<script type="text/javascript">
		var recipe_scale = 1.4;
		$('#recipe_image<?php echo $recipe['Recipe']['id'];?>').height($('#recipe_image<?php echo $recipe['Recipe']['id'];?>').width() * recipe_scale);
		$('#recipe_title<?php echo $recipe['Recipe']['id'];?>').width($('#recipe_image<?php echo $recipe['Recipe']['id'];?>').width());
	
	
		$( window ).resize(function() {
	  		$('#recipe_image<?php echo $recipe['Recipe']['id'];?>').height($('#recipe_image<?php echo $recipe['Recipe']['id'];?>').width() * recipe_scale);
			$('#recipe_title<?php echo $recipe['Recipe']['id'];?>').width($('#recipe_image<?php echo $recipe['Recipe']['id'];?>').width()-20);
		});
	</script>

<?php endforeach; ?>

</div>

