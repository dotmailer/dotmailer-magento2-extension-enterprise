<?php

namespace Dotdigitalgroup\Enterprise\Api;

interface FormManagementInterface
{
    /**
     * Fetch Pages, Surveys or Forms for a website.
     *
     * @param int $websiteId
     * @return \Dotdigitalgroup\Enterprise\Api\Data\FormOptionInterface[]
     */
    public function getFormOptions($websiteId);

    /**
     * Get form data object by ID
     *
     * @param int $formId
     * @return \Dotdigitalgroup\Enterprise\Api\Data\FormDataInterface
     */
    public function augmentFormData($formId);
}
