<?php
App::uses('AppController', 'Controller');

class NeedlesController extends AppController 
{
	public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow('index','view');
    }

	public function index($menu_id = null)
	{
		// Find every active items (is_active is 1)
		if($menu_id == null) 
		{
			$needles = $this->Needle->find('all', array('conditions' => array('Needle.is_active' => 1), 'order' => array('Needle.name')));
			$title = 'Alle strikkepinde og hæklenåle';
		} 
		else 
		{	
			$needles = $this->Needle->find('all', array('conditions' => array('Needle.is_active' => 1, 'Needle.menu_id' => $menu_id), 'order' => array('Needle.name')));
			$menu = $this->Needle->Menu->find('first', array('conditions' => array('Menu.id' => $menu_id)));

			if(!empty($menu))
			{
				$title = $menu['Menu']['name'];	
			}
			
		}

		if(empty($needles) && !$this->isAuthorized())
		{
			// Tell the user it was unsuccessful
			$this->Session->setFlash('Ingen strikkepinde/hæklenåle under denne menu'.$this->Session->read('Message.error.warning'), null, array(), 'warning');

			$this->redirect(array('controller' => 'pages', 'action' => 'index'));
		}
		
		$this->set('title', $title);
		$this->set('needles', $needles);

		// Keep the correct menu open
		$this->Session->write('Menu.active', $menu_id);
	}

	public function view($id = null)
	{
		// Find the requestet item ()
		$needle = $this->Needle->find('first', array('conditions' => array('Needle.id' => $id, 'Needle.is_active' => 1), 'recursive' => 3));

		// Check if the needle exist
		if(empty($needle))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne strikkepind/hæklenål findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'pages', 'action' => 'index'));
		}
		
		if($needle['Needle']['product_count'] > 0 ||  $this->isAuthorized())
		{
			// Send the information of item to the view
			$this->set('needle', $needle);	
		}
		else
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne strikkepind/hæklenål findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'pages', 'action' => 'index'));
		}

		// Keep the correct menu open
		$this->Session->write('Menu.active', $needle['Menu']['id']);
	}

	public function add()
	{	
		// Find the related models for the view
		$menus = $this->Needle->Menu->find('all', array('conditions' => array('OR' => array(
			    																			 array('Menu.type' => 'knit'),
																					    	 array('Menu.type' => 'crochet'),
		))));

		
		$brands =  $this->Needle->Brand->find('list', array('conditions' => array('type' => 'needle', 'is_active' => 1)));
		$materials = $this->Needle->Material->find('list', array('conditions' => array('type' => 'needle')));
		

		// Checks if it is save to creat an item
		$safe_to_create = !empty($materials) && !empty($menus) && !empty($brands);

		if(!$safe_to_create)
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Du kan ikke oprette en strikkepind/hæklenål på nuværende tidspunkt. Sørg for at der er mindst en menu, et mærke og et materiale som passer til garnkvaliteter.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'needles', 'action' => 'index'));
		}

		// If this request is a post
		if($this->request->is('post'))
		{	
			// It is a new item?
			$this->Needle->create();

			// Was the save succesfull?
			if ($this->Needle->saveAll($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Strikkepinden/hæklenålen blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'needles', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Strikkepinden/hæklenålen blev ikke gemt.'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}

		// Sends related models to the view
		$menus_to_view = array();
		foreach ($menus as $key => $menu) 
		{
			
			switch ($menu['Menu']['type']) {
				case 'knit':
					$menus_to_view[$menu['Menu']['id']] = $menu['Menu']['name'] . ' - Strikkepinde';
					break;
				
				case 'crochet':
					$menus_to_view[$menu['Menu']['id']] = $menu['Menu']['name'] . ' - Hæklenåle';
					break;
			}
		}

		// Sends related models to the view
		$this->set('menus', $menus_to_view);
		$this->set('brands', $brands);
		$this->set('materials', $materials);

	}

	public function edit($id = null)
	{	
		// Find the requestet item
		$needle = $this->Needle->find('first', array('conditions' => array('Needle.id' => $id)));

		// Does the item exist?
		if(empty($needle))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne strikkepind/hæklenål findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'needles', 'action' => 'index'));
		}
		// Is the item still active
		else if(!$needle['Needle']['is_active'])
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Du kan ikke rette en inaktive strikkepind/hæklenål.'.$this->Session->read('Message.error.message'), null, array(), 'error');

			// Send the user to the index
			$this->redirect(array('controller' => 'needles', 'action' => 'index'));
		}

		// Call the add function without creating a new instance
		if($this->request->is('post'))
		{
			// Was the save succesfull?
			if ($this->Needle->edit($this->request->data)) 
			{	
				// Tell the user it went well
				$this->Session->setFlash('Strikkepinden/hæklenålen blev gemt.', null, array(), 'success');

				// Send the user to the index
				$this->redirect(array('controller' => 'needles', 'action' => 'index'));
			}
			else
			{
				// Tell the user it was unsuccessful
				$this->Session->setFlash('Der opstod en fejl. Strikkepinden/hæklenålen blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
			}
		}
	
		// Send the information of the old item to the view
		$this->set('needle', $needle);

		// Send related models to the view
		$menus = $this->Needle->Menu->find('all', array('conditions' => array('OR' => array(
			    																			 array('Menu.type' => 'knit'),
																					    	 array('Menu.type' => 'crochet'),
		))));
		$brands =  $this->Needle->Brand->find('list', array('conditions' => array('type' => 'needle', 'is_active' => 1)));
		$materials = $this->Needle->Material->find('list', array('conditions' => array('type' => 'needle')));

		$menus_to_view = array();
		foreach ($menus as $key => $menu) 
		{
			
			switch ($menu['Menu']['type']) {
				case 'knit':
					$menus_to_view[$menu['Menu']['id']] = $menu['Menu']['name'] . ' - Strikkepinde';
					break;
				
				case 'crochet':
					$menus_to_view[$menu['Menu']['id']] = $menu['Menu']['name'] . ' - Hæklenåle';
					break;
			}
		}

		$this->set('menus', $menus_to_view);
		$this->set('brands', $brands);
		$this->set('materials', $materials);
	}

	public function delete($id = null)
	{
		// Find the requestet item
		$needle = $this->Needle->find('first', array('conditions' => array('Needle.id' => $id)));

		// Does the item exist?
		if(empty($needle))
		{
			// Tell the user this item did not exist
			$this->Session->setFlash('Denne strikkepind/hæklenål findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

			// Send the user to the index
			$this->redirect(array('controller' => 'needles', 'action' => 'index'));
		}

		// Did the deletion go well?
		if($this->Needle->deactivate($needle['Needle']['id']))
	    {
			// Tell the user it went well
			$this->Session->setFlash('Strikkepinden/hæklenålen blev slettet.', null, array(), 'success');  
	    }
	    else
	    {
	      	// Tell the user it was unsuccessful
	     	$this->Session->setFlash('Der opstod en fejl. Strikkepinden/hæklenålen blev ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
	    }

	    // Send the user back to the index
	    $this->redirect(array('controller' => 'needles', 'action' => 'index'));
	}
}

?>