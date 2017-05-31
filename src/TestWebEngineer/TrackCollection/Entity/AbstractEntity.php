<?php
declare(strict_types = 1);
namespace TestWebEngineer\TrackCollection\Entity;

use TestWebEngineer\Track\Entity\EntityInterface as TrackEntityInterface;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionTrackNotFoundException;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionDuplicateTrackException;

/**
 * Class AbstractEntity
 *
 * @package TestWebEngineer\TrackCollection\Entity
 */
abstract class AbstractEntity implements EntityInterface
{

    /**
     * Track list
     *
     * @var TrackEntityInterface[]
     */
    private $list;

    /**
     * AbstractEntity constructor.
     */
    public function __construct()
    {
        $this->list = [];
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\TrackCollection\Entity\EntityInterface::add()
     */
    public function add(TrackEntityInterface $track)
    {
        $trackId = $track->getId();

        if (isset($this->list[$trackId])) {
            throw new TrackCollectionDuplicateTrackException();
        }

        $this->list[$trackId] = $track;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\TrackCollection\Entity\EntityInterface::getList()
     */
    public function getList(): array
    {
        return $this->list;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see JsonSerializable::jsonSerialize()
     */
    function jsonSerialize()
    {
        return  $this->getList();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\TrackCollection\Entity\EntityInterface::removeAll()
     */
    public function removeAll()
    {
        $this->list = [];
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\TrackCollection\Entity\EntityInterface::remove()
     */
    public function remove(TrackEntityInterface $track)
    {
        $trackId = $track->getId();
        if (!isset($this->list[$trackId])) {
            throw new TrackCollectionTrackNotFoundException();
        }
        unset($this->list[$trackId]);
    }
}
