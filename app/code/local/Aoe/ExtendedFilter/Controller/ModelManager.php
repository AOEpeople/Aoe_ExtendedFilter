<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  2014-04-01
 */
abstract class Aoe_ExtendedFilter_Controller_ModelManager extends Aoe_ExtendedFilter_Controller_Model
{
    /**
     * Create a new record
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function newAction()
    {
        $model = $this->loadModel();
        if ($model->getId()) {
            $this->_forward('noroute');
            return;
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            try {
                $model->addData($this->preprocessPostData($postData));
                $model->save();
                $this->_redirectUrl($this->getHelper()->getGridUrl());
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Edit an existing record
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function editAction()
    {
        $model = $this->loadModel();
        if (!$model->getId()) {
            $this->_forward('noroute');
            return;
        }

        if ($this->getRequest()->isPost()) {
            $postData = $this->getRequest()->getPost();
            try {
                $model->addData($this->preprocessPostData($postData));
                $model->save();
                $this->_redirectUrl($this->getHelper()->getGridUrl());
                return;
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
            }
        }

        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Delete an existing record
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function deleteAction()
    {
        $model = $this->loadModel();
        if (!$model->getId()) {
            $this->_forward('noroute');
            return;
        }

        try {
            $model->delete();
            $this->_redirectUrl($this->getHelper()->getGridUrl());
        } catch (Exception $e) {
            Mage::logException($e);
            $this->_getSession()->addError($e->getMessage());
            $this->_redirectUrl($this->getHelper()->getEditUrl($model));
        }
    }

    /**
     * Pre-process the POST data before adding to the model
     *
     * @param array $postData
     *
     * @return array
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    protected function preprocessPostData(array $postData)
    {
        return $postData;
    }

    protected function getAclActionName()
    {
        $action = parent::getAclActionName();

        if ($action !== 'view') {
            $action = 'edit';
        }

        return $action;
    }

}
