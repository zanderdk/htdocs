<h1 class="page-header">
	<span>Mærker</span>

	<!-- Add -->
	<?php echo $this->Html->link(
	'<span class="glyphicon glyphicon-plus"></span>',
	array('controller' => 'brands', 'action' => 'add'),
	array('class' => 'btn btn-success btn-sm pull-right', 'escapeTitle' => false,)
	);?>
</h1>

<table class="table">
	<thead>
		<th>#</th>
		<th>Navn</th>
		<th>Type</th>
		<th>Operation</th>
	</thead>
	<tbody>
		<?php foreach($brands as $brand) : ?>
		<tr>
			<td><?php echo $brand['Brand']['id']; ?></td>
			<td><?php echo $brand['Brand']['name']; ?></td>
			<td>
				<?php
				switch ($brand['Brand']['type']) {
					case 'yarn':
						echo 'Garn';
						break;
					
					case 'needle':
						echo 'Strikkepinde';
						break;
				}

				?>
			</td>
			<td>
				<!-- Operations -->

				<!-- Info -->
				<button class="btn btn-info btn-xs" data-toggle="modal" data-target="#brandifo<?php echo $brand['Brand']['id']; ?>">
			  		<span class="glyphicon glyphicon-info-sign"></span>
				</button>

				<div class="modal" id="brandifo<?php echo $brand['Brand']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
						  	<div class="modal-header">
						    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    	<h4 class="modal-title" id="myModalLabel">Tilhørende produkter</h4>
						  	</div>
						  	<div class="modal-body">
						  			<h4>Garn kvaliteter</h4>
						    		<ul class="list-unstyled">
					    				<?php foreach($brand['Yarn'] as $i => $yarn) : ?>
					    					<?php if($yarn['is_active']) : ?>
					    						<li>
					    							<a href="

					    							<?php echo $this->Html->url(array(
													    'controller' => 'yarns',
													    'action' => 'view',
													    $yarn['id']
													)); ?>

					    							"><?php echo $yarn['name']; ?></a>
					    						</li>
					    					<?php endif; ?>
					    				<?php endforeach?>
						    		</ul>
						    		<h4>Udstyr</h4>
						    		<ul class="list-unstyled">
					    				<?php foreach($brand['Needle'] as $i => $needle) : ?>
					    					<?php if($needle['is_active']) : ?>
					    						<li>
					    							<a href="

					    							<?php echo $this->Html->url(array(
													    'controller' => 'needles',
													    'action' => 'view',
													    $needle['id']
													)); ?>

					    							"><?php echo $needle['name']; ?></a>
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
	            array('controller' => 'brands', 'action' => 'edit', $brand['Brand']['id']),
	            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
	            );?>

	            <!-- Delete -->
				<?php echo $this->Html->link(
					'<span class="glyphicon glyphicon-trash"></span>',
					array('controller' => 'brands', 'action' => 'delete', $brand['Brand']['id']),
					array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
					'Er du sikker på du vil slette dette mærke?'
				);?>
				<!-- Operations Collapse -->

		</tr>
		<?php endforeach; ?>
	</tbody>
</table>