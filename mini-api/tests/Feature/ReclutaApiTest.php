<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ReclutaApiTest extends TestCase
{
    public function test_obtener_reclutados()
    {
        $response = $this->get('/api/reclutados');

        $response->assertStatus(200);
    }

    public function test_29_de_febrero_anio_no_bisiesto()
    {
        $dataToPost = [
            "name" => "Agustin Elias",
            "suraname" => "Gonzalez",
            "birthday" => "2003/02/29",
            "documentType" => "DNI",
            "documentNumber" => 12345678
        ];

        $response = $this->post('/api/recluta', $dataToPost);

        $response->assertStatus(400);
    }

    public function test_campos_extra()
    {
        $dataToPost = [
            "name" => "Agustin Elias",
            "suraname" => "Gonzalez",
            "birthday" => "2000/05/10",
            "documentType" => "DNI",
            "documentNumber" => 12345678,
            "age" => 25
        ];

        $response = $this->post('/api/recluta', $dataToPost);

        $response->assertStatus(400);
    }

    public function test_recluta_vacio()
    {
        $dataToPost = [];

        $response = $this->post('/api/recluta', $dataToPost);

        $response->assertStatus(400);
    }

    public function test_cumpleanios_limite_inf()
    {
        $dataToPost = [
            "name" => "Agustin Elias",
            "suraname" => "Gonzalez",
            "birthday" => "1899/12/31",
            "documentType" => "DNI",
            "documentNumber" => 12345678
        ];

        $response = $this->post('/api/recluta', $dataToPost);

        $response->assertStatus(400);
    }

    public function test_cumpleanios_limite_sup()
    {
        $dataToPost = [
            "name" => "Agustin Elias",
            "suraname" => "Gonzalez",
            "birthday" => date('Y/m/d',strtotime("+1 day")),
            "documentType" => "DNI",
            "documentNumber" => 12345678
        ];

        $response = $this->post('/api/recluta', $dataToPost);

        $response->assertStatus(400);
    }

    public function test_documento_invalido()
    {
        $dataToPost = [
            "name" => "Agustin Elias",
            "suraname" => "Gonzalez",
            "birthday" => "2000/05/10",
            "documentType" => "VISA",
            "documentNumber" => 12345678
        ];

        $response = $this->post('/api/recluta', $dataToPost);

        $response->assertStatus(400);
    }

    public function test_cumpleanios_formato_invalido()
    {
        $dataToPost = [
            "name" => "Agustin Elias",
            "suraname" => "Gonzalez",
            "birthday" => "2000-05-10",
            "documentType" => "DNI",
            "documentNumber" => 12345678
        ];

        $response = $this->post('/api/recluta', $dataToPost);

        $response->assertStatus(400);
    }

    public function test_caracteres_invalidos_en_nombre()
    {
        $dataToPost = [
            "name" => "Agustin%^Elias",
            "suraname" => "Gonzalez",
            "birthday" => "2000/05/10",
            "documentType" => "DNI",
            "documentNumber" => 12345678
        ];

        $response = $this->post('/api/recluta', $dataToPost);

        $response->assertStatus(400);
    }

    public function test_nombre_excede_longitud_maxima()
    {
        $dataToPost = [
            "name" => "Agustin Eliasssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssssss",
            "suraname" => "Gonzalez",
            "birthday" => "2000/05/10",
            "documentType" => "DNI",
            "documentNumber" => 12345678
        ];

        $response = $this->post('/api/recluta', $dataToPost);

        $response->assertStatus(400);
    }
}
