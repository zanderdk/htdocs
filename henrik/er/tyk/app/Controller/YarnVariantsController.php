<?php
App::uses('AppController', 'Controller');

class YarnVariantsController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('index','view');
    }

	public function add($yarn_id = null)
	{	
		// Find the requestet item
		$yarn = $this->YarnVariant->Yarn->find('first', array('conditions' => array('Yarn.id' => $yarn_id, 'Yarn.is_active' => 1)));

		// Does the item exist?
		if(empty($yarn))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Garnkvaliteten findes ikke.'.$this->Session->read('Message.error.message'), null, array(), 'error');

			// Send the user to the index
			$this->redirect(array('controller' => 'yarns', 'action' => 'index'));
		}

		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->YarnVariant->create();

			// Was the save succesfull?
			if ($this->YarnVariant->save($this->request->data)) 
			{	
				// Find the requestet item
				$yarn_variant = $this->YarnVariant->find('first', array('conditions' => array('YarnVariant.id' => $this->YarnVariant->id)));
				if(!empty($yarn_variant))
				{
					// Tell the user it went well
					$this->Session->setFlash('Garnvarianten blev gemt.', null, array(), 'success');

					// Send the user to the index
					$this->redirect(array('controller' => 'yarns', 'action' => 'view', $yarn_id));
				}
				
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Garnvarianten blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
			
		// Send the related models to the view
		$this->set('yarn', $yarn);
		$colors[0] = 'Ingen';
		ksort($colors);
		$this->set('colors', $colors);
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$yarn_variant = $this->YarnVariant->find('first', array('conditions' => array('YarnVariant.id' => $id)));

		// Does the item exist?
		if(empty($yarn_variant))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne garnvariant findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'yarns', 'action' => 'index'));
		}
		// Is the item still active
		else if(!$yarn_variant['YarnVariant']['is_active'])
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Du kan ikke rette en inaktive garnvariant.'.$this->Session->read('Message.error.message'), null, array(), 'error');

			// Send the user to the index
			$this->redirect(array('controller' => 'yarns', 'action' => 'view', $yarn_variant['YarnVariant']['yarn_id']));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->YarnVariant->edit($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Garnvarianten blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'yarns', 'action' => 'view', $yarn_variant['YarnVariant']['yarn_id']));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Garnvarianten blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	
		// Send the information of the old item to the view
		$this->set('yarn_variant', $yarn_variant);
		
		// Send the related models to the view
		$colors = $this->YarnVariant->Color->find('list');
		$colors[0] = 'Ingen';
		ksort($colors);
		$this->set('colors', $colors);
		
		
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$yarn_variant = $this->YarnVariant->find('first', array('conditions' => array('YarnVariant.id' => $id)));

		// Does the item exist?
		if(empty($yarn_variant))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne garnvariant findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'yarns', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->YarnVariant->deactivate($yarn_variant['YarnVariant']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Garnvarianten blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Garnvarianten ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'yarns', 'action' => 'view', $yarn_variant['YarnVariant']['yarn_id']));
	}

	public function make_thumbnail($id = null)
	{
		// Find the requestet item
		$yarn_variant = $this->YarnVariant->find('first', array('conditions' => array('YarnVariant.id' => $id)));

		// Does the item exist?
		if(empty($yarn_variant))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne garnvariant findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'yarns', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->YarnVariant->makeThumbnail($yarn_variant['YarnVariant']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Thumbnail opdateret.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Thumbnailen blev ikke opdateret.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'yarns', 'action' => 'view', $yarn_variant['YarnVariant']['yarn_id']));
	}

}

?>