<?php
declare(strict_types = 1);
namespace TestWebEngineer\User\Repository;

/**
 * Common query for User
 */
trait QueryTrait
{

    /**
     * Select User MySql DataBase
     *
     * @return string
     */
    private function retrieveSqlQuerySelectTrackByUserId(): string
    {
        return 'SELECT u.id,u.name,u.email FROM user AS u WHERE u.id =:user_id';
    }
}
