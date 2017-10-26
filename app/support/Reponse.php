<?php
/**
 * Created by PhpStorm.
 * User: chengyang
 * Date: 2017/10/26
 * Time: 下午10:09
 */
namespace App\Support;

use League\Fractal\TransformerAbstract;
use Illuminate\Contracts\Routing\ResponseFactory;
use Symfony\Component\HttpFoundation\Response as HttpResponse;

class Reponse
{
    private $response;

    public $transform;

    private $statusCode = HttpResponse::HTTP_OK;

    public function __construct(ResponseFactory $response, Transform $transform)
    {
        $this->response = $response;
        $this->transform = $transform;
    }

    public function withCreated($resource = null, TransformerAbstract $transformer = null)
    {
        $this->statusCode = HttpResponse::HTTP_CREATED;

        if (is_null($resource)) {
            return $this->json();
        }

        return $this->item($resource, $transformer);
    }

    public function withNoContent()
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_NO_CONTENT
        )->json();
    }

    public function withBadRequest($message = 'Bad Request')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_BAD_REQUEST
        )->withError($message);
    }

    public function withUnauthorized($message = 'unauthorized')
    {
        return $this->setStatusCode(
            HttpResponse::HTTP_FORBIDDEN
        )->withError($message);
    }

    
}