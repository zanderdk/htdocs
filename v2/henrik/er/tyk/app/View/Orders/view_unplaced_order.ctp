<div class="page-header">
  <h1><span style="color:#5cb85c;">Indkøbskurv </span>&raquo; <span style="color:#5cb85c;">Levering </span>  &raquo; Ordreoversigt &raquo; <span class="text-muted"> Betal</h1>
</div>

<div class="well well-sm">
    Er du sikker på det er disse varer du ønsker at købe?
</div>

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
                            'controller' => 'needle_variants',
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
       
            <td style="vertical-align: middle;">
                <?php echo $order_item['amount']; ?>
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
                <?php if($order['Order']['saving'] > 0) : ?>
                    <p>Rabat</p>
                <?php endif; ?>
                <p><strong>Subtotal</strong></p> 
                <p><strong>Moms</strong></p>
            </td>
            <td class="text-right">
                <p><?php echo $this->Number->currency($order['Order']['shipping_price'], 'DKK');?></p>
                <?php if($order['Order']['saving'] > 0) : ?>
                    <p style="color:#5cb85c;"><strong><?php echo $this->Number->currency($order['Order']['saving'], 'DKK');?></strong></p>
                <?php endif; ?>
                <p><strong><?php echo $this->Number->currency($order['Order']['sub_total'], 'DKK');?></strong></p>
                <p><strong><?php echo $this->Number->currency($order['Order']['tax'], 'DKK');?></strong></p>
            </td>
        </tr>
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
<hr/>
<div class="row">
    <div class="col-lg-6">
        <u>Ordren sendes til:</u>
        <address>
          <strong><?php echo $order['Customer']['first_name'] . ' ' . $order['Customer']['last_name']; ?></strong><br>
          <?php echo $order['Customer']['ShippingAddress']['street'] . ' ' .
                     $order['Customer']['ShippingAddress']['zip_code'] . ' ' .
                     $order['Customer']['ShippingAddress']['city_name']?><br>
        <?php debug($this) ?>
          <?php echo $order['Customer']['email_address']; ?><br>
          <?php echo $order['Customer']['phone_number']; ?><br>
        </address>
    </div>
    <div class="col-lg-6">
        <?php if(!empty($order['Order']['customer_note'])) : ?>
                <div class="panel panel-default">
                
                <div class="panel-body">
                <label>Note til forhandler</label> <br/>
                <?php echo $order['Order']['customer_note']; ?></div>
                </div>
        <?php endif; ?>
    </div>
</div>


<form name="payform" METHOD="post" ACTION="https://payment.architrade.com/paymentweb/start.action">
    <input type="hidden" name="accepturl" value="<?php echo $this->Html->url(array('controller' => 'orders', 'action' => 'view_as_receipt', $order['Order']['id']), true); ?>">
    <input type="hidden" name="callbackurl" value="http://bundgaardsgarn.dk<?php echo $this->Html->url(array('controller' => 'payments', 'action' => 'accept_payment')); ?>">
    <input type="hidden" name="cancelurl" value="<?php echo $this->Html->url(array('controller' => 'orders', 'action' => 'view_unplaced_order'), true); ?>">
    <input type="hidden" name="captureNow" value="1">
    <input type="hidden" name="amount" value="<?php echo $dibs_price; ?>">
    <input type="hidden" name="currency" value="<?php echo $currency; ?>">
    <input type="hidden" name="merchant" value="<?php echo $merchant_id; ?>">
    <input type="hidden" name="orderid" value="<?php echo $order['Order']['id']; ?>">
    <input type="hidden" name="md5key" value="<?php echo $md5_key; ?>">
    <input type="hidden" name="paytype" value="<?php echo $paytype; ?>">
    <!-- <input type="hidden" name="test" value="1"> -->
    <button type="submit" class="btn btn-success btn-lg btn-block">
        <span style="font-size:1.3em;">Gå til betaling </span>
            <span class="glyphicon glyphicon-credit-card"></span>
    </button>
</form>

<br/>