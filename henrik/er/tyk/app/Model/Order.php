<?php
App::uses('AppModel', 'Model');

class Order extends AppModel 
{
    public $displayField = 'id';

    private $shipping_price = 39;
    private $free_shipping_threshold = 500;
    private $tax_rate = 0.20;

    public $validate = array(
        'state' => array(
            'rule'    => array('inList', array('unplaced', 'received', 'packed', 'resolved')),
            'allowEmpty' => false,
            'message' => 'Du skal vælge et stadie.'
         ),
        'shipping_price' => array(
            'decimal' => array(
                'rule' => 'decimal',
                'allowEmpty' => false,
                'message' => 'Du skal angive en pris.',
            ),
            'notNegative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'En pris kan ikke være negativ.'
            )
        ),
    );

    public $hasMany = array(
        'OrderItem'
    );

    public $belongsTo = array(
        'Customer',
        'Coupon'
    );

    private function purgeInactives($order_id) 
    {   
        // Find the order
        $order = $this->find('first', array('conditions' => array('Order.id' => $order_id), 'recursive' => 2));

        // Run through all order items in the order
        foreach ($order['OrderItem'] as $key => $order_item) 
        {   
            // Check if the order was a yarn batch
            if($order_item['yarn_batch_id'] != 0)
            {
                // Check if the item is active
                 if(!$order_item['YarnBatch']['is_active'])
                {
                    $this->OrderItem->delete($order_item['id']);
                    SessionComponent::setFlash(' Der blev fjernet en varer fra din indkøbskurv da det er ikke længere er aktivt. '.SessionComponent::read('Message.warning.message'), null, array(), 'warning');
                }
                // Check if there are any of this item left in stock
                else if ($order_item['YarnBatch']['stock_quantity'] < 1) 
                {
                    $this->OrderItem->delete($order_item['id']);
                    SessionComponent::setFlash(' En af varene i din indkøbskurv er ikke længere på lager. '.SessionComponent::read('Message.warning.message'), null, array(), 'warning');
                }
            }
            // Check if the order was a needle variant
            else if($order_item['needle_variant_id'] != 0)
            {
                // Check if the item is active
                if(!$order_item['NeedleVariant']['is_active'])
                {
                    $this->OrderItem->delete($order_item['id']);
                    SessionComponent::setFlash(' Der blev fjernet en varer fra din indkøbskurv da det er ikke længere er aktivt. '.SessionComponent::read('Message.warning.message'), null, array(), 'warning');
                }
                // Check if there are any of this item left in stock
                else if ($order_item['NeedleVariant']['stock_quantity'] < 1) 
                {
                    $this->OrderItem->delete($order_item['id']);
                    SessionComponent::setFlash(' En af varene i din indkøbskurv er ikke længere på lager. '.SessionComponent::read('Message.warning.message'), null, array(), 'warning');
                }
            }
            // Check if the order was a recipe
            else if($order_item['recipe_id'] != 0)
            {
                // Check if the item is active
                if(!$order_item['Recipe']['is_active'])
                {
                    $this->OrderItem->delete($order_item['id']);
                    SessionComponent::setFlash(' Der blev fjernet en varer fra din indkøbskurv da det er ikke længere er aktivt. '.SessionComponent::read('Message.warning.message'), null, array(), 'warning');
                } 
            }
            else if($order_item['color_sample_id'] != 0)
            {
                // Check if the item is active
                if(!$order_item['ColorSample']['is_active'])
                {
                    $this->OrderItem->delete($order_item['id']);
                    SessionComponent::setFlash(' Der blev fjernet en varer fra din indkøbskurv da det er ikke længere er aktivt. '.SessionComponent::read('Message.warning.message'), null, array(), 'warning');
                } 
            }
            else
            {
                $this->OrderItem->delete($order_item['id']);
            }

        }      

        // If the order have a coupon and it is no long active
        if($order['Coupon']['id'] != 0 && !$order['Coupon']['is_active'])
        {   
            SessionComponent::setFlash(' Kuponen i din indkøbskurv blev fjernet fordi den ikke længere er gyldig. '.SessionComponent::read('Message.warning.message'), null, array(), 'warning');
            $this->Order->set($order);
            $this->Order->saveField('coupon_id', null);
        }

    }

    private function resetOrderItemsPrices($order_id)
    {
        $order_items = $this->OrderItem->find('all', array('conditions' => array('OrderItem.order_id' => $order_id)));

        foreach ($order_items as $key => $order_item) 
        {
            $this->OrderItem->set($order_item);
            $this->OrderItem->resetPrice();
        }
    }

    public function refresh()
    {   
        // Remove edited or removed items
        $this->purgeInactives($this->id);
        $this->resetOrderItemsPrices($this->id);

        $order = $this->find('first', array('conditions' => array('Order.id' => $this->id), 'recursive' => 2));

        // Items order by prices, highest first
        $order_items = Set::sort($order['OrderItem'],'{n}.price', 'desc');
        $order_price = 0;
        $order_saving = 0;
        $total_amount_of_items = 0;

        $is_only_color_samples = true;
      
        // Run through all items
        foreach ($order_items as $key => $order_item) 
        {
            $new_total_item_price = $order_item['price'];
            if($order['Coupon']['id'] != 0)
            {
                
                if($order_item['yarn_batch_id'] != 0)
                {
                    // There exist an item that is not a color sample
                    $is_only_color_samples = false;

                    foreach ($order['Coupon']['YarnBatch'] as $key => $yarn_batch_on_coupon) 
                    {       
                        if($yarn_batch_on_coupon['id'] == $order_item['yarn_batch_id'])
                        {
                            // Calcualte savings
                            $calculated_savings = $this->calculateSaving($yarn_batch_on_coupon['price'], 
                                                                         $order_item['amount'], 
                                                                         $order['Coupon']['percentage_discount'], 
                                                                         $order['Coupon']['actual_discount'],
                                                                         $order['Coupon']['item_amount']);

                            // Was this a better soloution
                            if($order_item['saving'] < $order_item['price'] - $calculated_savings['new_total_item_price'])
                            {
                                $new_total_item_price = $calculated_savings['new_total_item_price'];
                                $best_coupon['amount_left'] = $calculated_savings['coupon_item_amount_left'];
                                $best_coupon['id'] = $order['Coupon']['id'];
                            }         
                        } 
                        else
                        {
                            continue;
                        }

                    }
                }
                else if($order_item['needle_variant_id'] != 0)
                {
                    // There exist an item that is not a color sample
                    $is_only_color_samples = false;

                    if(!$order_item['NeedleVariant']['is_active'])
                    {
                        foreach ($order['Coupon']['YarnBatch'] as $key => $yarn_batch_on_coupon) 
                        {
                            if($needle_variant_on_coupon['id'] == $order_item['needle_variant_id'])
                            {                        
                                // Calcualte savings
                                $calculated_savings = $this->calculateSaving($needle_variant_on_coupon['price'], 
                                                                             $order_item['amount'], 
                                                                             $order['Coupon']['percentage_discount'], 
                                                                             $order['Coupon']['actual_discount'],
                                                                             $order['Coupon']['item_amount']);

                                // Was this a better soloution
                                if($order_item['saving'] < $order_item['price'] - $calculated_savings['new_total_item_price'])
                                {
                                    $new_total_item_price = $calculated_savings['new_total_item_price'];
                                    $best_coupon['amount_left'] = $calculated_savings['coupon_item_amount_left'];
                                    $best_coupon['id'] = $order['Coupon']['id'];
                                }
                            }
                            else
                            {
                                continue;
                            }
                        } 
                    } 
                }
                else if($order_item['recipe_id'] != 0)
                {
                    // There exist an item that is not a color sample
                    $is_only_color_samples = false;
                }

                $order_item['saving'] = $order_item['price'] - $new_total_item_price;
                
            }

                // Calucalte the prices of item
                $order_saving += $order_item['saving'];
                $order_price += $order_item['price'] - $order_item['saving'];

                // Store prices on item
                $this->OrderItem->set($order_item);
                $this->OrderItem->saveField('saving', $order_item['saving']);
                $this->OrderItem->saveField('price', $order_item['price'] - $order_item['saving']);

            // Count up the counter
            $total_amount_of_items += $order_item['amount'];
        }    

        $shipping_price = 0;

        // Calculate shipping prince and remainder (if it is only color samples keep it at 0)
        if($order_price < $this->free_shipping_threshold)
        {
            $shipping_price = $this->shipping_price;
        }

        $free_shipping_remainder = $this->free_shipping_threshold - $order_price;    
        


        $order_price = $order_price + $shipping_price;
               
        // Calculate taxing
        $tax = $order_price * $this->tax_rate;
        $sub_total = $order_price - $tax;

        // Store order data
        $this->saveField('shipping_price', $shipping_price);
        $this->saveField('free_shipping_remainder', $free_shipping_remainder);
        $this->saveField('amount', $total_amount_of_items);
        $this->saveField('saving', $order_saving);
        $this->saveField('price', $order_price);
        $this->saveField('tax', $tax);
        $this->saveField('sub_total', $sub_total); 
        $this->saveField('modified', date('Y-m-d H:i:s'));

    }

    private function calculateSaving($item_price, $item_amount, $coupon_saving_percent, $coupon_saving_actual, $coupon_item_amount)
    {
        $new_item_price = 0;
        $new_total_item_price = 0;
        $coupon_item_amount_left = 0;

        // Check if the percentage discount is null, then use actual discount otherwise use percentage
        if($coupon_saving_percent == null)
        {
            $new_item_price = $item_price - $coupon_saving_actual;
            $new_item_price = $new_item_price < 0 ? 0 : $new_item_price;
        }
        else
        {
            $new_item_price = $item_price - ($item_price * $coupon_saving_percent/100 );
        }

        // Check if there is enough to get savings on all of this particular item
        if($item_amount <= $coupon_item_amount)
        {   
            $new_total_item_price = $new_item_price * $item_amount;
            $coupon_item_amount_left = $coupon_item_amount - $item_amount;
        }
        else
        {
            $new_total_item_price = $new_item_price * $coupon_item_amount + $item_price * ($item_amount - $coupon_item_amount);
            $coupon_item_amount_left = $coupon_item_amount;
        }

        return array('new_total_item_price' => $new_total_item_price, 'coupon_item_amount_left' => $coupon_item_amount_left);
    }

    public function updateStockQuantityAfterPurchase() 
    {   
        // Find the order
        $order = $this->find('first', array('conditions' => array('Order.id' => $this->id), 'recursive' => 2));

        // Run through all order items in the order
        foreach ($order['OrderItem'] as $key => $order_item) 
        {   
            // Check if the order was a yarn batch
            if($order_item['yarn_batch_id'] != 0)
            {
                // Find the item
                $yarn_batch = $this->OrderItem->YarnBatch->find('first', array('conditions' => array('YarnBatch.id' => $order_item['yarn_batch_id'])));

                // Calculate new stock amount
                $new_stock_quantity = $yarn_batch['YarnBatch']['stock_quantity'] - $order_item['amount'];

                $this->OrderItem->YarnBatch->id = $yarn_batch['YarnBatch']['id'];
                $this->OrderItem->YarnBatch->saveField('stock_quantity', $new_stock_quantity);
            }
            // Check if the order was a needle variant
            else if($order_item['needle_variant_id'] != 0)
            {
                // Find the item
                $needle_variant = $this->OrderItem->NeedleVariant->find('first', array('conditions' => array('NeedleVariant.id' => $order_item['needle_variant_id'])));

                // Calculate new stock amount
                $new_stock_quantity = $needle_variant['NeedleVariant']['stock_quantity'] - $order_item['amount'];

                $this->OrderItem->NeedleVariant->id = $needle_variant['NeedleVariant']['id'];
                $this->OrderItem->NeedleVariant->saveField('stock_quantity', $new_stock_quantity);
            }
        }      

        // If the order have a coupon and it is no long active
        if($order['Coupon']['id'] != 0 && !$order['Coupon']['is_active'])
        {   
            SessionComponent::setFlash(' Kuponnen i din indkøbskurv blev fjernet fordi den ikke længere er gyldig. '.SessionComponent::read('Message.warning.message'), null, array(), 'warning');
            $this->Order->set($order);
            $this->Order->saveField('coupon_id', null);
        }

    }
}

?>