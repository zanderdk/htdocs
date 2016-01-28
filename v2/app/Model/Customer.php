<?php
App::uses('AppModel', 'Model');

class Customer extends AppModel 
{
	public $displayField = 'email_adress';
    
	public $validate = array(
        'first_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'allowEmpty' => false,
                'message' => 'Du skal angive et fornavn uden mellemrum og specialtegn.',
            ),
        ),
        'last_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'allowEmpty' => false,
                'message' => 'Du skal angive et efternavn uden mellemrum og specialtegn.',
            ),
        ),
        'email_address' => array(
            'email' => array(
                'rule' => 'email',
                'allowEmpty' => false,
                'message' => 'Du skal angive en emailadresse (mail@adresse.dk).',
            ),
        ),
        'phone_number' => array(
            'phone' => array(
                'rule' => array('phone', '/^\d{8}$/','all'), // TODO fix this
                'allowEmpty' => false,
                'message' => 'Du skal angive et rigtigt telefonnummer (12345678).',
            ),
        ),
	);

    public $hasMany = array(
        // TODO 
        'Order'
    );

    public $belongsTo = array(
        'BillingAddress' => array(
            'className' => 'Address',
            'foreignKey' => 'billing_address_id'
        ),
        'ShippingAddress' => array(
            'className' => 'Address',
            'foreignKey' => 'shipping_address_id'
        )
    );

    public function beforeValidate($options = array())
    {
        
        // Check if the customer wants it shipped to the same place as he bills to
        if($this->data['Customer']['ShippingAddress']['same_shipping_address'])
        {         
            // Copy the info from billing
            $this->data['Customer']['ShippingAddress']['zip_code'] = $this->data['Customer']['BillingAddress']['zip_code'];
            $this->data['Customer']['ShippingAddress']['city_name'] = $this->data['Customer']['BillingAddress']['city_name'];
            $this->data['Customer']['ShippingAddress']['street'] = $this->data['Customer']['BillingAddress']['street'];
        }

        if($this->ShippingAddress->save($this->data['Customer']['ShippingAddress']) && 
           $this->BillingAddress->save($this->data['Customer']['BillingAddress']))
        {
            $this->data['Customer']['shipping_address_id'] = $this->ShippingAddress->id;
            $this->data['Customer']['billing_address_id'] = $this->BillingAddress->id;
        } else 
        {
            return false;
        }
       
        // Remove spaces from string before validating it e.g. (12 34 56 78 => 12345678)
        $this->data['Customer']['phone_number'] = str_replace(' ', '', $this->data['Customer']['phone_number']);

        return true;
    }

    public function beforeSave($options = array())
    {
        // Store customer information consistenly
        $this->data['Customer']['first_name'] = ucwords(strtolower($this->data['Customer']['first_name']));
        $this->data['Customer']['last_name'] = ucwords(strtolower($this->data['Customer']['last_name']));
        $this->data['Customer']['email_address'] = strtolower($this->data['Customer']['email_address']);
    }
}

?>