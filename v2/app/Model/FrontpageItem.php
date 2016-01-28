<?php
App::uses('AppModel', 'Model');

class FrontpageItem extends AppModel 
{
	public $displayField = 'id';
    
	public $validate = array(
        'url' => array(
            'url' => array(
                'rule' => 'url',
                'message' => 'Du skal skrive et link til knappen der vises.',
            ),
        ),
        'button_text' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal skrive hvad der skal stå på knappen.',
            ),
        ),
        'description' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal lave en beskrivelse.',
            ),
        ),
	);

    public function afterSave($created, $options = array())
    {
        // Sets the file to be null if no file was given
        if(empty($this->data['FrontpageItem']['file']))
        {
            $this->data['FrontpageItem']['file'] = null;
        }

        if(FileComponent::fileGiven($this->data['FrontpageItem']['file']))
        {
             // Check if the upload went well
            if(!FileComponent::uploadAndResizeImage($this->data['FrontpageItem']['file'], $this->data['FrontpageItem']['id'], 'png', 'frontpage_items', 1000, 1000))
            {   
                // Was this a creation of a item?
                if($created)
                {   
                    // Delete the entry in the database
                    $this->delete($this->data['FrontpageItem']['id']);
                }
                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('Billedet til denne forside artikel blev ikke uploadet korrekt. Artiklen blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
            }
        }
    }

    public function afterDelete()
    {
        if(!FileComponent::deleteFile($this->id, 'png', true , 'frontpage_items'))
        {
            // Inform the user that the file is still on the server
            SessionComponent::setFlash('Billedet til denne forside artikel blev ikke slettet og ligger stadig på serveren.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
        }
    }
    

}

?>