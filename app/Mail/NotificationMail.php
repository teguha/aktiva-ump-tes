<?php

namespace App\Mail;

use App\Models\Globals\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $record;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Notification $record)
    {
        $this->record  = $record;
        $this->subject = $record->show_module.' - '.$record->show_message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject($this->subject)
                    ->view('mails.notification')
                    ->with([
                        'record' => $this->record
                    ]);
    }
}
