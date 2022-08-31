<?php

namespace Dotdigitalgroup\Enterprise\Api\Data;

interface FormOptionInterface
{
    /**
     * Get value
     *
     * @return string
     */
    public function getValue();

    /**
     * Set value
     *
     * @param string $value
     * @return $this
     */
    public function setValue($value);

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel();

    /**
     * Set label
     *
     * @param string $name
     * @return $this
     */
    public function setLabel($name);
}
