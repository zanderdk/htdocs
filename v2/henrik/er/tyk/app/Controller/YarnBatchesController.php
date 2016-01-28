<?php
App::uses('AppController', 'Controller');

class YarnBatchesController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
    }

	public function add($yarn_variant_id = null)
	{	
		// Find the requestet item
		$yarn_variant = $this->YarnBatch->YarnVariant->find('first', array('conditions' => array('YarnVariant.id' => $yarn_variant_id, 'YarnVariant.is_active' => 1)));

		// Find items that are neede to create the item
		$availability_category = $this->YarnBatch->AvailabilityCategory->find('first', array('conditions' => array('AvailabilityCategory.type' => 'yarn')));

		// Does the item exist?
		if(empty($yarn_variant))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Garnvarianten findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'yarns', 'action' => 'index'));
		}

		// Checks if it is save to creat an item
		$safe_to_create = !empty($availability_category);
		
		if(!$safe_to_create)
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Du kan ikke oprette et garnparti på nuværende tidspunkt. Sørg for at der er mindst en beholdningskategori som passer til garn.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the yarn
			$this->redirect(array('controller' => 'yarns', 'action' => 'view', $yarn_variant['Yarn']['id']));
		}

		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->YarnBatch->create();

			// Was the save succesfull?
			if ($this->YarnBatch->save($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Garnpartiet blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'yarns', 'action' => 'view', $yarn_variant['Yarn']['id']));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Garnpartiet blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}

		// Send the related models to the view
		$this->set('yarn_variant', $yarn_variant);
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$yarn_batch = $this->YarnBatch->find('first', array('conditions' => array('YarnBatch.id' => $id), 'recursive' => 2));

		// Does the item exist?
		if(empty($yarn_batch))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Dette garnparti findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'yarns', 'action' => 'index'));
		}
		// Is the item still active
		else if(!$yarn_batch['YarnBatch']['is_active'])
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Du kan ikke rette et inaktive garnparti.'.$this->Session->read('Message.error.message'), null, array(), 'error');

			// Send the user to the index
			$this->redirect(array('controller' => 'yarns', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->YarnBatch->edit($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Garnpartiet blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'yarns', 'action' => 'view', $yarn_batch['YarnVariant']['Yarn']['id'])); 
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Garnpartiet blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	
		// Send the information of the old item to the view
		$this->set('yarn_batch', $yarn_batch);
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$yarn_batch = $this->YarnBatch->find('first', array('conditions' => array('YarnBatch.id' => $id), 'recursive' => 2));

		// Does the item exist?
		if(empty($yarn_batch))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Dette garnparti findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'yarns', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->YarnBatch->deactivate($yarn_batch['YarnBatch']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Garnpartiet blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Garnpartiet blev ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'yarns', 'action' => 'view', $yarn_batch['YarnVariant']['Yarn']['id']));
	}
}

?>