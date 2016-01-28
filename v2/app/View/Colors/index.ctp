<h1 class="page-header">
	<span>Farver</span>

	<!-- Edit -->
	<?php echo $this->Html->link(
	'<span class="glyphicon glyphicon-plus"></span>',
	array('controller' => 'colors', 'action' => 'add'),
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
		<?php foreach($colors as $color) : ?>
		<tr>
			<td><?php echo $color['Color']['id']; ?></td>
			<td><?php echo $color['Color']['name']; ?></td>
			<td>

				<!-- Operations -->

				<!-- Info -->
				<button class="btn btn-info btn-xs" data-toggle="modal" data-target="#colorinfo<?php echo $color['Color']['id']; ?>">
			  		<span class="glyphicon glyphicon-info-sign"></span>
				</button>

				<div class="modal" id="colorinfo<?php echo $color['Color']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
						  	<div class="modal-header">
						    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    	<h4 class="modal-title" id="myModalLabel">Tilhørende garnvarianter</h4>
						  	</div>
						  	<div class="modal-body">
						    		<ul class="list-unstyled">
					    				<?php foreach($color['YarnVariant'] as $i => $yarn_variant) : ?>
					    					<?php if($yarn_variant['is_active']) : ?>
					    						<li>
					    							<a href="

					    							<?php echo $this->Html->url(array(
													    'controller' => 'yarns',
													    'action' => 'view',
													    $yarn_variant['id']
													)); ?>

					    							"><?php echo $yarn_variant['color_code']; ?></a>
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
	            array('controller' => 'colors', 'action' => 'edit', $color['Color']['id']),
	            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
	            );?>

	            <!-- Delete -->
				<?php echo $this->Html->link(
					'<span class="glyphicon glyphicon-trash"></span>',
					array('controller' => 'colors', 'action' => 'delete', $color['Color']['id']),
					array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
					'Er du sikker på du vil slette denne farve?'
				);?>
				<!-- Operations Collapse -->

		</tr>
		<?php endforeach; ?>
	</tbody>
</table>