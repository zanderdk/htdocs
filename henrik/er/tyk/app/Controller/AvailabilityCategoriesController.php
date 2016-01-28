<?php
App::uses('AppController', 'Controller');

class AvailabilityCategoriesController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

	public function index()
	{
		$this->set('availability_categories', 
			$this->AvailabilityCategory->find('all', 
				array('order' => array(
					'AvailabilityCategory.type' => 'desc',
					'AvailabilityCategory.is_below' => 'desc', 
					)
				)
			)
		);
	}

	public function add()
	{	
		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->AvailabilityCategory->create();

			// Was the save succesfull?
			if ($this->AvailabilityCategory->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Beholdningskategorien blev gemt.', null, array(), 'success');
				$this->Session->delete('Message.error'); // Hides false error

				// Send the user to the index
				$this->redirect(array('controller' => 'availability_categories', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Beholdningskategorien blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$availability_category = $this->AvailabilityCategory->find('first', array('conditions' => array('AvailabilityCategory.id' => $id)));

		// Does the item exist?
		if(empty($availability_category))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne beholdningskategori findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'availability_categories', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->AvailabilityCategory->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Beholdningskategorien blev gemt.', null, array(), 'success');
				$this->Session->delete('Message.error'); // Hides false error

				// Send the user to the index
				$this->redirect(array('controller' => 'availability_categories', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Beholdningskategorien blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
		
		// Send the information of the old item to the view
		$this->set('availability_category', $availability_category);
		
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$availability_category = $this->AvailabilityCategory->find('first', array('conditions' => array('AvailabilityCategory.id' => $id)));

		// Does the item exist?
		if(empty($availability_category))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne beholdningskategori findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'availability_categories', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->AvailabilityCategory->delete($availability_category['AvailabilityCategory']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Beholdningskategorien blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Beholdningskategorien ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'availability_categories', 'action' => 'index'));
	}
}

?>