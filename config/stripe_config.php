<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../vendor/autoload.php';

\Stripe\Stripe::setApiKey('sk_test_51QkLaLFDDnLDSMgdZ3dYCyO34XoZV2ZAxKgoUeay2QkT15V9gnlvWDTVzIdKDses5LrTAOzW8ObsRWJYhSZXE4yf00cJ0s9f2m'); // Clave secreta

$stripe = new \Stripe\StripeClient('sk_test_51QkLaLFDDnLDSMgdZ3dYCyO34XoZV2ZAxKgoUeay2QkT15V9gnlvWDTVzIdKDses5LrTAOzW8ObsRWJYhSZXE4yf00cJ0s9f2m');
