<?php
declare(strict_types = 1);
namespace TestWebEngineer\TrackCollection\Tests\Entity;

use TestWebEngineer\Track\Entity\EntityInterface as TrackEntityInterface;
use TestWebEngineer\TrackCollection\Entity\Entity as TrackCollectionEntity;
use PHPUnit\Framework\TestCase;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionDuplicateTrackException;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionTrackNotFoundException;

/**
 * Class TrackCollectionEntityTest
 * @package TestWebEngineer\TrackCollection\Tests\Entity
 */
class TrackCollectionEntityTest extends TestCase
{

    public function testRemoveTracks()
    {
        /**
         *
         * @var TrackEntityInterface $track
         */
        $track = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');
        $trackCollection = new TrackCollectionEntity();

        $track->expects($this->exactly(2))
              ->method('getId')
              ->willReturn(8007);
        $trackCollection->add($track);
        $trackCollection->remove($track);
        $this->assertSame([], $trackCollection->getList());
    }

    public function testRemoveAll()
    {
        /**
         *
         * @var TrackEntityInterface $track
         */
        $track = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');
        $trackCollection = new TrackCollectionEntity();

        $track->expects($this->once())
              ->method('getId')
              ->willReturn(8007);
        $trackCollection->add($track);
        $trackCollection->removeAll();
        $this->assertSame([], $trackCollection->getList());
    }

    public function testAddTracks()
    {
        /**
         *
         * @var TrackEntityInterface $track
         */
        $track = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');
        $trackCollection = new TrackCollectionEntity();

        $track->expects($this->once())
              ->method('getId')
              ->willReturn(8008);

        $trackCollection->add($track);
        $this->assertSame(
            [
                8008 => $track,
            ],
            $trackCollection->getList()
        );
    }

    public function testTrackCollectionDuplicateTrackException()
    {
        /**
         *
         * @var TrackEntityInterface $track
         */
        $track = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');
        $trackCollection = new TrackCollectionEntity();

        $track->expects($this->exactly(2))
              ->method('getId')
              ->willReturn(8008);

        $trackCollection->add($track);
        $this->expectException(TrackCollectionDuplicateTrackException::class);
        $trackCollection->add($track);
    }

    public function testTrackCollectionTrackNotFoundException()
    {
        /**
         *
         * @var TrackEntityInterface $track
         */
        $track = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');
        $trackCollection = new TrackCollectionEntity();

        $track->expects($this->once())
              ->method('getId')
              ->willReturn(8008);

        $this->expectException(TrackCollectionTrackNotFoundException::class);
        $trackCollection->remove($track);
    }

    public function testJsonSerialize()
    {
        /**
         *
         * @var TrackEntityInterface $track
         */
        $track = $this->createMock('TestWebEngineer\Track\Entity\EntityInterface');
        $trackCollection = new TrackCollectionEntity();

        $track->expects($this->once())
              ->method('getId')
              ->willReturn(8000);
        $trackCollection->add($track);
        $json = '{"8000":{"id":"8000","name":"Master of puppet","meta":{"duration":"565"}}}';
        $data = [
            'id'   => '8000',
            'name' => 'Master of puppet',
            'meta' => [
                'duration' => '565',
            ],
        ];

        $track->expects($this->once())
              ->method('jsonSerialize')
              ->willReturn($data);
        $this->assertSame($json, json_encode($trackCollection));
    }
}
