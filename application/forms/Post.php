<?php

class Application_Form_Post extends Zend_Form
{

        public function init()
        {
            $this->setName('post');
            $id = new Zend_Form_Element_Hidden('id');
            $id->addFilter('Int');
            $title = new Zend_Form_Element_Text('title');
            $title->setLabel('Название')
                ->setRequired(true)
                ->addFilter('StripTags')
                ->addFilter('StringTrim')
                ->addValidator('NotEmpty');
            $body = new Zend_Form_Element_Textarea('body');
            $body->setLabel('Текст')
                ->setRequired(true)
                ->setAttrib('id', 'need-tinymce')
                //->addFilter('StripTags')
                //->addFilter('StringTrim')
                ->addValidator('NotEmpty');

            $img = new Zend_Form_Element_File('img');
            $img->setLabel('Изображение')
                //->setRequired(true)
                ->addValidator('Size', false, 1024000)
                ->addValidator('Extension', false, 'jpg,png,gif')
                //->addFilter('StripTags')
                //->addFilter('StringTrim')
                ->addValidator('NotEmpty');

            $updated_at = new Zend_Form_Element_Text('updated_at');
            $updated_at->setLabel('Обновлено')
                ->setRequired(true)
                ->setAttrib('id', 'datepicker')
                //->addFilter('StripTags')
                //->addFilter('StringTrim')
                ->addValidator('NotEmpty');

            $submit = new Zend_Form_Element_Submit('submit');
            $submit->setAttrib('id', 'submitbutton');
            $this->addElements(array($id, $title, $body, $img, $updated_at, $submit));
        }


}

