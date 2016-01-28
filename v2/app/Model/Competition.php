<?php
App::uses('AppModel', 'Model');

class Competition extends AppModel 
{
    public $displayField = 'title';

    public $validate = array(
        'title' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive en titel.',
            ),
        ),
        'description' => array(
            'notEmpty' => array(
                'rule' => 'notEmpty',
                'message' => 'Du skal angive en beskrivelse.',
            ),
        ),
    );

    public $hasMany = array(
        'Participant'
    );

    public function afterSave($created, $options = array())
    {
        // Sets the file to be null if no file was given
        if(empty($this->data['Competition']['file']))
        {
            $this->data['Competition']['file'] = null;
        }

        // Upload the image of the variant
        if(FileComponent::fileGiven($this->data['Competition']['file']))
        {   
             // Check if the upload went well (The image is limited to 500 x 500 pixels)
            if(!FileComponent::uploadAndResizeImage($this->data['Competition']['file'], $this->data['Competition']['id'], 'png', 'competitions', 1000, 1000))
            {   
                // Was this a creation of an item?
                if($created)
                {   
                    // Delete the entry in the database
                    $this->delete($this->data['Competition']['id']);
                }
                // It did not go well delete the image and inform the user
                SessionComponent::setFlash('Billedet til denne konkurrence blev ikke uploadet korrekt. Konkurrencen blev ikke gemt.'.SessionComponent::read('Message.error.message'), null, array(), 'error');
                return false;
            }
        }

    }

    public function close($data)
    {
        $competition = $this->find('first', array('conditions' => array('Competition.id' => $data['Competition']['id'])));

        if(!$competition['Competition']['is_active'])
        {
            SessionComponent::setFlash(' Denne konkurrence er allerede lukket. '.SessionComponent::read('Message.warning.message'), null, array(), 'error');
            return false;
        }

        $this->set($competition);
        $this->saveField('is_active', 0);
        return true;
    }

    public function reopen($data)
    {
        $competition = $this->find('first', array('conditions' => array('Competition.id' => $data['Competition']['id'])));

        if($competition['Competition']['is_active'])
        {
            SessionComponent::setFlash(' Denne konkurrence er allerede åben. '.SessionComponent::read('Message.warning.message'), null, array(), 'error');
            return false;
        }

        $this->set($competition);
        $this->saveField('is_active', 1);
        return true;
    }

}

?>