<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Hydrator;

use TestWebEngineer\User\Entity\Entity as UserEntity;
use TestWebEngineer\User\Entity\EntityInterface as UserEntityInterface;
use TestWebEngineer\User\Exception\UserHydratorInvalidArgumentException;

/**
 * Class AbstractHydrator
 *
 * @package TestWebEngineer\User\Hydrator
 */
abstract class AbstractHydrator implements HydratorInterface
{

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\User\Hydrator\HydratorInterface::hydrate()
     */
    public function hydrate(array $data): UserEntityInterface
    {
        if (!isset($data['id']) || !isset($data['name']) || !isset($data['email'])) {
            throw new UserHydratorInvalidArgumentException('not valid');
        }
        $entity = new UserEntity((int)$data['id'], $data['name'], $data['email']);

        return $entity;

    }
}
