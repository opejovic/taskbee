<?php

namespace taskbee\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TaskDeleted extends Notification
{
    use Queueable;

    /**
     * The task instance
     * 
     * @var \App\Models\Task
     */
    protected $task;

    /**
     * The user instance
     * 
     * @var \App\Models\user
     */
    protected $user;

    /**
     * Create a new notification instance.
     *
     * @param $task
     * @param $user
     */
	public function __construct($task, $user)
	{
		
		$this->task = $task;
		$this->user = $user;
	}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database']; // can be changed to mail -- it fires it auto.
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
			'member' => "{$this->user->full_name}",
			'message' => "deleted task '{$this->task->name}'."
        ];
    }
}
