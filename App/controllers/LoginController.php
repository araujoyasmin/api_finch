
<?php 

use Service\LoginService;

require_once ('./App/services/LoginService.php');

class LoginController{
    private $loginService;
    private $secretKey = 'api_finch';

    public function __construct(LoginService $loginService) {
        $this->loginService = $loginService;
    }

    public function login($request)
    {
        $postjson = json_decode(file_get_contents('php://input'),true);
        $user = $this->loginService->apiLogin($postjson);
        
        if($user){

            $payload = [
                'sub' => $user['email'],
                'perfil' => $user['id_perfil'],
                'iat' => time(),
                'exp' => time() + 3600
            ];

            $jwt = $this->generateToken($payload);

            return $jwt;
        }else {
            $error = [
                'error' => 'denied',
                'message' => 'Email e/ou CPF invalidos!'
            ];
            http_response_code(400); 
            return ($error);
        }
        
    }

    private function generateToken($payload) {
        $header = json_encode(['alg' => 'HS256', 'typ' => 'JWT']);
        $header = base64_encode($header);

        $payload = json_encode($payload);
        $payload = base64_encode($payload);

        $signature = hash_hmac('sha256', "$header.$payload", $this->secretKey, true);
        $signature = base64_encode($signature);

        $jwt = "$header.$payload.$signature";

        return $jwt;
    }

    public function checkAuth(){
        $headers = apache_request_headers();
        $token = $headers['Authorization'] ?? '';
        if (!empty($token)) {
            // Verifique o token e obtenha os dados do usuário
            $decodedToken = self::verifyToken($token);
            if ($decodedToken) {
                // O token é válido
                $request['user'] = $decodedToken->sub;
                $request['perfil'] = $decodedToken->perfil;
                
                return $request;
            } else {
                // O token é inválido ou expirado
                return false;
            }
        } else {
            // Token não fornecido
           return false;
        }
    }
    private static function verifyToken($token) {
        $secretKey = 'api_finch';
        $tokenParts = explode('.', $token);
        
        if (count($tokenParts) === 3) {
            $payload = base64_decode($tokenParts[1]);
            
            $decodedToken = json_decode($payload);
           
            $isValidSignature = hash_hmac('sha256', "$tokenParts[0].$tokenParts[1]", $secretKey, true) === base64_decode($tokenParts[2]);
            
            if ($isValidSignature) {
                // Verifique a validade do token (data de expiração, por exemplo)
                $currentTime = time();
                if ($decodedToken->exp >= $currentTime) {
                    return $decodedToken;
                }
            }
        }
        return null;
    }
}

