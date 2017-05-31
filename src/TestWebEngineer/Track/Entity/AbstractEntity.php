<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Entity;

/**
 * Class AbstractEntity
 *
 * @package TestWebEngineer\Track\Entity
 */
abstract class AbstractEntity implements EntityInterface
{

    /**
     * Track id
     *
     * @var int
     */
    private $id;

    /**
     * Track name
     *
     * @var string
     */
    private $name;

    /**
     * Track meta
     *
     * @var array
     */
    private $meta;

    /**
     * AbstractEntity constructor.
     *
     * @param int $id
     * @param string $name
     * @param array $meta
     */
    public function __construct(int $id, string $name, array $meta = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->meta = $meta;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\Track\Entity\EntityInterface::getId()
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\Track\Entity\EntityInterface::getName()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\Track\Entity\EntityInterface::getMeta()
     */
    public function getMeta(): array
    {
        return $this->meta;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see JsonSerializable::jsonSerialize()
     */
    function jsonSerialize()
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'meta' => $this->getMeta()
        ];
    }
}
