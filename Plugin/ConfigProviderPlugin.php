<?php

namespace Dotdigitalgroup\Enterprise\Plugin;

use Dotdigitalgroup\Email\Model\Sync\Integration\DotdigitalConfig;
use Dotdigitalgroup\Enterprise\Helper\Config;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use ReflectionClass;

class ConfigProviderPlugin
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * ConfigProviderPlugin constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * After get configuration by store
     *
     * @param DotdigitalConfig $subject
     * @param array $result
     * @param string|int $storeId
     * @return array
     */
    public function afterGetConfigByStore(DotdigitalConfig $subject, $result, $storeId)
    {
        foreach ($this->getPaths() as $path) {
            $keys = explode("/", $path);
            $configValue = $this->scopeConfig->getValue(
                $path,
                ScopeInterface::SCOPE_STORES,
                $storeId
            );
            $result[$keys[0]][$keys[1]][$keys[2]] = (string) $configValue;
        }

        return $result;
    }

    /**
     * Get paths
     *
     * @return array
     */
    private function getPaths()
    {
        $reflectionClass = new ReflectionClass(Config::class);
        return $reflectionClass->getConstants();
    }
}
