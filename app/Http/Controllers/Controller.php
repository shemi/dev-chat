<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $status_code = 200;

    /**
     * @return int
     */
    protected function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param int $status_code
     * @return $this
     */
    protected function setStatusCode($status_code)
    {
        $this->status_code = $status_code;

        return $this;
    }


    protected function responseNotFound($message = 'Not found.')
    {
        return $this->setStatusCode(404)->responseWithError($message);
    }



    protected function responseNotAuthorized($message = 'not Authorized.')
    {
        return $this->setStatusCode(401)->responseWithError($message);
    }

    protected function responseValidationError($messages = 'Check all your fields.', $code = 422)
    {
        if(is_string($messages)) {
            $messages = [
                'form' => [$messages]
            ];
        }

        return response()->json($messages, $code);
    }

    protected function responseInternalError($message = 'Internal error.')
    {
        return $this->setStatusCode(500)->responseWithError($message);
    }

    protected function responseBadRequest($message = 'Bad Request')
    {
        return $this->setStatusCode(400)->responseWithError($message);
    }

    protected function responseWithError($message, $resultCode = 'err')
    {

        return response()->json([
            'message' => $message,
            'resultCode' => $resultCode,
            'code'    => $this->getStatusCode()
        ], $this->getStatusCode());
    }



    protected function response($data, $headers = [], $resultCode = 'ok')
    {
        $data = [
            'data' => $data,
            'resultCode' => $resultCode,
            'code'    => $this->getStatusCode()
        ];


        return response($data, $this->getStatusCode(), $headers);
    }

}
