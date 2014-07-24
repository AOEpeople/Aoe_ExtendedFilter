<?php

/**
 * @author Lee Saferite <lee.saferite@aoe.com>
 * @since  3/24/14
 *
 * @method $this setRowUrlRoute(string $route)
 * @method $this setRowUrlParams(array $params)
 * @method $this setRowUrlQueryParams(array $params)
 * @method array|null getRowUrlParams()
 * @method array|null getRowUrlQueryParams()
 */
class Aoe_ExtendedFilter_Block_Widget_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function setId($id)
    {
        // Cache the old JS object name if possible
        if ($this->getData('id') !== null) {
            $oldJsObjectName = $this->getJsObjectName();
        }

        // replace . : - with _
        $id = ($id ? str_replace(array('.', ':', '-'), '_', $id) : $id);

        parent::setId($id);

        // Swap the old JS object name for the new JS object name
        if ($this->getData('id') !== null && isset($oldJsObjectName)) {
            $newJsObjectName = $this->getJsObjectName();
            if ($newJsObjectName !== $oldJsObjectName) {
                foreach ($this->getChild() as $child) {
                    /** @var Mage_Core_Block_Abstract $child */
                    if ($child instanceof Mage_Adminhtml_Block_Widget_Button && $child->getOnclick()) {
                        $onclick = $child->getOnclick();
                        $onclick = str_replace($oldJsObjectName, $newJsObjectName, $onclick);
                        $child->setOnclick($onclick);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Return row url for js event handlers
     *
     * @param Varien_Object
     *
     * @return string
     */
    public function getRowUrl($item)
    {
        if (!$item instanceof Varien_Object) {
            return parent::getRowUrl($item);
        }

        $rowUrlRoute = trim($this->getRowUrlRoute());
        if (empty($rowUrlRoute)) {
            return parent::getRowUrl($item);
        }

        $params = array();

        $rowUrlParams = $this->getRowUrlParams();
        $rowUrlParams = (is_array($rowUrlParams) ? $rowUrlParams : explode(',', $rowUrlParams));
        $rowUrlParams = array_filter(array_map('trim', $rowUrlParams));
        foreach ($rowUrlParams as $key => $value) {
            if (strpos($value, '{{') === 0 && strrpos($value, '}}') === (strlen($value) - 2)) {
                $value = $item->getDataUsingMethod(substr($value, 2, -2));
            }

            $params[$key] = $value;
        }

        $rowUrlQueryParams = $this->getRowUrlQueryParams();
        $rowUrlQueryParams = (is_array($rowUrlQueryParams) ? $rowUrlQueryParams : explode(',', $rowUrlQueryParams));
        $rowUrlQueryParams = array_filter(array_map('trim', $rowUrlQueryParams));
        foreach ($rowUrlQueryParams as $key => $value) {
            if (strpos($value, '{{') === 0 && strrpos($value, '}}') === (strlen($value) - 2)) {
                $value = $item->getDataUsingMethod(substr($value, 1, -1));
            }
            $params['_query'][$key] = $value;
        }

        return $this->getUrl($rowUrlRoute, $params);
    }

    public function getSortable()
    {
        return ($this->hasData('sortable') ? (bool)$this->getData('sortable') : true);
    }


    /**
     * Sort columns by predefined order
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    public function sortColumnsByOrder()
    {
        $columns = $this->_columns;
        foreach ($this->getColumnsOrder() as $columnId => $after) {
            if ($after[0] === '-') {
                $before = substr($after, 1);
                $after = null;
            } else {
                $before = null;
            }
            if ($after !== null && isset($columns[$after])) {
                $currentPosition = array_search($columnId, array_keys($columns));
                $value = array_splice($columns, $currentPosition, 1);

                $destPosition = array_search($after, array_keys($columns)) + 1;
                $columns = array_slice($columns, 0, $destPosition) + $value + array_slice($columns, $destPosition);
            } elseif ($before !== null && $columns[$before]) {
                $currentPosition = array_search($columnId, array_keys($columns));
                $value = array_splice($columns, $currentPosition, 1);

                $destPosition = array_search($before, array_keys($columns));
                $columns = array_slice($columns, 0, $destPosition) + $value + array_slice($columns, $destPosition);
            }
        }

        $this->_columns = $columns;

        end($this->_columns);
        $this->_lastColumnId = key($this->_columns);
        return $this;
    }

    /**
     * Modify grid column
     *
     * @param $columnId
     * @param $key
     * @param $value
     *
     * @return $this
     *
     * @author Lee Saferite <lee.saferite@aoe.com>
     */
    public function updateColumn($columnId, $key, $value)
    {
        if (isset($this->_columns[$columnId]) && $this->_columns[$columnId] instanceof Varien_Object) {
            $this->_columns[$columnId]->setData($key, $value);
        }

        return $this;
    }
}
