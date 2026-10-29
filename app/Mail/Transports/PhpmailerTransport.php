<?php

namespace App\Mail\Transports;

use PHPMailer\PHPMailer\PHPMailer;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\TransportInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\RawMessage;

/**
 * Symfony Mailer transport backed by PHPMailer — the same send path the
 * nexus broker uses (PHPMailer over SMTP), so credentials behave identically.
 */
class PhpmailerTransport implements TransportInterface
{
    public function __construct(private array $config = [])
    {
    }

    public function send(RawMessage $message, ?Envelope $envelope = null): ?SentMessage
    {
        $mail = new PHPMailer(true);
        $cfg = array_merge(config('mail.mailers.phpmailer', []), $this->config);
        $from = config('mail.from');

        try {
            $mail->isSMTP();
            $mail->Host = $cfg['host'] ?? 'localhost';
            $mail->Port = (int) ($cfg['port'] ?? 25);
            $mail->SMTPAuth = ! empty($cfg['username']);
            $mail->Username = trim($cfg['username'] ?? '');
            // Gmail app passwords are pasted as "xxxx xxxx xxxx xxxx" — strip spaces
            $mail->Password = str_replace(' ', '', (string) ($cfg['password'] ?? ''));
            $mail->SMTPSecure = match ($cfg['scheme'] ?? null) {
                'smtps' => PHPMailer::ENCRYPTION_SMTPS,
                'smtp' => PHPMailer::ENCRYPTION_STARTTLS,
                default => '',
            };
            $mail->SMTPAutoTLS = ($cfg['scheme'] ?? '') !== '';
            $mail->Timeout = (int) ($cfg['timeout'] ?? 10);
            $mail->CharSet = 'UTF-8';

            $env = $envelope ?? Envelope::create($message);
            $mail->setFrom(
                $env->getSender()->getAddress(),
                $env->getSender()->getName() ?: ($from['name'] ?? ''),
            );

            if ($message instanceof \Symfony\Component\Mime\Email) {
                $seen = [];
                foreach ($message->getTo() as $a) {
                    $mail->addAddress($a->getAddress(), $a->getName());
                    $seen[strtolower($a->getAddress())] = true;
                }
                foreach ($message->getCc() as $a) {
                    $mail->addCC($a->getAddress(), $a->getName());
                    $seen[strtolower($a->getAddress())] = true;
                }
                foreach ($message->getBcc() as $a) {
                    $mail->addBCC($a->getAddress(), $a->getName());
                    $seen[strtolower($a->getAddress())] = true;
                }
                foreach ($message->getReplyTo() as $a) {
                    $mail->addReplyTo($a->getAddress(), $a->getName());
                }
                // Envelope recipients absent from headers still need delivery
                foreach ($env->getRecipients() as $r) {
                    if (! isset($seen[strtolower($r->getAddress())])) {
                        $mail->addAddress($r->getAddress(), $r->getName());
                    }
                }

                $mail->Subject = $message->getSubject() ?? '';
                if ($html = $message->getHtmlBody()) {
                    $mail->isHTML(true);
                    $mail->Body = $html;
                    $mail->AltBody = $message->getTextBody() ?: strip_tags($html);
                } else {
                    $mail->isHTML(false);
                    $mail->Body = $message->getTextBody() ?? '';
                }
                foreach ($message->getAttachments() as $att) {
                    $body = $att->getBody();
                    $mail->addStringAttachment(
                        is_resource($body) ? stream_get_contents($body) : $body,
                        $att->getFilename() ?? 'attachment',
                        'base64',
                        $att->getContentType(),
                    );
                }

                // Carry non-standard headers (X-*, Laravel message metadata).
                $handled = ['to', 'cc', 'bcc', 'from', 'sender', 'reply-to', 'subject',
                    'content-type', 'content-transfer-encoding', 'mime-version',
                    'date', 'message-id'];
                foreach ($message->getHeaders()->all() as $name => $header) {
                    if (! in_array(strtolower($name), $handled, true)) {
                        $mail->addCustomHeader($name, $header->getBodyAsString());
                    }
                }
            } else {
                // A RawMessage carries full RFC822 source (headers included) —
                // stuffing it into Body would produce malformed mail.
                throw new \InvalidArgumentException(
                    'PhpmailerTransport only supports Email messages, not raw RFC822.'
                );
            }

            $mail->send();
        } catch (\Throwable $e) {
            // Must be a TransportExceptionInterface so Symfony's failover
            // transport marks this mailer dead and falls through to `log`.
            throw new TransportException(
                sprintf('PHPMailer transport "%s" failed: %s', $cfg['host'] ?? '?', $e->getMessage()),
                0,
                $e,
            );
        }

        $sent = new SentMessage($message, $env);
        $sent->setMessageId($mail->getLastMessageID());

        return $sent;
    }

    public function __toString(): string
    {
        return 'phpmailer://';
    }
}
