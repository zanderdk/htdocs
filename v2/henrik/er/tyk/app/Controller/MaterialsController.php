<?php
App::uses('AppController', 'Controller');

class MaterialsController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

	public function index()
	{
		$this->set('materials', $this->Material->find('all'));
	}

	public function add()
	{	
		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->Material->create();

			// Was the save succesfull?
			if ($this->Material->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Materialet blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'materials', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Materialet blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$material = $this->Material->find('first', array('conditions' => array('Material.id' => $id)));

		// Does the item exist?
		if(empty($material))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Dette materiale findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'materials', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->Material->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Materialet blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'materials', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Materialet blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}

		// Send the information of the old item to the view
		$this->set('material', $material);
		
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$material = $this->Material->find('first', array('conditions' => array('Material.id' => $id)));

		// Does the item exist?
		if(empty($material))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Dette materiale findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'materials', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->Material->delete($material['Material']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Materialet blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Materialet ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'materials', 'action' => 'index'));
	}
}

?>