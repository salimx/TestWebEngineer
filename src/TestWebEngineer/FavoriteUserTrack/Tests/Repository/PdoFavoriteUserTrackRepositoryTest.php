<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Tests\Entity;

use TestWebEngineer\FavoriteUserTrack\Repository\PdoRepository as FavoriteUserTrackRepository;
use PHPUnit\Framework\TestCase;
use TestWebEngineer\Track\Repository\QueryTrait as TrackQueryTrait;
use TestWebEngineer\FavoriteUserTrack\Repository\QueryTrait as FavoriteUserTrackQueryTrait;

/**
 * Class PdoTrackRepositoryTest
 *
 * @package TestWebEngineer\Track\Tests\Entity
 */
class PdoFavoriteUserTrackRepositoryTest extends TestCase
{
    use TrackQueryTrait;
    use FavoriteUserTrackQueryTrait;

    public function testListTrackByUserId()
    {
        /**
         *
         * @var \PDO $connection
         */
        $connection = $this->createMock('\PDO');

        /**
         *
         * @var \TestWebEngineer\Track\Hydrator\HydratorInterface $trackCollectionHydrator
         */
        $trackCollectionHydrator = $this->createMock('\TestWebEngineer\TrackCollection\Hydrator\HydratorInterface');

        /**
         *
         * @var \PDOStatement $statement
         */
        $statement = $this->createMock('\PDOStatement');

        /**
         *
         * @var \PDOStatement $statementMeta
         */
        $statementMeta = $this->createMock('\PDOStatement');

        /**
         *
         * @var TestWebEngineer\TrackCollection\Entity\EntityInterface $trackCollection
         */
        $trackCollection = $this->createMock('TestWebEngineer\TrackCollection\Entity\EntityInterface');

        $repository = new FavoriteUserTrackRepository($connection, $trackCollectionHydrator);

        $connection->expects($this->at(0))
            ->method('prepare')
            ->with($this->retrieveSqlQuerySelectTrackListUserTrackByUserId())
            ->willReturn($statement);

        $statement->expects($this->once())
            ->method('bindValue')
            ->with(':user_id', 8000, \PDO::PARAM_INT);

        $statement->expects($this->once())
            ->method('execute');

        $data = [
            [
                'id' => '8009',
                'name' => 'I like to move it'
            ]
        ];
        $statement->expects($this->once())
            ->method('fetchAll')
            ->willReturn($data);

        $connection->expects($this->at(1))
            ->method('prepare')
            ->with($this->retrieveSqlQuerySelectTrackMetaByTrackId())
            ->willReturn($statementMeta);

        $statementMeta->expects($this->once())
            ->method('bindValue')
            ->with(':track_id', 8009, \PDO::PARAM_INT);

        $statementMeta->expects($this->once())
            ->method('execute');

        $dataMeta = [
            [
                'meta_name' => 'duration',
                'meta_value' => '901'
            ]
        ];
        $statementMeta->expects($this->once())
            ->method('fetchAll')
            ->willReturn($dataMeta);

        $dataRebuild = [
            [
                'id' => '8009',
                'name' => 'I like to move it',
                'meta' => [
                    $dataMeta[0]['meta_name'] => $dataMeta[0]['meta_value']
                ]
            ]
        ];

        $trackCollectionHydrator->expects($this->once())
            ->method('hydrate')
            ->with($dataRebuild)
            ->willReturn($trackCollection);

        $this->assertSame($trackCollection, $repository->listFavoriteUserTrackByUserId(8000));
    }

    public function testSaveFavoriteUserTrackByUserIdEmptyList()
    {
        /**
         *
         * @var \PDO $connection
         */
        $connection = $this->createMock('\PDO');

        /**
         *
         * @var \TestWebEngineer\Track\Hydrator\HydratorInterface $trackCollectionHydrator
         */
        $trackCollectionHydrator = $this->createMock('\TestWebEngineer\TrackCollection\Hydrator\HydratorInterface');

        /**
         *
         * @var \PDOStatement $deleteStatement
         */
        $deleteStatement = $this->createMock('\PDOStatement');

        /**
         *
         * @var TestWebEngineer\TrackCollection\Entity\EntityInterface $trackCollection
         */
        $trackCollection = $this->createMock('TestWebEngineer\TrackCollection\Entity\EntityInterface');

        $repository = new FavoriteUserTrackRepository($connection, $trackCollectionHydrator);

        $trackCollection->expects($this->once())
            ->method('getList')
            ->willReturn([]);

        $connection->expects($this->once())
            ->method('prepare')
            ->with($this->retrieveSqlQueryDelete())
            ->willReturn($deleteStatement);

        $deleteStatement->expects($this->once())
            ->method('bindValue')
            ->with(':user_id', 8000, \PDO::PARAM_INT);

        $deleteStatement->expects($this->once())
            ->method('execute');

        $this->assertSame(false, $repository->saveFavoriteUserTrackByUserId(8000, $trackCollection));
    }

    public function testSaveFavoriteUserTrackByUserId()
    {
        /**
         *
         * @var \PDO $connection
         */
        $connection = $this->createMock('\PDO');

        /**
         *
         * @var \TestWebEngineer\Track\Hydrator\HydratorInterface $trackCollectionHydrator
         */
        $trackCollectionHydrator = $this->createMock('\TestWebEngineer\TrackCollection\Hydrator\HydratorInterface');

        /**
         *
         * @var \PDOStatement $deleteStatement
         */
        $deleteStatement = $this->createMock('\PDOStatement');

        /**
         *
         * @var \PDOStatement $insertStatement
         */
        $insertStatement = $this->createMock('\PDOStatement');

        /**
         *
         * @var TestWebEngineer\TrackCollection\Entity\EntityInterface $trackCollection
         */
        $trackCollection = $this->createMock('TestWebEngineer\TrackCollection\Entity\EntityInterface');

        /**
         *
         * @var TestWebEngineer\Track\Entity\EntityInterface $track
         */
        $track = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');

        $repository = new FavoriteUserTrackRepository($connection, $trackCollectionHydrator);

        $trackCollection->expects($this->once())
            ->method('getList')
            ->willReturn([
            8009 => $track
        ]);

        // WARNING : bug phpunit OR PDO, but beginTransaction launch the first prepare... we have to start index at 1
        $connection->expects($this->once())
            ->method('beginTransaction');

        $connection->expects($this->at(1)) // 1 instead of 0.... could be one day corrected?
            ->method('prepare')
            ->with($this->retrieveSqlQueryInsert())
            ->willReturn($insertStatement);

        $insertStatement->expects($this->at(0))
            ->method('bindValue')
            ->with(':user_id', 8000, \PDO::PARAM_INT);

        $insertStatement->expects($this->at(1))
            ->method('bindValue')
            ->with(':track_id', 8009, \PDO::PARAM_INT);

        $insertStatement->expects($this->once())
            ->method('execute');


        $deleteQuery = $this->retrieveSqlQueryDeleteNotIn();
        $deleteQuery = str_replace(':track_ids', implode(',', [8009]), $deleteQuery);

        $connection->expects($this->at(2))
            ->method('prepare')
            ->with($deleteQuery)
            ->willReturn($deleteStatement);

        $deleteStatement->expects($this->once())
            ->method('bindValue')
            ->with(':user_id', 8000, \PDO::PARAM_INT);

        $deleteStatement->expects($this->once())
            ->method('execute');

        $connection->expects($this->once())
            ->method('commit');

        $this->assertSame(true, $repository->saveFavoriteUserTrackByUserId(8000, $trackCollection));
    }
}
