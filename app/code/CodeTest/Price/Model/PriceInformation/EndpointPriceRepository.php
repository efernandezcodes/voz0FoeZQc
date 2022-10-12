<?php
/**
 * @author Edward Fernandez <efernandez.codes@gmail.com>
 */
namespace CodeTest\Price\Model\PriceInformation;

use CodeTest\Price\Api\Data\PriceInterface;
use CodeTest\Price\Api\Data\PriceInterfaceFactory;
use CodeTest\Price\Model\PriceInformation\Command\GetUnitPriceByProductId;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class EndpointPriceRepository extends AbstractPriceInfoRepository
{
    /**
     * @var GetUnitPriceByProductId
     */
    protected GetUnitPriceByProductId $getUnitPriceByProductId;

    /**
     * @param GetUnitPriceByProductId $getUnitPriceByProductId
     * @param PriceInterfaceFactory $priceFactory
     * @param PriceHelper $priceHelper
     */
    public function __construct(
        GetUnitPriceByProductId $getUnitPriceByProductId,
        PriceInterfaceFactory $priceFactory,
        PriceHelper $priceHelper
    ) {
        $this->getUnitPriceByProductId = $getUnitPriceByProductId;

        parent::__construct($priceFactory, $priceHelper);
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

        return $this->createPrice($productId, $priceData);
    }
}
