<?php

use League\Fractal\Cursor\Cursor;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;

class ApiController extends Controller
{

    const CODE_FORBIDDEN = 1;
    const CODE_INTERNAL_ERROR = 2;
    const CODE_NOT_FOUND = 3;
    const CODE_UNAUTHORIZED = 4;
    const CODE_WRONG_ARGS = 4;
    const CODE_VALIDATION_ERROR = 5;
    const CODE_DUPLICATE = 6;

    /**
     * Status code of the response.
     *
     * @var integer
     */
    protected $statusCode = 200;

    /**
     * Available scopes in the controller.
     *
     * @var array
     */
    protected $availableScopes = ['user'];

    /**
     * Create a new ApiController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->fractal = new Manager;
        $this->fractal->setRequestedScopes(explode(',', Input::get('embed')));
    }

    /**
     * Generates a response with the given item.
     *
     * @param  array  $item
     * @param  mixed  $callback
     * @return Response
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Generates a response with the given collection.
     *
     * @param  array  $collection
     * @param  mixed  $callback
     * @return Response
     */
    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Generates a response with the given collection using a cursor.
     *
     * @param  array   $collection
     * @param  mixed   $callback
     * @param  mixed   $current
     * @param  integer $next
     * @return Response
     */
    protected function respondWithCursor($collection, $callback, $current = 0, $next = 0)
    {
        $resource = new Collection($collection, $callback);

        $cursor = new Cursor($current, $next, count($collection));
        $resource->setCursor($cursor);

        $rootScope = $this->fractal->createData($resource);

        return $this->respondWithArray($rootScope->toArray());
    }

    /**
     * Generates a response with the given array and headers.
     *
     * @param  array  $array
     * @param  array  $headers
     * @return Response
     */
    protected function respondWithArray(array $array, array $headers = array())
    {
        $response = Response::json($array, $this->getStatusCode(), $headers);

        return $response;
    }

    /**
     * Generates a Response with 201 HTTP header and a given location of a
     * created resource.
     *
     * @param  string  $location
     * @return Response
     */
    protected function respondCreated($location)
    {
        $this->setStatusCode(201);

        $response = Response::make(null, $this->getStatusCode(), ['Location' => $location]);

        return $response;
    }

    /**
     * Generates a Response with 204 HTTP header.
     *
     * @return Response
     */
    protected function respondEmpty()
    {
        $this->setStatusCode(204);

        $response = Response::make(null, $this->getStatusCode());

        return $response;
    }

    /**
     * Generates a base error Response with the given message, error code and
     * optional extra parameters.
     *
     * @param  string   $message
     * @param  integer  $errorCode
     * @param  array    $extra
     * @return Response
     */
    protected function respondWithError($message, $errorCode, $extra = array())
    {
        if ($this->statusCode === 200) {
            trigger_error('You better have a really good reason for erroring on a 200...', E_USER_WARNING);
        }

        $data = array_merge([
            'code' => $errorCode,
            'message' => $message,
        ], $extra);

        return $this->respondWithArray($data);
    }

    /**
     * Generates a Response with a 400 HTTP header and a given message.
     *
     * @param  string $message
     * @return Response
     */
    protected function errorWrongArgs($message = 'Wrong Arguments')
    {
        return $this->setStatusCode(400)->respondWithError($message, self::CODE_WRONG_ARGS);
    }

    /**
     * Generates a Response with a 401 HTTP header and a given message.
     *
     * @param  string $message
     * @return Response
     */
    protected function errorUnauthorized($message = 'Unauthorized')
    {
        return $this->setStatusCode(401)->respondWithError($message, self::CODE_UNAUTHORIZED);
    }

    /**
     * Generates a Response with a 403 HTTP header and a given message.
     *
     * @param  string $message
     * @return Response
     */
    protected function errorForbidden($message = 'Forbidden')
    {
        return $this->setStatusCode(403)->respondWithError($message, self::CODE_FORBIDDEN);
    }

    /**
     * Generates a Response with a 404 HTTP header and a given message.
     *
     * @param  string $message
     * @return Response
     */
    protected function errorNotFound($message = 'Resource Not Found')
    {
        return $this->setStatusCode(404)->respondWithError($message, self::CODE_NOT_FOUND);
    }

    /**
     * Generates an error Response for duplicated content with a 409 HTTP header
     * and a given message.
     *
     * @param  string $message
     * @return Response
     */
    protected function errorDuplicate($message = 'Duplicate resource')
    {
        return $this->setStatusCode(409)->respondWithError($message, self::CODE_DUPLICATE);
    }

    /**
     * Generates a Response with a 422 HTTP header and attaches an array of
     *  errors.
     *
     * @param  array|\Illuminate\Validation\Validator  $errors
     * @param  string                                  $message
     * @return Response
     */
    protected function errorValidation($errors = array(), $message = 'Validation failed')
    {
        // if we pass an instance of Validator let's MessageBag errors
        if ($errors instanceof Illuminate\Validation\Validator) {
            $errors = $errors->errors()->toArray();
        }

        $data = ['errors' => $errors];

        return $this->setStatusCode(422)->respondWithError($message, self::CODE_VALIDATION_ERROR, $data);
    }

    /**
     * Generates a Response with a 500 HTTP header and a given message.
     *
     * @param  string $message
     * @return Response
     */
    protected function errorInternalError($message = 'Internal Server Error')
    {
        return $this->setStatusCode(500)->respondWithError($message, self::CODE_INTERNAL_ERROR);
    }

    /**
     * Guess which type of error we should return from a given error array.
     *
     * @param  array $error
     * @return Response
     */
    protected function guessError($error)
    {
        if ($error['type'] == 'validation') {
            return $this->errorValidation($error['bag']);
        } elseif ($error['type'] == 'not_found') {
            return $this->errorNotFound();
        } elseif ($error['type'] == 'forbidden') {
            return $this->errorForbidden();
        } elseif ($error['type'] == 'duplicate') {
            return $this->errorDuplicate();
        }

        return $this->errorInternalError();
    }

    /**
     * Getter for statusCode
     *
     * @return integer
     */
    protected function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * Setter for statusCode
     *
     * @param  integer  $statusCode
     * @return self
     */
    protected function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }

    /**
     * Get the available scopes.
     *
     * @return array
     */
    protected function getAvailableScopes()
    {
        return $this->availableScopes;
    }

    /**
     * Set the available scopes.
     *
     * @param array $scopes
     */
    protected function setAvailableScopes(array $scopes)
    {
        $this->availableScopes = $scopes;

        return $this;
    }

    /**
     * Get the requested scopes. It will also check if the requested scopes are
     * available to be fetched.
     *
     * @return array
     */
    protected function getRequestedScopes()
    {
        $available = $this->getAvailableScopes();
        $requested = $this->fractal->getRequestedScopes();

        return array_intersect($available, $requested);
    }

}
