<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  3/24/14
 */
class Aoe_ExtendedFilter_Block_Widget_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function setFormData($key, $value = null)
    {
        $form = $this->getForm();
        if ($form instanceof Varien_Data_Form) {
            if ($key instanceof Varien_Object) {
                $key = $key->getData();
                $value = null;
            }
            $form->setData($key, $value);
        }
        return $this;
    }

    public function addFormData($data)
    {
        $form = $this->getForm();
        if ($form instanceof Varien_Data_Form) {
            if ($data instanceof Varien_Object) {
                $data = $data->getData();
            }
            $form->addData($data);
        }
        return $this;
    }

    public function setFormValues(array $data)
    {
        $form = $this->getForm();
        if ($form instanceof Varien_Data_Form) {
            if ($data instanceof Varien_Object) {
                $data = $data->getData();
            }
            $form->setValues($data);
        }
        return $this;
    }

    public function addFormValues(array $data)
    {
        $form = $this->getForm();
        if ($form instanceof Varien_Data_Form) {
            if ($data instanceof Varien_Object) {
                $data = $data->getData();
            }
            $form->addValues($data);
        }
        return $this;
    }
}
