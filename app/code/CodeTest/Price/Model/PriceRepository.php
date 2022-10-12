<?php

namespace CodeTest\Price\Model;

use CodeTest\Price\Api\Data\PriceInterface;
use CodeTest\Price\Api\PriceRepositoryInterface;
use Magento\Framework\Exception\LocalizedException;

class PriceRepository implements PriceRepositoryInterface
{
    /**
     * @param PriceRepositoryInterface|null $sourceRepository
     * @throws LocalizedException
     */
    public function __construct(protected ?PriceRepositoryInterface $sourceRepository = null)
    {
        if ($this->sourceRepository === null) {
            throw new LocalizedException(__('Source repository is not defined.'));
        }
    }

    public function get(int $productId): ?PriceInterface
    {
        return $this->sourceRepository->get($productId);
    }
}
