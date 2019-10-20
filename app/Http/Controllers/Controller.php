<?php

namespace App\Http\Controllers;

use App\Utils\Response;
use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use Response;

    /**
     * @OA\OpenApi(
     *     @OA\Info(
     *         version="1.0.0",
     *         title="Fotomi",
     *         description="This is the service definitions for Fotomi.  You can find out more about Fotomi at [https://fotomi.now.sh](https://fotomi.now.sh).  For this documentaion, you can use the api key `special-key` to test the authorization filters.",
     *         termsOfService="https://fotomi.now.sh/",
     *         @OA\Contact(
     *             email="osarenrenenoch@gmail.com"
     *         ),
     *         @OA\License(
     *             name="Apache 2.0",
     *             url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *         ),
     *         termsOfService="http://swagger.io/terms/"
     *     ),
     *      @OA\Server(
     *         description="staging",
     *         url="https://fotomi-api.herokuapp.com/api/v1"
     *     ),
     *     @OA\Server(
     *         description="local",
     *         url="http://0.0.0.0:8000/api/v1"
     *     ),
     *     @OA\ExternalDocumentation(
     *         description="Find out more about Fotomi",
     *         url="https://fotomi.now.sh"
     *     )
     * )
     */
}
