<h1 class="page-header">Send ordre  #<?php echo $order['Order']['id']; ?></h1>

<?php echo $this->Form->create(
    'Order', 
    array(
        'action' => 'send',
        'inputDefaults' => array(
            'div' => 'form-group',
            'wrapInput' => false,
            'class' => 'form-control',
            'label' => false,
            'error' => array('attributes' => array('before' => 'span', 'class' => 'label label-danger'))
        ),
        'class' => 'well',


    )
); ?>

<!-- id input -->
<?php echo $this->Form->input('id', array('value' => $order['Order']['id'], 'type' => 'hidden',
)); ?>

<!-- delivery_label input -->
<?php echo $this->Form->input('delivery_label', array('label' => 'Label', 'type' => 'text',
                                                            'between' => '<div class="input-group"><div class="input-group-addon"><span class ="glyphicon glyphicon-barcode"></span></div>',
                                                            'after' => '</div>')
); ?>

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


<?php if(!empty($order['Order']['customer_note'])) : ?>
    <label>Note til forhandleren</label>
    <p> <?php echo $order['Order']['customer_note']; ?></p>
<?php endif; ?>

<table class="table">
    <thead>
        <tr>
            <th>Varenummer</th>
            <th>Link</th>
            <th>Antal</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($order['OrderItem'] as $order_item) : ?>
            <tr>
            <td>
                <?php if($order_item['yarn_batch_id']  != 0) : ?>
                    <?php echo $order_item['YarnBatch']['intern_product_code']; ?>
                <?php elseif($order_item['needle_variant_id']  != 0) : ?>
                    <?php echo $order_item['NeedleVariant']['intern_product_code']; ?>
                <?php elseif($order_item['recipe_id']  != 0) : ?>
                    n/a
                <?php elseif($order_item['color_sample_id']  != 0) : ?>
                    n/a
                <?php endif; ?>
            </td>

            <!-- PRODCUT LINK -->
            <td style="vertical-align: middle;">
            <?php if($order_item['yarn_batch_id']  != 0) : ?>
                <a id="cart_item" 
                href="<?php echo $this->Html->url(array(
                            'controller' => 'yarns',
                            'action' => 'view',
                            $order_item['YarnBatch']['YarnVariant']['Yarn']['id']
                        )); ?>">
                    <?php echo $order_item['YarnBatch']['YarnVariant']['Yarn']['name'] . ' - ' .
                               $order_item['YarnBatch']['YarnVariant']['color_code'] . '-' .
                               $order_item['YarnBatch']['batch_code'] ?>
                </a>
            <?php elseif($order_item['needle_variant_id']  != 0) : ?>
                <a id="cart_item" 
                href="<?php echo $this->Html->url(array(
                            'controller' => 'needles',
                            'action' => 'view',
                            $order_item['NeedleVariant']['Needle']['id']
                        )); ?>">
                    <?php echo $order_item['NeedleVariant']['Needle']['name'] . ' - ' .
                               $order_item['NeedleVariant']['product_code'] ?>
                </a>
            <?php elseif($order_item['recipe_id']  != 0) : ?>
                    Print af <a id="cart_item" target="_blank"
                    href="<?php echo $this->Html->url(array(
                                'controller' => 'recipes',
                                'action' => 'view_pdf',
                                $order_item['recipe_id']
                            )); ?>">
                        <?php echo $order_item['Recipe']['name']; ?>
                    </a>
            <?php elseif($order_item['color_sample_id']  != 0) : ?>
                    Farveprøve af <a id="cart_item" 
                            href="<?php echo $this->Html->url(array(
                            'controller' => 'yarns',
                            'action' => 'view',
                             $order_item['ColorSample']['id']
                        )); ?>">
                        <?php echo $order_item['ColorSample']['name']; ?>
                    </a>
                    
            <?php endif; ?>
            </td>
            <!-- PRODCUT LINK COLLAPSE -->   

            <td><?php echo $order_item['amount']; ?></td>
        <?php endforeach; ?>
            
        </tr>
    </tbody>
</table>
