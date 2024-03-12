&lt;?php

/**
 * Manages the login page for tenants using Filament resources.
 * Provides UI for tenant authentication.
 */

namespace App\Filament\Resources\TenantResource\Pages;

use Filament\Resources\Pages\Page;
use Filament\Forms;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class Login extends Page
{
    protected static string $view = 'filament.resources.tenant-resource.pages.login';

    protected function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->required()
                    ->label('Username'),
                Forms\Components\PasswordInput::make('password')
                    ->required()
                    ->label('Password'),
            ]);
    }

    public function mount()
    {
        if (Auth::check()) {
            return Redirect::to('/dashboard');
        }
    }

    public function login(array $data)
    {
        if (Auth::attempt(['email' => $data['email'], 'password' => $data['password']])) {
            return Redirect::to($this->redirectTo);
        }

        $this->notify('danger', 'Invalid credentials.');
    }

    protected string $redirectTo = '/dashboard';
}

        $this->notify('danger', 'Invalid credentials.');
    }

    protected string $redirectTo = '/dashboard';
}

    protected string $redirectTo = '/dashboard';
}

        $this->notify('danger', 'Invalid credentials.');
    }

    protected string $redirectTo = '/dashboard';
}
