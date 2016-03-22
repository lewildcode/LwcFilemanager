<?php
namespace LwcFilemanager\Form;

use Zend\Form\Form;

class UploadFiles extends Form
{

    /**
     *
     * @param string $name
     *            OPTIONAL
     * @param array $options
     *            OPTIONAL
     */
    public function __construct($name = null, $options = array())
    {
        parent::__construct($name, $options);
        
        $this->setAttribute('enctype', 'multipart/form-data');
        
        $this->add($this->getFileElement());
        $this->add($this->getBaseElement());
        $this->add($this->getOverwriteElement());
        $this->add($this->getSubmitElement());
    }

    /**
     *
     * @return array
     */
    public function getOverwriteElement()
    {
        return array(
            'name' => 'overwrite',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Overwrite existing files',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            ),
            'attributes' => array(
                'class' => 'checkbox'
            )
        );
    }
    
    /**
     * 
     * @return array
     */
    public function getBaseElement()
    {
        return array(
            'name' => 'base',
            'type' => 'hidden'
        );
    }

    /**
     *
     * @return array
     */
    public function getFileElement()
    {
        return array(
            'name' => 'files',
            'type' => 'file',
            'options' => array(
                'label' => 'Select file(s)',
                'label_attributes' => array(
                    'class' => 'control-label'
                )
            ),
            'attributes' => array(
                'multiple' => 'multiple',
                'required' => 'required',
                'class' => 'form-control'
            )
        );
    }
    
    /**
     *
     * @return array
     */
    public function getSubmitElement()
    {
        return array(
            'name' => 'submit',
            'type' => 'submit',
            'attributes' => array(
                'value' => 'Upload files',
                'class' => 'btn btn-primary'
            )
        );
    }
}