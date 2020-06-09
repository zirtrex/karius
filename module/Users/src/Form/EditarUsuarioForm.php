<?php
namespace Users\Form;

use Laminas\Form\Form;

class EditarUsuarioForm extends Form
{

    protected $selectTable;

    public function init()
    {
        parent::__construct('editarUsuarioForm');
        
        $this->setAttribute('method', 'post');
        
        $this->setAttributes(array(
            'class' => 'uk-form'
        ));
        
        $this->add(array(
            'name' => 'cod_usuario',
            'type' => 'hidden',
            'attributes' => array(
                'id' => 'cod_usuario'
            )
        ));
        
        $this->add(array(
            'name' => 'rol',
            'type' => 'hidden',
            'attributes' => array(
                'id' => 'rol'
            )
        ));
        
        $this->add(array(
            'name' => 'nombres',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => array(
                'id' => 'nombres',
                'placeholder' => 'Ingrese nombre(s)',
                'required' => 'required',
                'class' => 'uk-input'
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
                'id' => 'primerApellido',
                'placeholder' => 'Ingrese apellido(s)',
                'required' => 'required',
                'class' => 'uk-input'
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
                'class' => 'uk-input'
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
                'placeholder' => 'Ingrese celular',
                'class' => 'uk-input'
            ),
            'options' => array(
                'label' => 'Celular',
                'label_attributes' => array(
                    'class' => 'uk-form-label'
                )
            )
        ));
        
        $this->add(array(
            'name' => 'csrf',
            'type' => 'Laminas\Form\Element\Csrf'
        ));
        
        $this->add(array(
            'name' => 'guardar',
            'type' => 'Laminas\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Editar datos',
                'class' => 'uk-button uk-button-primary'
            )
        ));
    }
}