<?php
namespace Admin\Form;

use Laminas\Form\Form;

class ClienteForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('clienteForm');
        
        $this->setAttributes([
            'method' => 'post'
        ]);
        
        $this->add([
            'name' => 'cod_cliente',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'cod_cliente'
            ]
        ]);
        
        $this->add([
            'name' => 'razon_social',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'razon_social',
                'placeholder' => 'Ingrese razón social',
                'required' => 'required',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Razón Social',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'direccion_legal',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'direccion_legal',
                'placeholder' => 'Ingrese dirección',
                'required' => 'required',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Dirección Legal',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);        
        
        $this->add(array(
            'name' => 'guardar',
            'type' => 'Laminas\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Guardar',
                'class' => 'uk-button uk-button-primary'
            )
        ));
    }
}