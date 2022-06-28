<?php

namespace Dotdigitalgroup\Enterprise\Api\Data;

interface FormDataInterface
{
    /**
     * Get form name
     *
     * @return string
     */
    public function getFormName();

    /**
     * Set form name
     *
     * @param string $name
     * @return $this
     */
    public function setFormName($name);

    /**
     * Get page ID
     *
     * @return string
     */
    public function getFormPageId();

    /**
     * Set page ID
     *
     * @param string $pageId
     * @return $this
     */
    public function setFormPageId($pageId);

    /**
     * Get form domain
     *
     * @return string
     */
    public function getFormDomain();

    /**
     * Set form domain
     *
     * @param string $domain
     * @return $this
     */
    public function setFormDomain($domain);

    /**
     * Get script src attribute
     *
     * @return string
     */
    public function getScriptSrc();

    /**
     * Set script src attribute
     *
     * @param string $src
     * @return $this
     */
    public function setScriptSrc($src);

    /**
     * Get form sharing type
     *
     * @return string
     */
    public function getFormSharing();

    /**
     * Set form sharing type
     *
     * @param string $sharing
     * @return $this
     */
    public function setFormSharing($sharing);

    /**
     * Get form attach to identifier
     *
     * @return string
     */
    public function getFormAttachment();

    /**
     * Set form attach to identifier
     *
     * @param string $selector
     * @return $this
     */
    public function setFormAttachment($selector);
}
