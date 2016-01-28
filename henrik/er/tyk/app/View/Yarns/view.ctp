<script type="text/javascript">
    <div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.4&appId=349172198526012";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
</script>
<h1 class="page-header">
    <span><?php echo $yarn['Yarn']['name']; ?></span>
    <?php if($logged_in) : ?>
        <span class="pull-right">
            <!-- Add -->
            <?php echo $this->Html->link(
            '<span class="glyphicon glyphicon-pencil"></span>',
            array('controller' => 'yarns', 'action' => 'edit', $yarn['Yarn']['id']),
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
                <!-- If the yarn have no gugage do not show it -->
                <?php if($yarn['Yarn']['gauge_masks'] != 0 || $yarn['Yarn']['gauge_rows'] != 0) : ?>
                    <tr>
                        <td>Strikkefasthed (10 x 10 cm)</td>
                        <td>
                        <?php if($yarn['Yarn']['gauge_masks'] != 0) : ?>
                            <?php echo $yarn['Yarn']['gauge_masks'] . ' m'; ?>
                        <?php endif; ?>
                        <?php if($yarn['Yarn']['gauge_masks'] != 0 && $yarn['Yarn']['gauge_rows'] != 0) : ?>
                            <?php echo ' x '; ?>
                        <?php endif; ?>
                        <?php if($yarn['Yarn']['gauge_rows'] != 0) : ?>
                            <?php echo $yarn['Yarn']['gauge_rows'] . ' r'; ?>
                        <?php endif; ?>

                        </td>
                    </tr>
                <?php endif; ?>
                <!-- If the yarn have needle-size do not show it -->
                <?php if($yarn['Yarn']['needle_size_min'] != 0 || $yarn['Yarn']['needle_size_max'] != 0) : ?>
                    <tr>
                        <td>Pindestørrelse</td>
                        <td><?php echo $yarn['Yarn']['needle_size_min'] . ' - ' . $yarn['Yarn']['needle_size_max']; ?></td>
                    </tr>
                <?php endif; ?>

                <tr>
                    <td>Løbelængde pr. 50 g</td>
                    <td><?php echo $yarn['Yarn']['length']; ?> cm</td>
                </tr>
                <tr>
                    <td>Vægt</td>
                    <td><?php echo $yarn['Yarn']['weight']; ?> g</td>
                </tr>
                <tr>
                    <td>Mærke</td>
                    <td><?php echo $yarn['Brand']['name']; ?></td>
                </tr>
                <tr>
                    <th colspan="2"> Materiale indhold</th>
                </tr>
                <?php foreach ($yarn['YarnPart'] as $key => $yarn_part) : ?>
                    <tr>
                        <td><?php echo $yarn_part['Material']['name']; ?></td>
                        <td><?php echo $yarn_part['percentage']?> %</td>
                    </tr>
                <?php endforeach; ?>
                <?php if(!empty($yarn['CareLabel'])) : ?>
                    <tr>
                        <th colspan="2"> Vaskemærker</th>
                    </tr>
                    
                        <tr>
                            <td colspan="2">
                                <?php foreach($yarn['CareLabel'] as $i => $care_label) : ?>
                                    <?php echo $this->Html->image('/img/care_labels/'.$care_label['id'].'.png', 
                                        array('style' => 'width:30px; display:inline; margin:0;',
                                              'data-toggle' => 'tooltip',
                                              'id' => 'care_label_'.$care_label['id'],
                                              'data-placement' => 'top',
                                              'title' => $care_label['name']));?>
                                    <script type="text/javascript">
                                    $("#care_label_<?php echo $care_label['id']; ?>").tooltip();
                                    </script>
                                <?php endforeach; ?>

                            </td>
                        </tr>
                    
                <?php endif; ?>
                <tr>
                    <th colspan="2"> Vareinformation</th>
                </tr>
                <tr>
                    <td>Farvenummer</td>
                    <td id="color_code"></td>
                <tr>
                <tr>
                    <td>Partinummer</td>
                    <td id="batch_code"></td>
                <tr>
                <tr>
                    <td>Varenummer</td>
                    <td id="intern_product_code"></td>
                <tr>
                    <th colspan="2">Farveprøve</th>
                </tr>
                <tr>
                    <td>
                        <span id="color_sample_price">
                            <?php echo $this->Number->currency($yarn['Yarn']['price'], 'DKK');?>
                        </span>
                    </td>
                    <td>
                        <a  id="color_sample_button" href="
                        <?php echo $this->Html->url(array('controller' => 'orders', 'action' => 'add_color_sample', $yarn['Yarn']['id'])); ?>
                        " class="btn btn-xs btn-success"><span class="glyphicon glyphicon-tint"></span> <span>Køb farveprøve</span></a>
                    </td>
            </tbody>
        </table>
        <div  style="margin-bottom:20px;"class="fb-like" data-href="
        <?php echo $this->Html->url(array("controller" => "yarns",
                                             "action" => "view",
                                             $yarn['Yarn']['id']), 
                                             true); ?>
        " data-layout="button" data-action="like" data-show-faces="false" data-share="true"></div>
    </div>
    <div id="product" class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
        
        <?php foreach ($yarn['YarnVariant'] as $key => $yarn_variant) : ?>
            <?php if($yarn_variant['is_active'] && ($yarn_variant['product_count'] > 0 || $logged_in)) : ?>
                <span style="display:none;" id="discount" class="discount">50%</span>
                <div id="active_image_container" class="thumbnail product_container" style="width:100%;" data-toggle="modal" data-target="#image_popup_modal">
                    <div id="active_image" style="width:100%; height:100%; background-image: url('<?php echo $this->Html->url('/img/yarn_variants/'. $yarn_variant['id'].'.png'); ?>'); background-repeat:no-repeat; background-position: center; background-size:cover;"></div>
                    <h4 id="active_title" class="product_title" style="position:relative; bottom:40px;">
                        <?php echo $yarn_variant['color_code']; ?>
                    </h4>
                </div>
                <div id="sale" class="well">
                    <div class="row-fluid" style="text-align:center; vertical-align:middle;">
                        <p style="margin:0;display:none;" id="previous_price_container">Før <s id="previous_price">DKK100,00</s></p>
                        <label id="price" style="font-size:1.3em;position:relative; top:3px;"></label> 
                        <span style="line-height:35px; margin:0;" class="label" id="availability"></span>
                        <p id="item_quantity" style="display:none;" class="text-muted"></p>
                    </div>
                    <div class="row-fluid" style="text-align:center; vertical-align:middle;">
                    </div>
                    <?php echo $this->Form->create('Orders', array(
                        'action' => 'add_yarn_batch', 
                         'class' => 'form-inline',
                         'style' => 'text-align:center; margin-top:30px;',
                         'inputDefaults' => array(
                            'div' => 'form-group',
                            'wrapInput' => false,
                            'class' => 'form-control',
                            'label' => false,))); ?>

                        <?php 
                        $options = array();
                        $first_yarn_batch = null;
                        foreach ($yarn_variant['YarnBatch'] as $key => $yarn_batch) 
                        {
                            if($yarn_batch['is_active']) {
                                $options[$yarn_batch['id']] = $yarn_batch['batch_code'];    
                                if($first_yarn_batch == null)
                                {
                                    $first_yarn_batch = $yarn_batch;
                                }
                            }

                        } ?>

                        <script type="text/javascript">
                        $( document ).ready(function() {
                                $('#price').text("<?php echo $this->Number->currency($first_yarn_batch['price'], 'DKK');?>");
                                if(<?php echo $first_yarn_batch['show_discount']; ?> && <?php echo $first_yarn_batch['discount']; ?> > 0)
                                {
                                    $('#previous_price_container').show();
                                    $('#previous_price').text("<?php echo $this->Number->currency($first_yarn_batch['previous_price'], 'DKK');?>");

                                    $('#discount').show();
                                    $('#discount').text("- <?php echo $first_yarn_batch['discount']; ?>%");
                                }
                                else
                                {
                                    $('#previous_price_container').hide();
                                    $('#discount').hide();
                                }
                                
                                
                                $('#color_code').text("<?php echo $yarn_variant['color_code']; ?>");
                                if(<?php echo $first_yarn_batch['item_quantity']; ?> > 1)
                                {
                                    $("#item_quantity").css("display", "block");
                                    $("#item_quantity").text("Der er <?php echo $first_yarn_batch['item_quantity'] ?> produkter i denne varer");
                                }
                                $('#availability').text("<?php

                                        echo $first_yarn_batch['AvailabilityCategory']['name'];  
                                        if($first_yarn_batch['AvailabilityCategory']['show_amount'])
                                        {
                                            echo ' ('. $first_yarn_batch['stock_quantity'] . ' tilbage)';
                                        }
                                 ?>");
                                $("#availability").removeClass();
                                $('#availability').addClass("label label-<?php echo $first_yarn_batch['AvailabilityCategory']['color']; ?>");

                                $("#batch_code").text("<?php echo $first_yarn_batch['batch_code'];?>");
                                $("#intern_product_code").text("<?php echo $first_yarn_batch['intern_product_code'];?>");
            
                            }); 
                        </script>

                        <!-- The amount input -->
                        <?php echo $this->Form->input('amount', array('value' => 1, 'type' => 'number', 'label' => false, 'style' => 'width:60px;', 'before' => '<label style="position:relative; left:40px; bottom:30px;">Antal</label>')); ?>

                        <!-- The id input  -->
                        <?php echo $this->Form->input('yarn_batch_id', array('style' => 'width:100px;', 'id' => 'yarn_batch_input','required' => true, 'label' => false, 'options' => $options, 'before' => '<label style="position:relative; left:5px; bottom:30px; width:0;">Partinummer</label>')); ?>
                        
                        <!-- The refresh button  -->
                        <button type="submit" class="btn btn-default">
                            <span class="glyphicon glyphicon-shopping-cart"></span> Læg i kurv 
                        </button>
                    <?php echo $this->Form->end(); ?>


                    <!-- Modal -->
                    <div class="modal fade" id="image_popup_modal" tabindex="-1" role="dialog" aria-labelledby="image_popup_modal_label">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-body">
                                    <img id="image_popup" style="width:100%;" src="<?php echo $this->Html->url('/img/yarn_variants/'. $yarn_variant['id'].'.png'); ?>"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php break; ?>
            <?php endif;?>
        <?php endforeach; ?>
    </div>
</div>


<div class="row">
    <?php if($logged_in) : ?>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
            <div class="thumbnail" id="new_yarn_image" style="display: table; width: 100%; text-align: center;">
                
                    <a style="display: table-cell; vertical-align: middle; height:100%;"
                    href=" <?php echo $this->Html->url(array(
                                            'controller' => 'yarn_variants',
                                            'action' => 'add', $yarn['Yarn']['id']
                                        )); ?>"
                    type="button" class="btn btn-success btn-lg btn-block">
                    <span style="" class="glyphicon glyphicon-plus"></span>
                </a>
            </div>
        </div>
        
    <?php endif; ?>
    
    <?php foreach ($yarn['YarnVariant'] as $key => $yarn_variant) : ?>
        <?php if(!$yarn_variant['is_active']) { continue; } ?>
        <?php 
            $skip = true;
            foreach ($yarn_variant['YarnBatch'] as $key => $yarn_batch) 
            {
                if($yarn_batch['is_active'])
                {
                    $skip = false;
                    break;
                }
            }

        ?>
        <?php if(!$skip || $logged_in) : ?>
        <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                <div
                 id="yarn_variant_image<?php echo $yarn_variant['id'];?>" 
                 class="thumbnail product_container" 
                 style="width:100%; cursor:pointer;">
                    <div style="width:100%; height:100%;background: url(<?php echo $this->Html->url('/img/yarn_variants/'.$yarn_variant['id'].'.png'); ?>); background-repeat:no-repeat; background-position: center; background-size:cover;" 
                     <?php if($logged_in && !($yarn_variant['product_count'] > 0)) 
                    {
                    echo 'id="yarn_variant_warning'. $yarn_variant['id']. '" data-toggle="tooltip" data-placement="top" title="Vises ikke til brugeren"';
                    }?>
                    >

                        <!-- Operations if logged in-->
                        <?php if($logged_in) : ?>
                            <div style="padding:10px;">
                                <!-- Edit -->
                                <?php echo $this->Html->link(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                array('controller' => 'yarn_variants', 'action' => 'edit', $yarn_variant['id']),
                                array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                                );?>

                                <!-- Delete -->
                                <?php echo $this->Html->link(
                                    '<span class="glyphicon glyphicon-trash"></span>',
                                    array('controller' => 'yarn_variants', 'action' => 'delete', $yarn_variant['id']),
                                    array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
                                    'Er du sikker på du vil slette denne garnvariant?'
                                );?>

                                <?php if(!$yarn_variant['product_count'] > 0) : ?>
                                    <!-- Info -->
                                    <a href="#" class="btn btn-xs btn-warning">
                                        <span class="glyphicon glyphicon-eye-close"></span>
                                    </a>
                                <?php endif; ?>
                                <?php if(!$yarn_variant['is_thumbnail']) : ?>
                                    <!-- MakeThumbnail -->
                                    <?php echo $this->Html->link(
                                        '<span class="glyphicon glyphicon-star"></span>',
                                        array('controller' => 'yarn_variants', 'action' => 'make_thumbnail', $yarn_variant['id']),
                                        array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false),
                                        'Er du sikker på du vil gøre denne til thumbnail?'
                                    );?>
                                <?php endif; ?>

                            </div>
                        <?php endif; ?>

                        <h4 id="yarn_variant_title<?php echo $yarn_variant['id'];?>" class="product_title ">
                            <?php echo $yarn_variant['color_code']; ?>
                        </h4>

                    </div>
                </div>
            </div>

            <?php 
            $yarn_batch_options = "";
            $yarn_batch_change_script = "";
            foreach ($yarn_variant['YarnBatch'] as $key => $yarn_batch) 
            {
                if(!$yarn_batch['is_active']) {continue;}
                
                $yarn_batch_options = $yarn_batch_options . '<option value="'. $yarn_batch['id'].'">'.$yarn_batch['batch_code'].'</option>';
                $yarn_batch_change_script = $yarn_batch_change_script . ' if ($("#yarn_batch_input").val() == "'.$yarn_batch['id'].'") {
                    $("#price").text("DKK '.$yarn_batch['price'].'");
                    if('.$first_yarn_batch['show_discount'] .' && '. $first_yarn_batch['discount'] .' > 0)
                    {
                        $("#previous_price_container").show();
                        $("#previous_price").text("'. $this->Number->currency($first_yarn_batch['previous_price'], 'DKK').'");
                        
                        $("#discount").show();
                        $("#discount").text("- '. $first_yarn_batch['discount'].'%");
                    }
                    else
                    {
                        $("#previous_price_container").hide();
                        $("#discount").hide();
                    }
                    $("#batch_code").text("'. $yarn_batch['batch_code'] .'");
                    $("#intern_product_code").text("'. $yarn_batch['intern_product_code'] .'");
                    if('.$yarn_batch['item_quantity'].' > 1)
                    {
                        $("#item_quantity").show();
                        $("#item_quantity").text("Der er '. $yarn_batch['item_quantity'].' produkter i denne varer");
                    } 
                    else
                    {
                        $("#item_quantity").hide();
                    }
                    $("#availability").text("'. $yarn_batch['AvailabilityCategory']['name'];


                    if($yarn_batch['AvailabilityCategory']['show_amount'])
                    {
                        $yarn_batch_change_script = $yarn_batch_change_script . ' ('. $yarn_batch['stock_quantity']. ' tilbage)';
                    }


                    $yarn_batch_change_script = $yarn_batch_change_script . '");
                    $("#availability").removeClass();
                    $("#availability").addClass("label label-'. $yarn_batch['AvailabilityCategory']['color'] .'");
                } ';

            } ?>


            <!-- Script to handle the scaling -->
            <script type="text/javascript">

                $('#yarn_variant_warning<?php echo $yarn_variant['id'];?>').tooltip();
                var scale = 0.63; 
                $('#yarn_variant_image<?php echo $yarn_variant['id'];?>').height($('#yarn_variant_image<?php echo $yarn_variant['id'];?>').width() * scale);
                $('#new_yarn_image').height($('#yarn_variant_image<?php echo $yarn_variant['id'];?>').width() * scale);
                
                $('#yarn_variant_title<?php echo $yarn_variant['id'];?>').width($('#yarn_variant_image<?php echo $yarn_variant['id'];?>').width());

                $('#active_image_container').height($('#active_image_container').width() * scale);
                $('#active_title').width($('#active_image_container').width());

                $('#yarn_variant_image<?php echo $yarn_variant['id'];?>').click(function()
                {
                  $('#active_image').css("background-image", "url(<?php echo $this->Html->url('/img/yarn_variants/'.$yarn_variant['id'].'.png');?>)");
                  $( "#yarn_batch_input" ).empty();
                  $( "#yarn_batch_input" ).append('<?php echo $yarn_batch_options; ?>');
                  $('#active_title').text("<?php echo $yarn_variant['color_code']; ?>");
                  $('#color_code').text("<?php echo $yarn_variant['color_code']; ?>");
                  $("#image_popup").attr("src", "<?php echo $this->Html->url('/img/yarn_variants/'.$yarn_variant['id'].'.png');?>");


                  <?php 
                        foreach ($yarn_variant['YarnBatch'] as $key => $yarn_batch) 
                        {
                            if(!$yarn_batch['is_active']) {continue;}
                            echo '$("#batch_code").text("'. $yarn_batch['batch_code']. '");';
                            echo '$("#intern_product_code").text("'. $yarn_batch['intern_product_code']. '");';
                            echo '$("#price").text("'.$this->Number->currency($yarn_batch['price'], 'DKK').'");
                            if('.$yarn_batch['show_discount'] .' && '. $yarn_batch['discount'] .' > 0)
                            {
                                $("#previous_price_container").show();
                                $("#previous_price").text("'. $this->Number->currency($yarn_batch['previous_price'], 'DKK').'");
                                
                                $("#discount").show();
                                $("#discount").text("- '. $yarn_batch['discount'].'%");
                            }
                            else
                            {
                                $("#previous_price_container").hide();
                                $("#discount").hide();
                            }
                            $("#availability").text("'. $yarn_batch['AvailabilityCategory']['name'];
                            if($yarn_batch['AvailabilityCategory']['show_amount'])
                            {
                                echo ' ('. $yarn_batch['stock_quantity'] . ' tilbage)';
                            }

                            echo '");
                            if('.$yarn_batch['item_quantity'].' > 1)
                            {
                                $("#item_quantity").show();
                                $("#item_quantity").text("Der er '. $yarn_batch['item_quantity'].' produkter i denne varer");
                            } 
                            else
                            {
                                $("#item_quantity").hide();
                            }
                            $("#availability").removeClass();
                            $("#availability").addClass("label label-'. $yarn_batch['AvailabilityCategory']['color'] .'");';

                            break;
                        }
                    ?>

                });
            
            
                $( window ).resize(function() {
                    $('#yarn_variant_image<?php echo $yarn_variant['id'];?>').height($('#yarn_variant_image<?php echo $yarn_variant['id'];?>').width()* scale);
                    $('#new_yarn_image').height($('#yarn_variant_image<?php echo $yarn_variant['id'];?>').width() * scale);
                    $('#yarn_variant_title<?php echo $yarn_variant['id'];?>').width($('#yarn_variant_image<?php echo $yarn_variant['id'];?>').width()-20);
                    $('#active_image_container').height($('#active_image_container').width() * scale);
                    $('#active_title').width($('#active_image_container').width());
                });

                $("#yarn_batch_input").change(function() {
                    <?php echo $yarn_batch_change_script; ?>
                });

            </script>
    <?php endif;?>
    <?php endforeach; ?>
</div>

<?php if(!empty($yarn['Recipe'])) : ?>
    <h4 class="page-header"><strong>Opskrifter til denne garnnøgle</strong></h4>
    <div class="row h-scroll">

    <?php 
        $ctr=0; 
        shuffle($yarn['Recipe']);
        foreach ($yarn['Recipe'] as $key => $recipe) : ?>
        <?php if($ctr>=12) break; else $ctr++;  ?>
        <?php if($recipe['is_active']) : ?>
            <div class="col-lg-2 col-md-4 col-sm-6 col-xs-12">
                <div id="recipe_image<?php echo $recipe['id'];?>" 
                     class="thumbnail product_container" 
                     style="width:100%; cursor:pointer;"
                     onclick="window.location = '<?php echo $this->Html->url(array('controller' => 'recipes', 'action' => 'view', $recipe['id'])); ?>';">
                        <div style="width:100%; height:100%;background: url(<?php echo $this->Html->url('/img/recipes/'.$recipe['id'].'.png'); ?>); background-repeat:no-repeat; background-position: center; background-size:cover;">
                            <p class="product_title" id="recipe_title<?php echo $recipe['id'];?>">
                                <?php echo $recipe['name']; ?>
                            </p>
                        </div>
                </div>
            </div>
        <?php endif; ?>

        <script type="text/javascript">
            var recipe_scale = 1.4;
            $('#recipe_image<?php echo $recipe['id'];?>').height($('#recipe_image<?php echo $recipe['id'];?>').width() * recipe_scale);
            $('#recipe_title<?php echo $recipe['id'];?>').width($('#recipe_image<?php echo $recipe['id'];?>').width());
        
        
            $( window ).resize(function() {
                $('#recipe_image<?php echo $recipe['id'];?>').height($('#recipe_image<?php echo $recipe['id'];?>').width() * recipe_scale);
                $('#recipe_title<?php echo $recipe['id'];?>').width($('#recipe_image<?php echo $recipe['id'];?>').width()-20);
            });
        </script>

        <?php endforeach;?>
    </div>

    <br/>
<?php endif; ?>