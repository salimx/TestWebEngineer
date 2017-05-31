<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Tests\Entity;

use TestWebEngineer\Track\Exception\TrackNotFoundException;
use TestWebEngineer\Track\Repository\PdoRepository as TrackRepository;
use PHPUnit\Framework\TestCase;
use TestWebEngineer\Track\Repository\QueryTrait as TrackQueryTrait;

/**
 * Class PdoTrackRepositoryTest
 *
 * @package TestWebEngineer\Track\Tests\Entity
 */
class PdoTrackRepositoryTest extends TestCase
{
    use TrackQueryTrait;

    public function testFindByTrackId()
    {
        /**
         *
         * @var \PDO $connection
         */
        $connection = $this->createMock('\PDO');

        /**
         *
         * @var \TestWebEngineer\Track\Hydrator\HydratorInterface $hydrator
         */
        $hydrator = $this->createMock('\TestWebEngineer\Track\Hydrator\HydratorInterface');

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
         * @var TestWebEngineer\Track\Entity\EntityInterface $track
         */
        $track = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');

        $repository = new TrackRepository($connection, $hydrator, $trackCollectionHydrator);

        $connection->expects($this->at(0))
            ->method('prepare')
            ->with($this->retrieveSqlQuerySelectTrackByTrackId())
            ->willReturn($statement);

        $statement->expects($this->once())
            ->method('bindValue')
            ->with(':track_id', 8001, \PDO::PARAM_INT);

        $statement->expects($this->once())
            ->method('execute');

        $data = [
            'id' => '8001',
            'name' => 'I like to move it'
        ];
        $statement->expects($this->once())
            ->method('fetch')
            ->willReturn($data);

        $connection->expects($this->at(1))
            ->method('prepare')
            ->with($this->retrieveSqlQuerySelectTrackMetaByTrackId())
            ->willReturn($statementMeta);

        $statementMeta->expects($this->once())
            ->method('bindValue')
            ->with(':track_id', 8001, \PDO::PARAM_INT);

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
            'id' => '8001',
            'name' => 'I like to move it',
            'meta' => [
                $dataMeta[0]['meta_name'] => $dataMeta[0]['meta_value']
            ]
        ];
        $hydrator->expects($this->once())
            ->method('hydrate')
            ->with($dataRebuild)
            ->willReturn($track);

        $this->assertSame($track, $repository->findByTrackId(8001));
    }

    public function testTrackNotFoundException()
    {
        /**
         *
         * @var \PDO $connection
         */
        $connection = $this->createMock('\PDO');

        /**
         *
         * @var \TestWebEngineer\Track\Hydrator\HydratorInterface $hydrator
         */
        $hydrator = $this->createMock('\TestWebEngineer\Track\Hydrator\HydratorInterface');

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

        $repository = new TrackRepository($connection, $hydrator, $trackCollectionHydrator);

        $connection->expects($this->once())
            ->method('prepare')
            ->with($this->retrieveSqlQuerySelectTrackByTrackId())
            ->willReturn($statement);

        $statement->expects($this->once())
            ->method('bindValue')
            ->with(':track_id', 8001, \PDO::PARAM_INT);

        $statement->expects($this->once())
            ->method('execute');

        $data = false;

        $statement->expects($this->once())
            ->method('fetch')
            ->willReturn($data);
        $this->expectException(TrackNotFoundException::class);
        $repository->findByTrackId(8001);
    }

    public function testList()
    {
        /**
         *
         * @var \PDO $connection
         */
        $connection = $this->createMock('\PDO');

        /**
         *
         * @var \TestWebEngineer\Track\Hydrator\HydratorInterface $hydrator
         */
        $hydrator = $this->createMock('\TestWebEngineer\Track\Hydrator\HydratorInterface');

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

        $repository = new TrackRepository($connection, $hydrator, $trackCollectionHydrator);

        $connection->expects($this->at(0))
            ->method('prepare')
            ->with($this->retrieveSqlQuerySelectTrack())
            ->willReturn($statement);

        $statement->expects($this->once())
            ->method('execute');

        $data = [
            [
                'id' => '8001',
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
            ->with(':track_id', 8001, \PDO::PARAM_INT);

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
                'id' => '8001',
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

        $this->assertSame($trackCollection, $repository->list());
    }
}
