<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
 * Class DeploymentController
 *
 * @package App\Http\Controllers
 */
class DeploymentController extends Controller
{
    /**
     * @param Request $request
     */
    public function deploy(Request $request)
    {
        $signature = $request->header('X-Hub-Signature'); // $headers = getallheaders(); $headers['X-Hub-Signature']
        $payload = file_get_contents('php://input');
        if ($this->isFromGithub($payload, $signature)) {
            echo shell_exec("cd /html/wechat && git pull");
            http_response_code(200);
        } else {
            abort(403);
        }
    }

    /**
     * @param $payload
     * @param $signature
     * @return bool
     */
    private function isFromGithub($payload, $signature)
    {
        return 'sha1=' . hash_hmac('sha1', $payload, env('WEBHOOKS_SECRET'), false) === $signature;
    }
}