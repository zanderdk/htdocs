<?php
App::uses('AppController', 'Controller');

class RecipesController extends AppController 
{
    public function beforeFilter() 
    {
        parent::beforeFilter();
        $this->Auth->allow(array('index', 'view_pdf', 'view'));
    }

    public function index($category = null)
    {

        if($category == null)
        {
            $recipes =  $this->Recipe->find('all', array('order' => array('Recipe.name' => 'ASC'), 'conditions' => array('Recipe.is_active' => true), 'recursive' => 1));   
            $title = 'Alle - Opskrifter';
        }
        else if($category == 'females')
        {
            $recipes =  $this->Recipe->find('all', array('order' => array('Recipe.name' => 'ASC'), 'conditions' => array('Recipe.is_active' => true, 'Recipe.category' => 'females'), 'recursive' => 1));   
            $title = 'Damer - Opskrifter';
        }
        else if($category == 'babies')
        {
            $recipes =  $this->Recipe->find('all', array('order' => array('Recipe.name' => 'ASC'), 'conditions' => array('Recipe.is_active' => true, 'Recipe.category' => 'babies'), 'recursive' => 1));    
            $title = 'Baby - Opskrifter';
        }
        else if($category == 'children')
        {
            $recipes =  $this->Recipe->find('all', array('order' => array('Recipe.name' => 'ASC'), 'conditions' => array('Recipe.is_active' => true, 'Recipe.category' => 'children'), 'recursive' => 1));  
            $title = 'Børn - Opskrifter';
        }

        $this->set('title', $title);
        $this->set('recipes', $recipes);
        $this->set('categories', array('females' => 'Damer', 'babies' => 'Baby', 'children' => 'Børn'));
        
    }

    public function view($id = null)
    {
        // Find the requestet item ()
        
       $recipe = $this->Recipe->find('first', array('conditions' => array('Recipe.id' => $id, 'Recipe.is_active' => 1), 'recursive' => 3));

        // Check if the yarn exist
        if(empty($recipe))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne opskrift findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }

        $this->set('recipe', $recipe);

    }

    public function add()
    {   
        // If this request is a post
        if($this->request->is('post'))
        {   
            // It is a new item?
            $this->Recipe->create();

            // Was the save succesfull?
            if ($this->Recipe->save($this->request->data)) 
            {   
                $recipe = $this->Recipe->find('first', array('conditions' => array('Recipe.id' => $this->Recipe->id)));
                if(!empty($recipe))
                {
                    $this->Session->setFlash('Opskriften blev gemt.', null, array(), 'success');
                    
                    // Send the user to the index
                    $this->redirect(array('controller' => 'recipes', 'action' => 'index'));
                }
            }
            else
            {
                // Tell the user it was unsuccessful
                $this->Session->setFlash('Der opstod en fejl. Opskriften blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
            }
        }

        $this->set('yarns', $this->Recipe->Yarn->find('list', array('conditions' => array('Yarn.is_active' => true))));
    }

    public function edit($id)
    {
        // Find the requestet item
        $recipe = $this->Recipe->find('first', array('conditions' => array('Recipe.id' => $id)));
        // Does the item exist?
        if(empty($recipe))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne opskrift findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }
        // Is the item still active
        else if(!$recipe['Recipe']['is_active'])
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Du kan ikke rette et inaktive opskrift.'.$this->Session->read('Message.error.message'), null, array(), 'error');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }

        // Call the add function without creating a new instance
        if($this->request->is('post'))
        {
            // Was the save succesfull?
            if ($this->Recipe->edit($this->request->data)) 
            {   
                // Tell the user it went well
                $this->Session->setFlash('Opskriften blev gemt.', null, array(), 'success');

                // Send the user to the index
                $this->redirect(array('controller' => 'recipes', 'action' => 'index'));
            }
            else
            {
                // Tell the user it was unsuccessful
                $this->Session->setFlash('Der opstod en fejl. Garnkvaliteten blev ikke gemt'.$this->Session->read('Message.error.message'), null, array(), 'error');
            }
        }
    
        // Send the information of the old item to the view
        $this->set('recipe', $recipe);

        // Send related models to the view
        // Yarns
        $all_yarns = $this->Recipe->Yarn->find('list', array('conditions' => array('Yarn.is_active' => true)));
        $related_yarns = $this->Recipe->RecipesYarn->find('all', array('conditions' => array('recipe_id' => $id)));

        
        foreach ($all_yarns as $i => $yarn)
        {   
            $all_yarns[$i] = array('name' => $yarn, 'checked' => 0);
            foreach ($related_yarns as $j => $related_yarn)
            {   
                if($i == $related_yarn['RecipesYarn']['yarn_id'])
                {
                    $all_yarns[$i]['checked'] = 1;
                    break;
                }
            }
        }
        $this->set('yarns', $all_yarns);

    }

        public function delete($id = null)
    {
        // Find the requestet item
        $recipe = $this->Recipe->find('first', array('conditions' => array('Recipe.id' => $id)));

        // Does the item exist?
        if(empty($recipe))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne opskrift findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }

        // Did the deletion go well?
        if($this->Recipe->deactivate($recipe['Recipe']['id']))
        {
            // Tell the user it went well
            $this->Session->setFlash('Opskriften blev slettet.', null, array(), 'success');  
        }
        else
        {
            // Tell the user it was unsuccessful
            $this->Session->setFlash('Der opstod en fejl. Opskriften blev ikke slettet.'.$this->Session->read('Message.error.message'), null, array(), 'error');
        }

        // Send the user back to the index
        $this->redirect(array('controller' => 'pages', 'action' => 'index'));
    }

    public function view_pdf($id) {

        // Find the requestet item
        $recipe = $this->Recipe->find('first', array('conditions' => array('Recipe.id' => $id)));
        // Does the item exist?
        if(empty($recipe))
        {
            // Tell the user this item did not exist
            $this->Session->setFlash('Denne opskrift findes ikke.'.$this->Session->read('Message.warning.message'), null, array(), 'warning');

            // Send the user to the index
            $this->redirect(array('controller' => 'pages', 'action' => 'index'));
        }

        $this->response->file('webroot/files/recipes/'.$id.'.pdf');
        
        return $this->response;
    }

}