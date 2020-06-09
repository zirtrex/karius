<?php
namespace Users\Form;
 
use Laminas\Form\Form;
//use Laminas\Captcha;
//use Laminas\Form\Element; 


class LoginForm extends Form 
{
    public function __construct()
    {
        parent::__construct('loginForm');
 
        $this->setAttributes(['method' => 'post', 'class' => 'uk-form']);
 
        $this->add([
            'name' => 'usuario',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => [
                'placeholder' => 'Usuario',
                'required' => 'required',
                'autocomplete' => "nope",
            	'class' => 'uk-input uk-border-pill',
            ],
            'options' => [
                'label' => 'Usuario',
                'label_attributes' => [
                    'class' => 'uk-form-label'
                ],
            ],
        ]);
 
        $this->add([
            'name' => 'clave',
            'type' => 'Laminas\Form\Element\Password',
            'attributes' => [
                'placeholder' => 'Clave',
                'required' => 'required',
            	'class' => 'uk-input uk-border-pill',
            ],
            'options' => [
                'label' => 'Clave',
                'label_attributes' => [
                    'class' => 'uk-form-label'
                ],
            ],
        ]);
        
        $this->add([
            'name' => 'csrf',
            'type' => 'Laminas\Form\Element\Csrf'
        ]);
 
        $this->add([
            'name' => 'ingresar',
            'type' => 'Laminas\Form\Element\Submit',
            'attributes' => [
                'value' => 'Ingresar',
                'id' => 'ingresar',
                'class' => 'uk-button uk-button-primary uk-border-pill uk-width-1-1 g-recaptcha',
                'data-sitekey' => "6Lc5EWkUAAAAACiEcN5uU0Ng_h0fRHQ1Zp91fXtj",
                'data-callback' => "onSubmit"
            ],
        ]); 
    }
}