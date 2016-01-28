<?php
App::uses('AppController', 'Controller');

class FrontpageItemsController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

	public function index()
	{
		$this->set('frontpage_items', $this->FrontpageItem->find('all'));
	}

	public function add()
	{	
		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->FrontpageItem->create();

			// Was the save succesfull?
			if ($this->FrontpageItem->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Forside artiklen blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'frontpage_items', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Forside artiklen blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$frontpage_item = $this->FrontpageItem->find('first', array('conditions' => array('FrontpageItem.id' => $id)));

		// Does the item exist?
		if(empty($frontpage_item))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Dette forside artikel findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'frontpage_items', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->FrontpageItem->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Forside artiklen blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'frontpage_items', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Forside artiklen blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');	
			}		
		}

		// Send the information of the old item to the view
		$this->set('frontpage_item', $frontpage_item);
		
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$frontpage_item = $this->FrontpageItem->find('first', array('conditions' => array('FrontpageItem.id' => $id)));

		// Does the item exist?
		if(empty($frontpage_item))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne menu findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'frontpage_items', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->FrontpageItem->delete($frontpage_item['FrontpageItem']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Forside artiklen blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Forside artiklen ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'frontpage_items', 'action' => 'index'));
	}
}

?>