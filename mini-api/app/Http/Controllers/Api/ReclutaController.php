<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReclutaController extends Controller
{
    private string $baseUrl = "https://reclutamiento-dev-procontacto-default-rtdb.firebaseio.com/reclutier.json";

    public function getAll()
    {
        $response = Http::get($this->baseUrl);

        if ($response->failed()) {
            return response()->json([
                'status' => 'error', 
                'details' => 'no se pudo acceder a la lista de reclutas'
            ], $response->status());
        }

        return response()->json([
            'status' => 'exitoso',
            'details' => 'lista de reclutas obtenida exitosamente',
            'reclutas list' => $response->json(),
        ], 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    public function store(Request $request) 
    {
        $allowed_fields = ['name','suraname','birthday','documentType','documentNumber'];
        $extra_fields = array_diff(array_keys($request->all()), $allowed_fields);

        if (!empty($extra_fields)) {
            return response()->json([
                'status' => 'error',
                'details' => 'campos no permitidos en la request',
                'extra fields' => array_values($extra_fields)
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|min:1|max:30|regex:/^[\p{L}\s\'\-]+$/u',
            'suraname'       => 'required|string|min:1|max:30|regex:/^[\p{L}\s\'\-]+$/u',
            'birthday'       => 'required|date_format:Y/m/d|after_or_equal:1900/01/01|before_or_equal:today',
            'documentType'   => 'required|in:CUIT,DNI',
            'documentNumber' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'details' => 'datos invalidos',
                'errors' => $validator->errors()
            ], 400);
        }

        $name = ucwords(strtolower($request->input('name')));
        $suraname = ucwords(strtolower($request->input('suraname')));

        $birthday = Carbon::createFromFormat('Y/m/d', $request->input('birthday'));
        $age = $birthday->age;

        $dataToPost = [
            'name'           => $name,
            'suraname'       => $suraname,
            'birthday'       => $birthday->format('Y/m/d') . '/',
            'age'            => $age,
            'documentType'   => $request->input('documentType'),
            'documentNumber' => $request->input('documentNumber'),
        ];

        $responseToPost = Http::post($this->baseUrl, $dataToPost);

        if ($responseToPost->failed()) {
            return response()->json([
                'status' => 'error',
                'details' => 'error al enviar los datos del recluta a Firebase'
            ], $responseToPost->status());
        }

        return response()->json(
            ['status' => 'exitoso',
            'details' => 'datos del recluta enviados exitosamente a Firebase',
            'data sent' => $dataToPost
        ], 201, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
