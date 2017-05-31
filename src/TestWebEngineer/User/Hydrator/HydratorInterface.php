<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Hydrator;

use TestWebEngineer\User\Entity\EntityInterface as UserEntityInterface;

/**
 * Interface HydratorInterface
 *
 * @package TestWebEngineer\User\Hydrator
 */
interface HydratorInterface
{

    /**
     *
     * @param array $data
     * @return UserEntityInterface
     */
    public function hydrate(array $data): UserEntityInterface;
}
