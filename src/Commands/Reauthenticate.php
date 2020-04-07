<?php

namespace Stickee\Laravel2fa\Commands;

use Illuminate\Console\Command;

class Reauthenticate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = '2fa:reauthenticate
        {--email= : The email of the user to reauthenticate}
        {--force : run without asking for confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate the secret key for a user\'s two factor authentication';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userClass = config('laravel-2fa.user_class');
        $email = $this->option('email');

        if (!$email) {
            $email = $this->ask('What is the user\'s email address?');
        }

        $user = $userClass::where('email', $email)->first();

        if (!$user) {
            $this->error('No user with that email.');

            return;
        }

        $this->info('A new secret will be generated for ' . $user->email);
        $this->info('This action will invalidate the previous secret key.');

        if (!$this->option('force') && !$this->confirm('Do you wish to continue?')) {
            return;
        }

        $google2fa = app('pragmarx.google2fa');

        $user->google2fa_secret = $google2fa->generateSecretKey();
        $user->save();

        $this->info('The new secret is: ' . $user->google2fa_secret);
    }
}
