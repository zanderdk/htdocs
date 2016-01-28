<?php
App::uses('AppController', 'Controller');

class NeedleVariantsController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('index','view');
    }

	public function add($needle_id = null)
	{	
		// Find the requestet item
		$needle = $this->NeedleVariant->Needle->find('first', array('conditions' => array('Needle.id' => $needle_id, 'Needle.is_active' => 1)));

		// Find items that are neede to create the item
		$availability_category = $this->NeedleVariant->AvailabilityCategory->find('first', array('conditions' => array('AvailabilityCategory.type' => 'needle')));

		// Does the item exist?
		if(empty($needle))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Strikkepinden/hæklenålen findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'needles', 'action' => 'index'));
		}

		// Checks if it is save to creat an item
		$safe_to_create = !empty($availability_category);
		
		if(!$safe_to_create)
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Du kan ikke oprette en variant på nuværende tidspunkt. Sørg for at der er mindst en beholdningskategori som passer til strikkepinde/hæklenåle.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'needles', 'action' => 'view', $needle['Needle']['id']));
		}

		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->NeedleVariant->create();

			// Was the save succesfull?
			if ($this->NeedleVariant->save($this->request->data)) 
			{	
				// Find the requestet item
				$needle_variant = $this->NeedleVariant->find('first', array('conditions' => array('NeedleVariant.id' => $this->NeedleVariant->id)));

				if(!empty($needle_variant)) 
				{
					// Tell the user it went well
					$this->Session->setFlash('Varianten blev gemt.', null, array(), 'success');

					// Send the user to the index
					$this->redirect(array('controller' => 'needles', 'action' => 'view', $needle['Needle']['id']));
				}
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Varianten blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}

		// Send the related models to the view
		$this->set('needle', $needle);
	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$needle_variant = $this->NeedleVariant->find('first', array('conditions' => array('NeedleVariant.id' => $id)));

		// Does the item exist?
		if(empty($needle_variant))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne variant findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'needles', 'action' => 'index'));
		}
		// Is the item still active
		else if(!$needle_variant['NeedleVariant']['is_active'])
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Du kan ikke rette en inaktive variant.'.$this->Session->read('Message.error.message'), null, array(), 'error');

			// Send the user to the index
			$this->redirect(array('controller' => 'needles', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->NeedleVariant->edit($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Varianten blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'needles', 'action' => 'view', $needle_variant['Needle']['id']));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Varianten blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	
		// Send the information of the old item to the view
		$this->set('needle_variant', $needle_variant);
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$needle_variant = $this->NeedleVariant->find('first', array('conditions' => array('NeedleVariant.id' => $id)));

		// Does the item exist?
		if(empty($needle_variant))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne variant findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'needles', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->NeedleVariant->deactivate($needle_variant['NeedleVariant']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Varianten blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Varianten blev ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'needles', 'action' => 'view', $needle_variant['Needle']['id']));
	}
}

?>