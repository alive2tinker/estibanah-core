<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class FormInvitation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected $link, $title;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($newLink, $newTitle)
    {
        $this->link = $newLink;
        $this->title = $newTitle;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.form_invitation', [
            'link' => $this->link,
            'user' => Auth::user(),
            'title' => $this->title
        ]);
    }
}
