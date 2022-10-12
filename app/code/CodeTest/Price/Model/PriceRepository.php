<?php

namespace CodeTest\Price\Model;

use CodeTest\Price\Api\Data\PriceInterface;
use CodeTest\Price\Api\PriceRepositoryInterface;
use CodeTest\Price\Exception\PriceException;
use CodeTest\Price\Model\PriceInformation\ConfigInterface;
use Magento\Framework\Exception\LocalizedException;

class PriceRepository implements PriceRepositoryInterface
{
    const SOURCE_REPO_PRICE_INFORMATION = 'price_info';
    const SOURCE_REPO_PRODUCT = 'product';

    protected ?PriceRepositoryInterface $sourceRepository = null;

    /**
     * @param ConfigInterface $config
     * @param array|null $sourceMap
     *
     * @author Edward Fernandez <efernandez.codes@gmail.com>
     */
    public function __construct(
        protected ConfigInterface $config,
        protected ?array $sourceMap = null
    ) {
    }

    /**
     * @param int $productId
     *
     * @return PriceInterface|null
     * @throws LocalizedException
     * @throws PriceException
     *
     * @author Edward Fernandez <efernandez.codes@gmail.com>
     */
    public function get(int $productId): ?PriceInterface
    {
        $sourceRepository = $this->resolveSourceRepository();
        if ($sourceRepository === null) {
            throw new LocalizedException(__('Source repository is not defined.'));
        }

        return $sourceRepository->get($productId);
    }

    /**
     * @return PriceRepositoryInterface|null
     * @throws LocalizedException
     *
     * @author Edward Fernandez <efernandez.codes@gmail.com>
     */
    protected function resolveSourceRepository(): ?PriceRepositoryInterface
    {
        if ($this->sourceRepository === null) {
            $sourceType = $this->config->isEnabled() ? self::SOURCE_REPO_PRICE_INFORMATION : self::SOURCE_REPO_PRODUCT;
            if (!isset($this->sourceMap[$sourceType])) {
                throw new LocalizedException(
                    __("The %1 value wasn't found in the source repositories.", $sourceType)
                );
            }

            $sourceRepository = $this->sourceMap[$sourceType];
            if (!$sourceRepository instanceof PriceRepositoryInterface) {
                throw new LocalizedException(
                    __('Given source repository must implement "%1"', PriceRepositoryInterface::class)
                );
            }

            $this->sourceRepository = $sourceRepository;
        }

        return $this->sourceRepository;
    }
}
