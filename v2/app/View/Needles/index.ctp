<h1 class="page-header">
    <span><?php echo $title; ?></span>
    <?php if($logged_in) : ?>
        <span class="pull-right">
            <!-- Add -->
            <?php echo $this->Html->link(
            '<span class="glyphicon glyphicon-plus"></span>',
            array('controller' => 'needles', 'action' => 'add'),
            array('class' => 'btn btn-success btn-sm', 'escapeTitle' => false,)
            );?>
        </span>
    <?php endif; ?>
</h1>

<h5><span class="text-muted">Klik på et billede for at vælge et produkt.</span></h5>
<hr/>

<div class="row">
    <?php foreach ($needles as $key => $needle) : ?>
        <?php if($needle['Needle']['product_count'] > 0 || $logged_in) : ?>
        
        <?php

        $my_parms = array(
            str_replace(' ', '_', $needle['Needle']['name'])
        );

        ?>

            <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12" style="margin-bottom:20px;">
                <div
                 onclick="window.location = '<?php echo $this->Html->url(array_merge(array('controller' => 'needles', 'action' => 'view', $needle['Needle']['id']), $my_parms)); ?>';"
                 id="needle_image<?php echo $needle['Needle']['id'];?>" 
                 class="thumbnail product_container" 
                 style="width:100%; cursor:pointer;">
                    <?php 
                    $image_url = '';
                    foreach ($needle['NeedleVariant'] as $key => $needle_variant) 
                    {
                        if($needle_variant['is_active'])
                        {
                            $image_url = $this->Html->url('/img/needle_variants/'.$needle_variant['id'].'.png');
                            break;
                        }
                    }

                    ?>
                    <div style="width:100%; height:100%;background: url(<?php echo $image_url ?>); background-repeat:no-repeat; background-position: center; background-size:cover;"
                    <?php if($logged_in && !($needle['Needle']['product_count'] > 0)) 
                    {
                        echo 'id="needle_warning'. $needle['Needle']['id']. '" data-toggle="tooltip" data-placement="top" title="Vises ikke til brugeren"';
                    }?>
                    >
                        <!-- Operations if logged in-->
                        <?php if($logged_in) : ?>
                            <div style="padding:10px;">
                                <!-- Edit -->
                                <?php echo $this->Html->link(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                array('controller' => 'needles', 'action' => 'edit', $needle['Needle']['id']),
                                array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                                );?>

                                <!-- Delete -->
                                <?php echo $this->Html->link(
                                    '<span class="glyphicon glyphicon-trash"></span>',
                                    array('controller' => 'needles', 'action' => 'delete', $needle['Needle']['id']),
                                    array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
                                    'Er du sikker på du vil slette denne garnkvalitet?'
                                );?>

                                <?php if(!$needle['Needle']['product_count'] > 0) : ?>
                                    <!-- Info -->
                                    <a href="#" class="btn btn-xs btn-warning">
                                        <span class="glyphicon glyphicon-eye-close"></span>
                                    </a>
                                <?php endif; ?>
                            </div>
                            

                        <?php endif; ?>

                        <h4 id="needle_title<?php echo $needle['Needle']['id'];?>" class="product_title">
                            <?php echo $needle['Needle']['name']; ?>
                        </h4>

                    </div>
                </div>

                
                
            </div>

            <!-- Script to handle the scaling -->
            <script type="text/javascript">
                $('#needle_warning<?php echo $needle['Needle']['id'];?>').tooltip();
                $('#needle_image<?php echo $needle['Needle']['id'];?>').height($('#needle_image<?php echo $needle['Needle']['id'];?>').width() * 0.63);
                $('#needle_title<?php echo $needle['Needle']['id'];?>').width($('#needle_image<?php echo $needle['Needle']['id'];?>').width());
            
                $( window ).resize(function() {
                    $('#needle_image<?php echo $needle['Needle']['id'];?>').height($('#needle_image<?php echo $needle['Needle']['id'];?>').width() * 0.63);
                    $('#needle_title<?php echo $needle['Needle']['id'];?>').width($('#needle_image<?php echo $needle['Needle']['id'];?>').width()-20);
                });
            </script>
        <?php endif; ?>
    <?php endforeach ; ?>
</div>

<?php /*
<?php debug('OLD STUFF BELOW'); ?>

<h1 class="page-header">
    <span>Strikkepinde</span>

    <span class="pull-right">
        <!-- Add -->
        <?php echo $this->Html->link(
        '<span class="glyphicon glyphicon-plus"></span>',
        array('controller' => 'needles', 'action' => 'add'),
        array('class' => 'btn btn-success btn-sm', 'escapeTitle' => false,)
        );?>
    </span>

</h1>

<table class="table table-condensed ">
    <thead>
        <th>#</th>
        <th>Navn</th>
        <th>Pris</th>
        <th>Menu</th>
        <th>Mærke</th>
        <th>Oprettet</th>
        <th>Redigeret</th>
        <th>Operation</th>
    </thead>
    <tbody>
        <?php foreach($needles as $needle) : ?>
        <tr>
            <td><?php echo $needle['Needle']['id']; ?></td>
            <td><?php echo $needle['Needle']['name']; ?></td>
            <td><?php echo $needle['Menu']['name']; ?></td>
            <td><?php echo $needle['Brand']['name']; ?></td>
            <td><?php echo $needle['Needle']['created']; ?></td>
            <td><?php echo $needle['Needle']['modified']; ?></td>
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
                                Her står der noget om de ting som gør at du ikke kan slette denne ting!
                            </div>
                        </div>
                    </div>
                </div>
                <!-- View -->
                <?php echo $this->Html->link(
                '<span class="glyphicon glyphicon-eye-open"></span>',
                array('controller' => 'needles', 'action' => 'view', $needle['Needle']['id']),
                array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                );?>

                <!-- Edit -->
                <?php echo $this->Html->link(
                '<span class="glyphicon glyphicon-pencil"></span>',
                array('controller' => 'needles', 'action' => 'edit', $needle['Needle']['id']),
                array('class' => 'btn btn-default btn-xs', 'escapeTitle' => false,)
                );?>

                <!-- Delete -->
                <?php echo $this->Html->link(
                    '<span class="glyphicon glyphicon-trash"></span>',
                    array('controller' => 'needles', 'action' => 'delete', $needle['Needle']['id']),
                    array('class' => 'btn btn-danger btn-xs', 'escapeTitle' => false),
                    'Er du sikker på du vil slette denne garnkvalitet?'
                );?>
                <!-- Operations Collapse -->

        </tr>
        <?php endforeach; ?>
    </tbody>
</table> */ ?>
