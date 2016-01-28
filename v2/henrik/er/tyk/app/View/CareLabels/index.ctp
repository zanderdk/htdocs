<h1 class="page-header">

	<span>Vaskemærker</span>

	<!-- Add -->
	<?php echo $this->Html->link(
	'<span class="glyphicon glyphicon-plus"></span>',
	array('controller' => 'care_labels', 'action' => 'add'),
	array('class' => 'btn btn-success btn-sm pull-right', 'escapeTitle' => false,)
	);?>
</h1>

<table class="table">
	<thead>
		<th>#</th>
		<th>Billede</th>
		<th>Beskrivelse</th>
		<th>Operation</th>
	</thead>
	<tbody>
		<?php foreach($care_labels as $care_label) : ?>
		<tr>
			<td><?php echo $this->Html->image('/img/care_labels/'.$care_label['CareLabel']['id'].'.png', array('style' => 'height:40px;', 'class' => 'thumbnail'));?></td>
			<td><?php echo $care_label['CareLabel']['id']; ?></td>
			<td><?php echo $care_label['CareLabel']['name']; ?></td>
			<td>

				<!-- Operations -->

				<!-- Info -->
				<button class="btn btn-info btn-xs" data-toggle="modal" data-target="#carelabelinfo<?php echo $care_label['CareLabel']['id']; ?>">
			  		<span class="glyphicon glyphicon-info-sign"></span>
				</button>

				<div class="modal" id="carelabelinfo<?php echo $care_label['CareLabel']['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
						  	<div class="modal-header">
						    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    	<h4 class="modal-title" id="myModalLabel">Tilhørende garnkvaliteter</h4>
						  	</div>
						  	<div class="modal-body">
						    		<ul class="list-unstyled">
					    				<?php foreach($care_label['Yarn'] as $i => $yarn) : ?>
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
						  	</div>
						</div>
					</div>
				</div>

				<!-- Edit -->
				<?php echo $this->Html->link(
	            '<span class="glyphicon glyphicon-pencil"></span>',
	            array('controller' => 'care_labels', 'action' => 'edit', $care_label['CareLabel']['id']),
	            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
	            );?>

	            <!-- Delete -->
				<?php echo $this->Html->link(
					'<span class="glyphicon glyphicon-trash"></span>',
					array('controller' => 'care_labels', 'action' => 'delete', $care_label['CareLabel']['id']),
					array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
					'Er du sikker på du vil slette dette vaskemærke?'
				);?>
				<!-- Operations Collapse -->

		</tr>
		<?php endforeach; ?>
	</tbody>
</table>