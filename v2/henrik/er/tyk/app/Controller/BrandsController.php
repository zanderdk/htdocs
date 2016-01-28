<?php
App::uses('AppController', 'Controller');

class BrandsController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

	public function index()
	{
		// Find every active items (is_active is 1)
		$this->set('brands', $this->Brand->find('all', array('conditions' => array('Brand.is_active' => 1))));
	}

	public function add()
	{	
		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->Brand->create();

			// Was the save succesfull?
			if ($this->Brand->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Mærket blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'brands', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Mærket blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$brand = $this->Brand->find('first', array('conditions' => array('Brand.id' => $id)));

		// Does the item exist?
		if(empty($brand))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Dette mærke findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'brands', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->Brand->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Mærket blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'brands', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Mærket blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}

		// Send the information of the old item to the view
		$this->set('brand', $brand);	
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$brand = $this->Brand->find('first', array('conditions' => array('Brand.id' => $id)));

		// Does the item exist?
		if(empty($brand))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Dette mærke findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'brands', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->Brand->delete($brand['Brand']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Mærket blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Mærket ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'brands', 'action' => 'index'));
	}
}

?>