<?php
App::uses('AppController', 'Controller');

class CategoriesController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

	public function index()
	{
		$this->set('categories', $this->Category->find('all'));
	}

	public function add()
	{	
		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->Category->create();

			// Was the save succesfull?
			if ($this->Category->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Kategorien blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'categories', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Kategorien blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$category = $this->Category->find('first', array('conditions' => array('Category.id' => $id)));

		// Does the item exist?
		if(empty($category))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne menu findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'categories', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->Category->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Kategorien blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'categories', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Kategorien blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');	
			}		
		}

		// Send the information of the old item to the view
		$this->set('category', $category);
		
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$category = $this->Category->find('first', array('conditions' => array('Category.id' => $id)));

		// Does the item exist?
		if(empty($category))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne menu findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'categories', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->Category->delete($category['Category']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Kategorien blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Kategorien ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'categories', 'action' => 'index'));
	}
}

?>