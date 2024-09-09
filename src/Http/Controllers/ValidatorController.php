<?php

namespace Eminiarts\Tabs\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValidatorController extends Controller
{
    /**
     * Work
     * @param  Request $request
     */
    public function index(Request $request)
    {
        $request->validate([
            'validations' => ['required']
        ]);

        $validations = $this->castArray(json_decode($request->input('validations')));

        $request->validate($validations);

        return response()->json([
            'message' => 'Yeah'
        ]);
    }

    /**
     * Cast array
     */
    public function castArray(array $validations): array
    {
        $castedArray = [];

        foreach ($validations as $validation) {
            $really = (array) $validation;

            foreach ($really as $key => $value) {
                $cs[$key] = $value;
            }
        }

        return $cs;
    }
}
