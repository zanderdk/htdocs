<h1 class="page-header">
	<span>Materialer</span>

	<!-- Add -->
	<?php echo $this->Html->link(
	'<span class="glyphicon glyphicon-plus"></span>',
	array('controller' => 'materials', 'action' => 'add'),
	array('class' => 'btn btn-success btn-sm pull-right', 'escapeTitle' => false,)
	);?>
</h1>

<table class="table">
	<thead>
		<th>#</th>
		<th>Navn</th>
		<th>Type</ht>
		<th>Operation</th>
	</thead>
	<tbody>
		<?php foreach($materials as $material) : ?>
		<tr>
			<td><?php echo $material['Material']['id']; ?></td>
			<td><?php echo $material['Material']['name']; ?></td>
			<td>
				<?php
				switch ($material['Material']['type']) {
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
				<button class="btn btn-info btn-xs" data-toggle="modal" data-target="#myModal">
			  		<span class="glyphicon glyphicon-info-sign"></span>
				</button>

				<div class="modal" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-dialog">
						<div class="modal-content">
						  	<div class="modal-header">
						    	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
						    	<h4 class="modal-title" id="myModalLabel">Tilhørende emner</h4>
						  	</div>
						  	<div class="modal-body">
						  		<!-- // TODO -->
						    	På sigt kommer der til at være links til de garnkvaliteter der hører til dette materiale hvis hr Bundgaard ønsker det.
						  	</div>
						</div>
					</div>
				</div>

				<!-- Edit -->
				<?php echo $this->Html->link(
	            '<span class="glyphicon glyphicon-pencil"></span>',
	            array('controller' => 'materials', 'action' => 'edit', $material['Material']['id']),
	            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
	            );?>

	            <!-- Delete -->
				<?php echo $this->Html->link(
					'<span class="glyphicon glyphicon-trash"></span>',
					array('controller' => 'materials', 'action' => 'delete', $material['Material']['id']),
					array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
					'Er du sikker på du vil slette dette materiale?'
				);?>
				<!-- Operations Collapse -->

		</tr>
		<?php endforeach; ?>
	</tbody>
</table>