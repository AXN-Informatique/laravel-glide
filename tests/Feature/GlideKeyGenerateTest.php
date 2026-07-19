<?php

declare(strict_types=1);

namespace Axn\LaravelGlide\Tests\Feature;

use Axn\LaravelGlide\Tests\TestCase;
use Illuminate\Support\Facades\File;

class GlideKeyGenerateTest extends TestCase
{
    private string $envPath;

    private ?string $previousEnvContent = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this->envPath = base_path('.env');

        if (File::exists($this->envPath)) {
            $this->previousEnvContent = File::get($this->envPath);
        }
    }

    protected function tearDown(): void
    {
        if ($this->previousEnvContent !== null) {
            File::put($this->envPath, $this->previousEnvContent);
        } elseif (File::exists($this->envPath)) {
            File::delete($this->envPath);
        }

        parent::tearDown();
    }

    public function test_it_replaces_an_existing_sign_key(): void
    {
        File::put($this->envPath, "APP_NAME=Testing\nGLIDE_SIGN_KEY=old-key\nMAIL_MAILER=log\n");

        $this->artisan('glide:key-generate')->assertSuccessful();

        $content = File::get($this->envPath);

        $this->assertMatchesRegularExpression('/^GLIDE_SIGN_KEY=.+$/m', $content);
        $this->assertStringNotContainsString('GLIDE_SIGN_KEY=old-key', $content);
        $this->assertStringContainsString('APP_NAME=Testing', $content);
        $this->assertStringContainsString('MAIL_MAILER=log', $content);
    }

    public function test_it_appends_the_sign_key_when_the_line_is_missing(): void
    {
        File::put($this->envPath, "APP_NAME=Testing\n");

        $this->artisan('glide:key-generate')->assertSuccessful();

        $content = File::get($this->envPath);

        $this->assertMatchesRegularExpression('/^GLIDE_SIGN_KEY=.+$/m', $content);
        $this->assertStringContainsString('APP_NAME=Testing', $content);
    }

    public function test_show_displays_the_current_key_without_modifying_the_file(): void
    {
        File::put($this->envPath, "GLIDE_SIGN_KEY=current-key\n");

        $this->artisan('glide:key-generate', ['--show' => true])
            ->expectsOutputToContain('current-key')
            ->assertSuccessful();

        $this->assertSame("GLIDE_SIGN_KEY=current-key\n", File::get($this->envPath));
    }
}
