<?php
namespace Admin\Form\Filter;

use Laminas\InputFilter\InputFilter;

class VehiculoFilter extends InputFilter
{

    public function __construct()
    {
        $this->add([
            'name' => 'cod_vehiculo',
            'required' => false            
        ]);
        
        $this->add([
            'name' => 'marca',
            'required' => true,
            'filters' => [
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ],
            'validators' => [
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 100
                    )
                )
            ]
        ]);
        
        $this->add([
            'name' => 'placa',
            'required' => true,
            'filters' => [
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ],
            'validators' => [
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 2,
                        'max' => 45
                    )
                )
            ]
        ]);
        
        $this->add([
            'name' => 'soat',
            'required' => true,
            'filters' => [
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ],
            'validators' => [
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 4,
                        'max' => 45
                    )
                )
            ]
        ]);
    }
}