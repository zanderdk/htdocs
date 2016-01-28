<?php
App::uses('AppModel', 'Model');

class Address extends AppModel 
{
	public $displayField = 'id';
    
	public $validate = array(
        'zip_code' => array(
            'number' => array(
                'allowEmpty' => false,
                'rule'    => array('range', 999, 10000),
                'message' => 'Angiv et postnummer'
            )
        ),
        'city_name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et bynavn.',
            ),
        ),
        'street' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive en gade/vej og nummer.',
            ),
        ),
	);

    public $belongsTo = array(
        'Customer',
    );

    public function beforeSave($options = array())
    {
        foreach ($this->data as $address_key => $address) 
        {
            foreach ($address as $field_key => $value) {
                if($field_key == 'city_name' || $field_key == 'street')
                {
                    // Ensures that the streetname and cityname is always 
                    $this->data[$address_key][$field_key] = ucfirst(strtolower($value));
                }
            }
            
        }
    }

    // TODO 
    // Validate the address using the API: http://geo.oiorest.dk/
        // Update is_valid
        // Consider when this should happen!
}

?>