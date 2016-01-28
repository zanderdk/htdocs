<h1 class="page-header">
	<span>Opskrifts kategories</span>

	<!-- Add -->
	<?php echo $this->Html->link(
	'<span class="glyphicon glyphicon-plus"></span>',
	array('controller' => 'categories', 'action' => 'add'),
	array('class' => 'btn btn-success btn-sm pull-right', 'escapeTitle' => false,)
	);?>
</h1>

<table class="table">
	<thead>
		<th>#</th>
		<th>Navn</th>
		<th>Operation</th>
	</thead>
	<tbody>
		<?php foreach($categories as $category) : ?>
		<tr>
			<td><?php echo $category['Category']['id']; ?></td>
			<td><?php echo $category['Category']['name']; ?></td>
			<td>
				<!-- Operations -->

				<!-- Info -->
				<button class="btn btn-info btn-xs" data-toggle="modal" data-target="#menuinfo<?php echo $category['Category']['id']; ?>">
			  		<span class="glyphicon glyphicon-info-sign"></span>
				</button>

				<div class="modal" id="menuinfo<?php echo $category['Category']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
						  	<div class="modal-header">
						    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    	<h4 class="modal-title" id="myModalLabel">Tilhørende opskrifter</h4>
						  	</div>
						  	<div class="modal-body">
					    		<ul class="list-unstyled">
				    				<?php foreach($category['Recipe'] as $i => $recipe) : ?>
				    					<?php if($recipe['is_active']) : ?>
				    						<li>
				    							<a href="

				    							<?php echo $this->Html->url(array(
												    'controller' => 'recipes',
												    'action' => 'view',
												    $recipe['id']
												)); ?>

				    							"><?php echo $recipe['name']; ?></a>
				    						</li>
				    					<?php endif; ?>
				    				<?php endforeach?>
					    		</ul>						    		
						  	</div>
						</div>
					</div>
				</div>

				<!-- Edit -->
				<?php echo $this->Html->link(
	            '<span class="glyphicon glyphicon-pencil"></span>',
	            array('controller' => 'categories', 'action' => 'edit', $category['Category']['id']),
	            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
	            );?>

	            <!-- Delete -->
				<?php echo $this->Html->link(
					'<span class="glyphicon glyphicon-trash"></span>',
					array('controller' => 'categories', 'action' => 'delete', $category['Category']['id']),
					array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
					'Er du sikker på du vil slette denne opskrifts kategori?'
				);?>
				<!-- Operations Collapse -->
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>