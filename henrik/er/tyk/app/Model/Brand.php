<?php
App::uses('AppModel', 'Model');

class Brand extends AppModel 
{
	public $displayField = 'name';

	public $validate = array(
        'name' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive et navn.',
            ),
        ),
        'type' => array(
            'inList' => array(
                'rule'    => array('inList', array('yarn', 'needle', 'surplus_yarn')),
                'allowEmpty' => false,
                'message' => 'Du skal vælge type til menuen.'
            ),
        ),
	);

    public $hasMany = array(
        // TODO 
        'Yarn',
        'Needle'
    );

    public function beforeDelete($cascade = true)
    {   
        $related_yarns = $this->Yarn->find('all', array('conditions' => array('Yarn.brand_id' => $this->id, 'Yarn.is_active' => 1)));
        if(!empty($related_yarns))
        {
            // Inform the user that this item has relations
            SessionComponent::setFlash(' Dette mærke har stadig relationer til nogle produkter. Tryk på info knappen for at se hvad. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        } 
        $related_yarns = $this->Needle->find('all', array('conditions' => array('Needle.brand_id' => $this->id, 'Needle.is_active' => 1)));
        if(!empty($related_yarns))
        {
            // Inform the user that this item has relations
            SessionComponent::setFlash(' Dette mærke har stadig relationer til nogle produkter. Tryk på info knappen for at se hvad. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }   
    }

    // TODO 
    // Create a function that "deletes" or "updates" 
        // Set is_active/parrent/previous
}

?>