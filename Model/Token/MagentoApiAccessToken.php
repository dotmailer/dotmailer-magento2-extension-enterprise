<?php

namespace Dotdigitalgroup\Enterprise\Model\Token;

use Magento\Integration\Model\Oauth\TokenFactory;
use Magento\Backend\Model\Auth\Session;

class MagentoApiAccessToken
{
    /**
     * @var TokenFactory
     */
    private $tokenFactory;

    /**
     * @var Session
     */
    private $adminSession;

    /**
     * MagentoApiAccessToken constructor.
     *
     * @param TokenFactory $tokenFactory
     * @param Session $adminSession
     */
    public function __construct(
        TokenFactory $tokenFactory,
        Session $adminSession
    ) {
        $this->tokenFactory = $tokenFactory;
        $this->adminSession = $adminSession;
    }

    /**
     * Get access token for preview / page builder
     *
     * @return string
     */
    public function getTokenForPreview()
    {
        $token = $this->tokenFactory->create()
            ->createAdminToken($this->adminSession->getUser()->getId());

        return $token->getToken();
    }
}
