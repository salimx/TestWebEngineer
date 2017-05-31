<?php
declare(strict_types = 1);
namespace TestWebEngineer\TrackCollection\Hydrator;

use TestWebEngineer\Track\Hydrator\HydratorInterface as TrackHydratorInterface;
use TestWebEngineer\TrackCollection\Entity\Entity as TrackCollectionEntity;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionHydratorNotValidException;
use TestWebEngineer\TrackCollection\Entity\EntityInterface as TrackCollectionInterface;

/**
 * Class AbstractHydrator
 *
 * @package TestWebEngineer\TrackCollection\Hydrator
 */
abstract class AbstractHydrator implements HydratorInterface
{

    /**
     *
     * @var TrackHydratorInterface
     */
    protected $trackHydrator;

    /**
     * AbstractEntity constructor.
     * @param TrackHydratorInterface $trackHydrator
     */
    public function __construct(TrackHydratorInterface $trackHydrator)
    {
        $this->trackHydrator = $trackHydrator;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\TrackCollection\Hydrator\HydratorInterface::hydrate()
     */
    public function hydrate($data): TrackCollectionInterface
    {
        if (is_array($data)) {
            $trackCollection = new TrackCollectionEntity();
            foreach ($data as $trackData) {
                $track = $this->trackHydrator->hydrate($trackData);
                $trackCollection->add($track);
            }

            return $trackCollection;
        } else {
            throw new TrackCollectionHydratorNotValidException('not valid data');
        }
    }
}
