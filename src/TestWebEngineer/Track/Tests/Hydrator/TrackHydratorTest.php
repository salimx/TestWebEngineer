<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Tests\Hydrator;

use TestWebEngineer\Track\Hydrator\Hydrator as TrackHydrator;
use PHPUnit\Framework\TestCase;
use TestWebEngineer\Track\Exception\TrackHydratorInvalidArgumentException;
use TestWebEngineer\Track\Exception\MetaTypeNotDefinedException;

/**
 * Class TrackHydratorTest
 * @package TestWebEngineer\Track\Tests\Hydrator
 */
class TrackHydratorTest extends TestCase
{

    public function testInstance()
    {
        $data = [
            'id'   => '8002',
            'name' => 'Smoke on the water',
            'meta' => [
                'duration' => '6767',
                'enable'   => '0',
                'origin'   => 'USA',
            ],
        ];
        $hydrator = new TrackHydrator(
            [
                'duration' => 'int',
                'enable'   => 'bool',
                'origin'   => 'string',
            ]
        );
        $track = $hydrator->hydrate($data);

        $this->assertSame((int)$data['id'], $track->getId());
        $this->assertSame($data['name'], $track->getName());
        $this->assertSame((int)$data['meta']['duration'], $track->getMeta()['duration']);
        $this->assertSame((bool)$data['meta']['enable'], $track->getMeta()['enable']);
        $this->assertSame($data['meta']['origin'], $track->getMeta()['origin']);
    }

    public function testMetaTypeNotDefinedException()
    {
        $data = [
            'id'   => '8003',
            'name' => 'People are strange',
            'meta' => [
                'duration' => '778',
                'year'     => '1967',
            ],
        ];
        $hydrator = new TrackHydrator(
            [
                'duration' => 'int',
            ]
        );
        $this->expectException(MetaTypeNotDefinedException::class);
        $hydrator->hydrate($data);
    }

    public function testTrackHydratorInvalidArgumentException()
    {
        $data = ['id' => '8004'];
        $hydrator = new TrackHydrator(
            [
                'duration' => 'int',
            ]
        );
        $this->expectException(TrackHydratorInvalidArgumentException::class);
        $hydrator->hydrate($data);
    }
}
