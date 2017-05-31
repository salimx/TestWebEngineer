<?php
declare(strict_types = 1);
namespace TestWebEngineer\FavoriteUserTrack\Repository;

/**
 * Common query for FavoriteUserTrack
 */
trait QueryTrait
{

    /**
     *
     * @return string
     */
    private function retrieveSqlQueryInsert(): string
    {
        return 'INSERT IGNORE INTO favorite_user_tracks
                (`user_id`,`track_id`)
                VALUES (:user_id,:track_id)';
    }
    /**
     *
     * @return string
     */
    private function retrieveSqlQueryDelete(): string
    {
        return 'DELETE 
            FROM favorite_user_tracks 
            WHERE user_id=:user_id';
    }

    /**
     *
     * @return string
     */
    private function retrieveSqlQueryDeleteNotIn(): string
    {
        return 'DELETE 
            FROM favorite_user_tracks
            WHERE user_id=:user_id AND track_id NOT IN(:track_ids)';
    }

    /**
     *
     * @return string
     */
    private function retrieveSqlQuerySelectTrackListUserTrackByUserId(): string
    {
        return 'SELECT t.id,t.name 
            FROM favorite_user_tracks AS ut 
            JOIN track t ON ut.track_id=t.id 
            WHERE ut.user_id=:user_id';
    }
}
