<?php
namespace Admin\Form;

use Laminas\Form\Form;

class ConductorForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('conductoreForm');        
        
        $this->setAttributes([
            'method' => 'post'
        ]);
        
        $this->add([
            'name' => 'cod_conductor',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'cod_conductor'
            ]
        ]);
        
        $this->add([
            'name' => 'nombres',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'nombres',
                'placeholder' => 'Ingrese nombres',
                'required' => 'required',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Nombres',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'apellidos',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'apellidos',
                'placeholder' => 'Ingrese apellidos',
                'required' => 'required',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Apellidos',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'numero_licencia',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'numero_licencia',
                'placeholder' => 'Ingrese número de licencia',
                'required' => 'required',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Número de licencia',
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