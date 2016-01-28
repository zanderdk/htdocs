<?php
App::uses('AppModel', 'Model');

class Participant extends AppModel 
{
    public $displayField = 'email_address';

    public $validate = array(
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

    public function beforeSave($options = array())
    {
        // TODO Check if there are any with the same email or phone_number --> Throw error
    }

    public $belongsTo = array(
        'Competition'
    );

}

?>