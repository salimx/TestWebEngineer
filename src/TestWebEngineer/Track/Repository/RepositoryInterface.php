<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Repository;

use TestWebEngineer\Track\Entity\EntityInterface as TrackEntityInterface;
use TestWebEngineer\TrackCollection\Entity\EntityInterface as TrackCollectionEntityInterface;
use TestWebEngineer\Track\Exception\TrackNotFoundException;

/**
 * Interface RepositoryInterface
 * @package TestWebEngineer\Track\Repository
 */
interface RepositoryInterface
{
    /**
     * @param int $id
     * @return TrackEntityInterface
     * @throws TrackNotFoundException
     */
    public function findByTrackId(int $id): TrackEntityInterface;

    /**
     * @return TrackCollectionEntityInterface
     */
    public function list(): TrackCollectionEntityInterface;


}
