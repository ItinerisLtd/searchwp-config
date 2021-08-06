<?php

declare(strict_types=1);

namespace Itineris\SearchWpConfig;

final class BasicAuth
{
    /**
     * @var string Basic auth username
     */
    protected string $username;

    /**
     * @var string Basic auth password
     */
    protected string $password;

    public static function register(): void
    {
        if (
            ! self::isValidConstant('HTTP_BASIC_AUTH_USERNAME') ||
            ! self::isValidConstant('HTTP_BASIC_AUTH_PASSWORD')
        ) {
            return;
        }

        $instance = new self(HTTP_BASIC_AUTH_USERNAME, HTTP_BASIC_AUTH_PASSWORD);

        add_filter('cron_request', [$instance, 'setCronRequestBasicAuthCredentials'], 999);

        /**
         * @since SearchWP 2.4
         */
        add_filter('searchwp_basic_auth_creds', [$instance, 'setBasicAuthCredentials']);

        /**
         * @since SearchWP 4.0.0
         */
        add_filter('searchwp\indexer\http_basic_auth_credentials', [$instance, 'setBasicAuthCredentials']);

        /**
         * @since SearchWP 4.1.0
         */
        add_filter('searchwp\background_process\http_basic_auth_credentials', [$instance, 'setBasicAuthCredentials']);
    }

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    protected static function isValidConstant(string $constant): bool
    {
        return defined($constant) && is_string($constant) && ! empty($constant);
    }

    public function setBasicAuthCredentials(): array
    {
        return [
            'username' => $this->username,
            'password' => $this->password,
        ];
    }

    public function setCronRequestBasicAuthCredentials(array $cron_request): array
    {
        $cron_request['args']['headers'] ??= [];
        if (isset($cron_request['args']['headers']['Authorization'])) {
            return $cron_request;
        }

        $cron_request['args']['headers']['Authorization'] = sprintf(
            'Basic %s',
            // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
            base64_encode("{$this->username}:{$this->password}")
        );

        return $cron_request;
    }
}
