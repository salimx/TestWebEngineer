<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Repository;

use TestWebEngineer\User\Entity\EntityInterface as UserEntityInterface;
use TestWebEngineer\User\Exception\UserNotFoundException;

/**
 * Interface RepositoryInterface
 *
 * @package TestWebEngineer\user\Repository
 */
interface RepositoryInterface
{

    /**
     * find a user by userId
     *
     * @param int $userId
     * @return UserEntityInterface
     * @throws UserNotFoundException
     */
    public function findByUserId(int $userId): UserEntityInterface;
}
