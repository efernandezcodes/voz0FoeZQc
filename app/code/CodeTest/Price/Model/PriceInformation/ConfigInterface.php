<?php
/**
 * @author Edward Fernandez <efernandez.codes@gmail.com>
 */
namespace CodeTest\Price\Model\PriceInformation;

interface ConfigInterface
{
    const XML_PATH_ENABLED = 'catalog/price_information/enabled';
    const XML_PATH_ENDPOINT_URL = 'catalog/price_information/endpoint_url';
    const XML_PATH_DEBUG = 'catalog/price_information/debug';

    /**
     * Check whether price information integration is enabled
     *
     * @return bool
     */
    public function isEnabled(): bool;

    /**
     * Retrieve price information API endpoint URL
     *
     * @return string
     */
    public function getEndpointUrl(): string;

    /**
     * Check whether debug mode is enabled
     *
     * @return bool
     */
    public function isDebug(): bool;
}
