<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Tests\Hydrator;

use TestWebEngineer\User\Hydrator\Hydrator as UserHydrator;
use PHPUnit\Framework\TestCase;
use TestWebEngineer\User\Exception\UserHydratorInvalidArgumentException;

/**
 * Class UserHydratorTest
 * @package TestWebEngineer\User\Tests\Hydrator
 */
class UserHydratorTest extends TestCase
{

    public function testInstance()
    {
        $data = [
            'id'    => '8004',
            'name'  => 'John',
            'email' => 'John@doe.com',
        ];
        $hydrator = new UserHydrator();
        $user = $hydrator->hydrate($data);

        $this->assertSame((int)$data['id'], $user->getId());
        $this->assertSame($data['name'], $user->getName());
        $this->assertSame($data['email'], $user->getEmail());
    }

    public function testInstanceException()
    {
        $data = ['name' => 'toto'];
        $hydrator = new UserHydrator();

        $this->expectException(UserHydratorInvalidArgumentException::class);
        $hydrator->hydrate($data);
    }
}
