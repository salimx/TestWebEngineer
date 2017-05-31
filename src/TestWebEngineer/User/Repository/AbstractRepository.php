<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Repository;

use TestWebEngineer\User\Hydrator\HydratorInterface as UserHydratorInterface;

/**
 * Class AbstractRepository
 *
 * @package TestWebEngineer\user\Repository
 */
abstract class AbstractRepository implements RepositoryInterface
{

    /**
     *
     * @var UserHydratorInterface
     */
    protected $hydrator;
}
