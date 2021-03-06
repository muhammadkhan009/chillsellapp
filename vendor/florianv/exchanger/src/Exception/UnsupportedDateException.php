<?php

declare(strict_types=1);

/*
 * This file is part of Exchanger.
 *
 * (c) Florian Voutzinos <florian@voutzinos.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Exchanger\Exception;

use Exchanger\Contract\ExchangeRateService;

/**
 * Exception thrown when a date is not supported by a service.
 *
 * @author Florian Voutzinos <florian@voutzinos.com>
 */
final class UnsupportedDateException extends Exception
{
    /**
     * The date.
     *
     * @var \DateTimeInterface
     */
    private $date;

    /**
     * The service.
     *
     * @var ExchangeRateService
     */
    private $service;

    /**
     * Constructor.
     *
     * @param \DateTimeInterface  $date
     * @param ExchangeRateService $service
     */
    public function __construct(\DateTimeInterface $date, ExchangeRateService $service)
    {
        parent::__construct(
            sprintf(
                'The date "%s" is not supported by the service "%s".',
                $date->format('Y-m-d'),
                \get_class($service)
            )
        );

        $this->service = $service;
    }

    /**
     * Gets the date.
     *
     * @return \DateTimeInterface
     */
    public function getDate(): \DateTimeInterface
    {
        return $this->date;
    }

    /**
     * Gets the service.
     *
     * @return ExchangeRateService
     */
    public function getService(): ExchangeRateService
    {
        return $this->service;
    }
}
