<?php
namespace Admin\Form\Filter;

use Laminas\InputFilter\InputFilter;

class ClienteFilter extends InputFilter
{
    public function __construct()
    {
        $this->add([
            'name' => 'razon_social',
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
            'name' => 'direccion_legal',
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
        
    }
}