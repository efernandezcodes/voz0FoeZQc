<?php
/**
 * @author Edward Fernandez <efernandez.codes@gmail.com>
 */
namespace CodeTest\Price\Model\PriceInformation;

use CodeTest\Price\Api\Data\PriceInterface;
use CodeTest\Price\Api\Data\PriceInterfaceFactory;
use CodeTest\Price\Api\PriceRepositoryInterface;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductPriceRepository implements PriceRepositoryInterface
{
    /**
     * @var PriceInterfaceFactory
     */
    protected PriceInterfaceFactory $priceFactory;

    /**
     * @var ProductRepositoryInterface
     */
    protected ProductRepositoryInterface $productRepository;

    /**
     * @param PriceInterfaceFactory $priceFactory
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        PriceInterfaceFactory $priceFactory,
        ProductRepositoryInterface $productRepository
    ) {
        $this->priceFactory = $priceFactory;
        $this->productRepository = $productRepository;
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

        return $this->priceFactory->create([
            'data' => [
                PriceInterface::PRODUCT_ID => $productId,
                PriceInterface::UNIT_PRICE => (float) $product->getPrice(),
            ]
        ]);
    }
}
