<?php
App::uses('AppController', 'Controller');

class ColorsController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

	public function index()
	{
		$this->set('colors', $this->Color->find('all'));
	}

	public function add()
	{	
		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->Color->create();

			// Was the save succesfull?
			if ($this->Color->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Garnfarven blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'colors', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Garnfarven blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$color = $this->Color->find('first', array('conditions' => array('Color.id' => $id)));

		// Does the item exist?
		if(empty($color))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne garnfarve findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'colors', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->Color->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Garnfarven blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'colors', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Garnfarven blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	
		// Send the information of the old item to the view
		$this->set('color', $color);
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$color = $this->Color->find('first', array('conditions' => array('Color.id' => $id)));

		// Does the item exist?
		if(empty($color))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne garnfarve findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'colors', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->Color->delete($color['Color']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Garnfarven blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Garnfarven ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'colors', 'action' => 'index'));
	}
}

?>