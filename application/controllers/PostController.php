<?php

class PostController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        $posts = new Application_Model_DbTable_Post();
        $this->view->posts = $posts->fetchAll();
    }

    public function addAction()
    {
        $form = new Application_Form_Post();
        $form->submit->setLabel('Add');
        $this->view->form = $form;

         if ($this->getRequest()->isPost()) {
             $file = $form->img->getFileInfo();
             if ($file){
                 $ext = substr($file['img']['name'], strrpos($file['img']['name'], '.'));
                 $newName = md5($file['img']['name']). $ext;
                 $form->img->addFilter('Rename', realpath(dirname('.')).
                     DIRECTORY_SEPARATOR.
                     'upload'.
                     DIRECTORY_SEPARATOR.
                     $newName);
                 $form->img->receive();
             }


             $formData = $this->getRequest()->getPost();
             if ($form->isValid($formData)) {
                 $title = $form->getValue('title');
                 $body = $form->getValue('body');
                 $img = $form->getValue('img');
                 if ($img == null) $img = '';
                 $updated_at = $form->getValue('updated_at');
                 $post = new Application_Model_DbTable_Post();
                 $post->addPost($title, $body, $newName, $updated_at);
                 $this->_helper->redirector('index');
             } else {
                 $form->populate($formData);
             }
         }
    }

    public function editAction()
    {
        $form = new Application_Form_Post();
        $form->submit->setLabel('Save');
        $this->view->form = $form;

        if ($this->getRequest()->isPost()) {

            $file = $form->img->getFileInfo();

            $file = $form->img->getFileInfo();
            if ($file){
                $ext = substr($file['img']['name'], strrpos($file['img']['name'], '.'));
                $newName = md5($file['img']['name']). $ext;
                $form->img->addFilter('Rename', realpath(dirname('.')).
                    DIRECTORY_SEPARATOR.
                    'upload'.
                    DIRECTORY_SEPARATOR.
                    $newName);
                $form->img->receive();
            }

            $formData = $this->getRequest()->getPost();
            if ($form->isValid($formData)) {
                $id = (int)$form->getValue('id');
                $title = $form->getValue('title');
                $body = $form->getValue('body');
                $img = $form->getValue('img');
                if ($img == null){
                    $img = '';
                } else{
                    $posts = new Application_Model_DbTable_Post();
                    $post = $posts->getPost($id);
                    $this->_deletePostImg($post);
                    
                }
                $updated_at = $form->getValue('updated_at');
                $posts = new Application_Model_DbTable_Post();
                $posts->updatePost($id, $title, $body, $newName, $updated_at);
                $this->_helper->redirector('index');
            } else {
                $form->populate($formData);
            }
        } else {
            $id = $this->_getParam('id', 0);
            if ($id > 0) {
                $posts = new Application_Model_DbTable_Post();
                $form->populate($posts->getPost($id));
            }
        }
    }

    public function deleteAction()
    {
        if ($this->getRequest()->isPost()) {
            $del = $this->getRequest()->getPost('del');
            if ($del == 'Yes') {
                $id = $this->getRequest()->getPost('id');
                $posts = new Application_Model_DbTable_Post();
                $post = $posts->getPost($id);
                $this->_deletePostImg($post);
                $posts->deletePost($id);
            }
            $this->_helper->redirector('index');
        } else {
            $id = $this->_getParam('id', 0);
            $posts = new Application_Model_DbTable_Post();
            $this->view->post = $posts->getPost($id);
        }
    }

    private function _deletePostImg($post){
        $img = $post['img'];
        $filename = realpath(dirname('.')).
                 DIRECTORY_SEPARATOR.
                 'upload'.
                 DIRECTORY_SEPARATOR.
                 $img;
        if (file_exists($filename)) unlink($filename);
    }


}