<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class TestMailCommand extends Command
{
    protected $signature = 'app:test-mail';
    protected $description = 'Test SMTP mail configuration';

    public function handle()
    {
        $this->info('Testing mail configuration...');

        try {
            Mail::raw('Test email from eAkademik - OTP SMTP is working!', function ($msg) {
                $msg->to('test@example.com')
                    ->subject('SMTP Test - eAkademik OTP');
            });
            $this->info('✓ Email sent successfully! Check your Mailtrap inbox.');
        } catch (\Exception $e) {
            $this->error('✗ Failed: ' . $e->getMessage());
        }

        return Command::SUCCESS;
    }
}