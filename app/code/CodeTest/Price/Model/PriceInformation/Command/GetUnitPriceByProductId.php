<?php
/**
 * @author Edward Fernandez <efernandez.codes@gmail.com>
 */
namespace CodeTest\Price\Model\PriceInformation\Command;

use CodeTest\Price\Exception\PriceException;
use CodeTest\Price\Logger\PriceLogger;
use CodeTest\Price\Model\PriceInformation\ConfigInterface;
use Magento\Framework\HTTP\ClientInterface;
use Magento\Framework\Phrase;
use Magento\Framework\Serialize\Serializer\Json;
use Psr\Log\LoggerInterface;
use Throwable;

class GetUnitPriceByProductId
{
    const ERROR_MESSAGE_API = 'Unable to retrieve or parse price data from API endpoint. Please try again later.';

    /**
     * @var ConfigInterface
     */
    protected ConfigInterface $config;

    /**
     * @var Json
     */
    protected Json $jsonSerializer;

    /**
     * @var ClientInterface
     */
    protected ClientInterface $client;

    /**
     * @var PriceLogger
     */
    protected PriceLogger $priceLogger;

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @param ConfigInterface $config
     * @param Json $jsonSerializer
     * @param ClientInterface $client
     * @param PriceLogger $priceLogger
     * @param LoggerInterface $logger
     */
    public function __construct(
        ConfigInterface $config,
        Json $jsonSerializer,
        ClientInterface $client,
        PriceLogger $priceLogger,
        LoggerInterface $logger
    ) {
        $this->config = $config;
        $this->jsonSerializer = $jsonSerializer;
        $this->client = $client;
        $this->priceLogger = $priceLogger;
        $this->logger = $logger;
    }

    /**
     * @param int $productId
     *
     * @return float|null
     */
    public function execute(int $productId): ?float
    {
        try {
            return $this->handleRequest($productId);
        } catch (PriceException $e) {
            $this->logger->error($e->getMessage());
        } catch (Throwable $e) {
            $this->logger->error(
                new PriceException(new Phrase(self::ERROR_MESSAGE_API), $e)
            );
        }

        return null;
    }

    /**
     * @throws PriceException
     */
    protected function handleRequest(int $productId): ?float
    {
        $requestUrl = $this->config->getEndpointUrl() . '/' . $productId;

        $this->client->setHeaders(['Content-Type' => 'application/json']);
        $this->client->get($requestUrl);

        return $this->handleResponse($productId, $requestUrl, $this->client->getBody());
    }

    /**
     * @throws PriceException
     */
    protected function handleResponse(int $productId, string $requestUrl, string $response): ?float
    {
        $json = $this->jsonSerializer->unserialize($response);
        $this->logResponse($productId, $requestUrl, $json);

        $success = $json['success'] ?? false;
        $unitPrice = $json['data']['unit_price'] ?? null;

        if (!$success || !$unitPrice) {
            $errorResponseCode = $json['responseCode'] ?
                sprintf('Response code: %s', $json['responseCode']) : null;

            throw new PriceException(
                new Phrase($json['errorMessage'] ?? $errorResponseCode ?? self::ERROR_MESSAGE_API)
            );
        }

        return (float) $unitPrice;
    }

    protected function logResponse(int $productId, string $requestUrl, array $data): void
    {
        if (!$this->config->isDebug()) {
            return;
        }

        $this->priceLogger->debug(
            var_export([
                'request' => [
                    'url' => $requestUrl,
                    'product_id' => $productId,
                ],
                'response' => $data
            ], true)
        );
    }
}
