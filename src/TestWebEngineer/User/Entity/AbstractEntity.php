<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Entity;

/**
 * Class AbstractEntity
 *
 * @package TestWebEngineer\User\Entity
 */
abstract class AbstractEntity implements EntityInterface
{

    /**
     * User id
     *
     * @var int
     */
    private $id;

    /**
     * User name
     *
     * @var string
     */
    private $name;

    /**
     * User Email
     *
     * @var string
     */
    private $email;

    /**
     * AbstractEntity constructor.
     *
     * @param int $id
     * @param string $name
     * @param string $email
     */
    public function __construct(int $id, string $name, string $email)
    {
        $this->setId($id)
            ->setName($name)
            ->setEmail($email);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\User\Entity\EntityInterface::getId()
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\User\Entity\EntityInterface::getName()
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\User\Entity\EntityInterface::getEmail()
     */
    public function getEmail(): string
    {
        return $this->email;
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
            'email' => $this->getEmail()
        ];
    }

    /**
     *
     * @param int $id
     * @return \TestWebEngineer\User\Entity\AbstractEntity
     */
    protected function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     *
     * @param string $name
     * @return \TestWebEngineer\User\Entity\AbstractEntity
     */
    protected function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *
     * @param string $email
     * @return \TestWebEngineer\User\Entity\AbstractEntity
     */
    protected function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }
}
