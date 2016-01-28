<?php
App::uses('AppModel', 'Model');

class Color extends AppModel 
{
	public $displayField = 'name';

	public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et navn.',
            ),
        ),
	);

    public $hasMany = array(
        // TODO 
        'YarnVariant'
    );

    public function beforeDelete($cascade = true)
    {   
        $related_yarn_variants = $this->YarnVariant->find('all', array('conditions' => array('YarnVariant.color_id' => $this->id, 'YarnVariant.is_active' => 1)));
        if(!empty($related_yarn_variants))
        {
            // Inform the user that this item has relations
            SessionComponent::setFlash(' Denne menu har stadig relationer til nogle garnvarianter. Tryk på info knappen for at se hvad'.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        } 
    }
}

?>