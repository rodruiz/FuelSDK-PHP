<?php

require __DIR__ . '/../vendor/autoload.php';
date_default_timezone_set('UTC');

function requireMarketingCloudConfig(\PHPUnit\Framework\TestCase $test): void
{
    if (!file_exists(__DIR__ . '/../config.php')) {
        $test->markTestSkipped(
            'Marketing Cloud integration tests require config.php; copy config.php.template and add test-account credentials.'
        );
    }
}
