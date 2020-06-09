<?php
namespace Admin\Form;

use Laminas\Form\Form;

class BuscarForm extends Form
{
    public function __construct()
    {
        parent::__construct('buscarForm');
        
        $this->setAttributes([
            'method' => 'post'
        ]);
        
        $this->add([
            'name' => 'texto',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'texto',
                'placeholder' => 'Ingrese texto a buscar',
                'class' => 'uk-search-input search-field'
            ],
            'options' => array(
                'label' => 'Buscar',
                'label_attributes' => array(
                    'class' => ''
                )
            )
        ]);
        
        $this->add([
            'name' => 'buscar',
            'type' => 'Laminas\Form\Element\Submit',
            'attributes' => [
                'value' => 'Buscar',
                'class' => 'uk-button uk-button-default'
            ]
        ]);
    }
}