<?php

namespace App\Notifications;

use App\Models\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CarReservedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 8;

    public function __construct(
        private readonly Car $car,
    ) {
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
     * Retry with progressive backoff to absorb provider limits.
     *
     * @return array<int, int>
     */
    public function backoff(): array
    {
        return [30, 60, 120, 240, 480, 900, 1200];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $carUrl = route('cars.show', $this->car);
        $reservedUntil = $this->car->reserved_until?->format('d/m/Y H:i');

        return (new MailMessage)
            ->subject('Reserva confirmada: '.$this->car->title)
            ->greeting('Olá,')
            ->line('A sua reserva foi registada com sucesso.')
            ->line('Veículo: '.$this->car->title)
            ->line('Marca: '.$this->car->brand)
            ->line('Modelo: '.$this->car->model)
            ->line('Preço: EUR '.number_format($this->car->price, 0, ',', ' '))
            ->line('Reserva válida até: '.$reservedUntil)
            ->action('Ver veículo', $carUrl)
            ->line('Obrigado por escolher a Br3basMotors.');
    }
}
