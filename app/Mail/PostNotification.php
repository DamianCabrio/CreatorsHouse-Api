<?php

namespace App\Mail;

use App\Models\Creator;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PostNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $post;
    public $creator;

    /**
     * Create a new message instance.
     *
     * @param Post $post
     * @param Creator $creator
     */
    public function __construct(Post $post, Creator $creator)
    {
        $this->post = $post;
        $this->creator = $creator;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.notification')->subject("¡Un creador que sigue creo un nuevo post!");
    }
}
