<?php
declare(strict_types = 1);
namespace TestWebEngineer\JsonApi\Controller;

use TestWebEngineer\SimpleRouter\Response;
use TestWebEngineer\SimpleRouter\ResponseInterface;
use TestWebEngineer\User\Hydrator\Hydrator as UserHydrator;
use TestWebEngineer\User\Repository\PdoRepository as UserRepository;
use TestWebEngineer\User\Exception\UserNotFoundException;
use TestWebEngineer\SimpleRouter\RequestInterface;

class UserApiController extends AbstractApiController
{

    /**
     *
     * @var UserRepository
     */
    private $userRepository;

    /**
     * TestWebEngineerJsonApiController constructor.
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $dsn = $configs['database']['driver'].':host='.$configs['database']['host'].
               ((!empty($configs['database']['port'])) ? (';port='.$configs['database']['port']) : '').
               ';dbname='.$configs['database']['schema'];

        $pdoConnection = new \PDO($dsn, $configs['database']['username'], $configs['database']['password']);

        $userHydrator = new UserHydrator();
        $this->userRepository = new UserRepository($pdoConnection, $userHydrator);

    }

    /**
     * Retrieve a User resource
     * user/userId
     *
     * @param RequestInterface $request
     * @return ResponseInterface
     */
    public function userFindByUserIdAction(RequestInterface $request): ResponseInterface
    {
        $response = new Response();
        $response->setContentType('application/json');
        $userId = (int)$request->getArguments()[0];
        try {
            $user = $this->userRepository->findByUserId($userId);
            $response->setStatus(200)->setContent(json_encode($user));
        } catch (UserNotFoundException $e) {
            $response->setStatus(404)->setContent(json_encode($this->errors['UNF']));
        } catch (\Exception $e) {
            $response->setStatus(505)->setContent(json_encode($this->errors['SE']));
        }

        return $response;
    }
}
