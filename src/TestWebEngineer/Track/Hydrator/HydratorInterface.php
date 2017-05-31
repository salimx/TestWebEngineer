<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Hydrator;

use TestWebEngineer\Track\Entity\EntityInterface as TrackEntityInterface;

/**
 * Interface HydratorInterface
 *
 * @package TestWebEngineer\Track\Hydrator
 */
interface HydratorInterface
{

    /**
     *
     * @param array $data
     * @return TrackEntityInterface
     */
    public function hydrate(array $data): TrackEntityInterface;
}
