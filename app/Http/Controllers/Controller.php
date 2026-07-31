<?php
 
//app/Http/Controllers/Controller.php
 
namespace App\Http\Controllers;
 
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
 

class Controller extends BaseController
{

/**
 * @OA\OpenApi(
 * @OA\Info(
 *     version="1.0.0",
 *     title="Kodementor Api Documentation",
 *     description="Kodementor Api Documentation",
 *    
 * )
 * )

 */



    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}