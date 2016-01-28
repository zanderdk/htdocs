<?php
App::uses('AppModel', 'Model');

class Coupon extends AppModel
{
    public $displayField = 'key';

    public $validate = array(
        'actual_discount' => array(
            'decimal' => array(
                'rule' => 'decimal',
                'allowEmpty' => true,
                'message' => 'Du skal angive rabat-beløb.',
            ),
            'notNegative' => array(
                'rule'    => array('comparison', '>=', 0),
                'message' => 'Et rabat-beløb kan ikke være negativ.'
            )
        ),
        'percentage_discount' => array(
            'number' => array(
                  'rule'    => array('range', 0, 101),
                  'allowEmpty' => true,
                  'message' => 'Du skal angive en procent del rabat.'
            )
        ),
        'amount' => array(
            'naturalNumber' => array(
                'rule' => array('naturalNumber', true),
                'allowEmpty' => false,
                'message' => 'Du skal hvor mange kuponer der er.',
            )
        ),
        'item_amount' => array(
            'naturalNumber' => array(
                'rule' => array('naturalNumber', true),
                'allowEmpty' => false,
                'message' => 'Du skal angive mange produkter kuponen gælder for per kupon.',
            )
        ),
        'expiration_date' => array(
            'date' => array(
                'rule'    => array('datetime', 'ymd'),
                'message' => 'Du skal angive en udløbs dato for kuponen.',
            )
        ),
    );

    public $hasAndBelongsToMany = array(
        'YarnBatch',
        'NeedleVariant'
    );

    public $hasOne = array(
        'Order',
    );

    public function beforeValidate($options = array())
    {
        // Assume no validation errors
        $valid = true;
        
        // Check if there is only set one type of key - random or custom
        if ($this->data['Coupon']['random_wanted'] && !$this->data['Coupon']['key']) {
            $this->data['Coupon']['key'] = strtoupper(dechex(time()));

            $coupon_same_key = $this->find('first', array('conditions' => array('Coupon.key' => $this->data['Coupon']['key'])));
            if (!empty($coupon_same_key)) {
            // Tell the user that the randomized key collided with a previous coupon
                SessionComponent::setFlash(' Der findes allerede en kupon med den kode. Prøv at opret en ny tilfældig kode om 5 sekunder. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
                $valid = false;
            }
        } else if (!$this->data['Coupon']['random_wanted'] && $this->data['Coupon']['key']) {
            $coupon_same_key = $this->find('first', array('conditions' => array('Coupon.key' => $this->data['Coupon']['key'])));
            if (!empty($coupon_same_key)) {
            // Tell the user that the chosen key collided with a previous coupon
                SessionComponent::setFlash(' Der findes allerede en kupon med den kode, vælg et andet. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
                $valid = false;
            }
        } else {
            // Tell the user that he must choose either a random key or custom key
            SessionComponent::setFlash(' Du skal vælge enten at skrive en kode eller at få en tilfældig kode. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            $valid = false;
        }

        $product_counter = 0; // Counter to see how many products where related to it

        // Remove all yarn_batches that have id 0 because that means it was not selected
        if (!empty($this->data['YarnBatch'])) {
            foreach ($this->data['YarnBatch'] as $i => $yarn_batch) {
                if ($yarn_batch == 0) {
                    unset($this->data['YarnBatch'][$i]);
                } else {
                    $product_counter ++;
                }
            }
        }

        // Remove all yarn_batches that have id 0 because that means it was not selected
        if (!empty($this->data['NeedleVariant'])) {
            foreach ($this->data['NeedleVariant'] as $i => $needle_variant) {
                if ($needle_variant == 0) {
                    unset($this->data['NeedleVariant'][$i]);
                } else {
                    $product_counter ++;
                }
            }
        }

        // Check if there is any product related to this coupon
        if ($product_counter < 1) {
        // Tell the user that there must be at least one product related
            SessionComponent::setFlash(' Du skal tilføje mindst et produkt til kupon-koden for at give nogen rabatter ud. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            $valid = false;
        }

        // Check that there is only set one type of discount
        if (!($this->data['Coupon']['percentage_discount'] xor $this->data['Coupon']['actual_discount'])) {
        // Tell the user that there must be at least one product related
            SessionComponent::setFlash(' Du skal tilføje én type rabat, enten procentvis eller faktisk rabat. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            $valid = false;
        }

        if (!$this->isUsable($this->data)) {
            SessionComponent::setFlash(' Din kupon kan ikke bruges fordi den udløbsdato er i fortiden, eller fordi antallet er under 1. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            $valid = false;
        }

        return $valid;
    }


    public function beforeSave($options = array())
    {
        if (!empty($this->data)) {
            $this->data['Coupon']['is_active'] = $this->isUsable($this->data);
        }
    }

    public function isUsable($coupon = null)
    {
    
        if ($coupon == null) {
            $coupon = $this->find('first', array('conditions' => array('Coupon.id' => $this->id)));
        }

        if (!empty($coupon['Coupon']['is_active'])) {
            return $coupon['Coupon']['is_active'];
        }

        return  CakeTime::isFuture($coupon['Coupon']['expiration_date']) && // Is the coupon expired by date
                $coupon['Coupon']['amount'] > 0;                            // Is there any coupons left of this type
    }

    public function purge()
    {
        // TODO purge coupons an CouponsOrder
    }
}
