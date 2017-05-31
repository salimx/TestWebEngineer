<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Tests\Entity;

use TestWebEngineer\Track\Entity\Entity as TrackEntity;
use PHPUnit\Framework\TestCase;

/**
 * Class TrackEntityTest
 * @package TestWebEngineer\Track\Tests\Entity
 */
class TrackEntityTest extends TestCase
{

    public function testInstance()
    {
        $id = 8001;
        $name = 'Passenger';
        $duration = 987;
        $artist = 'David Bowie';
        $track = new TrackEntity($id, $name, ['duration' => $duration, 'artist' => $artist]);

        $this->assertSame($id, $track->getId());
        $this->assertSame($name, $track->getName());
        $this->assertSame($duration, $track->getMeta()['duration']);
        $this->assertSame($artist, $track->getMeta()['artist']);
    }

    public function testJsonSerialize()
    {
        $id = 8002;
        $name = 'Let it go';
        $duration = 456;
        $json = '{"id":'.$id.',"name":"'.$name.'","meta":{"duration":'.$duration.'}}';
        $track = new TrackEntity($id, $name, ['duration' => $duration]);
        $this->assertSame($json, json_encode($track));
    }
}
