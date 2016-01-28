<h1 class="page-header"><?php echo $recipe['Recipe']['name'] ?></h1>
<div class="row">
	<div class="col-lg-5 col-md-5 col-sm-5 col-xs-12">
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

				<h3 id="recipe_title<?php echo $recipe['Recipe']['id'];?>" class="product_title ">
						<?php echo $recipe['Recipe']['name']; ?>
				</h3>
			</div>
		</div>
	</div>
	<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
		<div class="well clearfix">
			<span class="pull-left" style="font-size:1.3em;line-height:35px; margin:0; margin-right:1em;">
				<span class="glyphicon glyphicon-print"></span> Print af opskrift </span>
			<span class="pull-left">
				<label id="price" style="font-size:1.3em;line-height:35px; margin:0;">
					<?php echo $this->Number->currency($recipe['Recipe']['price'], 'DKK');?>
				</label>
			</span>
			<div class="pull-right">
				<?php echo $this->Form->create('Orders', array(
					'action' => 'add_recipe', 
					 'class' => 'form-inline',
					 'inputDefaults' => array(
						'div' => 'form-group',
						'wrapInput' => false,
						'class' => 'form-control',
						'label' => false,))); ?>


	            <!-- The amount input -->
	            <?php echo $this->Form->input('amount', array('value' => 1, 'type' => 'number', 'label' => false, 'style' => 'width:70px;')); ?>

	            <!-- The id input (hidden) -->
	            <?php echo $this->Form->input('recipe_id', array('label' => false, 'type' => 'text', 'style' => 'display:none;', 'value' => $recipe['Recipe']['id'])); ?>
	            
	            <!-- The refresh button  -->
	            <button type="submit" class="btn btn-default">
	            	<span class="glyphicon glyphicon-shopping-cart"></span> Læg i kurv 
	            </button>
	            <?php echo $this->Form->end(); ?>
	        </div>
		</div>
		<a href="<?php echo $this->Html->url(array('controller' => 'recipes', 'action' => 'view_pdf', $recipe['Recipe']['id']));?>" target="_blank" class="btn btn-default" style="width:100%;">
		<span class="font-size:1.3em;">Gratis Download</span>
		<span class="glyphicon glyphicon-download-alt"></span>
		</a>
		<div  style="margin:20px 0;"class="fb-like" data-href="
		<?php echo $this->Html->url(array("controller" => "recipes",
										     "action" => "view",
											 $recipe['Recipe']['id']), 
											 true); ?>
		" data-layout="button" data-action="like" data-show-faces="false" data-share="true"></div>
	</div>

	<?php foreach ($recipe['Yarn'] as $i => $yarn) 
		{
			if($yarn['is_active'] && ($yarn['product_count'] > 0)) { continue; }
			unset($recipe['Yarn'][$i]);
		}
	?>
	
	<?php if(!empty($recipe['Yarn'])) : ?>
		
	<div class="col-lg-7 col-md-7 col-sm-7 col-xs-12">
		<div class="row-fluid">
			<h3 class="page-header">Garnkvaliteter til denne opskrift</h3>
			<?php foreach($recipe['Yarn'] as $key => $yarn) : ?>

				<?php foreach ($yarn['YarnVariant'] as $key => $yarn_variant) {
					if($yarn_variant['is_active'] && $yarn_variant['is_thumbnail']/*&& ($yarn_variant['product_count'] > 0)*/)
					{
						$yarn_variant_id = $yarn_variant['id'];
						break;
					}
					else
					{
						continue;
					}
					
				} ?>
				<?php if(!empty($yarn_variant_id)) : ?>
					<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
						<div id="yarn_image<?php echo $yarn['id']?>" class="thumbnail product_container" style="cursor:pointer;"
						onclick="window.location = '<?php echo $this->Html->url(array('controller' => 'yarns', 'action' => 'view', $yarn['id'])); ?>';">
							<div style="width:100%; height:100%;background: url(<?php echo $this->Html->url('/img/yarn_variants/'.$yarn_variant_id.'.png'); ?>); background-repeat:no-repeat; background-position: center; background-size:cover;">
							</div>
							<h4 id="yarn_title<?php echo $yarn['id'];?>" class="product_title ">
								<?php echo $yarn['name']; ?>
							</h4>
						</div>
					</div>
					<script type="text/javascript">
						var yarn_scale = 0.63;
							$('#yarn_image<?php echo $yarn['id'];?>').height($('#yarn_image<?php echo $yarn['id'];?>').width() * yarn_scale);
							$('#yarn_title<?php echo $yarn['id'];?>').width($('#yarn_image<?php echo $yarn['id'];?>').width());
						
						
							$( window ).resize(function() {
						  		$('#yarn_image<?php echo $yarn['id'];?>').height($('#yarn_image<?php echo $yarn['id'];?>').width() * yarn_scale);
								$('#yarn_title<?php echo $yarn['id'];?>').width($('#yarn_image<?php echo $yarn['id'];?>').width()-20);
							});
					</script>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
</div>

<script type="text/javascript">
	var recipe_scale = 1.4;
		$('#recipe_image<?php echo $recipe['Recipe']['id'];?>').height($('#recipe_image<?php echo $recipe['Recipe']['id'];?>').width() * recipe_scale);
		$('#recipe_title<?php echo $recipe['Recipe']['id'];?>').width($('#recipe_image<?php echo $recipe['Recipe']['id'];?>').width());
	
	
		$( window ).resize(function() {
	  		$('#recipe_image<?php echo $recipe['Recipe']['id'];?>').height($('#recipe_image<?php echo $recipe['Recipe']['id'];?>').width() * recipe_scale);
			$('#recipe_title<?php echo $recipe['Recipe']['id'];?>').width($('#recipe_image<?php echo $recipe['Recipe']['id'];?>').width()-20);
		});
</script>

