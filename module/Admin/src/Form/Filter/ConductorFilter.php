<?php
namespace Admin\Form\Filter;

use Laminas\InputFilter\InputFilter;

class ConductorFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'nombres',
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
            'name' => 'apellidos',
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
            'name' => 'numero_licencia',
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