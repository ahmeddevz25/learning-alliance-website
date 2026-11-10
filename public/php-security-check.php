<?php
/**
 * PHP2Shell (CVE-2026-109618 / CVE-2026-109619) Security Checker
 * IMPORTANT: Delete this file after checking your server!
 */

$currentVersion = PHP_VERSION;
$majorMinor = PHP_MAJOR_VERSION . '.' . PHP_MINOR_VERSION;

// Safe versions patched on October 21, 2026
$safeThresholds = [
    '8.2' => '8.2.35',
    '8.3' => '8.3.36',
    '8.4' => '8.4.27',
    '8.5' => '8.5.12',
];

$isSafe = false;
$statusText = '';
$statusClass = '';
$recommendation = '';

if (isset($safeThresholds[$majorMinor])) {
    $minRequired = $safeThresholds[$majorMinor];
    if (version_compare($currentVersion, $minRequired, '>=')) {
        $isSafe = true;
        $statusText = "SAFE: Your PHP version is patched against PHP2Shell.";
        $statusClass = "safe";
        $recommendation = "No action needed for PHP core. Your current version meets or exceeds the required patch ($minRequired).";
    } else {
        $isSafe = false;
        $statusText = "VULNERABLE: Current version is lower than the patch ($minRequired).";
        $statusClass = "vulnerable";
        $recommendation = "Immediate server update required! Ask your server admin/DevOps to run OS package updates and restart PHP-FPM / Web Server.";
    }
} else {
    // EOL versions like 8.1, 8.0, 7.x
    $isSafe = false;
    $statusText = "HIGH RISK: PHP $majorMinor is End-of-Life (EOL) with NO official patch!";
    $statusClass = "vulnerable";
    $recommendation = "Upgrade PHP to at least 8.3 (>= 8.3.36) or 8.2 (>= 8.2.35) immediately.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP2Shell Security Status</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #0f172a;
            color: #f8fafc;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .card {
            background: #1e293b;
            border-radius: 16px;
            padding: 32px;
            max-width: 620px;
            width: 100%;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.5);
            border: 1px solid #334155;
        }
        h1 {
            margin-top: 0;
            font-size: 24px;
            color: #38bdf8;
            border-bottom: 1px solid #334155;
            padding-bottom: 16px;
        }
        .badge {
            display: inline-block;
            padding: 10px 18px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            margin: 16px 0;
        }
        .safe {
            background: #14532d;
            color: #4ade80;
            border: 1px solid #22c55e;
        }
        .vulnerable {
            background: #7f1d1d;
            color: #f87171;
            border: 1px solid #ef4444;
        }
        .details-table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }
        .details-table td {
            padding: 10px;
            border-bottom: 1px solid #334155;
        }
        .details-table td:first-child {
            font-weight: 600;
            color: #94a3b8;
            width: 40%;
        }
        .details-table td:last-child {
            color: #e2e8f0;
            font-family: monospace;
            font-size: 15px;
        }
        .recommendation {
            background: #0b1329;
            padding: 16px;
            border-radius: 8px;
            margin-top: 20px;
            border-left: 4px solid <?php echo $isSafe ? '#22c55e' : '#ef4444'; ?>;
            line-height: 1.5;
            font-size: 14px;
        }
        .alert-warning {
            background: #451a03;
            border: 1px solid #f59e0b;
            color: #fef3c7;
            padding: 12px;
            border-radius: 8px;
            margin-top: 24px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h1>PHP2Shell Vulnerability Checker</h1>

        <div class="badge <?php echo $statusClass; ?>">
            <?php echo $statusText; ?>
        </div>

        <table class="details-table">
            <tr>
                <td>Installed PHP Version:</td>
                <td><strong><?php echo htmlspecialchars($currentVersion); ?></strong></td>
            </tr>
            <tr>
                <td>Required Safe Version:</td>
                <td><?php echo isset($safeThresholds[$majorMinor]) ? $safeThresholds[$majorMinor] . '+' : 'Upgrade to 8.2+'; ?></td>
            </tr>
            <tr>
                <td>Server SAPI:</td>
                <td><?php echo htmlspecialchars(php_sapi_name()); ?></td>
            </tr>
            <tr>
                <td>Server Time:</td>
                <td><?php echo date('Y-m-d H:i:s T'); ?></td>
            </tr>
        </table>

        <div class="recommendation">
            <strong>Action Needed:</strong><br>
            <?php echo $recommendation; ?>
        </div>

        <div class="alert-warning">
            ⚠️ <strong>Security Notice:</strong> Please delete this file (<code>public/php-security-check.php</code>) via FileZilla as soon as you have finished checking!
        </div>
    </div>
</body>
</html>
