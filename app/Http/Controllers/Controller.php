<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\OpenApi(openapi: OA\OpenApi::VERSION_3_1_0, security: [['bearerAuth' => []]])]
#[OA\Info (version: "1.0.0", description: "Liberfly API test", title: "Liberfly API")]
#[OA\License(name: 'MIT', identifier: 'MIT')]
#[OA\Server(url: 'https://localhost/api', description: 'API server')]
#[OA\SecurityScheme(securityScheme: 'bearerAuth', type: 'http', description: 'Basic Auth', scheme: 'bearer')]
abstract class Controller
{
    //
}
