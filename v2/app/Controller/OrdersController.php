<?php
App::uses('AppController', 'Controller');
App::uses('Customer', 'AppModel');
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Payments');



class OrdersController extends AppController 
{
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow(
                        'add_color_sample',
                        'add_coupon',
                        'add_needle_variant',
                        'add_recipe',
                        'add_yarn_batch',
                        'purge_unplaced_orders',
                        'remove_color_sample',
                        'remove_coupon',
                        'remove_needle_variant',
                        'remove_recipe',
                        'remove_yarn_batch',
                        'set_color_sample_amount',
                        'set_needle_variant_amount',
                        'set_recipe_amount',
                        'set_yarn_batch_amount',
                        'view_as_receipt',
                        'view_unplaced_order'
        );
    }

    public function add_yarn_batch($yarn_batch_id = null, $amount = 1) 
    {
        if($this->request->is('post'))
        {
            $yarn_batch_id = $this->request->data['Orders']['yarn_batch_id'];
            $amount = $this->request->data['Orders']['amount'];     
        }

        $this->set_yarn_batch_amount($yarn_batch_id, $amount, true);
    }

    public function set_yarn_batch_amount($yarn_batch_id = null, $amount = 1, $add = false) 
    {
        if($this->request->is('post'))
        {
            $yarn_batch_id = $this->request->data['Orders']['yarn_batch_id'];
            $amount = $this->request->data['Orders']['amount'];     
        }

        // Check if the amount is a larger than 0
        if($amount < 0)
        {
            // Tell the user that only positive numbers larger than 0 is allowed as amount
            $this->Session->setFlash('Du kan ikke tilføje \''. $amount .'\' af den ønskede vare.' .$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }
        else if($amount == 0)
        {
            // If the user wants 0 of an item, remove it
            $this->remove_yarn_batch($yarn_batch_id);
        }
        
        // Checks if the yarn_batches exists
        $this->access_yarn_batch($yarn_batch_id, $amount);

        // Find the existing order item if it was not found create a new one
        $existingOrderItem = $this->Order->OrderItem->find('first', array('conditions' => array('OrderItem.order_id' => $this->Cookie->read('Order.id'), 'OrderItem.yarn_batch_id' => $yarn_batch_id)));
        if(empty($existingOrderItem))
        {
            $this->Order->OrderItem->create();
            if(!$this->Order->OrderItem->save(array('amount' => $amount, 'yarn_batch_id' => $yarn_batch_id, 'order_id' => $this->Cookie->read('Order.id'))))
            {
                $this->Session->setFlash('Der skete en fejl kunne ikke opdatere varen. Prøv igen.', null, array(), 'warning');
            }
        } else 
        {
           // If the request was to add
            if($add) 
            {
                $amount += $existingOrderItem['OrderItem']['amount'];

            } 
        }        

        $this->Order->OrderItem->set($existingOrderItem);
        if(!$this->Order->OrderItem->saveField('amount', $amount))
        {
            $this->Session->setFlash('Der skete en fejl kunne ikke opdatere varen. Prøv igen.', null, array(), 'warning');
        }
        else if($add)
        {
            // Tell the user this item was added to the cart
            $this->Session->setFlash('Tilføjet til indkøbskurv', null, array(), 'success');
        } 
        else
        {
            // Tell the user this item was updated
            $this->Session->setFlash('Vare opdateret', null, array(), 'success');
        }
        // Send the user to the location it was called
        return $this->redirect($this->referer());
    }

    public function remove_yarn_batch($yarn_batch_id)
    {
        $this->access_yarn_batch($yarn_batch_id);

        // Find the existing order item and remove it if it was found
        $existingOrderItem = $this->Order->OrderItem->find('first', array('conditions' => array('OrderItem.order_id' => $this->Cookie->read('Order.id'), 'OrderItem.yarn_batch_id' => $yarn_batch_id)));
        if(!empty($existingOrderItem))
        {
            $this->Order->OrderItem->delete($existingOrderItem['OrderItem']['id']);
        }

        return $this->redirect($this->referer());
    }

    public function add_needle_variant($needle_variant_id = null, $amount = 1) 
    {

        if($this->request->is('post'))
        {   
            $needle_variant_id = $this->request->data['Orders']['needle_variant_id'];
            $amount = $this->request->data['Orders']['amount'];     
        }



        $this->set_needle_variant_amount($needle_variant_id, $amount, true);
    }

    public function set_needle_variant_amount($needle_variant_id = null, $amount = 1, $add = false) 
    {
        if($this->request->is('post'))
        {
            $needle_variant_id = $this->request->data['Orders']['needle_variant_id'];
            $amount = $this->request->data['Orders']['amount'];     
        }

        // Check if the amount is a larger than 0
        if($amount < 1)
        {
            // Tell the user that only positive numbers larger than 0 is allowed as amount
            $this->Session->setFlash('Du kan ikke tilføje \''. $amount .'\' af den ønskede vare.' .$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }
        else if($amount == 0)
        {
            // If the user wants 0 of an item, remove it
            $this->remove_needle_variant($needle_variant_id);
        }

        // Checks if the yarn_batches exists
        $this->access_needle_variant($needle_variant_id, $amount);
        
        
        // Find the existing order item if it was not found create a new one
        $existingOrderItem = $this->Order->OrderItem->find('first', array('conditions' => array('OrderItem.order_id' => $this->Cookie->read('Order.id'), 'OrderItem.needle_variant_id' => $needle_variant_id)));
        if(empty($existingOrderItem))
        {
            $this->Order->OrderItem->create();
            if(!$this->Order->OrderItem->save(array('amount' => $amount, 'needle_variant_id' => $needle_variant_id, 'order_id' => $this->Cookie->read('Order.id'))))
            {
                $this->Session->setFlash('Der skete en fejl kunne ikke opdatere varen. Prøv igen.', null, array(), 'warning');
            }
        } 
        else 
        {
            // If the request was to add
            if($add) 
            {
                $amount += $existingOrderItem['OrderItem']['amount'];
            }
        }      

        $this->Order->OrderItem->set($existingOrderItem);
        if(!$this->Order->OrderItem->saveField('amount', $amount))
        {
            $this->Session->setFlash('Der skete en fejl kunne ikke opdatere varen. Prøv igen.', null, array(), 'warning');
        }
        else if($add)
        {
            // Tell the user this item was added to the cart
            $this->Session->setFlash('Tilføjet til indkøbskurv', null, array(), 'success');
        } 
        else
        {
            // Tell the user this item was updated
            $this->Session->setFlash('Vare opdateret', null, array(), 'success');
        }
        // Send the user to the location it was called
        return $this->redirect($this->referer());
    }

    public function remove_needle_variant($needle_variant_id)
    {
        $this->access_needle_variant($needle_variant_id);

        // Find the existing order item and remove it if it was found
        $existingOrderItem = $this->Order->OrderItem->find('first', array('conditions' => array('OrderItem.order_id' => $this->Cookie->read('Order.id'), 'OrderItem.needle_variant_id' => $needle_variant_id)));
        if(!empty($existingOrderItem))
        {
            $this->Order->OrderItem->delete($existingOrderItem['OrderItem']['id']);
        }

        return $this->redirect($this->referer());
    } 

    public function add_recipe($recipe_id = null, $amount = 1) 
    {
        if($this->request->is('post'))
        {
            $recipe_id = $this->request->data['Orders']['recipe_id'];
            $amount = $this->request->data['Orders']['amount'];     
        }

        $this->set_recipe_amount($recipe_id, $amount, true);
    }

    public function set_recipe_amount($recipe_id = null, $amount = 1, $add = false) 
    {
        if($this->request->is('post'))
        {
            $recipe_id = $this->request->data['Orders']['recipe_id'];
            $amount = $this->request->data['Orders']['amount'];     
        }

        // Checks if the yarn_batches exists
        $this->access_recipe($recipe_id);

        // Check if the amount is a larger than 0
        if($amount < 0)
        {
            // Tell the user that only positive numbers larger than 0 is allowed as amount
            $this->Session->setFlash('Du kan ikke tilføje \''. $amount .'\' af den ønskede vare.' .$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }
        else if($amount == 0)
        {
            // If the user wants 0 of an item, remove it
            $this->remove_recipe($recipe_id);
        }
        
        // Find the existing order item if it was not found create a new one
        $existingOrderItem = $this->Order->OrderItem->find('first', array('conditions' => array('OrderItem.order_id' => $this->Cookie->read('Order.id'), 'OrderItem.recipe_id' => $recipe_id)));
        if(empty($existingOrderItem))
        {
            $this->Order->OrderItem->create();
            if(!$this->Order->OrderItem->save(array('amount' => $amount, 'recipe_id' => $recipe_id, 'order_id' => $this->Cookie->read('Order.id'))))
            {
                $this->Session->setFlash('Der skete en fejl kunne ikke opdatere varen. Prøv igen.', null, array(), 'warning');
            }
        } else 
        {
           // If the request was to add
            if($add) 
            {
                $amount += $existingOrderItem['OrderItem']['amount'];

            } 
        }        

        $this->Order->OrderItem->set($existingOrderItem);
        if(!$this->Order->OrderItem->saveField('amount', $amount))
        {
            $this->Session->setFlash('Der skete en fejl kunne ikke opdatere varen. Prøv igen.', null, array(), 'warning');
        }
        else if($add)
        {
            // Tell the user this item was added to the cart
            $this->Session->setFlash('Tilføjet til indkøbskurv', null, array(), 'success');
        } 
        else
        {
            // Tell the user this item was updated
            $this->Session->setFlash('Vare opdateret', null, array(), 'success');
        }
        // Send the user to the location it was called
        return $this->redirect($this->referer());
    }

    public function remove_recipe($recipe_id)
    {
        $this->access_recipe($recipe_id);

        // Find the existing order item and remove it if it was found
        $existingOrderItem = $this->Order->OrderItem->find('first', array('conditions' => array('OrderItem.order_id' => $this->Cookie->read('Order.id'), 'OrderItem.recipe_id' => $recipe_id)));
        if(!empty($existingOrderItem))
        {
            $this->Order->OrderItem->delete($existingOrderItem['OrderItem']['id']);
        }

        return $this->redirect($this->referer());
    }

    public function add_color_sample($yarn_id = null, $amount = 1) 
    {
        if($this->request->is('post'))
        {
            $yarn_id = $this->request->data['Orders']['color_sample_id'];
            $amount = $this->request->data['Orders']['amount'];     
        }

        $this->set_color_sample_amount($yarn_id, $amount, true);
    }

    public function set_color_sample_amount($yarn_id = null, $amount = 1, $add = false) 
    {
        if($this->request->is('post'))
        {
            $yarn_id = $this->request->data['Orders']['color_sample_id'];
            $amount = $this->request->data['Orders']['amount'];     
        }

        // Checks if the yarn_batches exists
        $this->access_color_sample($yarn_id);

        // Check if the amount is a larger than 0
        if($amount < 0)
        {
            // Tell the user that only positive numbers larger than 0 is allowed as amount
            $this->Session->setFlash('Du kan ikke tilføje \''. $amount .'\' af den ønskede vare.' .$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }
        else if($amount == 0)
        {
            // If the user wants 0 of an item, remove it
            $this->remove_color_sample($yarn_id);
        }
        
        // Find the existing order item if it was not found create a new one
        $existingOrderItem = $this->Order->OrderItem->find('first', array('conditions' => array('OrderItem.order_id' => $this->Cookie->read('Order.id'), 'OrderItem.color_sample_id' => $yarn_id)));
        if(empty($existingOrderItem))
        {
            $this->Order->OrderItem->create();
            if(!$this->Order->OrderItem->save(array('amount' => $amount, 'color_sample_id' => $yarn_id, 'order_id' => $this->Cookie->read('Order.id'))))
            {
                $this->Session->setFlash('Der skete en fejl kunne ikke opdatere varen. Prøv igen.', null, array(), 'warning');
            }
        } else 
        {
           // If the request was to add
            if($add) 
            {
                $amount += $existingOrderItem['OrderItem']['amount'];

            } 
        }        

        $this->Order->OrderItem->set($existingOrderItem);
        if(!$this->Order->OrderItem->saveField('amount', $amount))
        {
            $this->Session->setFlash('Der skete en fejl kunne ikke opdatere varen. Prøv igen.', null, array(), 'warning');
        }
        else if($add)
        {
            // Tell the user this item was added to the cart
            $this->Session->setFlash('Tilføjet til indkøbskurv', null, array(), 'success');
        } 
        else
        {
            // Tell the user this item was updated
            $this->Session->setFlash('Vare opdateret', null, array(), 'success');
        }
        // Send the user to the location it was called
        return $this->redirect($this->referer());
    }

    public function remove_color_sample($yarn_id)
    {
        $this->access_color_sample($yarn_id);

        // Find the existing order item and remove it if it was found
        $existingOrderItem = $this->Order->OrderItem->find('first', array('conditions' => array('OrderItem.order_id' => $this->Cookie->read('Order.id'), 'OrderItem.color_sample_id' => $yarn_id)));
        if(!empty($existingOrderItem))
        {
            $this->Order->OrderItem->delete($existingOrderItem['OrderItem']['id']);
        }

        return $this->redirect($this->referer());
    }

    // STOP NYE

    public function add_coupon($coupon_key = null)
    {
        if($this->request->is('post'))
        {
            $coupon_key = $this->request->data['Coupon']['key'];     
        }
        $coupon = $this->Order->Coupon->find('first', array('conditions' => array('Coupon.key' => $coupon_key)));
        
        if(empty($coupon))
        {
            // Tell the user this coupon was added to the cart
            $this->Session->setFlash('En kupon med denne kode findes ikke.', null, array(), 'warning');

            // Send the user to the location it was called
            $this->redirect($this->referer());
        }

        $this->Order->Coupon->id = $coupon['Coupon']['id'];

        if(!$this->Order->Coupon->isUsable())
        {
            // Tell the user this coupon was added to the cart
            $this->Session->setFlash('En kupon med denne kode kan ikke længere bruges.', null, array(), 'warning');

            // Send the user to the location it was called
            $this->redirect($this->referer());
        }

        $order = $this->Order->find('first', array('conditions' => array('Order.id' => $this->Cookie->read('Order.id'))));

        // Did the order have a coupon already
        if($order['Coupon']['id'] != 0)
        {   
            // Was the coupon the same
            if($order['Coupon']['id'] == $coupon['Coupon']['id'])
            {
                $succes_msg = 'Du har allerede denne kupon tilknyttet.';
            }
            else
            {
                $succes_msg ='Du kan kun have en kupon. Kuponen med koden ' . $coupon_key . ' blev tilknyttet.';
            }
        }
        // Just add the coupon
        else 
        {
            $succes_msg = 'Kuponen med koden ' . $coupon_key . ' blev tilknyttet.';
        }

        // Update the coupon
        $this->Order->set($order);
        if($this->Order->saveField('coupon_id', $coupon['Coupon']['id']))
        {
            $this->Session->setFlash($succes_msg, null, array(), 'success');
        }
        else 
        {
            $this->Session->setFlash('Der skete en fejl. Kunne ikke gemme kuponnen.', null, array(), 'warning');
        }

        // Send the user to the location it was called
        return $this->redirect($this->referer());
    }

    public function remove_coupon($coupon_id)
    {
        // TODO
    }

    public function purge_unplaced_orders()
    {
        $old_orders = $this->Order->find('all', array('conditions' => array('Order.state' => 'unplaced')));

        $delete_count = 0;
        foreach ($old_orders as $key => $old_order) 
        {
            if(!CakeTime::wasWithinLast("61 days", $old_order['Order']['modified'])
            || (!CakeTime::wasWithinLast("1 day", $old_order['Order']['modified']) && $old_order['Order']['amount'] == 0))
            {
                foreach ($old_order['OrderItem'] as $key => $old_order_item) 
                {
                    // Delete all order items related
                    $this->Order->OrderItem->delete($old_order_item['id']);
                }

                // Delete old orders
                $this->Order->delete($old_order['Order']['id']);
                $delete_count++;    
            }
        }
        
        $this->Session->setFlash('Der blev slettet ' . $delete_count . ' gamle ordre.' .$this->Session->read('Message.success.message'), null, array(), 'success');
        $this->redirect(array('controller' => 'pages', 'action' => 'index'));
    }

    // VIEW FUNCTIONS

    public function index()
    {
        $orders = $this->Order->find('all', array('conditions' => array('Order.state' => array('received', 'packed'))));
        $this->set('orders', $orders);
    }

    public function resolved_orders()
    {
        $orders = $this->Order->find('all', array('conditions' => array('Order.state' => 'resolved')));
        $this->set('orders', $orders);
    }

    public function pack($order_id = null, $is_packed = false)
    {
        $order = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id, 'Order.state' => 'received'), 'recursive' => 4));

        // Does the item exist?
        if(empty($order))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Ordren findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }

        if($is_packed)
        {
            $this->Order->set($order);
            $this->Order->saveField('state', 'packed');
            $this->Order->saveField('date_packed', date('Y-m-d H:i:s'));
            $this->Session->setFlash('Ordren er nu sat til "Pakket".'.$this->Session->read('Message.success.message'), null, array(), 'success');
            $this->redirect(array('controller' => 'orders', 'action' => 'send', $order['Order']['id']));
        }

        $this->set('order', $order);
    }

    public function send($order_id = null)
    {
        if($this->request->is('post'))
        {             
            $this->Order->id = $this->request->data['Order']['id'];
            $this->Order->saveField('state', 'resolved');
            $this->Order->saveField('date_resolved', date('Y-m-d H:i:s'));
            $this->Order->saveField('delivery_label', $this->request->data['Order']['delivery_label']);
            $this->Session->setFlash('Ordren er nu sat til "Sendt".'.$this->Session->read('Message.success.message'), null, array(), 'success');
            $this->redirect(array('controller' => 'orders', 'action' => 'index'));
        } 

        $order = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id, 'Order.state' => 'packed'), 'recursive' => 4));
        // Does the item exist?
        if(empty($order))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Ordren findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }

        // if($is_resolved) {
        //     // Send the email receipt
        //     $Email = new CakeEmail('receipt');
        //     $Email->to($order['Customer']['email_address']);
        //     $Email->template('receipt_send')->viewVars( array('order' => $order));
        //     $Email->send(); 
        // }

        $this->set('order', $order);
    }

    public function view_as_receipt($order_id = null)
    {   
         $order = $this->Order->find('first', array('conditions' => array('Order.id' => $order_id), 'recursive' => 4));

        // Does the item exist?
        if(empty($order))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Ordren findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }

        if(CakeTime::wasWithinLast('5 minutes', $order['Order']['date_received']) || $this->isAuthorized())
        {
            $this->set('order', $order);
        }
        else
        {
            $this->Session->setFlash('Ordren findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }    
    }

    public function view_unplaced_order()
    {

        if($this->request->is('post'))
        {
            debug($this->data);
        }

        $order = $this->Order->find('first', array('conditions' => array('Order.id' => $this->Cookie->read('Order.id')), 'recursive' => 4));

        // Does the item exist?
        if(empty($order))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Ordren findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }

        if(count($order['OrderItem']) < 1)
        {
            $this->Session->setFlash('Din indkøbskurv er tom.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            $this->redirect(array('controller' => 'payments', 'action' => 'cart'));
        }

        if($order['Order']['conditions_accepted'])
        {
            // Calculate the price sent to Dibs. (Dibs needs the price given in 'øre' as integer)
            $dibs_price = intval($order['Order']['price'] * 100);

            // Other variables needed for dibs form
            $currency = 'DKK';
            $merchant_id = '90198243';
            $paytype = 'DK';

            // Md5 key from admin panel in dibs
            $k1 = 'nIiU2tpiY?sfwWDO+^mVfC,.Grrwk;hT';
            $k2 = 'S#)M{gz_-3Yh(i11KFoE+B]3ZdagkIN1';

            // Calculate the md5-key for Dibs
            $parameter_string = '';
            $parameter_string .= 'merchant=' . $merchant_id;
            $parameter_string .= '&orderid=' . $order['Order']['id'];
            $parameter_string .= '&currency=' . $currency;
            $parameter_string .= '&amount=' . $dibs_price;
            $md5_key = MD5($k2 . MD5($k1 . $parameter_string));

            // Send needed values to the view
            $this->set('dibs_price', $dibs_price);
            $this->set('currency', $currency);
            $this->set('merchant_id', $merchant_id);
            $this->set('paytype', $paytype);
            $this->set('md5_key', $md5_key);
            $this->set('order', $order);
        }
        else
        {
            $this->Session->setFlash('Du skal acceptere vores betingelser.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            $this->redirect(array('controller' => 'payments', 'action' => 'billing'));
        }      
    }

    // Internal functions
    private function access_yarn_batch($yarn_batch_id, $amount = 0)
    {

        $yarn_batch = $this->Order->OrderItem->YarnBatch->find('first', array('conditions' => array('YarnBatch.id' => $yarn_batch_id, 'YarnBatch.is_active' => 1)));


        // Does the item exist?
        if(empty($yarn_batch))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Garnpartiet findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }
        else if($amount > $yarn_batch['YarnBatch']['stock_quantity'])
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Du kan ikke tilføje '. $amount .' af varen der er '. $yarn_batch['YarnBatch']['stock_quantity'] .' tilbage på lager.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }
    }

    private function access_needle_variant($needle_variant_id, $amount = 0)
    {
        $needle_variant = $this->Order->OrderItem->NeedleVariant->find('first', array('conditions' => array('NeedleVariant.id' => $needle_variant_id, 'NeedleVariant.is_active' => 1)));
        // Does the item exist?
        if(empty($needle_variant))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Strikkepinden/hæklenålen findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }

        else if($amount > $needle_variant['NeedleVariant']['stock_quantity'])
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Du kan ikke tilføje '. $amount .' af varen der er '. $yarn_batch['YarnBatch']['stock_quantity'] .' tilbage på lager.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }
    }

    private function access_recipe($recipe_id)
    {

        $recipe = $this->Order->OrderItem->Recipe->find('first', array('conditions' => array('Recipe.id' => $recipe_id, 'Recipe.is_active' => 1)));


        // Does the item exist?
        if(empty($recipe))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Opskriften findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }
    }

    private function access_color_sample($yarn_id)
    {

        $recipe = $this->Order->OrderItem->ColorSample->find('first', array('conditions' => array('ColorSample.id' => $yarn_id, 'ColorSample.is_active' => 1)));


        // Does the item exist?
        if(empty($yarn_id))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Farveprøven findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            return $this->redirect($this->referer());
        }
    }

}

?>