<?php
declare(strict_types = 1);
namespace TestWebEngineer\FavoriteUserTrack\Repository;

use TestWebEngineer\TrackCollection\Hydrator\HydratorInterface as TrackCollectionHydratorInterface;

/**
 * Class AbstractRepository
 *
 * @package TestWebEngineer\FavoriteUserTrack\Repository
 */
abstract class AbstractRepository implements RepositoryInterface
{

    /**
     *
     * @var TrackCollectionHydratorInterface
     */
    protected $trackCollectionHydrator;
}
