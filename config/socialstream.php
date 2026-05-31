<?php

use JoelButcher\Socialstream\Providers;

return [
    'middleware' => ['web'],
    'prompt' => 'Or Login Via',
    'providers' => [
        Providers::bitbucket(),
        Providers::facebook(),
        Providers::github(),
        Providers::gitlab(),
        Providers::google(),
        Providers::linkedin(),
        Providers::linkedinOpenId(),
        Providers::slack(),
        Providers::twitterOAuth2(),
    ],
    'component' => 'socialstream::components.socialstream',
    'show_provider_ids' => env('SOCIALSTREAM_SHOW_PROVIDER_IDS', false),
    'features' => [
        // \JoelButcher\Socialstream\Features::createAccountOnFirstLogin(),
        // \JoelButcher\Socialstream\Features::generateMissingEmails(),
        // \JoelButcher\Socialstream\Features::loginOnRegistration(),
        // \JoelButcher\Socialstream\Features::rememberSession(),
    ],
];
