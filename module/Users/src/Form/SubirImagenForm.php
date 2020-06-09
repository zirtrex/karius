<?php
namespace Users\Form;

use Laminas\Form\Form;

class SubirImagenForm extends Form
{    
    public function __construct()
    {        
        parent::__construct('subirImagenForm');
        $this->setAttribute('method', 'post');
        $this->setAttribute('enctype','multipart/form-data');        
        
        $this->add([
    		'name' => 'imagen',
    		'type' => 'Laminas\Form\Element\File',
    		'attributes' => [
    				'required' => 'required',
    				'id' => 'imagen',
    				'class' => 'hidden',
    		]
        ]);
    }
}
