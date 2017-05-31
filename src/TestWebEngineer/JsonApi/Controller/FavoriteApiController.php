<?php
declare(strict_types = 1);
namespace TestWebEngineer\JsonApi\Controller;

use TestWebEngineer\SimpleRouter\Response;
use TestWebEngineer\SimpleRouter\ResponseInterface;
use TestWebEngineer\Track\Hydrator\Hydrator as TrackHydrator;
use TestWebEngineer\User\Hydrator\Hydrator as UserHydrator;
use TestWebEngineer\TrackCollection\Hydrator\Hydrator as TrackCollectionHydrator;
use TestWebEngineer\Track\Repository\PdoRepository as TrackRepository;
use TestWebEngineer\FavoriteUserTrack\Repository\PdoRepository as FavoriteUserTrackRepository;
use TestWebEngineer\User\Repository\PdoRepository as UserRepository;
use TestWebEngineer\User\Exception\UserNotFoundException;
use TestWebEngineer\Track\Exception\TrackNotFoundException;
use TestWebEngineer\SimpleRouter\RequestInterface;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionDuplicateTrackException;
use TestWebEngineer\TrackCollection\Exception\TrackCollectionTrackNotFoundException;
use TestWebEngineer\Track\Exception\TrackHydratorInvalidArgumentException;

class FavoriteApiController extends AbstractApiController
{

    /**
     *
     * @var FavoriteUserTrackRepository
     */
    private $favoriteTrackUserRepository;

    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     *
     * @var TrackRepository
     */
    private $trackRepository;

    /**
     *
     * @var TrackHydrator
     */
    private $trackHydrator;

    /**
     * FavoriteApiController constructor.
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $dsn = $configs['database']['driver'].':host='.$configs['database']['host'].
               ((!empty($configs['database']['port'])) ? (';port='.$configs['database']['port']) : '').
               ';dbname='.$configs['database']['schema'];

        $pdoConnection = new \PDO($dsn, $configs['database']['username'], $configs['database']['password']);

        $this->trackHydrator = new TrackHydrator($configs['track_meta_type']);
        $trackCollectionHydrator = new TrackCollectionHydrator($this->trackHydrator);

        $this->trackRepository = new TrackRepository($pdoConnection, $this->trackHydrator, $trackCollectionHydrator);
        $this->favoriteTrackUserRepository = new FavoriteUserTrackRepository($pdoConnection, $trackCollectionHydrator);
        $userHydrator = new UserHydrator();
        $this->userRepository = new UserRepository($pdoConnection, $userHydrator);

    }

    /**
     * favorite/user/userId resource
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function favoriteUserTrackListAction(RequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->setContentType('application/json');
        $userId = (int)$request->getArguments()[0];
        try {
            $user = $this->userRepository->findByUserId($userId);
            $userTrack = $this->favoriteTrackUserRepository->listFavoriteUserTrackByUserId($user->getId());
            $response->setStatus(200)->setContent(json_encode($userTrack));
        } catch (UserNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['UNF']));
        } catch (TrackNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['TNF']));
        } catch (\Exception $e) {
            $response->setStatus(505)->setContent(json_encode($this->errors['SE']));
        }

        return $response;
    }


    /**
     * favorite/user/userId resource
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function favoriteUserTrackListUpdateAction(RequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->setContentType('application/json');
        $userId = (int)$request->getArguments()[0];
        try {
            $user = $this->userRepository->findByUserId($userId);
            $userTrack = $this->favoriteTrackUserRepository->listFavoriteUserTrackByUserId($user->getId());
            /**
             * change all the favorite track list
             */
            $userTrack->removeAll();
            foreach ($request->getInputValues() as $inputTrack) {
                $trackSend = $this->trackHydrator->hydrate($inputTrack);
                $track = $this->trackRepository->findByTrackId($trackSend->getId());
                if ($trackSend != $track) {
                    $response->setStatus(400)->setContent(json_encode($this->errors['CD']));

                    return $response;
                }
                $userTrack->add($track);
            }

            $this->favoriteTrackUserRepository->saveFavoriteUserTrackByUserId($user->getId(), $userTrack);

            $response->setStatus(201)->setContent(json_encode($userTrack));

        } catch (UserNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['UNF']));
        } catch (TrackNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['TNF']));
        } catch (\Exception $e) {
            $response->setStatus(505)->setContent(json_encode($this->errors['SE']));
        }

        return $response;
    }

    /**
     * favorite/user/userId resource
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function favoriteUserTrackListAddAction(RequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->setContentType('application/json');
        $userId = (int)$request->getArguments()[0];
        try {
            $user = $this->userRepository->findByUserId($userId);
            $userTrack = $this->favoriteTrackUserRepository->listFavoriteUserTrackByUserId($user->getId());
            $trackSend = $this->trackHydrator->hydrate($request->getInputValues());
            $track = $this->trackRepository->findByTrackId($trackSend->getId());
            if ($trackSend != $track) {
                    $response->setStatus(400)->setContent(json_encode($this->errors['CD']));

                return $response;
            }
            $userTrack->add($track);
            $this->favoriteTrackUserRepository->saveFavoriteUserTrackByUserId($user->getId(), $userTrack);

            $response->setStatus(204)->setContent('');

        } catch (UserNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['UNF']));
        } catch (TrackNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['TNF']));
        } catch (TrackHydratorInvalidArgumentException $e) {
            $response->setStatus(400)->setContent(json_encode($this->errors['CD']));
        } catch (TrackCollectionDuplicateTrackException $e) {
            $response->setStatus(409)->setContent(json_encode($this->errors['TAF']));
        } catch (\Exception $e) {
            $response->setStatus(505)->setContent(json_encode($this->errors['SE']));
        }

        return $response;
    }

    /**
     * favorite/user/userId resource
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function favoriteUserTrackListDeleteAction(RequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->setContentType('application/json');
        $userId = (int)$request->getArguments()[0];
        try {
            $user = $this->userRepository->findByUserId($userId);
            $userTrack = $this->favoriteTrackUserRepository->listFavoriteUserTrackByUserId($user->getId());
            $trackId = (int)$request->getArguments()[1];
            $track = $this->trackRepository->findByTrackId($trackId);
            $userTrack->remove($track);
            $this->favoriteTrackUserRepository->saveFavoriteUserTrackByUserId($user->getId(), $userTrack);

            $response->setContentType('')->setStatus(204)->setContent('');
        } catch (UserNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['UNF']));
        } catch (TrackNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['TNF']));
        } catch (TrackCollectionTrackNotFoundException $e) {
            $response->setStatus(412)->setContent(json_encode($this->errors['FNF']));
        } catch (\Exception $e) {
            $response->setStatus(505)->setContent(json_encode($this->errors['SE']));
        }

        return $response;
    }
}
