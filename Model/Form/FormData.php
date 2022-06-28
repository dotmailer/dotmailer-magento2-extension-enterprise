<?php

namespace Dotdigitalgroup\Enterprise\Model\Form;

use Dotdigitalgroup\Enterprise\Api\Data\FormDataInterface;

class FormData extends \Magento\Framework\Api\AbstractSimpleObject implements FormDataInterface
{
    /**
     * @inheritDoc
     */
    public function getFormName()
    {
        return $this->_get('form_name');
    }

    /**
     * @inheritDoc
     */
    public function setFormName($name)
    {
        return $this->setData('form_name', $name);
    }

    /**
     * @inheritDoc
     */
    public function getFormPageId()
    {
        return $this->_get('form_page_id');
    }

    /**
     * @inheritDoc
     */
    public function setFormPageId($pageId)
    {
        return $this->setData('form_page_id', $pageId);
    }

    /**
     * @inheritDoc
     */
    public function getFormDomain()
    {
        return $this->_get('form_domain');
    }

    /**
     * @inheritDoc
     */
    public function setFormDomain($domain)
    {
        return $this->setData('form_domain', $domain);
    }

    /**
     * @inheritDoc
     */
    public function getScriptSrc()
    {
        return $this->_get('script_src');
    }

    /**
     * @inheritDoc
     */
    public function setScriptSrc($src)
    {
        return $this->setData('script_src', $src);
    }

    /**
     * @inheritDoc
     */
    public function getFormSharing()
    {
        return $this->_get('form_sharing');
    }

    /**
     * @inheritDoc
     */
    public function setFormSharing($sharing)
    {
        return $this->setData('form_sharing', $sharing);
    }

    /**
     * @inheritDoc
     */
    public function getFormAttachment()
    {
        return $this->_get('form_attachment');
    }

    /**
     * @inheritDoc
     */
    public function setFormAttachment($selector)
    {
        return $this->setData('form_attachment', $selector);
    }
}
