<?php

namespace CodeTest\PriceGraphQl\Model;

use CodeTest\Price\Api\Data\PriceInterface;
use CodeTest\PriceGraphQl\Api\GraphQlPriceResultProcessorInterface;
use Magento\Framework\Exception\LocalizedException;

class GraphQlPriceResultProcessor implements GraphQlPriceResultProcessorInterface
{
    /**
     * @param GraphQlPriceResultProcessorInterface[] $processors
     */
    public function __construct(protected array $processors = [])
    {
    }

    /**
     * @param PriceInterface $price
     * @param array $graphQlResult
     *
     * @return void
     * @throws LocalizedException
     *
     * @author Edward Fernandez <efernandez.codes@gmail.com>
     */
    public function process(PriceInterface $price, array &$graphQlResult): void
    {
        foreach ($this->processors as $processor) {
            if (!$processor instanceof GraphQlPriceResultProcessorInterface) {
                throw new LocalizedException(
                    __('Given processor must implement "%1"', GraphQlPriceResultProcessorInterface::class)
                );
            }

            $processor->process($price, $graphQlResult);
        }
    }
}
