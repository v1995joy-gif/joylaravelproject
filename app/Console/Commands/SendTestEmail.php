<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendTestEmail extends Command
{
    protected $signature = 'mail:test {to : Recipient address} {--subject=Event Apparel SMTP Test} {--mailer= : Test a single mailer (phpmailer, smtp, log) instead of the failover chain}';

    protected $description = 'Send a test email through the configured mailer (failover: phpmailer -> smtp -> log)';

    public function handle(): int
    {
        $to = $this->argument('to');
        $mailer = $this->option('mailer') ?: config('mail.default');

        $this->info('Mailer: '.$mailer);
        $this->info('Host:   '.(config("mail.mailers.{$mailer}.host") ?: config('mail.mailers.smtp.host') ?: '(none)'));

        try {
            Mail::mailer($mailer)->raw(
                "If you can read this, your Event Apparel mailer works.\nSent: ".now()->toDateTimeString(),
                fn ($m) => $m->to($to)->subject($this->option('subject'))
            );
            $this->info("Sent to {$to} — check inbox/spam.");
            if ($mailer === 'failover') {
                $this->warn('Failover masks failures: if nothing arrived, check laravel.log for a log-fallback copy.');
            }

            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Send failed: '.$e->getMessage());
            $this->warn('With MAIL_MAILER=failover the message was written to storage/logs/laravel.log instead.');

            return self::FAILURE;
        }
    }
}
