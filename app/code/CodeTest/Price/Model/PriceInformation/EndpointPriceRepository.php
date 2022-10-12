<?php
/**
 * @author Edward Fernandez <efernandez.codes@gmail.com>
 */
namespace CodeTest\Price\Model\PriceInformation;

use CodeTest\Price\Api\Data\PriceInterface;
use CodeTest\Price\Api\Data\PriceInterfaceFactory;
use CodeTest\Price\Api\PriceRepositoryInterface;
use CodeTest\Price\Model\PriceInformation\Command\GetUnitPriceByProductId;

class EndpointPriceRepository implements PriceRepositoryInterface
{
    /**
     * @var PriceInterfaceFactory
     */
    protected PriceInterfaceFactory $priceFactory;

    /**
     * @var GetUnitPriceByProductId
     */
    protected GetUnitPriceByProductId $getUnitPriceByProductId;

    /**
     * @param PriceInterfaceFactory $priceFactory
     * @param GetUnitPriceByProductId $getUnitPriceByProductId
     */
    public function __construct(
        PriceInterfaceFactory $priceFactory,
        GetUnitPriceByProductId $getUnitPriceByProductId
    ) {
        $this->priceFactory = $priceFactory;
        $this->getUnitPriceByProductId = $getUnitPriceByProductId;
    }

    /**
     * @inheritDoc
     */
    public function get(int $productId): ?PriceInterface
    {
        $priceData = $this->getUnitPriceByProductId->execute($productId);
        if ($priceData === null) {
            return null;
        }

        return $this->priceFactory->create([
            'data' => [
                PriceInterface::PRODUCT_ID => $productId,
                PriceInterface::UNIT_PRICE => $priceData,
            ]
        ]);
    }
}
