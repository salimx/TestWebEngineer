<?php
declare(strict_types = 1);
namespace TestWebEngineer\TrackCollection\Hydrator;

use TestWebEngineer\TrackCollection\Entity\EntityInterface as TrackCollectionInterface;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionHydratorNotValidException;

/**
 * Interface HydratorInterface
 *
 * @package TestWebEngineer\TrackCollection\Hydrator
 */
interface HydratorInterface
{

    /**
     * Hydrate from array a TrackCollectionInterface
     *
     * @param array $data
     * @return TrackCollectionInterface
     * @throws TrackCollectionHydratorNotValidException
     */
    public function hydrate($data): TrackCollectionInterface;
}
