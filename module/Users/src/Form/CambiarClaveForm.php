<?php
namespace Users\Form;

use Laminas\Form\Form;

class CambiarClaveForm extends Form
{

    public function __construct($urlcaptcha)
    {
        parent::__construct('cambiarClaveForm');
        
        $this->setAttributes(['method' => 'post', 'class' => 'uk-form']);        
        
        $this->add([
            'name' => 'csrf',
            'type' => 'Laminas\Form\Element\Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 1200
                )
            )
        ]);
        
        $this->add([
            'name' => 'token',
            'type' => 'hidden',
            'attributes' => array(
                'id' => 'token'
            )
        ]);
        
        $this->add([
            'name' => 'anteriorClave',
            'type' => 'Laminas\Form\Element\Password',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Ingrese su clave anterior',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Clave anterior',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'nuevaClave',
            'type' => 'Laminas\Form\Element\Password',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Ingrese su nueva clave',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Nueva clave',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'confirmarNuevaClave',
            'type' => 'Laminas\Form\Element\Password',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Repita su nueva clave',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Confirmar nueva clave',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ]);
        
        $this->add([
            'name' => 'enviar',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Cambiar Clave',
                'class' => 'uk-button uk-button-primary uk-border-pill uk-width-1-1',
                'id' => 'cambiar-clave'
            )
        ]);
    }
}
