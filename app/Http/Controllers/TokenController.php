<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\HederaTokenService;

class TokenController extends Controller
{
    //
    protected $hederaService;

    public function __construct(HederaTokenService $hederaService)
    {
        $this->hederaService = $hederaService;
    }

    public function createToken()
    {
        try {
            $result = $this->hederaService->createVillageToken();
            // Update .env with Token ID
            $this->updateEnvFile('HEDERA_TOKEN_ID', $result['tokenId']);
            return response()->json([
                'message' => 'Village Token created successfully!',
                'tokenId' => $result['tokenId'],
                'status' => $result['status'],
                'transactionId' => $result['transactionId'],
                'hashscanUrl' => $result['hashscanUrl']
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create token',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    private function updateEnvFile($key, $value)
    {
        $path = base_path('.env');
        $content = file_get_contents($path);
        $newLine = "$key=$value";
        if (strpos($content, "$key=") !== false) {
            $content = preg_replace("/$key=.*/", $newLine, $content);
        } else {
            $content .= "\n$newLine";
        }
        file_put_contents($path, $content);
    }

    


}
