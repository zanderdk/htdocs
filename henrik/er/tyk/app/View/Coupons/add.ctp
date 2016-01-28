<h1 class="page-header">Opret kupon</h1>



<?php echo $this->Form->create(
    'Coupon', 
    array(
        'inputDefaults' => array(
        'div' => 'form-group',
        'wrapInput' => false,
        'class' => 'form-control',
        'label' => false,
        'error' => array('attributes' => array('wrap' => 'span', 'class' => 'label label-danger'))
        ),
        'class' => 'well',
    )
); ?>

<!-- key input -->
<?php echo $this->Form->input('key', array('label' => 'Kode')
); ?>
<!-- random_wanted? -->
<?php echo $this->Form->input('random_wanted', array(
            'class' => 'form-group inline',
            'style' =>'vertical-align: middle; margin:0;',
            'type' => 'checkbox',
            'label' => 'Tilfældig kode?'
));?>

<hr/> 

<!-- actual_discount input -->
<?php echo $this->Form->input('note', array('label' => 'Note')
); ?>

<!-- actual_discount input -->
<?php echo $this->Form->input('actual_discount', array('label' => 'Faktisk rabat')
); ?>

<!-- percentage_discount input -->
<?php echo $this->Form->input('percentage_discount', array('label' => 'Procentvis rabat')
); ?>
<p class="help-block">Du skal vælge enten faktisk rabat eller procentiv rabat</p>
<hr/> 

<!-- item_amount input -->
<?php echo $this->Form->input('item_amount', array('label' => 'Antal vare pr. kupon')
); ?>

<!-- amount input -->
<?php echo $this->Form->input('amount', array('label' => 'Antal kuponer')
); ?>

<!-- expiration_date input -->
<?php echo $this->Form->input('expiration_date', array('label' => 'Udløbsdato', 'id' => 'datetimepicker')); ?>


<!-- yarn_batches input -->

<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h2>Alle Produkter
            <input type="checkbox" name="" id="check_all">
        </h2>
        <script>
            $("#check_all").click(function () {
                if ($("#check_all").is(':checked')) {
                    $(".row input[type=checkbox]").each(function () {
                        $(this).prop("checked", true);
                    });

                } else {
                    $(".row input[type=checkbox]").each(function () {
                        $(this).prop("checked", false);
                    });
                }
            }); 
        </script>
    </div>
    <!-- Yarn Coupons -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3>Alt Garn
            <input type="checkbox" name="" id="check_all_yarn">
        </h3>
        <script>
            $("#check_all_yarn").click(function () {
                if ($("#check_all_yarn").is(':checked')) {
                    $(".yarn").each(function () {
                        $(this).prop("checked", true);
                    });

                } else {
                    $(".yarn").each(function () {
                        $(this).prop("checked", false);
                    });
                }
            }); 
        </script>
    </div>
    
    <?php $previous = 0; ?>
    <?php foreach($yarn_batches as $i => $yarn_batch) : ?>
        <?php if($yarn_batch['Yarn']['id'] != $previous)  {
            $previous = $yarn_batch['Yarn']['id']; ?>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4><?php echo $yarn_batch['Yarn']['name']; ?> - 
                    <?php switch ($yarn_batch['Menu']['type']) {
                        case 'yarn':
                            echo 'Garn';
                            break;
                        case 'surplus_yarn':
                            echo 'Restgarn';
                            break;
                    }?>
                    <input type="checkbox" name="" class="yarn" id="check_yarn_id_<?php echo $yarn_batch['Yarn']['id']; ?>">
                </h4>
            </div>
            <hr/>

            <script>
                $("#check_yarn_id_<?php echo $yarn_batch['Yarn']['id']; ?>").click(function () {
                if ($("#check_yarn_id_<?php echo $yarn_batch['Yarn']['id']; ?>").is(':checked')) {
                    $(".yarn_id_<?php echo $yarn_batch['Yarn']['id']; ?>").each(function () {
                        $(this).prop("checked", true);
                    });

                } else {
                    $(".yarn_id_<?php echo $yarn_batch['Yarn']['id']; ?>").each(function () {
                        $(this).prop("checked", false);
                    });
                }
            });
            </script>

        <?php } ?>

        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
            <div style="background:white;" class="well well-sm">
                <!-- id for the yarn_batch -->
                <?php echo $this->Form->checkbox('YarnBatch.'.$yarn_batch['YarnBatch']['id'], array('class' => 'yarn yarn_id_'. $yarn_batch['Yarn']['id'],'type' => 'checkbox', 'value' => $yarn_batch['YarnBatch']['id'])); ?>

                <!-- name of the yarn_batch -->
                <span>
                    <strong> 
                        <?php echo $yarn_batch['YarnBatch']['intern_product_code']; ?> 
                   </strong>
                </span>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Yarn Coupons Collapse -->

    <!-- Needle Coupons -->
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <h3>Alle Strikkepinde/Hæklenåle
            <input type="checkbox" name="" id="check_all_needles">
        </h3>
        <script>
            $("#check_all_needles").click(function () {
                if ($("#check_all_needles").is(':checked')) {
                    $(".needle").each(function () {
                        $(this).prop("checked", true);
                    });

                } else {
                    $(".needle").each(function () {
                        $(this).prop("checked", false);
                    });
                }
            }); 
        </script>
    </div>
 
    <?php $previous = 0; ?>
    <?php foreach($needle_variants as $i => $needle_variant) : ?>
        <?php if($needle_variant['Needle']['id'] != $previous)  {
            $previous = $needle_variant['Needle']['id']; ?>
            
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <h4><?php echo $needle_variant['Needle']['name']; ?> - 
                    <?php switch ($needle_variant['Menu']['type']) {
                        case 'crochet':
                            echo 'Hæklenål';
                            break;
                        case 'knit':
                            echo 'Strikkepind';
                            break;
                    }?>
                    <input type="checkbox" name="" class="needle" id="check_needle_id<?php echo $needle_variant['Needle']['id']; ?>">
                </h4>
            </div>
            <hr/>

            <script>
                $("#check_needle_id<?php echo $needle_variant['Needle']['id']; ?>").click(function () {
                if ($("#check_needle_id<?php echo $needle_variant['Needle']['id']; ?>").is(':checked')) {
                    $(".needle_id<?php echo $needle_variant['Needle']['id']; ?>").each(function () {
                        $(this).prop("checked", true);
                    });

                } else {
                    $(".needle_id<?php echo $needle_variant['Needle']['id']; ?>").each(function () {
                        $(this).prop("checked", false);
                    });
                }
            });
            </script>

        <?php } ?>

        <div class="col-lg-2 col-md-2 col-sm-3 col-xs-12">
            <div style="background:white;" class="well well-sm">
                <!-- id for the yarn_batch -->
                <?php echo $this->Form->checkbox('NeedleVariant.'.$needle_variant['NeedleVariant']['id'], array('class' => 'needle needle_id'. $needle_variant['Needle']['id'],'type' => 'checkbox', 'value' => $needle_variant['NeedleVariant']['id'])); ?>

                <!-- name of the yarn_batch -->
                <span>
                    <strong> 
                        <?php echo $needle_variant['NeedleVariant']['intern_product_code']; ?> 
                   </strong>
                </span>
            </div>
        </div>
    <?php endforeach; ?>
    <!-- Needle Coupons Collapse -->
</div>


<!-- Submit Button -->
<p class="text-center" style="margin:0;">
<?php echo $this->html->tag(
        'button', 
        '<span class="glyphicon glyphicon-floppy-disk"></span> <span style="font-size:1.3em;">Gem</span>', 
        array(
                'class' => 'btn btn-md btn-primary', 
                'type' => 'submit', 
                'style' => 'width:200px;'
        )
    ); ?>
</p>

<?php echo $this->Form->end(); ?>