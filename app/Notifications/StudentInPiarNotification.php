<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentInPiarNotification extends Notification
{
    use Queueable;

    protected $student;

    /**
     * Create a new notification instance.
     */
    public function __construct($student)
    {
        $this->student = $student;
    }

    /**
     * Canales de envío
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Correo
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Estudiante incluido en PIAR')
            ->line('El estudiante '.$this->student->name.' '.$this->student->last_name.' ha sido incluido en PIAR.')
            ->line('Puede revisar el informe en el sistema.')
            ->action('Ver estudiante', url('/'))
            ->line('Sistema de orientación escolar.');
    }

    /**
     * Array (si usas base de datos después)
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id_user_student' => $this->student->id,
            'student_name' => $this->student->name.' '.$this->student->last_name,
        ];
    }
}