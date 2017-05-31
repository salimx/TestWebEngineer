<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Entity;

/**
 * Interface EntityInterface
 *
 * @package TestWebEngineer\Track\Entity
 */
interface EntityInterface extends \JsonSerializable
{

    /**
     * retrieve Track id
     *
     * @return int
     */
    public function getId(): int;

    /**
     * retrieve Track name
     *
     * @return string
     */
    public function getName(): string;

    /**
     * retrieve Track data
     *
     * @return array
     */
    public function getMeta(): array;
}
