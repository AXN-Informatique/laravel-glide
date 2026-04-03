<?php

namespace Axn\LaravelGlide\Console\Commands;

use Illuminate\Console\Command;

class GlideKeyGenerate extends Command
{
    protected $signature = 'glide:key-generate {--show : Display the key instead of modifying files}';

    protected $description = 'Set the Glide sign key.';

    public function handle(): int
    {
        if ($this->option('show')) {
            $this->line('<comment>'.$this->getKeyFromEnvironmentFile().'</comment>');

            return self::SUCCESS;
        }

        $key = $this->generateRandomKey();

        $this->setKeyInEnvironmentFile($key);

        $this->info(\sprintf('Glide sign key [%s] set successfully.', $key));

        return self::SUCCESS;
    }

    /**
     * Get the key in the environment file.
     */
    protected function getKeyFromEnvironmentFile(): string
    {
        if (! preg_match('/^GLIDE_SIGN_KEY=(.*)$/m', $this->envFileContent(), $matches)) {
            return '';
        }

        if (! isset($matches[1])) {
            return '';
        }

        return $matches[1];
    }

    /**
     * Set the given key in the environment file.
     */
    protected function setKeyInEnvironmentFile(string $key): void
    {
        file_put_contents(base_path('.env'), str_replace(
            'GLIDE_SIGN_KEY='.$this->getKeyFromEnvironmentFile(),
            'GLIDE_SIGN_KEY='.$key,
            $this->envFileContent()
        ));
    }

    /**
     * Generate a random key for the application.
     */
    protected function generateRandomKey(): string
    {
        return base64_encode(random_bytes(128));
    }

    /**
     * Generate a random key for the application.
     */
    protected function envFileContent(): string
    {
        static $current = null;

        if ($current === null) {
            $current = file_get_contents(base_path('.env'));
        }

        return $current;
    }
}
