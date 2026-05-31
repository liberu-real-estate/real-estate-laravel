<?php

namespace Tests\Feature;

use JoelButcher\Socialstream\Providers;
use Tests\TestCase;

class SocialstreamConfigTest extends TestCase
{
    /**
     * @test
     */
    public function test_socialstream_config_has_social_media_providers(): void
    {
        $providers = config('socialstream.providers');

        $this->assertNotEmpty($providers, 'Socialstream providers should not be empty');
        $this->assertIsArray($providers);
    }

    /**
     * @test
     */
    #[\PHPUnit\Framework\Attributes\DataProvider('socialMediaProviderDataProvider')]
    public function test_socialstream_config_includes_provider(string $providerName): void
    {
        $providers = config('socialstream.providers');

        $names = collect($providers)->map(fn ($p) => is_object($p) ? $p->id : $p)->all();

        $this->assertContains(
            $providerName,
            $names,
            "Provider '{$providerName}' should be enabled in socialstream config"
        );
    }

    public static function socialMediaProviderDataProvider(): array
    {
        return [
            'bitbucket'      => ['bitbucket'],
            'facebook'       => ['facebook'],
            'github'         => ['github'],
            'gitlab'         => ['gitlab'],
            'google'         => ['google'],
            'linkedin'       => ['linkedin'],
            'linkedinOpenId' => ['linkedin-openid'],
            'slack'          => ['slack'],
            'twitter-oauth-2' => ['twitter-oauth-2'],
        ];
    }

    /**
     * @test
     */
    public function test_socialstream_config_excludes_twitter_oauth1(): void
    {
        $providers = config('socialstream.providers');

        $names = collect($providers)->map(fn ($p) => is_object($p) ? $p->id : $p)->all();

        $this->assertNotContains(
            'twitter',
            $names,
            'Twitter OAuth 1.0 should not be enabled (requires live API keys)'
        );
    }

    /**
     * @test
     */
    public function test_socialstream_config_has_middleware(): void
    {
        $middleware = config('socialstream.middleware');

        $this->assertContains('web', $middleware);
    }

    /**
     * @test
     */
    public function test_socialstream_config_has_prompt(): void
    {
        $prompt = config('socialstream.prompt');

        $this->assertIsString($prompt);
        $this->assertNotEmpty($prompt);
    }
}
