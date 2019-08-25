<?php

namespace taskbee\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class TaskCreatedEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The task instance.
     *
     * @var \App\Models\Task
     */
    public $task;

    /**
     * The authenticated user instance.
     *
     * @var \App\Models\User
     */
    public $authUser;

    /**
     * Create a new message instance.
     *
     * @param $task
     * @param $authUser
     */
    public function __construct($task, $authUser)
    {
        $this->task = $task;
        $this->authUser = $authUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->authUser->email)->markdown('emails.task-created');
    }
}
