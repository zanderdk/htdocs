<?php
App::uses('AppModel', 'Model');

class Material extends AppModel 
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
        'YarnPart'
    );

    public function beforeDelete($cascade = true)
    {   
        $related_yarns = $this->YarnPart->find('all', array('conditions' => array('YarnPart.material_id' => $this->id)));
        if(!empty($related_yarns))
        {
            // Inform the user that this item has relations
            SessionComponent::setFlash(' Dette materiale har relationer til nogle produkter. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        } 
    }
}

?>