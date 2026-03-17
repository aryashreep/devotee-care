<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetPasswordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auth:reset-password {identifier? : The mobile number or email of the user}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset a user\'s password securely';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $identifier = $this->argument('identifier');

        if (!$identifier) {
            $identifier = $this->ask('Enter the user\'s mobile number or email');
        }

        $user = User::where('mobile_number', $identifier)
            ->orWhere('email', $identifier)
            ->first();

        if (!$user) {
            $this->error("User with identifier [{$identifier}] not found.");
            return 1;
        }

        $password = $this->secret('Enter the new password');
        $confirmPassword = $this->secret('Confirm the new password');

        if ($password !== $confirmPassword) {
            $this->error('Passwords do not match.');
            return 1;
        }

        if (strlen($password) < 8) {
            $this->error('Password must be at least 8 characters long.');
            return 1;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("Password for user [{$user->name}] has been reset successfully.");
        return 0;
    }
}
