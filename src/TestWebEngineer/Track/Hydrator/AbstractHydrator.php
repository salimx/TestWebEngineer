<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Hydrator;

use TestWebEngineer\Track\Entity\Entity as TrackEntity;
use TestWebEngineer\Track\Entity\EntityInterface as TrackEntityInterface;
use TestWebEngineer\Track\Exception\MetaTypeNotDefinedException;
use TestWebEngineer\Track\Exception\TrackHydratorInvalidArgumentException;

/**
 * Class AbstractHydrator
 *
 * @package TestWebEngineer\Track\Hydrator
 */
abstract class AbstractHydrator implements HydratorInterface
{

    /**
     * for additional meta data
     * configure the types (bool, int or string)
     *
     * @var array
     */
    private $metaTypes;

    /**
     * AbstractHydrator constructor.
     * @param array $metaTypes
     */
    public function __construct($metaTypes = [])
    {
        $this->metaTypes = $metaTypes;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \TestWebEngineer\Track\Hydrator\HydratorInterface::hydrate()
     */
    public function hydrate(array $data): TrackEntityInterface
    {
        if (!is_array($data) || !isset($data['id']) || !isset($data['name']) || !isset($data['meta'])) {
            throw new TrackHydratorInvalidArgumentException('not a valid Track array');
        }
        $meta = [];
        foreach ($data['meta'] as $key => $value) {
            if (!isset($this->metaTypes[$key])) {
                throw new MetaTypeNotDefinedException('not a valid meta type : '.$key);
            }
            $meta[$key] = $this->transform($this->metaTypes[$key], $value);
        }
        $entity = new TrackEntity((int)$data['id'], $data['name'], $meta);

        return $entity;

    }

    /**
     *
     * @param string $type
     * @param string $value
     * @return number|boolean|string
     */
    private function transform(string $type, $value)
    {
        switch ($type) {
            case 'int':
                return (int)$value;
            case 'bool':
                return (bool)$value;
        }

        return $value;
    }
}
