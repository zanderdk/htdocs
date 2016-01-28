<div class="page-header">
  <h1>Indkøbskurv <span class="text-muted">&raquo; Levering &raquo; Ordreoversigt &raquo; Betal</span></h1>
</div>

<?php if($cart_amount > 0) : ?>

<table class="table">
    <thead>
        <tr>
            <th style="width:35%;">Vare</th>
            <th style="width:25%;">Antal</th>
            <th class="text-right" style="width:20%;">Pris</th>
            <th class="text-right" style="width:20%;">Total</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($order['OrderItem'] as $key => $order_item): ?>
        <tr>
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
                        <?php echo $order_item['ColorSample']['name'];?>
                    </a>
                                        
            <?php endif; ?>
            </td>
            <!-- PRODCUT LINK COLLAPSE -->
       
            <td style="vertical-align: middle;">

                <!-- UPDATE ITEM-AMOUNT BUTTON -->
                <?php if($order_item['yarn_batch_id']  != 0) : ?>
                    <?php echo $this->Form->create('Orders', array('action' => 'set_yarn_batch_amount')); ?>

                    <!-- The amount input -->
                    <?php echo $this->Form->input('amount', array('value' => $order_item['amount'], 'type' => 'number', 'label' => false, 'class' => 'form-control pull-left', 'style' => 'width:55px; margin-right:3px;')); ?>

                    <!-- The id input (hidden) -->
                    <?php echo $this->Form->input('yarn_batch_id', array('value' => $order_item['yarn_batch_id'], 'type' => 'hidden')); ?>
                    
                    <!-- The refresh button  -->
                    <button type="submit" class="btn btn-default pull-left glyphicon glyphicon-refresh" style="margin-right:10px; margin-top:-1px;"></button>
                    <?php echo $this->Form->end(); ?>

                <?php elseif($order_item['needle_variant_id']  != 0) : ?>

                    <?php echo $this->Form->create('Orders', array('action' => 'set_needle_variant_amount')); ?>

                    <!-- The amount input -->
                    <?php echo $this->Form->input('amount', array('value' => $order_item['amount'], 'type' => 'number', 'label' => false, 'class' => 'form-control pull-left', 'style' => 'width:55px; margin-right:3px;')); ?>

                    <!-- The id input (hidden) -->
                    <?php echo $this->Form->input('needle_variant_id', array('value' => $order_item['needle_variant_id'], 'type' => 'hidden')); ?>
                    
                    <!-- The refresh button  -->
                    <button type="submit" class="btn btn-default pull-left glyphicon glyphicon-refresh" style="margin-right:10px; margin-top:-1px;"></button>
                    <?php echo $this->Form->end(); ?>

                <?php elseif($order_item['recipe_id']  != 0) : ?>

                    <?php echo $this->Form->create('Orders', array('action' => 'set_recipe_amount')); ?>

                    <!-- The amount input -->
                    <?php echo $this->Form->input('amount', array('value' => $order_item['amount'], 'type' => 'number', 'label' => false, 'class' => 'form-control pull-left', 'style' => 'width:55px; margin-right:3px;')); ?>

                    <!-- The id input (hidden) -->
                    <?php echo $this->Form->input('recipe_id', array('value' => $order_item['recipe_id'], 'type' => 'hidden')); ?>
                    
                    <!-- The refresh button  -->
                    <button type="submit" class="btn btn-default pull-left glyphicon glyphicon-refresh" style="margin-right:10px; margin-top:-1px;"></button>
                    <?php echo $this->Form->end(); ?>
                
                <?php elseif($order_item['color_sample_id']  != 0) : ?>

                    <?php echo $this->Form->create('Orders', array('action' => 'set_color_sample_amount')); ?>

                    <!-- The amount input -->
                    <?php echo $this->Form->input('amount', array('value' => $order_item['amount'], 'type' => 'number', 'label' => false, 'class' => 'form-control pull-left', 'style' => 'width:55px; margin-right:3px;')); ?>

                    <!-- The id input (hidden) -->
                    <?php echo $this->Form->input('color_sample_id', array('value' => $order_item['color_sample_id'], 'type' => 'hidden')); ?>
                    
                    <!-- The refresh button  -->
                    <button type="submit" class="btn btn-default pull-left glyphicon glyphicon-refresh" style="margin-right:10px; margin-top:-1px;"></button>
                    <?php echo $this->Form->end(); ?>

                <?php endif; ?>
                
                <!-- UPDATE ITEM-AMOUNT BUTTON COLLAPSE -->

                <!-- REMOVE ITEM BUTTON -->
                <?php if($order_item['yarn_batch_id']  != 0) : ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', 
                                             array('controller' => 'orders', 
                                                   'action' => 'remove_yarn_batch', 
                                                    $order_item['yarn_batch_id']), 
                                            array('class' => 'btn btn-danger pull-left', 'escapeTitle' => false,));?>
                <?php elseif($order_item['needle_variant_id']  != 0) : ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', 
                                             array('controller' => 'orders', 
                                                   'action' => 'remove_needle_variant', 
                                                    $order_item['needle_variant_id']), 
                                            array('class' => 'btn btn-danger pull-left', 'escapeTitle' => false,));?>
                <?php elseif($order_item['recipe_id']  != 0) : ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', 
                                             array('controller' => 'orders', 
                                                   'action' => 'remove_recipe', 
                                                    $order_item['recipe_id']), 
                                            array('class' => 'btn btn-danger pull-left', 'escapeTitle' => false,));?>
                <?php elseif($order_item['color_sample_id']  != 0) : ?>
                    <?php echo $this->Html->link('<span class="glyphicon glyphicon-trash"></span>', 
                                             array('controller' => 'orders', 
                                                   'action' => 'remove_color_sample', 
                                                    $order_item['color_sample_id']), 
                                            array('class' => 'btn btn-danger pull-left', 'escapeTitle' => false,));?>                                            
                <?php endif; ?>
                <!-- REMOVE ITEM BUTTON COLLAPSE -->
            </td>

            <td style="vertical-align: middle;" class="text-right">
            <?php if($order_item['yarn_batch_id']  != 0) : ?>
                <?php echo $this->Number->currency($order_item['YarnBatch']['price'], 'DKK');?>
            <?php elseif($order_item['needle_variant_id']  != 0) : ?>
                <?php echo $this->Number->currency($order_item['NeedleVariant']['price'], 'DKK');?>
            <?php elseif($order_item['recipe_id']  != 0) : ?>
                <?php echo $this->Number->currency($order_item['Recipe']['price'], 'DKK');?>
            <?php elseif($order_item['color_sample_id']  != 0) : ?>
                <?php echo $this->Number->currency($order_item['ColorSample']['price'], 'DKK');?>
            <?php endif; ?>
            </td>
            

            <td style="vertical-align: middle;"  class="text-right">
                <?php echo $this->Number->currency($order_item['price'], 'DKK');?>
                <?php if($order_item['saving'] > 0) : ?>
                    <br/>
                    <span style="color:#5cb85c;"><?php echo $this->Number->currency($order_item['saving'], 'DKK');?></span>
                <?php endif; ?>
            </td>
        </tr>
        
    <?php endforeach; ?>
        <tr>
            <th colspan="2"/>
            <td class="text-right">
                <p>Levering</p>
                <?php if($order['Order']['free_shipping_remainder'] > 0) : ?>
                    <p class="text-muted">Mangler til fri levering</p>
                <?php endif; ?>
                <?php if($order['Order']['saving'] > 0) : ?>
                    <p>Rabat</p>
                <?php endif; ?>
                <p><strong>Subtotal</strong></p> 
                <p><strong>Moms</strong></p>
            </td>
            <td class="text-right">
                <p><?php echo $this->Number->currency($order['Order']['shipping_price'], 'DKK');?></p>
                <?php if($order['Order']['free_shipping_remainder'] > 0) : ?>
                    <p class="text-muted"><?php echo $this->Number->currency($order['Order']['free_shipping_remainder'], 'DKK');?></p>
                <?php endif; ?>
                <?php if($order['Order']['saving'] > 0) : ?>
                    <p style="color:#5cb85c;"><strong><?php echo $this->Number->currency($order['Order']['saving'], 'DKK');?></strong></p>
                <?php endif; ?>
                <p><strong><?php echo $this->Number->currency($order['Order']['sub_total'], 'DKK');?></strong></p>
                <p><strong><?php echo $this->Number->currency($order['Order']['tax'], 'DKK');?></strong></p>
            </td>
        </tr>
        <tr class="active">
            <td>
                <h5 class="pull-right"><strong>Tilføj kuponkode </strong></h5>
            </td>
            <td colspan="3">
                <?php echo $this->Form->create('Order', array('action' => 'add_coupon', 'class' => 'form-inline')); ?>
                <?php echo $this->Form->input('Coupon.key', array('label' => false, 'required' => true, 'class' => 'form-control pull-left', 'style' => 'width:50%; margin-right:3px;')); ?>
                <button type="submit" class="btn btn-default pull-left glyphicon glyphicon-tags" style="margin-right:10px; margin-top:-1px;"></button>
                <?php echo $this->Form->end(); ?>
                </span>
            </td>
        </tr>
        <?php if ($order['Coupon']['id'] != 0) : ?>
        <tr class="active">
            <td colspan="4">
                <h3 style="margin:5px 0 10px 0;" class="text-center">
                    <span class="label label-success" style="margin:0 5px;">
                        <span class="glyphicon glyphicon-tag"></span> <?php echo $order['Coupon']['key']; ?>
                    </span>
                </h3>
            </td>
        </tr>
        <?php endif; ?>
        <tr>
            <th colspan="2"/>
            <th class="text-right">
                <h4>Total:</h4> 
            </th>
            <th class="text-right text-danger">
                <h4><strong><?php echo $this->Number->currency($order['Order']['price'], 'DKK');?></strong></h4>
            </th>
        </tr>
    </tbody>
</table>

<a href="
    <?php echo $this->Html->url(array(
                            'controller' => 'payments',
                            'action' => 'billing',
                        )); ?>"
    type="button" class="btn btn-success btn-lg btn-block">
    <span style="font-size:1.3em;">Fortsæt</span>
    <span class="glyphicon glyphicon-arrow-right"></span>
</a>
</br>
<?php else : ?>
    <h4 class="text-muted text-center" style="padding:100px;">Din indkøbskurv er tom</h4>
<?php endif; ?>