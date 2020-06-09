<?php
namespace Users\Form\Filter;

use Laminas\InputFilter\InputFilter;

class LoginFilter extends InputFilter
{

    public function __construct()
    {
        $this->add([
            'name' => 'usuario',
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
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'messages' => [
                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'Completar campo.'
                        ]
                    ]
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => '4',
                        'max' => '10',
                        'messages' => [
                            \Laminas\Validator\StringLength::TOO_SHORT => 'mínimo 4 letras.',
                            \Laminas\Validator\StringLength::TOO_LONG => 'máximo 10 letras.'
                        ]
                    ]
                ]
            ]
        ]);
        
        $this->add([
            'name' => 'clave',
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
                [
                    'name' => 'NotEmpty',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'messages' => [
                            \Laminas\Validator\NotEmpty::IS_EMPTY => 'Completar campo.'
                        ]
                    ]
                ],
                [
                    'name' => 'StringLength',
                    'options' => [
                        'encoding' => 'UTF-8',
                        'min' => '4',
                        'max' => '16',
                        'messages' => array(
                            \Laminas\Validator\StringLength::TOO_SHORT => 'mínimo 4 letras.',
                            \Laminas\Validator\StringLength::TOO_LONG => 'máximo 16 letras.'
                        )
                    ]
                ]
            ]
        ]);
        
        $this->add([
            'name' => 'csrf',
            'required' => true
        ]);
    }
}