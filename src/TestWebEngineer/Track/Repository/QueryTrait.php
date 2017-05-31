<?php
declare(strict_types = 1);
namespace TestWebEngineer\Track\Repository;

/**
 * Common query for Track
 */
trait QueryTrait
{

    /**
     *
     * @return string
     */
    private function retrieveSqlQuerySelectTrackByTrackId(): string
    {
        return 'SELECT t.id,t.name FROM track AS t WHERE t.id=:track_id';
    }
    /**
     *
     * @return string
     */
    private function retrieveSqlQuerySelectTrack(): string
    {
        return 'SELECT t.id,t.name FROM track AS t';
    }

    /**
     *
     * @return string
     */
    private function retrieveSqlQuerySelectTrackMetaByTrackId(): string
    {
        return 'SELECT tm.meta_name,tm.meta_value FROM track__meta AS tm WHERE tm.track_id=:track_id';
    }
}
