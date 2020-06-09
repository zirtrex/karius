<?php
namespace Users\Form;

use Laminas\Form\Form;
use Laminas\Captcha\Image as CaptchaImage;

class OlvidarClaveForm extends Form
{
    public function __construct($urlcaptcha)
    {
        parent::__construct('olvidarClaveForm');
        
        $this->setAttribute('method', 'post');        
        
        $this->add(array(
            'name' => 'correo',
            'type' => 'Laminas\Form\Element\Text',
            'attributes' => array(
                'required' => 'required',
                'placeholder' => 'Ingrese su correo',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Correo',
                'label_attributes' => [
                    'class' => 'uk-form-label'
                ],
            )
        ));
        
        $dirdata = './public';
        
        $captchaImage = new CaptchaImage([
            'font' => $dirdata . '/fonts/MGBLACK.ttf',
            'fontSize' => 32,
            'wordLen' => 5,
            'width' => 240,
            'height' => 100,
            'dotNoiseLevel' => 5,
            'lineNoiseLevel' => 2
        ]);
        
        $captchaImage->setImgDir($dirdata . '/captcha');
        $captchaImage->setImgUrl($urlcaptcha);
        
        $this->add(array(
            'type' => 'Laminas\Form\Element\Captcha',
            'name' => 'captcha',
            'attributes' => array(
                'placeholder' => 'Ingresa el código de la imagen',
                'required' => 'required',
                'class' => 'uk-input uk-border-pill'
            ),
            'options' => array(
                'label' => 'Ingresa el código generado',
                'label_attributes' => [
                    'class' => 'uk-form-label'
                ],
                'captcha' => $captchaImage,
                'messages' => array(
                    //\Laminas\Captcha\Imagen::BAD_CAPTCHA => 'La nueva clave no coincide.',
                ),
            )
        ));
        
        $this->add(array(
            'name' => 'csrf',
            'type' => 'Laminas\Form\Element\Csrf',
            'options' => array(
                'csrf_options' => array(
                    'timeout' => 1200
                )
            )
        ));
        
        $this->add(array(
            'name' => 'enviar',
            'type' => 'Laminas\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Enviar correo',                
                'id' => 'enviar-correo',
                'class' => 'uk-button uk-button-primary uk-border-pill uk-width-1-1'
            )
        ));
    }
}
