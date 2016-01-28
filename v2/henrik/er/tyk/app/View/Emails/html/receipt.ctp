<table style="width:100%; margin-bottom:30px; border-collapse: collapse; color:#777;">
        <tr style="vertical-align:middle; border-bottom:1px solid #777;">
            <td colspan="3" style="width:45%; padding:10px 0;"><?php echo $this->Html->image('logo.png', array('style' => 'height:50px; margin-top:-15px; vertical-align:middle;', 'fullBase' => true)); ?></td>
        </tr>
        <tr style="font-size:1em; border-bottom:1px solid #777;">
            <td style="padding: 5px 0;"><strong>Kvittering</strong> fra Bundgaards Garn</td>
            <td style="padding: 5px 0;">Ordre nr. <strong><?php echo $order['Order']['id']; ?></strong></td>
            <td style="padding: 5px 0;">Transaktion nr. <strong><?php echo $transaction_id; ?></strong></td>
        </tr>
</table>
<table style="text-align: left; width:100%; border-collapse: collapse;">
    <tr style="border-bottom:2px solid #dddddd;">
        <th style="width:35%; padding: 10px 0;">Vare</th>
        <th style="width:25%; padding: 10px 0;">Antal</th>
        <th style="width:20%; padding: 10px 0; text-align:right;">Pris</th>
        <th style="width:20%; padding: 10px 0; text-align:right;">Total</th>
    </tr>
    <?php foreach ($order['OrderItem'] as $key => $order_item): ?>
    <tr style="border-bottom:1px solid #dddddd;">
        <!-- PRODCUT LINK -->
        <td style="padding: 10px 0; vertical-align: middle;">
        <?php if($order_item['yarn_batch_id']  != 0) : ?>
            <?php echo $order_item['YarnBatch']['YarnVariant']['Yarn']['name'] . ' - ' . $order_item['YarnBatch']['YarnVariant']['color_code'] . '-' . $order_item['YarnBatch']['batch_code']; ?>
        <?php elseif($order_item['needle_variant_id']  != 0) : ?>
            <?php echo $order_item['NeedleVariant']['Needle']['name'] . ' - ' . $order_item['NeedleVariant']['product_code']; ?>
        <?php elseif($order_item['recipe_id']  != 0) : ?>
            Print af <?php echo $order_item['Recipe']['name']; ?>
        <?php elseif($order_item['color_sample_id']  != 0) : ?>
            Farveprøve af <?php echo $order_item['ColorSample']['name']; ?>
        <?php endif; ?>
        </td>
        <!-- PRODCUT LINK COLLAPSE -->
   
        <td style="padding: 10px 0; vertical-align: middle;">
            <?php echo $order_item['amount']; ?>
        </td>

        <td style="padding: 10px 0; vertical-align: middle; text-align:right;">
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
        
        <td style="padding: 10px 0; vertical-align: middle; text-align:right;" >
            <?php echo $this->Number->currency($order_item['price'], 'DKK');?>
            <?php if($order_item['saving'] > 0) : ?>
                <br/>
                <span style="color:#5cb85c;"><?php echo $this->Number->currency($order_item['saving'], 'DKK');?>´</span>
            <?php endif; ?>
        </td>
    </tr>
    
<?php endforeach; ?>
    <tr>
        <td colspan="2"/>
        <td style="text-align:right;">
            <p>Levering</p>
            <?php if($order['Order']['saving'] > 0) : ?>
                <p>Rabat</p>
            <?php endif; ?>
            <p><strong>Subtotal</strong></p> 
            <p><strong>Moms</strong></p>
        </td>
        <td style="text-align:right;">
            <p><?php echo $this->Number->currency($order['Order']['shipping_price'], 'DKK');?></p>
            <?php if($order['Order']['saving'] > 0) : ?>
                <p style="color:#5cb85c;"><strong><?php echo $this->Number->currency($order['Order']['saving'], 'DKK');?></strong></p>
            <?php endif; ?>
            <p><strong><?php echo $this->Number->currency($order['Order']['sub_total'], 'DKK');?></strong></p>
            <p><strong><?php echo $this->Number->currency($order['Order']['tax'], 'DKK');?></strong></p>
        </td>
    </tr>
    <tr>
        <td colspan="2"/>
        <td style="text-align:right;">
            <h4>Total:</h4> 
        </td>
        <td style="text-align:right; color:#900000;">
            <h4><strong><?php echo $this->Number->currency($order['Order']['price'], 'DKK');?></strong></h4>
        </td>
    </tr>
</table>

<div style="width:50%; float:left;">
    <h4>Ordren sendes til:</h4>
    <p>
      <strong><?php echo $order['Customer']['first_name'] . ' ' . $order['Customer']['last_name']; ?></strong><br>
      <?php echo $order['Customer']['ShippingAddress']['street'] . ' ' .
                 $order['Customer']['ShippingAddress']['zip_code'] . ' ' .
                 $order['Customer']['ShippingAddress']['city_name']?><br>
      <?php echo $order['Customer']['email_address']; ?><br>
      <?php echo $order['Customer']['phone_number']; ?><br>
    </p>
</div>

<div style="width:50%; float:left;">
<?php if(!empty($order['Order']['customer_note'])) : ?>
    <h4>Note til forhandleren:</h4>
    <p>
      <?php echo $order['Order']['customer_note']; ?>
    </p>
<?php endif; ?>
</div>

<hr style="width:100%; border: 0; border-bottom: 1px solid #ddd;">

<p style="color:#999; font-size:0.8em;">
<strong>Bundggards Garn</strong> <br/>
<a style="color:#777;" href="https://bundgaardsgarn.dk">bundgaardsgarn.dk</a> <br/>
<a href="mailto:kontakt@bundgaardsgarn.dk" style="color:#777;"> kontakt@bundgaardsgarn.dk</a> <br/>
Saltumvej 46 9700 Brønderslev <br/>
30369522 <br/>
<i>CVR:</i> 34526826 <br/>
</p>
