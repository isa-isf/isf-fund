<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\UserPasswordSetup;
use Illuminate\Console\Command;

class UserAddCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:add {--name=} {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = new User;

        $user->name = $this->option('name') ?: $this->ask('What\'s the name of user');
        $user->email = $this->option('email') ?: $this->ask('What\'s the E-Mail of user');
        $user->password = '';

        $user->save();

        $user->notify(new UserPasswordSetup);

        return 0;
    }
}
