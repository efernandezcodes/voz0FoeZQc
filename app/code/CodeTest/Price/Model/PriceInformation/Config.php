<?php
/**
 * @author Edward Fernandez <efernandez.codes@gmail.com>
 */
namespace CodeTest\Price\Model\PriceInformation;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Config implements ConfigInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private ScopeConfigInterface $scopeConfig;

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @inheritDoc
     */
    public function isEnabled(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_ENABLED);
    }

    /**
     * @inheritDoc
     */
    public function getEndpointUrl(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_ENDPOINT_URL);
    }

    /**
     * @inheritDoc
     */
    public function isDebug(): bool
    {
        return $this->scopeConfig->isSetFlag(self::XML_PATH_DEBUG);
    }
}
