<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\UserPasswordSetup;
use Illuminate\Console\Command;

class UserResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-password --user=';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send reset email';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::find($this->option('user'));

        $this->line('Name: ' . $user->name);
        $this->line('E-Mail: ' . $user->email);
        $result = $this->confirm('Are you sure to reset?');
        if (!$result) {
            return 0;
        }

        $user->password = '';
        $user->save();
        $user->notify(new UserPasswordSetup);

        return 0;
    }
}
