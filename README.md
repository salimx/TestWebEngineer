TestWebEngineer
===============

This a easy API Rest from scratch (without external libraries).
The purpose are to retrieve, add and delete track from a favorite list 
of tracks.

A user is defined by id, and have name and email.
A track is defined by id, and have name, and meta contained duration. 
Like that we can customised easily the track data without changing the
code.

Installation
------------
   
1.  Load the mysql dump on your DataBase server
    dump is one the data/dumpl.sql

2.  Create a user and allow  SELECT, INSERT, UPDATE, DELETE 
    privileges on the database for the user Created

3.  The document root is web directory and the index file is web/app.php

4.  Change the app/config/config.ini and configure your database access
    on database section

5.  The default root are :

    retrieve all tracks
    GET app.php/track/
    
    retrieve one track by trackId
    GET app.php/track/[trackID]
    
    retrieve one user by userId
    GET app.php/user/[userId]
    
    retrieve favorite tracks by UserId
    GET app.php/favorite/user/[userId]/track/
    
    modify the favorite tracks by UserId, PUT tracks
    PUT app.php/favorite/user/[userId]/track/
    
    add a track to the favorite by userId, PUT track
    POST app.php/favorite/user/[userId]/track
    
    delete a track to the favorite by userId and trackId
    DELETE app.php/favorite/user/[userId]/track/[trackID]
    
    they can be changed on the app/config/routing.ini
    
6.  The meta information from track could be changed on 
    app/config/config.ini track_meta_type section
    int, bool and string could be set
    eg : duration = int
         rate = int
         style = string
    
## Tests

### Technical

The unit testing tool is PHPUnit.

### Issue

The API access is not managed, neither by token, or by checksum access.

There is a bug on the phpunit when we mock a PDO connection. 
(see src/TestWebEngineer/FavoriteUserTrack/Tests/Repository/
PdoFavoriteUserTrackRepositoryTest.php)
