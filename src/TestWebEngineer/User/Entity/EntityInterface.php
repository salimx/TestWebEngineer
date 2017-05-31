<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Entity;

/**
 * Interface EntityInterface
 *
 * @package TestWebEngineer\User\Entity
 */
interface EntityInterface extends \JsonSerializable
{

    /**
     * retrieve User id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * retrieve User name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * retrieve User email
     *
     * @return string
     */
    public function getEmail(): string;
}
