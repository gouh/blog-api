<?php

namespace Gouh\BlogApi\App\Traits;

use Throwable;

trait JWTTrait
{
    /**
     * @param $str
     * @return string
     */
    private function base64UrlEncode($str): string
    {
        return rtrim(strtr(base64_encode($str), '+/', '-_'), '=');
    }

    /**
     * @param $headers
     * @param $payload
     * @return string
     */
    public function generateJwt($headers, $payload): string
    {
        $headers_encoded = $this->base64UrlEncode(json_encode($headers));
        $payload_encoded = $this->base64UrlEncode(json_encode($payload));

        $signature = hash_hmac('SHA256', "$headers_encoded.$payload_encoded", $this->config['secret'], true);
        $signature_encoded = $this->base64UrlEncode($signature);

        return "$headers_encoded.$payload_encoded.$signature_encoded";
    }

    /**
     * @param $jwt
     * @return array
     */
    public function validJwt($jwt): array
    {
        try {
            $tokenParts = explode('.', $jwt);
            $header = base64_decode($tokenParts[0]);
            $payload = base64_decode($tokenParts[1]);
            $signatureProvided = $tokenParts[2];
            $payloadAssoc = json_decode($payload, true);

            $expiration = $payloadAssoc['exp'];
            $isTokenExpired = ($expiration - time()) < 0;

            $base64UrlHeader = $this->base64UrlEncode($header);
            $base64UrlPayload = $this->base64UrlEncode($payload);
            $signature = hash_hmac('SHA256', $base64UrlHeader . "." . $base64UrlPayload, $this->config['secret'], true);
            $base64UrlSignature = $this->base64UrlEncode($signature);

            // verify it matches the signature provided in the jwt
            $isSignatureValid = ($base64UrlSignature === $signatureProvided);
            if ($isTokenExpired || !$isSignatureValid) {
                return [];
            } else {
                return $payloadAssoc;
            }
        } catch (Throwable $e) {
            return [];
        }
    }
}
