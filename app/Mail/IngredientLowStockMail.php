<?php

namespace App\Mail;

use App\Models\Ingredient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IngredientLowStockMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Ingredient $ingredient
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Ingredient {$this->ingredient->name} Low Stock",
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.ingredient-low-stock',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
