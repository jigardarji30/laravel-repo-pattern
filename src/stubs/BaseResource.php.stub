<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{


    /**
     * @param $data
     *
     * @return array
     */
    public function success($data, $code = 200)
    {
        $response =  [
            'status' => 'success',
            'data' => $data,
            'errors' => null
        ];
        return response()->json($response, $code);
    }

    /**
     * @param $messages
     *
     * @return array
     */
    public function error($messages, $code = 500)
    {
        $response = [
            'status' => 'error',
            'data' => null,
            'errors' => $messages
        ];
        if (!empty($messages)) {
            $response['errors'] = $messages;
        } else {
            $response['errors'] = 'Something went wrong!';
        }
        return response()->json($response, $code);
    }
}
