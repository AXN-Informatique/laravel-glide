<?php

namespace Axn\LaravelGlide\Console\Commands;

use Illuminate\Console\Command;

class GlideKeyGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'glide:key-generate {--show : Display the key instead of modifying files}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the Glide sign key.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if ($this->option('show')) {
            return $this->line('<comment>'.$this->getKeyFromEnvironmentFile().'</comment>');
        }

        $key = $this->generateRandomKey();

        $this->setKeyInEnvironmentFile($key);

        $this->info("Glide sign key [$key] set successfully.");

        return self::SUCCESS;
    }

    /**
     * Get the key in the environment file.
     */
    protected function getKeyFromEnvironmentFile(): string
    {
        $currentValue = '';

        if (preg_match('/^GLIDE_SIGN_KEY=(.*)$/m', $this->envFileContent(), $matches) && isset($matches[1])) {
            $currentValue = $matches[1];
        }

        return $currentValue;
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
