<?php
declare(strict_types = 1);
namespace TestWebEngineer\JsonApi\Controller;

use TestWebEngineer\SimpleRouter\Response;
use TestWebEngineer\SimpleRouter\ResponseInterface;
use TestWebEngineer\Track\Hydrator\Hydrator as TrackHydrator;
use TestWebEngineer\TrackCollection\Hydrator\Hydrator as TrackCollectionHydrator;
use TestWebEngineer\Track\Repository\PdoRepository as TrackRepository;
use TestWebEngineer\Track\Exception\TrackNotFoundException;
use TestWebEngineer\SimpleRouter\RequestInterface;

class TrackApiController extends AbstractApiController
{

    /**
     *
     * @var TrackRepository
     */
    private $trackRepository;

    /**
     * TrackApiController constructor.
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $dsn = $configs['database']['driver'].':host='.$configs['database']['host'].
               ((!empty($configs['database']['port'])) ? (';port='.$configs['database']['port']) : '').
               ';dbname='.$configs['database']['schema'];

        $pdoConnection = new \PDO($dsn, $configs['database']['username'], $configs['database']['password']);

        $trackHydrator = new TrackHydrator($configs['track_meta_type']);
        $trackCollectionHydrator = new TrackCollectionHydrator($trackHydrator);

        $this->trackRepository = new TrackRepository($pdoConnection, $trackHydrator, $trackCollectionHydrator);

    }

    /**
     * Retrieve a Track resource
     * /track/trackId
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function trackFindByTrackIdAction(RequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->setContentType('application/json');
        $trackId = (int)$request->getArguments()[0];
        try {
            $track = $this->trackRepository->findByTrackId($trackId);
            $response->setStatus(200)->setContent(json_encode($track));
        } catch (TrackNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['TNF']));
        } catch (\Exception $e) {
            $response->setStatus(505)->setContent(json_encode($this->errors['SE']));
        }

        return $response;
    }

    /**
     * Retrieve a Track ressourse
     * track/trackId
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function trackListAction(RequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->setContentType('application/json');
        try {
            $tracks = $this->trackRepository->list();
            $response->setStatus(200)->setContent(json_encode($tracks));
        } catch (TrackNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['TNF']));
        } catch (\Exception $e) {
            $response->setStatus(505)->setContent(json_encode($this->errors['SE']));
        }

        return $response;
    }
}
