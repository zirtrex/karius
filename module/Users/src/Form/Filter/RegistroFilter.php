<?php
namespace Users\Form\Filter;

use Laminas\InputFilter\InputFilter;
use Laminas\Db\Adapter\Adapter;

class RegistroFilter extends InputFilter
{
    private $dbAdapter; 

    public function __construct(Adapter $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        
        $this->add(array(
            'name' => 'nombres',
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
                        'max' => 45
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'apellidos',
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
                        'max' => 45
                    )
                )
            )
        ));       
        
        
        /*$this->add(array(
            'name' => 'numeroDNI',
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
                        'min' => 8,
                        'max' => 8
                    )
                ),
                array(
                    'name' => 'Laminas\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table' => 'usuario',
                        'field' => 'numeroDNI',
                        'adapter' => $this->dbAdapter,
                        'messages' => array(
                            \Laminas\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'El número de DNI ya existe',
                        ),
                    ),
                ),
            )
        ));*/
        
        $this->add(array(
            'name' => 'correo',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 1,
                        'max' => 200
                    )
                ),
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'domain' => true
                    )
                ),
                array(
                    'name' => 'Laminas\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table' => 'usuario',
                        'field' => 'correo',
                        'adapter' => $this->dbAdapter,
                        'messages' => array(
                            \Laminas\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'El correo ya existe',
                        ),
                    ),
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'telefono',
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
                        'min' => 8,
                        'max' => 11
                    )
                ),
                array(
                    'name' => 'Laminas\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table' => 'usuario',
                        'field' => 'telefono',
                        'adapter' => $this->dbAdapter,
                        'messages' => array(
                            \Laminas\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'El telefono ya existe',
                        ),
                    ),
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'usuario',
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
                        'min' => '4',
                        'max' => '10'
                    )
                ),
                array(
                    'name' => 'Laminas\Validator\Db\NoRecordExists',
                    'options' => array(
                        'table' => 'usuario',
                        'field' => 'usuario',
                        'adapter' => $this->dbAdapter,
                        'messages' => array(
                            \Laminas\Validator\Db\NoRecordExists::ERROR_RECORD_FOUND => 'El usuario ya existe',
                        ),
                    ),
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'clave',
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
                        'max' => 16
                    )
                )
            )
        ));
        
        $this->add(array(
            'name' => 'confirmarClave',
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
                        'max' => 16
                    )
                ),
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'confirmarClave',
                        'messages' => array(
                            \Laminas\Validator\Identical::NOT_SAME => 'La nueva clave no coincide.'
                        )
                    )
                )
            )
        ));
            
        
    }
}