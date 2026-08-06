<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class GooglePlayService
{
    protected ?array $serviceAccount = null;

    public function __construct()
    {
        // Service account loading is performed lazily when required,
        // so the controller can catch configuration errors and return a friendly message.
    }

    public function getProductIdForPlan(string $plan): string
    {
        return config("services.google_play.products.{$plan}") ?? $plan;
    }

    public function verifySubscriptionPurchase(string $packageName, string $productId, string $purchaseToken): array
    {
        $accessToken = $this->getAccessToken();

        $url = sprintf(
            'https://androidpublisher.googleapis.com/androidpublisher/v3/applications/%s/purchases/subscriptions/%s/tokens/%s',
            rawurlencode($packageName),
            rawurlencode($productId),
            rawurlencode($purchaseToken)
        );

        $response = Http::withToken($accessToken)
            ->acceptJson()
            ->get($url);

        if (!$response->successful()) {
            Log::error('Google Play subscription verification failed', [
                'status' => $response->status(),
                'body' => $response->body(),
                'product_id' => $productId,
                'package_name' => $packageName,
            ]);

            throw new \RuntimeException('Google Play verification failed. Please verify your purchase token and try again.');
        }

        return $response->json();
    }

    protected function getAccessToken(): string
    {
        $jwt = $this->buildJwt();

        $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
            'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
            'assertion' => $jwt,
        ]);

        if (!$response->successful()) {
            Log::error('Google Play auth token request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \RuntimeException('Unable to request Google Play access token.');
        }

        return $response->json('access_token');
    }

    protected function buildJwt(): string
    {
        $now = time();
        $payload = [
            'iss' => $this->serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/androidpublisher',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $now + 3600,
            'iat' => $now,
        ];

        $header = ['alg' => 'RS256', 'typ' => 'JWT'];
        $segments = [
            $this->base64UrlEncode(json_encode($header, JSON_UNESCAPED_SLASHES)),
            $this->base64UrlEncode(json_encode($payload, JSON_UNESCAPED_SLASHES)),
        ];

        $signingInput = implode('.', $segments);
        $privateKey = $this->serviceAccount['private_key'];

        if (!Str::startsWith($privateKey, '-----BEGIN PRIVATE KEY-----')) {
            $privateKey = "-----BEGIN PRIVATE KEY-----\n{$privateKey}\n-----END PRIVATE KEY-----";
        }

        $signature = '';
        if (!openssl_sign($signingInput, $signature, $privateKey, OPENSSL_ALGO_SHA256)) {
            throw new \RuntimeException('Unable to sign Google Play JWT.');
        }

        $segments[] = $this->base64UrlEncode($signature);

        return implode('.', $segments);
    }

    protected function loadServiceAccount(): array
    {
        if ($this->serviceAccount !== null) {
            return $this->serviceAccount;
        }

        $rawJson = config('services.google_play.service_account_json');
        $path = config('services.google_play.service_account_path');

        if (!empty($rawJson)) {
            $account = json_decode($rawJson, true);
        } elseif (!empty($path) && file_exists($path)) {
            $account = json_decode(file_get_contents($path), true);
        } else {
            throw new \RuntimeException('Google Play service account credentials are not configured.');
        }

        if (!is_array($account) || empty($account['client_email']) || empty($account['private_key'])) {
            throw new \RuntimeException('Google Play service account credentials are invalid.');
        }

        $this->serviceAccount = $account;

        return $this->serviceAccount;
    }

    private function base64UrlEncode(string $input): string
    {
        return rtrim(strtr(base64_encode($input), '+/', '-_'), '=');
    }
}
