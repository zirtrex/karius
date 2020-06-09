<?php
namespace Users\Form\Filter;

use Laminas\InputFilter\InputFilter;

class CambiarClaveFilter extends InputFilter
{

    public function __construct()
    {
        $this->add([
            'name' => 'anteriorClave',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 4,
                        'max' => 16,
                        'messages' => array(
                            \Laminas\Validator\StringLength::TOO_SHORT => 'mínimo 4 letras.',
                            \Laminas\Validator\StringLength::TOO_LONG=> 'máximo 16 letras.',
                        ),
                    )
                )
            )
        ]);
        
        $this->add([
            'name' => 'nuevaClave',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 4,
                        'max' => 16,
                        'messages' => array(
                            \Laminas\Validator\StringLength::TOO_SHORT => 'mínimo 4 letras.',
                            \Laminas\Validator\StringLength::TOO_LONG=> 'máximo 16 letras.',
                        ),
                    )
                )
            )
        ]);
        
        $this->add([
            'name' => 'confirmarNuevaClave',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StripTags'
                ),
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 4,
                        'max' => 16,
                        'messages' => array(
                            \Laminas\Validator\StringLength::TOO_SHORT => 'mínimo 4 letras.',
                            \Laminas\Validator\StringLength::TOO_LONG=> 'máximo 16 letras.',
                        ),
                    )
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'nuevaClave',
                        'messages' => array(
                            \Laminas\Validator\Identical::NOT_SAME => 'La nueva clave no coincide.'
                        )
                    )
                )
            )
        ]);
    }
}
