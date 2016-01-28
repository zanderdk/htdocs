<?php
App::uses('AppController', 'Controller');

class YarnsController extends AppController 
{
    //TODO Check if care_labels are related to them correctly

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
            $yarns = $this->Yarn->find('all', array('conditions' => array('Yarn.is_active' => 1), 'order' => array('Yarn.name')));
            $title = 'Alle garnkvaliteter';
        } 
        else 
        {   
            $yarns = $this->Yarn->find('all', array('conditions' => array('Yarn.is_active' => 1, 'Yarn.menu_id' => $menu_id), 'order' => array('Yarn.name')));
            $menu = $this->Yarn->Menu->find('first', array('conditions' => array('Menu.id' => $menu_id)));

            if(!empty($menu))
            {
                $title = $menu['Menu']['name']; 
            }
            
        }


        if(empty($yarns) && !$this->isAuthorized())
        {
            // Tell the user it was unsuccessful
            $this->Session->setFlash('Ingen garnkvaliteter under denne menu'.$this->Session->read('Message.error.warning'), null, array(), 'warning');

            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }
        
        $this->set('title', $title);
        $this->set('yarns', $yarns);

        // Keep the correct menu open
        $this->Session->write('Menu.active', $menu_id);
    }

    public function view($id = null)
    {
        // Find the requestet item ()
        $yarn = $this->Yarn->find('first', array('conditions' => array('Yarn.id' => $id, 'Yarn.is_active' => 1), 'recursive' => 3));

        // Check if the yarn exist
        if(empty($yarn))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne garnkvalitet findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }
        
        if($yarn['Yarn']['product_count'] > 0 ||  $this->isAuthorized())
        {
            // Send the information of item to the view
            $this->set('yarn', $yarn);  
        }
        else
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne garnkvalitet findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }

        // Keep the correct menu open
        $this->Session->write('Menu.active', $yarn['Menu']['id']);
    }

    public function add()
    {   
        // Find the related models for the view
        $menus = $this->Yarn->Menu->find('all', array('conditions' => array(
            'OR' => array(
                array('Menu.type' => 'yarn'),
                array('Menu.type' => 'surplus_yarn'),
            )
        )));
        $brands =  $this->Yarn->Brand->find('list', array('conditions' => array('type' => 'yarn', 'is_active' => 1)));
        $care_labels = $this->Yarn->CareLabel->find('list');
        $materials = $this->Yarn->YarnPart->Material->find('list', array('conditions' => array('type' => 'yarn')));
        

        // Checks if it is save to creat an item
        $safe_to_create = !empty($materials) && !empty($menus) && !empty($brands);

        if(!$safe_to_create)
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Du kan ikke oprette en garnkvalitet på nuværende tidspunkt. Sørg for at der er mindst en menu, et mærke og et materiale som passer til garnkvaliteter.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }

        // If this request is a post
        if($this->request->is('post'))
        {   
            // It is a new item?
            $this->Yarn->create();

            // Was the save succesfull?
            if ($this->Yarn->saveAll($this->request->data)) 
            {   
                // Tell the user it went well
                $this->Session->setFlash('Garnkvaliteten blev gemt.', null, array(), 'success');

                // Send the user to the index
                $this->redirect(array('controller' => 'pages', 'action' => 'index'));
            }
            else
            {
                // Tell the user it was unsuccessful
                $this->Session->setFlash('Der opstod en fejl. Garnkvaliteten blev ikke gemt.'.$this->Session->read('Message.error.message'), null, array(), 'error');
            }
        }

        $menus_to_view = array();
        // Sends related models to the view
        foreach ($menus as $key => $menu) 
        {
            
            switch ($menu['Menu']['type']) {
                case 'yarn':
                    $menus_to_view[$menu['Menu']['id']] = $menu['Menu']['name'] . ' - Garn';
                    break;
                
                case 'surplus_yarn':
                    $menus_to_view[$menu['Menu']['id']] = $menu['Menu']['name'] . ' - Restgarn';
                    break;
            }
        }

        $this->set('menus', $menus_to_view);

        $this->set('brands', $brands);
        $this->set('care_labels', $care_labels);
        $this->set('materials', $materials);

    }

    public function edit($id = null)
    {   
        // Find the requestet item
        $yarn = $this->Yarn->find('first', array('conditions' => array('Yarn.id' => $id)));

        // Does the item exist?
        if(empty($yarn))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne garnkvalitet findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }
        // Is the item still active
        else if(!$yarn['Yarn']['is_active'])
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Du kan ikke rette et inaktive garnkvalitet.'.$this->Session->read('Message.error.message'), null, array(), 'error');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }

        // Call the add function without creating a new instance
        if($this->request->is('post'))
        {
            // Was the save succesfull?
            if ($this->Yarn->edit($this->request->data)) 
            {   
                // Tell the user it went well
                $this->Session->setFlash('Garnkvaliteten blev gemt.', null, array(), 'success');

                // Send the user to the index
                $this->redirect(array('controller' => 'pages', 'action' => 'index'));
            }
            else
            {
                // Tell the user it was unsuccessful
                $this->Session->setFlash('Der opstod en fejl. Garnkvaliteten blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
            }
        }
    
        // Send the information of the old item to the view
        $this->set('yarn', $yarn);

        // Send related models to the view
        // Menus
        $menus =  $this->Yarn->Menu->find('all', array('conditions' => array(
            'OR' => array(
                array('Menu.type' => 'yarn'),
                array('Menu.type' => 'surplus_yarn'),
            )
        )));

        $menus_to_view = array();
        foreach ($menus as $key => $menu) 
        {
            
            switch ($menu['Menu']['type']) {
                case 'yarn':
                    $menus_to_view[$menu['Menu']['id']] = $menu['Menu']['name'] . ' - Garn';
                    break;
                
                case 'surplus_yarn':
                    $menus_to_view[$menu['Menu']['id']] = $menu['Menu']['name'] . ' - Restgarn';
                    break;
            }
        }

        $this->set('menus', $menus_to_view);

        // Brand
        $this->set('brands', $this->Yarn->Brand->find('list', array('conditions' => array('type' => 'yarn'))));

        // CareLabels
        $care_labels = $this->Yarn->CareLabel->find('list');
        $related_care_labels = $this->Yarn->CareLabelsYarn->find('all', array('conditions' => array('yarn_id' => $id)));

        
        foreach ($care_labels as $i => $care_label)
        {   
            $care_labels[$i] = array('name' => $care_label, 'checked' => 0);
            foreach ($related_care_labels as $j => $related_care_label)
            {   
                if($i == $related_care_label['CareLabelsYarn']['care_label_id'])
                {
                    $care_labels[$i]['checked'] = 1;
                    break;
                }
            }
        }
        $this->set('care_labels', $care_labels);    

        // Materials
        $materials = $this->Yarn->YarnPart->Material->find('list', array('conditions' => array('type' => 'yarn')));
        $related_materials = $this->Yarn->YarnPart->find('all', array('conditions' => array('yarn_id' => $id)));
        foreach ($materials as $i => $material)
        {
            foreach ($related_materials as $j => $related_material)
            {   
                $materials[$i] = array('name' => $material, 'percentage' => 0);
                if($i == $related_material['YarnPart']['material_id'])
                {
                    $materials[$i]['percentage'] = $related_material['YarnPart']['percentage'];
                    break;
                }
            }
        }
        $this->set('materials', $materials);    
    }

    public function delete($id = null)
    {
        // Find the requestet item
        $yarn = $this->Yarn->find('first', array('conditions' => array('Yarn.id' => $id)));

        // Does the item exist?
        if(empty($yarn))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne garnkvalitet findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }

        // Did the deletion go well?
        if($this->Yarn->deactivate($yarn['Yarn']['id']))
        {
            // Tell the user it went well
            $this->Session->setFlash('Garnkvaliteten blev slettet.', null, array(), 'success');  
        }
        else
        {
            // Tell the user it was unsuccessful
            $this->Session->setFlash('Der opstod en fejl. Garnkvaliteten blev ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
        }

        // Send the user back to the index
        $this->redirect(array('controller' => 'pages', 'action' => 'index'));
    }
}

?>