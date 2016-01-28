<h1 class="page-header">
    <span><?php echo $title; ?></span>
    <?php if($logged_in) : ?>
        <span class="pull-right">
            <!-- Add -->
            <?php echo $this->Html->link(
            '<span class="glyphicon glyphicon-plus"></span>',
            array('controller' => 'yarns', 'action' => 'add'),
            array('class' => 'btn btn-success btn-sm', 'escapeTitle' => false,)
            );?>
        </span>
    <?php endif; ?>
</h1>

<h5><span class="text-muted">Klik på et billede for at vælge et produkt.</span></h5>
<hr/>

<div class="row">
    <?php foreach ($yarns as $key => $yarn) : ?>
        <?php if($yarn['Yarn']['product_count'] > 0 || $logged_in) : ?>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                <div
                 onclick="window.location = '<?php echo $this->Html->url(array('controller' => 'yarns', 'action' => 'view', $yarn['Yarn']['id'])); ?>';"
                 id="yarn_image<?php echo $yarn['Yarn']['id'];?>" 
                 class="thumbnail product_container" 
                 style="width:100%; cursor:pointer;">
                    <?php 
                    $image_url = '';
                    foreach ($yarn['YarnVariant'] as $key => $yarn_variant) 
                    {
                        if($yarn_variant['is_active'] && $yarn_variant['is_thumbnail'])
                        {
                            $image_url = $this->Html->url('/img/yarn_variants/'.$yarn_variant['id'].'.png');
                            break;
                        }
                    }

                    ?>
                    <div style="width:100%; height:100%;background: url(<?php echo $image_url ?>); background-repeat:no-repeat; background-position: center; background-size:cover;"
                    <?php if($logged_in && !($yarn['Yarn']['product_count'] > 0)) 
                    {
                        echo 'id="yarn_warning'. $yarn['Yarn']['id']. '" data-toggle="tooltip" data-placement="top" title="Vises ikke til brugeren"';
                    }?>
                    >
                        <!-- Operations if logged in-->
                        <?php if($logged_in) : ?>
                            <div style="padding:10px;">
                                <!-- Edit -->
                                <?php echo $this->Html->link(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                array('controller' => 'yarns', 'action' => 'edit', $yarn['Yarn']['id']),
                                array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                                );?>

                                <!-- Delete -->
                                <?php echo $this->Html->link(
                                    '<span class="glyphicon glyphicon-trash"></span>',
                                    array('controller' => 'yarns', 'action' => 'delete', $yarn['Yarn']['id']),
                                    array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
                                    'Er du sikker på du vil slette denne garnkvalitet?'
                                );?>

                                <?php if(!$yarn['Yarn']['product_count'] > 0) : ?>
                                    <!-- Info -->
                                    <a href="#" class="btn btn-xs btn-warning">
                                        <span class="glyphicon glyphicon-eye-close"></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            

                        <?php endif; ?>

                        <h4 id="yarn_title<?php echo $yarn['Yarn']['id'];?>" class="product_title ">
                            <?php echo $yarn['Yarn']['name']; ?>
                        </h4>

                    </div>
                </div>

                
                
            </div>

            <!-- Script to handle the scaling -->
            <script type="text/javascript">
                $('#yarn_warning<?php echo $yarn['Yarn']['id'];?>').tooltip();
                $('#yarn_image<?php echo $yarn['Yarn']['id'];?>').height($('#yarn_image<?php echo $yarn['Yarn']['id'];?>').width() * 0.63);
                $('#yarn_title<?php echo $yarn['Yarn']['id'];?>').width($('#yarn_image<?php echo $yarn['Yarn']['id'];?>').width());
            
                $( window ).resize(function() {
                    $('#yarn_image<?php echo $yarn['Yarn']['id'];?>').height($('#yarn_image<?php echo $yarn['Yarn']['id'];?>').width() * 0.63);
                    $('#yarn_title<?php echo $yarn['Yarn']['id'];?>').width($('#yarn_image<?php echo $yarn['Yarn']['id'];?>').width()-20);
                });
            </script>
        <?php endif; ?>
    <?php endforeach ; ?>
</div>