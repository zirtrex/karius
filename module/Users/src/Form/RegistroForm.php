<?php
namespace Users\Form;

use Laminas\Form\Form;

class RegistroForm extends Form
{

    public function __construct()
    {
        parent::__construct('registroForm');
        
        $this->setAttributes(array(
            'method' => 'post',
            'class' => 'uk-form',
            'id' => 'registro-form'
        ));
        
        $this->add(array(
            'name' => 'cod_usuario',
            'type' => 'hidden',
            'attributes' => array(
                'id' => 'cod_usuario'
            )
        ));
        
        $this->add(array(
            'name' => 'nombres',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => array(
                'id' => 'nombres',
                'placeholder' => 'Ingrese nombre(s)',
                'required' => 'required',
                'autocomplete' => "nope",
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Nombre(s)',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'apellidos',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => array(
                'id' => 'apellidos',
                'placeholder' => 'Ingrese apellido(s)',
                'required' => 'required',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Apellido(s)',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'correo',
            'type' => 'Laminas\Form\Element\Email',
            'attributes' => array(
                'id' => 'correo',
                'placeholder' => 'Ingrese correo',
                'required' => 'required',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Correo',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'telefono',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => array(
                'id' => 'telefono',
                'placeholder' => 'Ingrese telefono',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Telefono',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'usuario',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => array(
                'placeholder' => 'Ingrese su usuario',
                'required' => 'required',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Usuario',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'clave',
            'type' => 'Laminas\Form\Element\Password',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Ingrese su clave',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Nueva clave',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'confirmarClave',
            'type' => 'Laminas\Form\Element\Password',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Repita su clave',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Confirmar nueva clave',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ));

        $this->add(array(
            'name' => 'registrarse',
            'type' => 'Laminas\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Registrarse',
                'id' => 'registrarse',
                'class' => 'uk-button uk-button-primary uk-border-pill uk-width-1-1 g-recaptcha',
                'data-sitekey' => "6LfTHyoUAAAAAJ4gsti7C_4r1CQnUyRHdysCymMJ",
                'data-callback' => "onSubmit"
            )
        ));
    }
}