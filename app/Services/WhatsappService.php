<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    public static function send($nomor, $pesan)
    {
        try {
            $nomor = preg_replace('/[^0-9]/', '', $nomor);
            if (str_starts_with($nomor, '0')) {
                $nomor = '62' . substr($nomor, 1);
            }

            $response = Http::withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $nomor,
                'message' => $pesan,
            ]);

            if ($response->failed()) {
                Log::error("WhatsApp API failed: " . $response->body());
            }
        } catch (\Exception $e) {
            Log::error("WhatsApp exception: " . $e->getMessage());
        }
    }
}