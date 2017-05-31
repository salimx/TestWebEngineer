<?php
declare(strict_types = 1);
namespace TestWebEngineer\FavoriteUserTrack\Repository;

use TestWebEngineer\TrackCollection\Entity\EntityInterface as TrackCollectionEntityInterface;

/**
 * Interface RepositoryInterface
 *
 * @package TestWebEngineer\FavoriteUserTrack\Repository
 */
interface RepositoryInterface
{

    /**
     * @param int                            $userId
     * @param TrackCollectionEntityInterface $userTracks
     * @return bool
     */
    public function saveFavoriteUserTrackByUserId(int $userId, TrackCollectionEntityInterface $userTracks) : bool;

    /**
     * @param int $userId
     * @return TrackCollectionEntityInterface
     */
    public function listFavoriteUserTrackByUserId(int $userId): TrackCollectionEntityInterface;
}
