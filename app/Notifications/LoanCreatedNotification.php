<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoanCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        protected Loan $loan
    ) {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Novo Empréstimo Criado')
            ->line('Um novo empréstimo foi criado com sucesso.')
            ->line('Detalhes do Empréstimo:')
            ->line('ID do Empréstimo: ' . $this->loan->id)
            ->line('Livro: ' . $this->loan->book->title)
            ->line('Data de Empréstimo: ' . $this->loan->loan_date)
            ->line('Data de Devolução: ' . $this->loan->return_date)
            ->action('Ver Empréstimo', url('/loans/' . $this->loan->id));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
