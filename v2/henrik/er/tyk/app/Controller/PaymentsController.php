<?php 
App::uses('AppController', 'Controller');
App::uses('Paypal', 'Paypal.Lib');
App::uses('Customer', 'AppModel');
App::uses('CakeEmail', 'Network/Email');
App::import('Controller', 'Orders');

class PaymentsController extends AppController 
{
    // Helpers
    public $helpers = array('Html');

    // The price of orders being shipped
    public $default_shipping_price = 39;
    public $free_shipping_threshold = 500;

    // Internal varialbe to deal with 
    private $dibs_accepted = false;

    public function beforeFilter()
    {
        $this->check_dibs_status($this->request);
        parent::beforeFilter();
        $this->Auth->allow('cart', 'billing', 'pay', 'accept_payment');
    }

    public function cart() 
    {
        $this->loadModel('Order');
        $this->set('order',$this->Order->find('first', array('conditions' => array('Order.id' => $this->Cookie->read('Order.id')), 'recursive' => 4)));
    }

    public function billing()
    {
        $this->loadModel('Order');
        $order = $this->Order->find('first', array('conditions' => array('Order.id' => $this->Cookie->read('Order.id')),  'recursive' => 2));
        
        if(count($order['OrderItem']) < 1)
        {
            $this->Session->setFlash('Din indkøbskurv er tom.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
            $this->redirect(array('controller' => 'payments', 'action' => 'cart'));
        }

        if($order['Customer']['id'] != 0)
        {
            $customer = $order['Customer'];
        } 
        else 
        {
            $customer = $order['Customer'];
            $customer['BillingAddress'] = array('id' => null, 'zip_code' => null, 'city_name' => null, 'street' => null);
            $customer['ShippingAddress'] = array('id' => null, 'zip_code' => null, 'city_name' => null, 'street' => null);
        }

        $customer['ShippingAddress']['same_shipping_address'] = $customer['ShippingAddress']['zip_code'] == $customer['BillingAddress']['zip_code'] && 
            $customer['ShippingAddress']['city_name'] == $customer['BillingAddress']['city_name'] &&
            $customer['ShippingAddress']['street'] == $customer['BillingAddress']['street'];

        $this->set('customer', $customer);
        $this->set('customer_note', $order['Order']['customer_note']);

        if($this->request->is('post'))
        {   
            // Save customer data
            if(!isset($order['Customer']['id']))
            {
                $this->Order->Customer->create();
            } 

            if(!$this->Order->Customer->save($this->data))
            {   
                // The save went wrong
                $this->Session->setFlash('Dine oplysninger blev ikke gemt.'.$this->Session->read('Message.error.message'), null, array(), 'error');
            }

            $this->Order->set($order);
            $this->Order->saveField('customer_id', $this->Order->Customer->id);
            $this->Order->saveField('customer_note', $this->data['Order']['customer_note']);

            // Check if the user agrees to the terms of service
            if(!$this->data['Condition']['accepted'])
            {
                $this->Session->setFlash('Du skal acceptere vores betingelser.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
                $this->redirect(array('controller' => 'payments', 'action' => 'billing'));
            }

            $this->Order->saveField('conditions_accepted', true);

            $this->redirect(array('controller' => 'orders', 'action' => 'view_unplaced_order'));    
        }   
    }

    public function accept_payment($order_id)
    {

        $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        
    }

    // Used to fetch dibs return DATA
    private function check_dibs_status()
    {
        if($this->request->is('post'))
        {   
            if(!empty($this->request['data']['transact']) && !empty($this->request['data']['orderid']) && !empty($this->request['data']['statuscode']) && $this->request['data']['statuscode'] == 5)
            {
                $transaction_id = $this->request['data']['transact'];
                $this->loadModel('Order');
                $order = $this->Order->find('first', array('conditions' => array('Order.id' => $this->request['data']['orderid']), 'recursive' => 4));
                
                if(!empty($order) && $order['Order']['state'] == 'unplaced')
                {
                    // Send the email receipt
                    $Email = new CakeEmail('receipt');
                    $Email->to($order['Customer']['email_address']);
                    $email->to('bugledk@gmail.com');
                    $Email->template('receipt')->viewVars( array('order' => $order, 'transaction_id' => $transaction_id));
                    $Email->send();

                    // Save the received order
                    $this->Order->set($order);
                    $this->Order->saveField('state', 'received');
                    $this->Order->saveField('transaction_id', $transaction_id);
                    $this->Order->saveField('date_received', date('Y-m-d H:i:s'));
                    $this->Order->updateStockQuantityAfterPurchase();

                    $this->Session->write('Payment.accepted', true);
                    $this->Session->write('Payment.transaction_id', $this->request['data']['transact']);    
                }
                
            }
        }
    }
}

?>