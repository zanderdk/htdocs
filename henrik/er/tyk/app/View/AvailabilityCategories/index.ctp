<h1 class="page-header">
	<span>Beholdningskategorier</span>

	<!-- Edit -->
	<?php echo $this->Html->link(
	'<span class="glyphicon glyphicon-plus"></span>',
	array('controller' => 'availability_categories', 'action' => 'add'),
	array('class' => 'btn btn-success btn-sm pull-right', 'escapeTitle' => false,)
	);?>
</h1>

<table class="table">
	<thead>
		<th>#</th>
		<th>Navn</th>
		<th>Type</th>
		<th>Grænse</th>
		<th>Vis antal?</th>
		<th>Operation</th>
	</thead>
	<tbody>
		<?php foreach($availability_categories as $availability_category) : ?>
		<tr>
			<td><?php echo $availability_category['AvailabilityCategory']['id']; ?></td>
			<td>
				<span class="label label-<?php echo $availability_category['AvailabilityCategory']['color']; ?>">
					<?php echo $availability_category['AvailabilityCategory']['name']; ?>
				</span>
			</td>
			<td>
				<?php switch ($availability_category['AvailabilityCategory']['type']) {
					case 'yarn':
						echo 'Garn';
						break;
					case 'needle':
						echo 'Strikkepinde';
						break;
				} ?>	
			</td>
			<td><?php echo $availability_category['AvailabilityCategory']['is_below']; ?></td>
			<td><?php if($availability_category['AvailabilityCategory']['show_amount']) echo 'Ja'; else echo 'Nej'; ?></td>
			<td>

				<!-- Operations -->

				<!-- Info -->
				<button class="btn btn-info btn-xs" data-toggle="modal" data-target="#categoryinfo<?php echo $availability_category['AvailabilityCategory']['id']; ?>">
			  		<span class="glyphicon glyphicon-info-sign"></span>
				</button>

				<div class="modal" id="categoryinfo<?php echo $availability_category['AvailabilityCategory']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
						  	<div class="modal-header">
						    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    	<h4 class="modal-title" id="myModalLabel">Tilhørende produkter</h4>
						  	</div>
						  	<div class="modal-body">
						  			<h4>Garn kvaliteter</h4>
						    		<ul class="list-unstyled">
					    				<?php foreach($availability_category['YarnBatch'] as $i => $yarn_batch) : ?>
					    					<?php if($yarn_batch['is_active']) : ?>
					    						<li>
					    							<a href="

					    							<?php echo $this->Html->url(array(
													    'controller' => 'yarns',
													    'action' => 'view',
													    $yarn_batch['id']
													)); ?>

					    							"><?php echo $yarn_batch['batch_code']; ?></a>
					    						</li>
					    					<?php endif; ?>
					    				<?php endforeach?>
						    		</ul>
						    		<h4>Udstyr</h4>
						    		<ul class="list-unstyled">
					    				<?php foreach($availability_category['NeedleVariant'] as $i => $needle_variant) : ?>
					    					<?php if($needle_variant['is_active']) : ?>
					    						<li>
					    							<a href="

					    							<?php echo $this->Html->url(array(
													    'controller' => 'needles',
													    'action' => 'view',
													    $needle_variant['id']
													)); ?>

					    							"><?php echo $needle_variant['product_code']; ?></a>
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
	            array('controller' => 'availability_categories', 'action' => 'edit', $availability_category['AvailabilityCategory']['id']),
	            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
	            );?>

	            <!-- Delete -->
				<?php echo $this->Html->link(
					'<span class="glyphicon glyphicon-trash"></span>',
					array('controller' => 'availability_categories', 'action' => 'delete', $availability_category['AvailabilityCategory']['id']),
					array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
					'Er du sikker på du vil slette denne kategori?'
				);?>
				<!-- Operations Collapse -->

		</tr>
		<?php endforeach; ?>
	</tbody>
</table>