<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Tests\Entity;

use TestWebEngineer\User\Entity\Entity as UserEntity;
use PHPUnit\Framework\TestCase;

/**
 * Class UserEntityTest
 * @package TestWebEngineer\User\Tests\Entity
 */
class UserEntityTest extends TestCase
{

    public function testInstance()
    {
        $id = 8003;
        $name = 'Jane';
        $email = 'jane@doe.com';
        $user = new UserEntity($id, $name, $email);

        $this->assertSame($id, $user->getId());
        $this->assertSame($name, $user->getName());
        $this->assertSame($email, $user->getEmail());
    }

    public function testJsonSerialize()
    {
        $id = 8003;
        $name = 'Jane';
        $email = 'jane@doe.com';
        $json = '{"id":'.$id.',"name":"'.$name.'","email":"'.$email.'"}';
        $user = new UserEntity($id, $name, $email);
        $this->assertSame($json, json_encode($user));
    }
}
