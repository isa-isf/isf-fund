<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeactivateUserCommand extends Command
{
    protected $signature = 'user:deactivate --email=';
    protected $description = 'Deactivate a user.';

    public function handle()
    {
        $user = User::whereEmail($this->option('email'))->firstOrFail();
        $user->is_active = false;
        $user->save();
        $this->line('Done.');

        return 0;
    }
}
