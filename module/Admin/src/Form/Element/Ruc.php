<?php
namespace Admin\Form\Element;

use Laminas\Filter;
use Laminas\Form\Element;
use Laminas\InputFilter\InputProviderInterface;
use Admin\Form\Validator\RucValidator;


class Ruc extends Element implements InputProviderInterface
{
    
    protected $validator;
    
    
    public function getValidator()
    {
        if (null === $this->validator) {
            $validator = new RucValidator();
            
            $this->validator = $validator;
        }
        
        return $this->validator;
    }
    
   
    public function setValidator(ValidatorInterface $validator)
    {
        $this->validator = $validator;
        return $this;
    }
    
    
    public function getInputSpecification()
    {
        return [
            'name' => $this->getName(),
            'required' => true,
            'filters' => [
                ['name' => Filter\StringTrim::class],
            ],
            'validators' => [
                $this->getValidator(),
                [
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => 11,
                        'max' => 11
                    )
                ]
            ],
        ];
    }
}

