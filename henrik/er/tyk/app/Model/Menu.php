<?php
App::uses('AppModel', 'Model');

class Menu extends AppModel 
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
                'rule'    => array('inList', array('yarn', 'knit', 'crochet', 'surplus_yarn')),
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
        $related_yarns = $this->Yarn->find('all', array('conditions' => array('Yarn.menu_id' => $this->id, 'Yarn.is_active' => 1)));
        if(!empty($related_yarns))
        {
            // Inform the user that this item has relations
            SessionComponent::setFlash(' Denne menu har stadig relationer til nogle produkter. Tryk på info knappen for at se hvad. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        } 
        $related_needles = $this->Needle->find('all', array('conditions' => array('Needle.menu_id' => $this->id, 'Needle.is_active' => 1)));
        if(!empty($related_needles))
        {
            // Inform the user that this item has relations
            SessionComponent::setFlash(' Denne menu har stadig relationer til nogle produkter. Tryk på info knappen for at se hvad. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        } 
    }
}

?>