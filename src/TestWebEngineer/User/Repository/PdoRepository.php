<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Repository;

use TestWebEngineer\User\Entity\EntityInterface as UserEntityInterface;
use TestWebEngineer\User\Exception\UserNotFoundException;
use TestWebEngineer\User\Hydrator\HydratorInterface as UserHydratorInterface;
use TestWebEngineer\User\Repository\RepositoryInterface as UserRepositoryInterface;
use TestWebEngineer\User\Repository\QueryTrait as UserQueryTrait;

/**
 * Class DbalUserRepository
 *
 * @package TestWebEngineer\User\Repository
 */
class PdoRepository extends AbstractRepository
{
    use UserQueryTrait;

    /**
     *
     * @var \PDO
     */
    protected $connection;

    /**
     * PDOUserRepository constructor.
     *
     * @param \PDO $connection
     * @param UserHydratorInterface $hydrator
     */
    public function __construct(\PDO $connection, UserHydratorInterface $hydrator)
    {
        $this->connection = $connection;
        $this->hydrator = $hydrator;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see UserRepositoryInterface::findById
     */
    public function findByUserId(int $userId): UserEntityInterface
    {
        $statement = $this->connection->prepare($this->retrieveSqlQuerySelectTrackByUserId());
        $statement->bindValue(':user_id', $userId, \PDO::PARAM_INT);
        $statement->execute();
        $result = $statement->fetch();

        if (! $result) {
            unset($result);
            unset($statement);

            throw new UserNotFoundException();
        }

        $user = $this->hydrator->hydrate($result);

        unset($result);
        unset($statement);

        return $user;
    }
}
