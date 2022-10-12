<?php
/**
 * @author Edward Fernandez <efernandez.codes@gmail.com>
 */
namespace CodeTest\Price\Model\PriceInformation;

use CodeTest\Price\Api\Data\PriceInterface;
use CodeTest\Price\Api\Data\PriceInterfaceFactory;
use CodeTest\Price\Api\PriceRepositoryInterface;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

abstract class AbstractPriceInfoRepository implements PriceRepositoryInterface
{
    /**
     * @var PriceInterfaceFactory
     */
    protected PriceInterfaceFactory $priceFactory;

    /**
     * @var PriceHelper
     */
    protected PriceHelper $priceHelper;

    /**
     * @param PriceInterfaceFactory $priceFactory
     * @param PriceHelper $priceHelper
     */
    public function __construct(
        PriceInterfaceFactory $priceFactory,
        PriceHelper $priceHelper
    ) {
        $this->priceFactory = $priceFactory;
        $this->priceHelper = $priceHelper;
    }

    /**
     * @param int $productId
     * @param float $unitPrice
     *
     * @return PriceInterface
     */
    protected function createPrice(int $productId, float $unitPrice): PriceInterface
    {
        return $this->priceFactory->create([
            'data' => [
                PriceInterface::PRODUCT_ID => $productId,
                PriceInterface::UNIT_PRICE => $unitPrice,
                PriceInterface::DISPLAY_PRICE => $this->priceHelper->currency($unitPrice, true, false),
            ]
        ]);
    }
}
