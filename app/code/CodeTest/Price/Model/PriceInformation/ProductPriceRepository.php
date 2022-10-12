<?php
/**
 * @author Edward Fernandez <efernandez.codes@gmail.com>
 */
namespace CodeTest\Price\Model\PriceInformation;

use CodeTest\Price\Api\Data\PriceInterface;
use CodeTest\Price\Api\Data\PriceInterfaceFactory;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;

class ProductPriceRepository extends AbstractPriceInfoRepository
{
    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @param ProductRepositoryInterface $productRepository
     * @param PriceInterfaceFactory $priceFactory
     * @param PriceHelper $priceHelper
     */
    public function __construct(
        ProductRepositoryInterface $productRepository,
        PriceInterfaceFactory $priceFactory,
        PriceHelper $priceHelper
    ) {
        $this->productRepository = $productRepository;

        parent::__construct($priceFactory, $priceHelper);
    }

    /**
     * @inheritDoc
     * @noinspection PhpUnusedLocalVariableInspection
     */
    public function get(int $productId): ?PriceInterface
    {
        try {
            $product = $this->productRepository->getById($productId);
        } catch (NoSuchEntityException $e) {
            return null;
        }

        return $this->createPrice($productId, (float) $product->getPrice());
    }
}
