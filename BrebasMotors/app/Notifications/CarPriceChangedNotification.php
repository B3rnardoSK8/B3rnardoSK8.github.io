<?php

namespace App\Notifications;

use App\Models\Car;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CarPriceChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public int $tries = 8;

    public function __construct(
        private readonly Car $car,
        private readonly float $oldPrice,
        private readonly float $newPrice,
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

        return (new MailMessage)
            ->subject('Atualização de preço: '.$this->car->title)
            ->greeting('Olá '.$notifiable->name.',')
            ->line('Um veículo que guardou como favorito sofreu uma alteração de preço.')
            ->line('Veículo: '.$this->car->title)
            ->line('Preço anterior: EUR '.number_format($this->oldPrice, 0, ',', ' '))
            ->line('Novo preço: EUR '.number_format($this->newPrice, 0, ',', ' '))
            ->action('Ver veículo', $carUrl)
            ->line('Obrigado por acompanhar a Br3basMotors!');
    }
}
