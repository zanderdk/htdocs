<?php

App::uses('AppController', 'Controller');

class CouponsController extends AppController 
{   
    
    public function beforeFilter() 
    {
        parent::beforeFilter();
    }

    public function index()
    {
        $this->set('coupons', $this->Coupon->find('all', array('order' => array('Coupon.is_active' => 'DESC', 'Coupon.created' => 'ASC'))));
    }
    public function add()
    {
        // Calls to fetch all active products and needed information without overflowing the system
        $yarn_batches = $this->Coupon->query("SELECT `Yarn`.`id`, `Yarn`.`name`, `Menu`.`type`, `YarnBatch`.`id`, `YarnBatch`.`intern_product_code` 
                        FROM `yarns` AS `Yarn`, `yarn_variants` AS `YarnVariant`, `yarn_batches` AS `YarnBatch`, `menus` AS `Menu` 
                        WHERE (`YarnBatch`.`yarn_variant_id` = `YarnVariant`.`id`) AND                              
                        (`YarnVariant`. `yarn_id` = `Yarn`.`id`) AND (`YarnBatch`.`is_active` = '1') AND                              
                        (`YarnVariant`.`is_active` = '1') AND (`Yarn`.`is_active` = '1') AND (`Yarn`.`menu_id` = `Menu`.`id`) 
                        ORDER BY `Yarn`.`name` ASC");

        $needle_variants = $this->Coupon->query("SELECT `Needle`.`id`, `Needle`.`name`, `Menu`.`type`, `NeedleVariant`.`id`, `NeedleVariant`.`intern_product_code`
                        FROM `needles` AS `Needle`, `needle_variants` AS `NeedleVariant`, `menus` AS `Menu` 
                        WHERE (`NeedleVariant`. `needle_id` = `Needle`.`id`) AND (`NeedleVariant`.`is_active` = '1') AND 
                        (`Needle`.`is_active` = '1') AND (`Needle`.`menu_id` = `Menu`.`id`) 
                        ORDER BY `Needle`.`name` ASC");
        
        // If this request is a post
        if($this->request->is('post'))
        {   
            // It is a new item?
            $this->Coupon->create();

            // Was the save succesfull?
            if ($this->Coupon->saveAll($this->request->data)) 
            {   
                // Tell the user it went well
                $this->Session->setFlash('Kuponen blev gemt.', null, array(), 'success');

                // Send the user to the index
                $this->redirect(array('controller' => 'coupons', 'action' => 'index'));
            }
            else
            {
                // Tell the user it was unsuccessful
                $this->Session->setFlash('Der opstod en fejl. Kuponen blev ikke gemt.'.$this->Session->read('Message.error.message'), null, array(), 'error');
            }
        }

        $this->set('yarn_batches', $yarn_batches);
        $this->set('needle_variants', $needle_variants);
    }

    public function deactivate($coupon_id)
    {
        // Find the requestet item
        $coupon = $this->Coupon->find('first', array('conditions' => array('Coupon.id' => $coupon_id)));

        // Does the item exist?
        if(empty($coupon))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne kupon findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
        }
        else if($coupon['Coupon']['is_active'] == 0)
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne kupon er ikke aktiv.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
        }
        else
        {
            // Set the instance of cupon to the one fetched from the id
            $this->Coupon->set($coupon);
            $this->Coupon->saveField('is_active', 0);

            // Tell the user this item did not exist
            $this->Session->setFlash('Kuponnen blev deaktiveret.'.$this->Session->read('Message.warning.success'), null, array(), 'success');
        }

        // Send the user to the index
        $this->redirect(array('controller' => 'coupons', 'action' => 'index'));
    }

    public function reactivate($coupon_id)
    {
        // Find the requestet item
        $coupon = $this->Coupon->find('first', array('conditions' => array('Coupon.id' => $coupon_id)));

        // Does the item exist?
        if(empty($coupon))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne kupon findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
        }
        else if($coupon['Coupon']['is_active'] == 1)
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne kupon er allerede aktiv.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');
        }
        else
        {   
            // Check if there are any more left
            if($coupon['Coupon']['amount'] < 1) 
            {
                // Tell the user that there are no more of these coupons
                $this->Session->setFlash('Der er ikke flere af disse kuponner tilbage. Den kan ikke reaktiveres..'.$this->Session->read('Message.error.success'), null, array(), 'error');
            }
            // Check if it has expired 
            else if(CakeTime::isPast($coupon['Coupon']['expiration_date'])) 
            {
                // Tell the user that this coupon has expired
                $this->Session->setFlash('Denne kupon er udløbet. Den kan ikke reaktiveres..'.$this->Session->read('Message.error.success'), null, array(), 'error');               
            } else 
            {
                // Set the instance of cupon to the one fetched from the id
                $this->Coupon->id = $coupon['Coupon']['id'];
                $this->Coupon->saveField('is_active', 1);

                // Tell the user this item did not exist
                $this->Session->setFlash('Kuponnen blev reaktivere.'.$this->Session->read('Message.warning.success'), null, array(), 'success');
            }
        }

        // Send the user to the index
        $this->redirect(array('controller' => 'coupons', 'action' => 'index'));
    }
}

?>