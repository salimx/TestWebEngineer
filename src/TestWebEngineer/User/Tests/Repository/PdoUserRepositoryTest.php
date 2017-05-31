<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Tests\Entity;

use TestWebEngineer\User\Exception\UserNotFoundException;
use TestWebEngineer\User\Repository\PdoRepository as UserRepository;
use PHPUnit\Framework\TestCase;
use TestWebEngineer\User\Repository\QueryTrait as UserQueryTrait;

/**
 * Class PdoUserRepositoryTest
 * @package TestWebEngineer\User\Tests\Entity
 */
class PdoUserRepositoryTest extends TestCase
{
    use UserQueryTrait;

    public function testFindByUserId()
    {
        /**
         *
         * @var \PDO $connection
         */
        $connection = $this->createMock('\PDO');

        /**
         *
         * @var \TestWebEngineer\User\Hydrator\HydratorInterface $hydrator
         */
        $hydrator = $this->createMock('\TestWebEngineer\User\Hydrator\HydratorInterface');

        /**
         *
         * @var TestWebEngineer\User\Entity\EntityInterface $user
         */
        $user = $this->createMock('TestWebEngineer\User\Entity\EntityInterface');

        /**
         *
         * @var \PDOStatement $statement
         */
        $statement = $this->createMock('\PDOStatement');

        $repository = new UserRepository($connection, $hydrator);

        $connection->expects($this->once())
                   ->method('prepare')
                   ->with($this->retrieveSqlQuerySelectTrackByUserId())
                   ->willReturn($statement);

        $statement->expects($this->once())
                  ->method('bindValue')
                  ->with(':user_id', 8001, \PDO::PARAM_INT);

        $statement->expects($this->once())
                  ->method('execute');

        $data = ['id' => '8001', 'name' => 'Jone', 'email' => 'Snow@got.com'];


        $statement->expects($this->once())
                  ->method('fetch')
                  ->willReturn($data);


        $hydrator->expects($this->once())
                 ->method('hydrate')
                 ->with($data)
                 ->willReturn($user);

        $this->assertSame($user, $repository->findByUserId(8001));

    }

    public function testUserNotFoundException()
    {
        /**
         *
         * @var \PDO $connection
         */
        $connection = $this->createMock('\PDO');

        /**
         *
         * @var \TestWebEngineer\User\Hydrator\HydratorInterface $hydrator
         */
        $hydrator = $this->createMock('\TestWebEngineer\User\Hydrator\HydratorInterface');

        /**
         *
         * @var \PDOStatement $statement
         */
        $statement = $this->createMock('\PDOStatement');

        $repository = new UserRepository($connection, $hydrator);

        $connection->expects($this->once())
                   ->method('prepare')
                   ->with($this->retrieveSqlQuerySelectTrackByUserId())
                   ->willReturn($statement);

        $statement->expects($this->once())
                  ->method('bindValue')
                  ->with(':user_id', 8001, \PDO::PARAM_INT);

        $statement->expects($this->once())
                  ->method('execute');

        $data = false;

        $statement->expects($this->once())
                  ->method('fetch')
                  ->willReturn($data);
        $this->expectException(UserNotFoundException::class);
        $repository->findByUserId(8001);

    }

}
