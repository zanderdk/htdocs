<?php
App::uses('AppModel', 'Model');
App::import('Component', 'File');

class CareLabel extends AppModel 
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

    public $hasAndBelongsToMany = array(
        // TODO
        'Yarn'
    );

    public function afterSave($created, $options = array())
    {
        // Sets the file to be null if no file was given
        if(empty($this->data['YarnVariant']['file']))
        {
            $this->data['YarnVariant']['file'] = null;
        }

        if(FileComponent::fileGiven($this->data['CareLabel']['file']))
        {
             // Check if the upload went well
            if(!FileComponent::uploadAndResizeImage($this->data['CareLabel']['file'], $this->data['CareLabel']['id'], 'png', 'care_labels'))
            {   
                // Was this a creation of a item?
                if($created)
                {   
                    // Delete the entry in the database
                    $this->delete($this->data['CareLabel']['id']);
                }
                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('Billedet til dette vaskemærke blev ikke uploadet korrekt. Vaskemærket blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
            }
        }
    }

    public function beforeDelete($cascade = true)
    {   
        $related_yarns = $this->Yarn->CareLabelsYarn->find('all', array('conditions' => array('CareLabelsYarn.care_label_id' => $this->id)));
        if(!empty($related_yarns))
        {
            // Inform the user that this item has relations
            SessionComponent::setFlash(' Dette vaskemærke har stadig relationer til nogle garnkvaliteter. Tryk på info knappen for at se hvad. '.SessionComponent::read('Message.error.message'), null, array(), 'error');
            return false;
        }    
    }

    public function afterDelete()
    {
        if(!FileComponent::deleteFile($this->id, 'png', true , 'care_labels'))
        {
            // Inform the user that the file is still on the server
            SessionComponent::setFlash('Billedet til dette vaskemærke blev ikke slettet og ligger stadig på serveren.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
        }
    }
}

?>