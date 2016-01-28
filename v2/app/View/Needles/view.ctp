<h1 class="page-header">
	<span><?php echo $needle['Needle']['name']; ?></span>
	<?php if($logged_in) : ?>
		<span class="pull-right">
			<!-- Add -->
			<?php echo $this->Html->link(
			'<span class="glyphicon glyphicon-pencil"></span>',
			array('controller' => 'needles', 'action' => 'edit', $needle['Needle']['id']),
			array('class' => 'btn btn-default btn-sm', 'escapeTitle' => false,)
			);?>
		</span>
	<?php endif; ?>
</h1>

<div class="row">
	<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		<!-- Specs -->
		<table class="table table-condensed">
			<thead>
				<th colspan="2">Specifikationer</th>
			</thead>
			<tbody>
				<tr>
					<td>Mærke</td>
					<td><?php echo $needle['Brand']['name']; ?></td>
				</tr>
				<tr>
					<td>Materiale</td>
					<td><?php echo $needle['Material']['name']; ?></td>
				</tr>
				<th colspan="2">Dimensioner</th>
				<tr id="length_row">
					<td id="length_title">Længde</td>
					<td id="length"></td>
				</tr>
				<tr>
					<td>Tykkelse</td>
					<td id="girth"></td>
				</tr>
				<th colspan="2">Vareinformation</th>
						<tr>
								<td>Produktnummer</td>
								<td id="product_code"></td>
						</tr>
						<tr>
								<td>Varenummer</td>
								<td id="intern_product_code"></td>
						</tr>
			</tbody>
		</table>
		<div  style="margin-bottom:20px;"class="fb-like" data-href="
		<?php echo $this->Html->url(array("controller" => "needles",
										     "action" => "view",
											 $needle['Needle']['id']), 
											 true); ?>
		" data-layout="button" data-action="like" data-show-faces="false" data-share="true"></div>
	</div>
	<div id="product" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
		
		<?php foreach ($needle['NeedleVariant'] as $key => $needle_variant) : ?>
			<?php if($needle_variant['is_active']) : ?>
				<span style="display:none;" id="discount" class="discount">50%</span>
				<div id="active_image_container" class="thumbnail" style="width:100%;" data-toggle="modal" data-target="#image_popup_modal">
					<div id="active_image" style="width:100%; height:100%; background-image: url('<?php echo $this->Html->url('/img/needle_variants/'. $needle_variant['id'].'.png'); ?>'); background-repeat:no-repeat; background-position: center; background-size:cover;"></div>
					<h4 id="active_title" class="product_title" style="position:relative; bottom:40px;">
						<?php echo $needle_variant['girth']; if($needle_variant['girth_max'] > 0){echo ' - '. $needle_variant['girth_max'];} ?> mm
					</h4>
				</div>
				<div id="sale" class="well clearfix hidden-xs">
					<div class="row-fluid" style="text-align:center; vertical-align:middle;">
						<p style="margin:0;display:none;" id="previous_price_container">Før <s id="previous_price">DKK100,00</s></p>
						<label id="price" style="font-size:1.3em;position:relative; top:3px;"></label> 
						<span style="line-height:35px; margin:0;" class="label" id="availability"></span>
						<p id="item_quantity" style="display:none;" class="text-muted"></p>
					</div>
					
					<?php echo $this->Form->create('Orders', array(
						'action' => 'add_needle_variant', 
						 'class' => 'form-inline',
						'style' => 'text-align:center; margin-top:30px;',
						 'inputDefaults' => array(
							'div' => 'form-group',
							'wrapInput' => false,
							'class' => 'form-control',
							'label' => false,))); ?>



	                    <!-- The amount input -->
	                    <?php echo $this->Form->input('amount', array('value' => 1, 'type' => 'number', 'label' => false, 'style' => 'width:100px;', 'before' => '<label style="position:relative; left:40px; bottom:30px;">Antal</label>')); ?>

	                    <!-- The id input (hidden) -->
	                    <?php echo $this->Form->input('needle_variant_id', array('label' => false, 'value' => $needle_variant['id'], 'type' => 'text', 'style' => 'display:none;')); ?>
	                    
	                    <!-- The refresh button  -->
	                    <button type="submit" class="btn btn-default" style="margin-top:-2px;">
	                    	<span class="glyphicon glyphicon-shopping-cart"></span> Læg i kurv 
	                    </button>
	                    <?php echo $this->Form->end(); ?>

	                    <!-- Modal -->
						<div class="modal fade" id="image_popup_modal" tabindex="-1" role="dialog" aria-labelledby="image_popup_modal_label">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-body">
										<img id="image_popup" style="width:100%;" src="<?php echo $this->Html->url('/img/needle_variants/'. $needle_variant['id'].'.png'); ?>"/>
									</div>
								</div>
							</div>
						</div>

	                    <script type="text/javascript">
						$( document ).ready(function() {
						    	$('#price').text("<?php echo $this->Number->currency($needle_variant['price'], 'DKK');?>");
						    	if(<?php echo $needle_variant['show_discount']; ?> && <?php echo $needle_variant['discount']; ?> > 0)
						    	{
						    		$('#previous_price_container').show();
						    		$('#previous_price').text("<?php echo $this->Number->currency($needle_variant['previous_price'], 'DKK');?>");

						    		$('#discount').show();
						    		$('#discount').text("- <?php echo $needle_variant['discount']; ?>%");
						    	}
						    	else
						    	{
						    		$('#previous_price_container').hide();
						    		$('#discount').hide();
						    	}
						    	$('#product_code').text("<?php echo $needle_variant['product_code'];?>");
						    	$('#intern_product_code').text("<?php echo $needle_variant['intern_product_code'];?>");
						    	if(<?php echo $needle_variant['item_quantity']; ?> > 1)
						    	{
						    		$("#item_quantity").css("display", "block");
						    		$("#item_quantity").text("Der er <?php echo $needle_variant['item_quantity'] ?> produkter i denne varer");
						    	}
						    	$('#availability').text("<?php

						    	 		echo $needle_variant['AvailabilityCategory']['name'];  
						    	 		if($needle_variant['AvailabilityCategory']['show_amount'])
						    	 		{
						    	 			echo ' ('. $needle_variant['stock_quantity'] . ' tilbage)';
						    	 		}
						    	 ?>");
						    	$("#availability").removeClass();
						    	$('#availability').addClass("label label-<?php echo $needle_variant['AvailabilityCategory']['color']; ?>");
						    	if("<?php echo $needle_variant['length'];?>" > 0)
						    	{
						    		$("#length_row").show();
						    		$("#length_title").text("Længde på <?php if($needle_variant['is_wire']) echo 'wire'; else echo 'pind';?> cm");
							 		$("#length").text("<?php echo $needle_variant['length'];?> cm");
						    	}
						    	else
						    	{
						    		$("#length_row").hide();
						    	}
						    	
							 	$("#girth").text("<?php echo $needle_variant['girth']; if($needle_variant['girth_max'] > 0){echo ' - '. $needle_variant['girth_max'];} ?> mm");
			
							});	
						</script>
						
				</div>



				<div id="sale2" class="well clearfix visible-xs">
					<div class="row-fluid" >
						<p id="previous_price_container2">Før <s id="previous_price2">DKK100,00</s></p>
						<label id="price2" style="font-size:1.3em;position:relative; top:3px;"></label> 
						<span style="line-height:35px; margin:0;" class="label" id="availability2"></span>
						<p id="item_quantity2" style="display:none;" class="text-muted"></p>
					</div>
					
					<?php echo $this->Form->create('Orders', array(
						'action' => 'add_needle_variant', 
						 'class' => 'form-inline',
						 'inputDefaults' => array(
							'div' => 'form-group',
							'wrapInput' => false,
							'class' => 'form-control',
							'label' => false,))); ?>



	                    <!-- The amount input -->
                        <?php echo $this->Form->input('amount', array( 'div' => false, 'value' => 1, 'type' => 'number', 'label' => false, 'before' => '<label style="position:relative; float:left; ">Antal</label>')); ?>

	                    <!-- The id input (hidden) -->
	                    <?php echo $this->Form->input('needle_variant_id', array('label' => false, 'value' => $needle_variant['id'], 'type' => 'text', 'style' => 'display:none;')); ?>
	                    
	                    <!-- The refresh button  -->
	                    <button type="submit" class="btn btn-default" style="margin-top:-2px;">
	                    	<span class="glyphicon glyphicon-shopping-cart"></span> Læg i kurv 
	                    </button>
	                    <?php echo $this->Form->end(); ?>

	                    <!-- Modal -->
						<div class="modal fade" id="image_popup_modal" tabindex="-1" role="dialog" aria-labelledby="image_popup_modal_label">
							<div class="modal-dialog" role="document">
								<div class="modal-content">
									<div class="modal-body">
										<img id="image_popup" style="width:100%;" src="<?php echo $this->Html->url('/img/needle_variants/'. $needle_variant['id'].'.png'); ?>"/>
									</div>
								</div>
							</div>
						</div>

	                    <script type="text/javascript">
						$( document ).ready(function() {
						    	$('#price2').text("<?php echo $this->Number->currency($needle_variant['price'], 'DKK');?>");
						    	if(<?php echo $needle_variant['show_discount']; ?> && <?php echo $needle_variant['discount']; ?> > 0)
						    	{
						    		$('#previous_price_container2').show();
						    		$('#previous_price2').text("<?php echo $this->Number->currency($needle_variant['previous_price'], 'DKK');?>");

						    		$('#discount').show();
						    		$('#discount').text("- <?php echo $needle_variant['discount']; ?>%");
						    	}
						    	else
						    	{
						    		$('#previous_price_container2').hide();
						    		$('#discount').hide();
						    	}
						    	$('#product_code').text("<?php echo $needle_variant['product_code'];?>");
						    	$('#intern_product_code').text("<?php echo $needle_variant['intern_product_code'];?>");
						    	if(<?php echo $needle_variant['item_quantity']; ?> > 1)
						    	{
						    		$("#item_quantity2").css("display", "block");
						    		$("#item_quantity2").text("Der er <?php echo $needle_variant['item_quantity'] ?> produkter i denne varer");
						    	}
						    	$('#availability2').text("<?php

						    	 		echo $needle_variant['AvailabilityCategory']['name'];  
						    	 		if($needle_variant['AvailabilityCategory']['show_amount'])
						    	 		{
						    	 			echo ' ('. $needle_variant['stock_quantity'] . ' tilbage)';
						    	 		}
						    	 ?>");
						    	$("#availability2").removeClass();
						    	$('#availability2').addClass("label label-<?php echo $needle_variant['AvailabilityCategory']['color']; ?>");
						    	if("<?php echo $needle_variant['length'];?>" > 0)
						    	{
						    		$("#length_row").show();
						    		$("#length_title").text("Længde på <?php if($needle_variant['is_wire']) echo 'wire'; else echo 'pind';?> cm");
							 		$("#length").text("<?php echo $needle_variant['length'];?> cm");
						    	}
						    	else
						    	{
						    		$("#length_row").hide();
						    	}
						    	
							 	$("#girth").text("<?php echo $needle_variant['girth']; if($needle_variant['girth_max'] > 0){echo ' - '. $needle_variant['girth_max'];} ?> mm");
			
							});	
						</script>
						
				</div>



			<?php break; ?>
			<?php endif;?>
		<?php endforeach; ?>
	</div>
</div>


<div class="row">
	<?php if($logged_in) : ?>
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:20px;">
			<div class="thumbnail" id="new_needle_image" style="display: table; width: 100%; text-align: center;">
				
					<a style="display: table-cell; vertical-align: middle; height:100%;"
				    href=" <?php echo $this->Html->url(array(
				                            'controller' => 'needle_variants',
				                            'action' => 'add', $needle['Needle']['id']
				                        )); ?>"
				    type="button" class="btn btn-success btn-lg btn-block">
				   	<span style="" class="glyphicon glyphicon-plus"></span>
				</a>
			</div>
		</div>
		
	<?php endif; ?>
	
	<?php foreach ($needle['NeedleVariant'] as $key => $needle_variant) : ?>
		<?php if($needle_variant['is_active']) : ?>
		
		<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:20px;">
				<div
				 id="needle_variant_image<?php echo $needle_variant['id'];?>" 
				 class="thumbnail product_container" 
				 style="width:100%; cursor:pointer;">
					<div style="width:100%; height:100%;background: url(<?php echo $this->Html->url('/img/needle_variants/'.$needle_variant['id'].'.png'); ?>); background-repeat:no-repeat; background-position: center; background-size:cover;">

						<!-- Operations if logged in-->
						<?php if($logged_in) : ?>
							<div style="padding:10px;">
								<!-- Edit -->
								<?php echo $this->Html->link(
					            '<span class="glyphicon glyphicon-pencil"></span>',
					            array('controller' => 'needle_variants', 'action' => 'edit', $needle_variant['id']),
					            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
					            );?>

					            <!-- Delete -->
								<?php echo $this->Html->link(
									'<span class="glyphicon glyphicon-trash"></span>',
									array('controller' => 'needle_variants', 'action' => 'delete', $needle_variant['id']),
									array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
									'Er du sikker på du vil slette denne garnvariant?'
								);?>

							</div>
						<?php endif;?>
						

						<h4 id="needle_variant_title<?php echo $needle_variant['id'];?>" class="product_title ">
							<?php echo $needle_variant['girth']; if($needle_variant['girth_max'] > 0){echo ' - '. $needle_variant['girth_max'];} ?> mm
						</h4>

					</div>
				</div>
		</div>

		<?php  $needle_variant_availability_string = '';


		    	if($needle_variant['AvailabilityCategory']['show_amount'])
				{
					$needle_variant_availability_string = $needle_variant_availability_string . ' ('. $needle_variant['stock_quantity']. ' tilbage)';
				}

			?>

		<!-- Script to handle the scaling -->
		<script type="text/javascript"> 

			
			
			var scale = 0.63; 
			$('#needle_variant_image<?php echo $needle_variant['id'];?>').height($('#needle_variant_image<?php echo $needle_variant['id'];?>').width() * 0.63);
			$('#new_needle_image').height($('#needle_variant_image<?php echo $needle_variant['id'];?>').width() * scale);
			
			$('#needle_variant_title<?php echo $needle_variant['id'];?>').width($('#needle_variant_image<?php echo $needle_variant['id'];?>').width());

			$('#active_image_container').height($('#active_image_container').width() * scale);

			$('#needle_variant_image<?php echo $needle_variant['id'];?>').click(function()
			{
				$("#active_title").text("<?php echo $needle_variant['girth']; if($needle_variant['girth_max'] > 0){echo ' - '. $needle_variant['girth_max'];} ?> mm");
				$('#active_image').css("background-image", "url(<?php echo $this->Html->url('/img/needle_variants/'.$needle_variant['id'].'.png');?>)");
				$("#image_popup").attr("src",  "<?php echo $this->Html->url('/img/needle_variants/'.$needle_variant['id'].'.png');?>");
			 	
				if("<?php echo $needle_variant['length'];?>" > 0)
		    	{
		    		$("#length_row").show();
		    		$("#length_title").text("Længde på <?php if($needle_variant['is_wire']) echo 'wire'; else echo 'pind';?> cm");
			 		$("#length").text("<?php echo $needle_variant['length'];?> cm");
		    	}
		    	else
		    	{
		    		$("#length_row").hide();
		    	}

			 	$("#girth").text("<?php echo $needle_variant['girth']; if($needle_variant['girth_max'] > 0){echo ' - '. $needle_variant['girth_max'];} ?> mm");
			 	$('#price').text("<?php echo $this->Number->currency($needle_variant['price'], 'DKK');?>");
			 	if(<?php echo $needle_variant['show_discount']; ?> && <?php echo $needle_variant['discount']; ?> > 0)
		    	{
		    		$('#previous_price_container').show();
		    		$('#previous_price').text("<?php echo $this->Number->currency($needle_variant['previous_price'], 'DKK');?>");

		    		$('#discount').show();
		    		$('#discount').text("- <?php echo $needle_variant['discount']; ?>%");
		    	}
		    	else
		    	{
		    		$('#previous_price_container').hide();
		    		$('#discount').hide();
		    	}
			 	$('#product_code').text("<?php echo $needle_variant['product_code'];?>");
				$('#intern_product_code').text("<?php echo $needle_variant['intern_product_code'];?>");
		    	$('#availability').text("<?php echo $needle_variant['AvailabilityCategory']['name'] .''. $needle_variant_availability_string; ?>");
		    	$('#availability').removeClass();
		    	$('#availability').addClass("label label-<?php echo $needle_variant['AvailabilityCategory']['color']; ?>");
		    	$("#OrdersNeedleVariantId").val("<?php echo $needle_variant['id']; ?>")

		    	if(<?php echo $needle_variant['item_quantity']; ?> > 1)
		    	{
		    		$("#item_quantity").css("display", "block");
		    		$("#item_quantity").text("Der er <?php echo $needle_variant['item_quantity'] ?> produkter i denne varer");
		    	}
		    	else
		    	{
		    		$("#item_quantity").css("display", "none");
		    	}

                window.scrollTo(0, 0);

			});
		
		
			$( window ).resize(function() {
		  		$('#needle_variant_image<?php echo $needle_variant['id'];?>').height($('#needle_variant_image<?php echo $needle_variant['id'];?>').width()) * scale;
		  		$('#new_needle_image').height($('#needle_variant_image<?php echo $needle_variant['id'];?>').width() * scale);
				$('#needle_variant_title<?php echo $needle_variant['id'];?>').width($('#needle_variant_image<?php echo $needle_variant['id'];?>').width()-20);
				$('#active_image_container').height($('#active_image_container').width() * scale);
			});

		</script>
		<?php endif;?>
	
	<?php endforeach; ?>

</div>

<?php /*

<?php debug('OLD STUFF BELOW'); ?>
<h1 class="page-header">
	<span><?php echo $needle['Needle']['name'] ?></span>

	<span class="pull-right">
		<!-- Add -->
		<?php echo $this->Html->link(
		'<span class="glyphicon glyphicon-plus-sign"></span>',
		array('controller' => 'needle_variants', 'action' => 'add', $needle['Needle']['id']),
		array('class' => 'btn btn-success btn-sm', 'escapeTitle' => false,)
		);?>
	</span>

</h1>
<!-- yarn information -->
<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
	<table class="table">
		<thead>
			<th colspan="2">Information</th>
		</thead>
		<tbody>
			<tr>
				<td>#</td>
				<td><?php echo $needle['Needle']['id']; ?></td>
			</tr>
			<tr>
				<td>Navn</td>
				<td><?php echo $needle['Needle']['name']; ?></td>
			</tr>
			<tr>
				<td>Menu</td>
				<td><?php echo $needle['Menu']['name']; ?></td>
			</tr>
			<tr>
				<td>Mærke</td>
				<td><?php echo $needle['Brand']['name']; ?></td>
			</tr>
			<tr>
				<td>Materiale</td>
				<td><?php echo $needle['Material']['name']; ?></td>
			</tr>
			<tr>
				<td>Oprettet</td>
				<td><?php echo $needle['Needle']['created']; ?></td>
			</tr>
			<tr>
				<td>Opdateret</td>
				<td><?php echo $needle['Needle']['modified']; ?></td>
			</tr>
		</tbody>
	</table>
</div>

<div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
	<table class="table">
		<thead>
			<th>Produktkode</th>
			<th>Pris</th>
			<th>Billede</th>
			<th>Operationer</th>
		</thead>
		<tbody>
			<?php foreach($needle['NeedleVariant'] as $i => $needle_variant) : ?>
					<?php if($needle_variant['is_active']) :?>
						<tr>
							<td><?php echo $needle_variant['product_code']; ?></td>
							<td><?php echo $needle_variant['price']; ?></td>
							<td>

							<!-- current uploaded file -->
							<?php echo $this->Html->image('/img/needle_variants/'.$needle_variant['id'].'.png', array('style' => 'width:150px', 'class' => 'thumbnail'));?>
							</td>
						<td>

							<!-- Add To Cart -->
							<?php echo $this->Html->link(
				            '<span class="glyphicon glyphicon-plus"></span>',
				            array('controller' => 'orders', 'action' => 'add_needle_variant', $needle_variant['id']),
				            array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
				            );?>

							<!-- Edit -->
							<?php echo $this->Html->link(
					        '<span class="glyphicon glyphicon-pencil"></span>',
					        array('controller' => 'needle_variants', 'action' => 'edit', $needle_variant['id']),
				    	    array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
				            );?>

				            <!-- Delete -->
							<?php echo $this->Html->link(
								'<span class="glyphicon glyphicon-trash"></span>',
								array('controller' => 'needle_variants', 'action' => 'delete', $needle_variant['id']),
								array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
								'Er du sikker på du vil slette denne strikkepindsvariant?'
							);?>
						</td>
						</tr>
					<?php endif; ?>
					
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
*/ ?>
