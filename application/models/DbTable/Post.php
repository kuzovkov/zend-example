<?php

class Application_Model_DbTable_Post extends Zend_Db_Table_Abstract
{

    protected $_name = 'post';

    public function getPost($id)
    {
         $id = (int)$id;
         $row = $this->fetchRow('id = ' . $id);
         if (!$row) {
             throw new Exception("Could not find row $id");
         }
         return $row->toArray();
     }

    public function addPost($title, $body, $img, $updated_at)
    {
        $updated_at = new DateTime($updated_at);
        $updated_at = $updated_at->format('Y-m-d H:i:s');

        
        $data = array(
            'title' => $title,
            'body' => $body,
            'img' => $img,
            'updated_at' => $updated_at
        );
        $this->insert($data);
    }

    public function updatePost($id, $title, $body, $img, $updated_at)
    {
        $data = array(
            'title' => $title,
            'body' => $body,
            'img' => $img,
            'updated_at' => $updated_at
        );
        $this->update($data, 'id = '. (int)$id);
    }

    public function deletePost($id)
    {
        $this->delete('id =' . (int)$id);
    }


}

