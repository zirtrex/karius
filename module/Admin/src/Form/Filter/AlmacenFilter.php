<?php
namespace Admin\Form\Filter;

use Laminas\InputFilter\InputFilter;

class AlmacenFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'nombre_almacen',
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
            'name' => 'direccion_almacen',
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
                        'max' => 500
                    )
                )
            ]
        ]);
        
        $this->add([
            'name' => 'cod_cliente',
            'required' => true
        ]);
        
    }
}