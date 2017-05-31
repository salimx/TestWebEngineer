<?php
declare(strict_types = 1);
namespace TestWebEngineer\TrackCollection\Tests\Hydrator;

use TestWebEngineer\TrackCollection\Hydrator\Hydrator as TrackCollectionHydrator;
use TestWebEngineer\Track\Hydrator\HydratorInterface as TrackHydratorInterface;
use PHPUnit\Framework\TestCase;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionHydratorNotValidException;

/**
 * Class TrackCollectionHydratorTest
 * @package TestWebEngineer\TrackCollection\Tests\Hydrator
 */
class TrackCollectionHydratorTest extends TestCase
{

    public function testInstanceWithTracks()
    {
        $data = [
            [
                'id'   => '8001',
                'name' => 'I Love it',
                'meta' => [
                    'duration' => '501',
                ],
            ],
            [
                'id'   => '8002',
                'name' => 'knockin on heaven door',
                'meta' => [
                    'duration' => '908',
                ],
            ],
        ];
        /**
         *
         * @var TrackHydratorInterface $trackHydrator
         */
        $trackHydrator = $this->createMock('TestWebEngineer\Track\Hydrator\HydratorInterface');

        $hydrator = new TrackCollectionHydrator($trackHydrator);

        /**
         *
         * @var TestWebEngineer\Track\Entity\EntityInterface $track
         */
        $track1 = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');
        $track1->expects($this->once())
               ->method('getId')
               ->willReturn(8001);

        $trackHydrator->expects($this->at(0))
                      ->method('hydrate')
                      ->with($data[0])
                      ->willReturn($track1);

        /**
         *
         * @var TestWebEngineer\Track\Entity\EntityInterface $track
         */
        $track2 = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');
        $track2->expects($this->once())
               ->method('getId')
               ->willReturn(8002);

        $trackHydrator->expects($this->at(1))
                      ->method('hydrate')
                      ->with($data[1])
                      ->willReturn($track2);


        $trackCollection = $hydrator->hydrate($data);

        $this->assertSame(
            [
                8001 => $track1,
                8002 => $track2,
            ],
            $trackCollection->getList()
        );
    }

    public function testTrackCollectionHydratorNotValidException()
    {
        $data = '';
        /**
         *
         * @var TrackHydratorInterface $trackHydrator
         */
        $trackHydrator = $this->createMock('TestWebEngineer\Track\Hydrator\HydratorInterface');

        $hydrator = new TrackCollectionHydrator($trackHydrator);
        $this->expectException(TrackCollectionHydratorNotValidException::class);
        $hydrator->hydrate($data);
    }
}
