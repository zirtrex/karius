<?php
namespace Admin\Form;

use Laminas\Form\Form;

class AlmacenForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('almacenForm');
        
        $this->setAttributes(['method' => 'post']);
        
        $this->add([
            'name' => 'cod_almacen',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'cod_almacen'
            ]
        ]);
        
        $this->add([
            'name' => 'nombre_almacen',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'nombre_almacen',
                'placeholder' => 'Ingrese nombre',
                'required' => 'required',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Nombre',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'direccion_almacen',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'direccion_almacen',
                'placeholder' => 'Ingrese dirección',
                'required' => 'required',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Dirección',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'cod_cliente',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'cod_cliente'
            ]
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