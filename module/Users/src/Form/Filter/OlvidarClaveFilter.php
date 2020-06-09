<?php
namespace Users\Form\Filter;

use Laminas\InputFilter\InputFilter;
use Laminas\Db\Adapter\Adapter;

class OlvidarClaveFilter extends InputFilter
{
    public function __construct(Adapter $adapter)
    {
        $this->add([
            'name' => 'csrf',
            'required' => true
        ]);
        
        $this->add([
            'name' => 'correo',
            'required' => true,
            'filters' => array(
                array(
                    'name' => 'StringTrim'
                )
            ),
            'validators' => array(
                array(
                    'name' => 'EmailAddress',
                    'options' => array(
                        'domain' => true,
                        'messages' => array(
                            \Laminas\Validator\EmailAddress::INVALID_FORMAT => 'Email formato inválido'
                        )
                    )
                ),
                
                array(
                    'name' => 'Laminas\Validator\Db\RecordExists',
                    'options' => array(
                        'table' => 'usuario',
                        'field' => 'correo',
                        'adapter' => $adapter,
                        'messages' => array(
                            \Laminas\Validator\Db\NoRecordExists::ERROR_NO_RECORD_FOUND => 'El correo no existe'
                        )
                    )
                )
            )
        ]);
    }
}
