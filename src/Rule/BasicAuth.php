<?php

namespace Alpaca\StoreRouter\Rule;

use Exception;
use Symfony\Component\HttpFoundation\IpUtils;

class BasicAuth extends Rule implements RuleContract
{

    /**
     * @param string $value
     *
     * @return bool
     */
    public function assert(string $value): ?bool
    {
        [$user, $pass] = explode(':', $value) + [null, null];

        if (empty($user) || empty($pass)) {
            return false;
        }

        if ((!isset($_SERVER['PHP_AUTH_USER'])
                || $_SERVER['PHP_AUTH_USER'] !== $user
                || $_SERVER['PHP_AUTH_PW'] !== $pass)
            && !$this->remoteAddressIsWhitelisted()
        ) {
            $this->unauthorize();
        }

        return true;
    }

    /**
     * @return bool
     */
    protected function remoteAddressIsWhitelisted()
    {
        if (empty($this->getGroupId())) {
            return false;
        }

        try {
            $whitelistFilePath = BP . "/app/etc/ip-whitelist/" . $this->getGroupId();

            if (!is_readable($whitelistFilePath)) {
                return false;
            }

            $whitelistCidrs = array_filter(
                array_map('trim', explode("\n", file_get_contents($whitelistFilePath)))
            );
        } catch (Exception $e) {
            return false;
        }

        if (!is_array($whitelistCidrs)) {
            return false;
        }

        foreach ($whitelistCidrs as $cidr) {
            // Ignore commented lines.
            if (substr($cidr, 0, 1) === "#") {
                continue;
            }

            if ($this->cidrContainsIpAddress($this->app->remoteAddress(), $cidr)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $address
     * @param string $cidr
     *
     * @return bool
     */
    protected function cidrContainsIpAddress($address, $cidr)
    {
        return IpUtils::checkIp($address, $cidr);
    }

    /**
     * @return void
     */
    protected function unauthorize()
    {
        header('WWW-Authenticate: Basic realm="Restricted site"');
        header('HTTP/1.0 401 Unauthorized');

        die('<h1>401 - Unauthorized</h1>');
    }
}
