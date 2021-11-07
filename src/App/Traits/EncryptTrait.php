<?php

namespace Gouh\BlogApi\App\Traits;

trait EncryptTrait
{
    /**
     * @param string $phrase
     * @return string
     */
    private function encrypt(string $phrase): string
    {
        $secret = $this->config['secret'];
        return hash('sha256', $phrase . $secret);
    }
}
