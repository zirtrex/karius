<?php
namespace Admin\Form;

use Laminas\Form\Form;

class VehiculoForm extends Form
{

    public function __construct($name = null)
    {
        parent::__construct('vehiculoForm');        
        
        $this->setAttributes([
            'method' => 'post'
        ]);
        
        $this->add([
            'name' => 'cod_vehiculo',
            'type' => 'hidden',
            'attributes' => [
                'id' => 'cod_vehiculo'
            ]
        ]);
        
        $this->add([
            'name' => 'marca',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'marca',
                'placeholder' => 'Ingrese marca',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Marca',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'placa',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'placa',
                'placeholder' => 'Ingrese placa',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Placa',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'modelo',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'modelo',
                'placeholder' => 'Ingrese modelo',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Modelo',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'color',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'color',
                'placeholder' => 'Ingrese color',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Color',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'soat',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'id' => 'soat',
                'placeholder' => 'Ingrese soat',
                'class' => 'uk-input'
            ],
            'options' => array(
                'label' => 'Soat',
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