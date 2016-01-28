<?php
App::uses('AppController', 'Controller');

class CareLabelsController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

	public function index()
	{
		$this->set('care_labels', $this->CareLabel->find('all'));
	}

	public function add()
	{	
		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->CareLabel->create();

			// Was the save succesfull?
			if ($this->CareLabel->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Vaskemærket blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'care_labels', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Vaskemærket blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$care_label = $this->CareLabel->find('first', array('conditions' => array('CareLabel.id' => $id)));

		// Does the item exist?
		if(empty($care_label))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Dette vaskemærke findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'care_labels', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->CareLabel->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Vaskemærket blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'care_labels', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Vaskemærket blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
		
		// Send the information of the old item to the view
		$this->set('care_label', $care_label);
		
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$care_label = $this->CareLabel->find('first', array('conditions' => array('CareLabel.id' => $id)));

		// Does the item exist?
		if(empty($care_label))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Dette vaskemærke findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'care_labels', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->CareLabel->delete($care_label['CareLabel']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Vaskemærket blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Vaskemærket blev ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'care_labels', 'action' => 'index'));
	}
}

?>