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
        $key = $this->generateRandomKey();

        if ($this->option('show')) {
            return $this->line('<comment>'.$key.'</comment>');
        }

        $current = $this->setKeyInEnvironmentFile($key);

        $this->line('<comment>'.$current.'</comment>');

        $this->info("Glide sign key [$key] set successfully.");
    }

    /**
     * Set the key in the environment file.
     *
     * @param  string  $key
     * @return void
     */
    protected function setKeyInEnvironmentFile($key)
    {
        $currentContent = file_get_contents(base_path('.env'));

        $currentValue = '';

        if (preg_match('/^GLIDE_SIGN_KEY=(.*)$/m', $currentContent, $matches) && isset($matches[1])) {
            $currentValue = $matches[1];
        }

        file_put_contents(base_path('.env'), str_replace(
            'GLIDE_SIGN_KEY='.$currentValue,
            'GLIDE_SIGN_KEY='.$key,
            $currentContent
        ));
    }

    /**
     * Generate a random key for the application.
     *
     * @return string
     */
    protected function generateRandomKey()
    {
        return base64_encode(random_bytes(128));
    }
}
