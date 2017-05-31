<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Repository;

use TestWebEngineer\Track\Entity\EntityInterface as TrackEntityInterface;
use TestWebEngineer\Track\Exception\TrackNotFoundException;
use TestWebEngineer\Track\Hydrator\HydratorInterface as TrackHydratorInterface;
use TestWebEngineer\Track\Repository\RepositoryInterface as TrackRepositoryInterface;
use TestWebEngineer\Track\Repository\QueryTrait as TrackQueryTrait;
use TestWebEngineer\TrackCollection\Entity\EntityInterface as TrackCollectionEntityInterface;
use TestWebEngineer\TrackCollection\Hydrator\HydratorInterface as TrackCollectionHydratorInterface;

/**
 * Class PdoRepository
 *
 * @package TestWebEngineer\Track\Repository
 */
class PdoRepository extends AbstractRepository
{
    use TrackQueryTrait;

    /**
     *
     * @var \PDO
     */
    private $connection;

    /**
     * PdoRepository constructor.
     *
     * @param \PDO $connection
     * @param TrackHydratorInterface $hydrator
     * @param TrackCollectionHydratorInterface $trackCollectionHydrator
     */
    public function __construct(\PDO $connection, TrackHydratorInterface $hydrator, TrackCollectionHydratorInterface $trackCollectionHydrator)
    {
        $this->trackCollectionHydrator = $trackCollectionHydrator;
        $this->connection = $connection;
        $this->hydrator = $hydrator;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see TrackRepositoryInterface::findByTrackId
     */
    public function findByTrackId(int $id): TrackEntityInterface
    {
        $statement = $this->connection->prepare($this->retrieveSqlQuerySelectTrackByTrackId());
        $statement->bindValue(':track_id', $id, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch();

        if (! $result) {
            unset($result);
            unset($statement);

            throw new TrackNotFoundException();
        }

        $statementMeta = $this->connection->prepare($this->retrieveSqlQuerySelectTrackMetaByTrackId());
        $statementMeta->bindValue(':track_id', $id, \PDO::PARAM_INT);
        $statementMeta->execute();
        $resultMeta = $statementMeta->fetchAll();
        $result['meta'] = [];
        foreach ($resultMeta as $meta) {
            $result['meta'][$meta['meta_name']] = $meta['meta_value'];
        }

        $value = $this->hydrator->hydrate($result);

        unset($result);
        unset($statement);
        unset($resultMeta);
        unset($statementMeta);

        return $value;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see RepositoryInterface::list
     */
    public function list(): TrackCollectionEntityInterface
    {
        $statement = $this->connection->prepare($this->retrieveSqlQuerySelectTrack());
        $statement->execute();
        $result = $statement->fetchAll();
        foreach($result as &$trackData){
            $statementMeta = $this->connection->prepare($this->retrieveSqlQuerySelectTrackMetaByTrackId());
            $statementMeta->bindValue(':track_id', $trackData['id'], \PDO::PARAM_INT);
            $statementMeta->execute();
            $resultMeta = $statementMeta->fetchAll();
            $trackData['meta'] = [];
            foreach ($resultMeta as $meta) {
                $trackData['meta'][$meta['meta_name']] = $meta['meta_value'];
            }
        }
        unset($statement);
        $trackCollection = $this->trackCollectionHydrator->hydrate($result);
        unset($result);

        return $trackCollection;
    }
}
