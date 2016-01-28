<?php
App::uses('AppController', 'Controller');

class MenusController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

	public function index()
	{
		$this->set('menus', $this->Menu->find('all'));
	}

	public function add()
	{	
		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->Menu->create();

			// Was the save succesfull?
			if ($this->Menu->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Menuen blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'menus', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Menuen blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$menu = $this->Menu->find('first', array('conditions' => array('Menu.id' => $id)));

		// Does the item exist?
		if(empty($menu))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne menu findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'menus', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->Menu->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Menuen blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'menus', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Menuen blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');	
			}		
		}

		// Send the information of the old item to the view
		$this->set('menu', $menu);
		
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$menu = $this->Menu->find('first', array('conditions' => array('Menu.id' => $id)));

		// Does the item exist?
		if(empty($menu))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne menu findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'menus', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->Menu->delete($menu['Menu']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Menuen blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Menuen ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'menus', 'action' => 'index'));
	}
}

?>