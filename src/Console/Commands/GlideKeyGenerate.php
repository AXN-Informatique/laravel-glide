<?php

declare(strict_types=1);

namespace Axn\LaravelGlide\Console\Commands;

use Illuminate\Console\Command;

class GlideKeyGenerate extends Command
{
    protected $signature = 'glide:key-generate {--show : Display the key instead of modifying files}';

    protected $description = 'Set the Glide sign key.';

    private ?string $envFileContent = null;

    public function handle(): int
    {
        if ($this->option('show')) {
            $this->line('<comment>'.$this->getKeyFromEnvironmentFile().'</comment>');

            return self::SUCCESS;
        }

        $key = $this->generateRandomKey();

        $this->setKeyInEnvironmentFile($key);

        $this->components->info(\sprintf('Glide sign key [%s] set successfully.', $key));

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

        return $matches[1];
    }

    /**
     * Set the given key in the environment file, appending the line if it is missing.
     */
    protected function setKeyInEnvironmentFile(string $key): void
    {
        $content = $this->envFileContent();

        if (preg_match('/^GLIDE_SIGN_KEY=/m', $content)) {
            $content = str_replace(
                'GLIDE_SIGN_KEY='.$this->getKeyFromEnvironmentFile(),
                'GLIDE_SIGN_KEY='.$key,
                $content
            );
        } else {
            $content .= ($content === '' || str_ends_with($content, "\n") ? '' : "\n").'GLIDE_SIGN_KEY='.$key."\n";
        }

        file_put_contents(base_path('.env'), $content);
    }

    /**
     * Generate a random key for the application.
     */
    protected function generateRandomKey(): string
    {
        return base64_encode(random_bytes(128));
    }

    /**
     * Get the content of the environment file.
     */
    protected function envFileContent(): string
    {
        return $this->envFileContent ??= file_get_contents(base_path('.env'));
    }
}
