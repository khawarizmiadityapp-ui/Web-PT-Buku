<?php

namespace App\Services;

class TotpService
{
    private static string $base32Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';

    /**
     * Generate a random Base32 secret key for TOTP (e.g. 16 chars)
     */
    public static function generateSecret(int $length = 16): string
    {
        $secret = '';
        for ($i = 0; $i < $length; $i++) {
            $secret .= self::$base32Chars[random_int(0, 31)];
        }
        return $secret;
    }

    /**
     * Decode a Base32 string into raw binary bytes
     */
    public static function base32Decode(string $secret): string
    {
        $secret = strtoupper(trim($secret));
        $buffer = 0;
        $bufferSize = 0;
        $binary = '';

        for ($i = 0; $i < strlen($secret); $i++) {
            $char = $secret[$i];
            $val = strpos(self::$base32Chars, $char);
            if ($val === false) {
                continue;
            }

            $buffer = ($buffer << 5) | $val;
            $bufferSize += 5;

            if ($bufferSize >= 8) {
                $bufferSize -= 8;
                $binary .= chr(($buffer >> $bufferSize) & 0xFF);
            }
        }

        return $binary;
    }

    /**
     * Calculate 6-digit TOTP code for a given timestamp and secret
     */
    public static function getCode(string $secret, ?int $timestamp = null): string
    {
        if ($timestamp === null) {
            $timestamp = time();
        }

        $timeSlice = floor($timestamp / 30);
        $secretKey = self::base32Decode($secret);

        // Pack time into 64-bit big-endian binary
        $timeData = pack('N*', 0) . pack('N*', $timeSlice);

        // Generate HMAC-SHA1
        $hash = hash_hmac('sha1', $timeData, $secretKey, true);

        // Dynamic truncation
        $offset = ord($hash[19]) & 0x0F;
        $binaryCode = (
            ((ord($hash[$offset]) & 0x7F) << 24) |
            ((ord($hash[$offset + 1]) & 0xFF) << 16) |
            ((ord($hash[$offset + 2]) & 0xFF) << 8) |
            (ord($hash[$offset + 3]) & 0xFF)
        );

        $otp = $binaryCode % 1000000;
        return sprintf('%06d', $otp);
    }

    /**
     * Verify a submitted 6-digit code with window tolerance (±30s)
     */
    public static function verifyCode(string $secret, string $code, int $discrepancy = 1): bool
    {
        $code = trim($code);
        if (strlen($code) !== 6 || !ctype_digit($code)) {
            return false;
        }

        $currentTime = time();
        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $checkTime = $currentTime + ($i * 30);
            $expectedCode = self::getCode($secret, $checkTime);
            if (hash_equals($expectedCode, $code)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Generate otpauth:// URI for QR code scanning
     */
    public static function getOtpAuthUri(string $company, string $accountName, string $secret): string
    {
        $encodedCompany = rawurlencode($company);
        $encodedAccount = rawurlencode($accountName);
        $label = "{$encodedCompany}:{$encodedAccount}";

        return "otpauth://totp/{$label}?secret={$secret}&issuer={$encodedCompany}&algorithm=SHA1&digits=6&period=30";
    }

    /**
     * Generate QR Code Image URL using SVG/QR API
     */
    public static function getQrCodeImageUrl(string $otpAuthUri, int $size = 200): string
    {
        return "https://api.qrserver.com/v1/create-qr-code/?size={$size}x{$size}&data=" . urlencode($otpAuthUri);
    }

    /**
     * Get remaining seconds in current 30-second TOTP cycle
     */
    public static function getRemainingSeconds(): int
    {
        return 30 - (time() % 30);
    }
}
