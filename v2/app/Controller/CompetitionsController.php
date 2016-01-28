<?php
App::uses('AppController', 'Controller');

class CompetitionsController  extends AppController 
{
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('participate_from_facebook','participate');
    }

    public function index()
    {
        $this->set('competitions', $this->Competition->find('all', array('order' => array('Competition.is_active' => 'desc'))));
    }

    public function add()
    {   
        // If this request is a post
        if($this->request->is('post'))
        {   
            // It is a new item?
            $this->Competition->create();

            // Was the save succesfull?
            if ($this->Competition->save($this->request->data)) 
            {   
                // Tell the user it went well
                $this->Session->setFlash('Konkurencen blev gemt.'.$this->Session->read('Message.success.message'), null, array(), 'success');

                // Send the user to the index
                $this->redirect(array('controller' => 'competitions', 'action' => 'index'));
            }
            else
            {
                // Tell the user it was unsuccessful
                $this->Session->setFlash('Der opstod en fejl. Konkurencen blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
            }
        }
    }

    public function edit($id = null)
    {   
        // Find the requestet item
        $competition = $this->Competition->find('first', array('conditions' => array('Competition.id' => $id)));

        if(empty($competition))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'competitions', 'action' => 'index'));
        }
        else if(!$competition['Competition']['is_active'])
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence er ikke aktiv.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'competitions', 'action' => 'index'));
        }

        // Call the add function without creating a new instance
        if($this->request->is('post'))
        {
            // Was the save succesfull?
            if ($this->Competition->save($this->request->data)) 
            {   
                // Tell the user it went well
                $this->Session->setFlash('Konkurencen blev gemt.', null, array(), 'success');

                // Send the user to the index
                $this->redirect(array('controller' => 'competitions', 'action' => 'index'));
            }
            else
            {
                // Tell the user it was unsuccessful
                $this->Session->setFlash('Der opstod en fejl. Konkurencen blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');    
            }       
        }

        // Send the information of the old item to the view
        $this->set('competition', $competition);
    }

    public function close($id = null)
    {   
        // Find the requestet item
        $competition = $this->Competition->find('first', array('conditions' => array('Competition.id' => $id)));

        if(empty($competition))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'competitions', 'action' => 'index'));
        }

        if($this->Competition->close($competition))
        {
           // Tell the user that the competition was reopened
            $this->Session->setFlash('Konkurencen er nu lukket.'.$this->Session->read('Message.success.message'), null, array(), 'success');
        }

        // Send the user to the index
        $this->redirect(array('controller' => 'competitions', 'action' => 'index')); 

    }

    public function reopen($id = null)
    {   
        // Find the requestet item
        $competition = $this->Competition->find('first', array('conditions' => array('Competition.id' => $id)));

        if(empty($competition))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'competitions', 'action' => 'index'));
        }

        if($this->Competition->reopen($competition))
        {
           // Tell the user that the competition was reopened
            $this->Session->setFlash('Konkurencen er nu genåbnet.'.$this->Session->read('Message.success.message'), null, array(), 'success');
        }

        // Send the user to the index
        $this->redirect(array('controller' => 'competitions', 'action' => 'index')); 

    }

    public function find_winner($id = null)
    {
        // Find the requestet item
        $competition = $this->Competition->find('first', array('conditions' => array('Competition.id' => $id)));

        // Does the item exist?
        if(empty($competition))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'competitions', 'action' => 'index'));
        }
        else if(!$competition['Competition']['is_active'])
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence er ikke aktiv.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'competitions', 'action' => 'index'));
        }

        $winner = null;

        if($this->request->is('post'))
        {   
            $count = $this->Competition->Participant->find('count', array('conditions' => array('Participant.competition_id' => $id)));
            $winner = $competition['Participant'][rand(0, $count-1)]; 
        }

        $this->set('competition', $competition);
        $this->set('winner', $winner);
    }

    public function participate_from_facebook($id = null)
    {
        // Find the requestet item
        $competition = $this->Competition->find('first', array('conditions' => array('Competition.id' => $id)));

        if(empty($competition))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        else if(!$competition['Competition']['is_active'])
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence er ikke aktiv.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }

        $this->Session->write('Competition.'. $id, true);
        $this->redirect(array('controller' => 'competitions', 'action' => 'participate', $id));

    }

    public function participate($id = null)
    {
        // Find the requestet item
        $competition = $this->Competition->find('first', array('conditions' => array('Competition.id' => $id)));

        if(empty($competition))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }
        else if(!$competition['Competition']['is_active'])
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne konkurrence er ikke aktiv.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }

        if(!($this->Session->check('Competition.'.$id) && $this->Session->read('Competition.'.$id)))
        {
            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'home'));
        }

        $this->set('competition', $competition);

        if($this->request->is('post'))
        {
            // It is a new item?
            $this->Competition->Participant->create();

            // Was the save succesfull?
            if ($this->Competition->Participant->save($this->request->data)) 
            {   
                // Ensure that the user can only access this site once per session
                $this->Session->write('Competition.'. $id, false);

                // Tell the user it went well
                $this->Session->setFlash('Velkommen i konkurrencen, held og lykke!.'.$this->Session->read('Message.success.message'), null, array(), 'success');

                // Send the user to the index
                $this->redirect(array('controller' => 'pages', 'action' => 'index'));
            }
            else
            {
                // Tell the user it was unsuccessful
                $this->Session->setFlash('Der opstod en fejl. Konkurencen blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
            }
        }
        
    }

}

?>