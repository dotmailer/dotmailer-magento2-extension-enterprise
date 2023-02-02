<?php

namespace Dotdigitalgroup\Enterprise\Controller\Adminhtml\Ajax;

use Dotdigitalgroup\Enterprise\Model\Form\FormData as FormDataModel;
use Dotdigitalgroup\Enterprise\Api\FormManagementInterface;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;

class Formdata extends Action implements HttpPostActionInterface
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    public const ADMIN_RESOURCE = 'Dotdigitalgroup_Email::automation';

    /**
     * @var FormManagementInterface
     */
    private $formManagementInterface;

    /**
     * @var JsonFactory
     */
    private $resultJsonFactory;

    /**
     * @param Context $context
     * @param FormManagementInterface $formManagementInterface
     * @param JsonFactory $resultJsonFactory
     */
    public function __construct(
        Context $context,
        FormManagementInterface $formManagementInterface,
        JsonFactory $resultJsonFactory
    ) {
        parent::__construct($context);
        $this->formManagementInterface = $formManagementInterface;
        $this->resultJsonFactory = $resultJsonFactory;
    }

    /**
     * Execute
     *
     * @return Json
     */
    public function execute()
    {
        $formId = $this->getRequest()->getParam('form_id');
        $formData = $this->formManagementInterface
            ->augmentFormData($formId);

        $resultJson = $this->resultJsonFactory->create();

        /** @var FormDataModel $formData */
        $resultJson->setData($formData->__toArray());

        return $resultJson;
    }
}
