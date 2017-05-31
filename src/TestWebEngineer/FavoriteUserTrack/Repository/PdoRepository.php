<?php
declare(strict_types = 1);
namespace TestWebEngineer\FavoriteUserTrack\Repository;

use TestWebEngineer\TrackCollection\Entity\EntityInterface as TrackCollectionEntityInterface;
use TestWebEngineer\TrackCollection\Hydrator\HydratorInterface as TrackCollectionHydratorInterface;
use TestWebEngineer\Track\Repository\QueryTrait as TrackQueryTrait;
use TestWebEngineer\FavoriteUserTrack\Repository\QueryTrait as FavoriteUserTrackQueryTrait;

/**
 * Class PdoRepository
 *
 * @package TestWebEngineer\FavoriteUserTrack\Repository
 */
class PdoRepository extends AbstractRepository
{
    use TrackQueryTrait;
    use FavoriteUserTrackQueryTrait;

    /**
     *
     * @var \PDO
     */
    protected $connection;

    /**
     * PdoRepository constructor.
     *
     * @param \PDO $connection
     * @param TrackCollectionHydratorInterface $trackCollectionHydrator
     */
    public function __construct(\PDO $connection, TrackCollectionHydratorInterface $trackCollectionHydrator)
    {
        $this->connection = $connection;
        $this->trackCollectionHydrator = $trackCollectionHydrator;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see RepositoryInterface::findFavoriteTrackByUserId
     */
    public function listFavoriteUserTrackByUserId(int $userId): TrackCollectionEntityInterface
    {
        $statement = $this->connection->prepare($this->retrieveSqlQuerySelectTrackListUserTrackByUserId());
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
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

    /**
     *
     * {@inheritdoc}
     *
     * @see RepositoryInterface::saveFavoriteTrackByUserId
     */
    public function saveFavoriteUserTrackByUserId(int $userId, TrackCollectionEntityInterface $userTracks) : bool
    {
        $tracks = $userTracks->getList();

        /*
         * No more track are in favorite
         */
        if (count($tracks) == 0) {
            $deleteQuery = $this->retrieveSqlQueryDelete();
            $deleteStatement = $this->connection->prepare($deleteQuery);
            $deleteStatement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
            $deleteStatement->execute();
            unset($deleteStatement);
            return false;
        }

        $this->connection->beginTransaction();
        $trackIds = [];
        foreach ($tracks as $trackId => $track) {
            $trackIds[] = $trackId;
            $this->insertSqlUserTrack($userId, $trackId);
        }

        $deleteQuery = $this->retrieveSqlQueryDeleteNotIn();

        // WARNING : PARAM INT ARRAY is not implemented
        $deleteQuery = str_replace(':track_ids', implode(',', $trackIds), $deleteQuery);

        $deleteStatement = $this->connection->prepare($deleteQuery);
        $deleteStatement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $deleteStatement->execute();
        unset($deleteStatement);

        $this->connection->commit();
        return true;
    }

    /**
     *
     * @param int $userId
     * @param int $trackId
     */
    private function insertSqlUserTrack(int $userId, int $trackId)
    {
        $insertStatement = $this->connection->prepare($this->retrieveSqlQueryInsert());
        $insertStatement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $insertStatement->bindValue(':track_id', $trackId, \PDO::PARAM_INT);
        $insertStatement->execute();
        unset($insertStatement);
    }
}
