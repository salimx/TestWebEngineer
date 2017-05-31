<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Repository;

use TestWebEngineer\Track\Hydrator\HydratorInterface as TrackHydratorInterface;
use TestWebEngineer\TrackCollection\Hydrator\HydratorInterface as TrackCollectionHydratorInterface;

/**
 * Class AbstractRepository
 *
 * @package TestWebEngineer\Track\Repository
 */
abstract class AbstractRepository implements RepositoryInterface
{

    /**
     *
     * @var TrackHydratorInterface
     */
    protected $hydrator;

    /**
     *
     * @var TrackCollectionHydratorInterface
     */
    protected $trackCollectionHydrator;
}
