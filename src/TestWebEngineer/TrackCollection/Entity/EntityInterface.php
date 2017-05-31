<?php
declare(strict_types = 1);
namespace TestWebEngineer\TrackCollection\Entity;

use TestWebEngineer\Track\Entity\EntityInterface as TrackEntityInterface;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionTrackNotFoundException;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionDuplicateTrackException;

/**
 * Interface EntityInterface
 *
 * @package TestWebEngineer\TrackCollection\Entity
 */
interface EntityInterface extends \JsonSerializable
{
    /**
     * retrieve Tracks Tracks
     *
     * @return TrackEntityInterface[]
     */
    public function getList(): array;

    /**
     * add a track on Tracks Tracks
     *
     * @param TrackEntityInterface $track*
     * @throws TrackCollectionDuplicateTrackException
     */
    public function add(TrackEntityInterface $track);

    /**
     *
     * @param TrackEntityInterface $track
     * @throws TrackCollectionTrackNotFoundException
     */
    public function remove(TrackEntityInterface $track);

    /**
     * remove all track on Tracks Tracks
     */
    public function removeAll();
}
