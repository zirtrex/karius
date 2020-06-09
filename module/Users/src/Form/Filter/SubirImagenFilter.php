<?php
namespace Users\Form\Filter;

use Laminas\InputFilter\InputFilter;

class SubirImagenFilter extends InputFilter
{

    public function __construct()
    {
        $this->add(array(
            'type' => 'Laminas\InputFilter\FileInput',
            'name' => 'imagen',
            'required' => true,
            'filters' => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target'=>'./public/img/perfil',
                        'useUploadName'=>true,
                        'useUploadExtension'=>true,
                        'overwrite'=>true,
                        'randomize'=>false
                    ]
                ]
            ],
            'validators' => [
                ['name'    => 'FileUploadFile'],
                /*['name'    => 'FileIsImage'],*/
                [
                    'name'    => 'FileImageSize',
                    'options' => [
                        'minWidth'  => 420,
                        'minHeight' => 420,
                        'maxWidth'  => 4096,
                        'maxHeight' => 4096
                    ]
                ],
            ],
        ));
    }
}
