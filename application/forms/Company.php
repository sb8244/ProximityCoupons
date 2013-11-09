<?php
namespace Application\Form;

class Company extends \Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        $this->setAttrib('class', 'form');
        $this->addElement('text', 'email', array(
        		'label' => 'Email',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array('NotEmpty', 'EmailAddress'),
                'placeholder' => 'email@company.com',
                'class' => 'form-control'
        ));
        
        $this->addElement('password', 'password', array(
        		'label' => 'Password',
        		'required' => true,
        		'filters' => array('StringTrim'),
        		'validators' => array('NotEmpty'),
                'class' => 'form-control'
        ));
        
        // Add the submit button
        $this->addElement('submit', 'submit', array(
        		'ignore'   => true,
        		'label'    => 'Create your Account!',
                'class' => 'form-control'
        ));
        
    }


}

