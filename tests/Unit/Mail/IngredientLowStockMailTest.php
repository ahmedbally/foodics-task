<?php

namespace Tests\Unit\Mail;

use App\Mail\IngredientLowStockMail;
use App\Models\Ingredient;
use PHPUnit\Framework\TestCase;

class IngredientLowStockMailTest extends TestCase
{
    public function test_mail_subject_is_correct()
    {
        $ingredient = new Ingredient(['name' => 'Sugar']);
        $mail = new IngredientLowStockMail($ingredient);

        $envelope = $mail->envelope();

        $this->assertEquals("Ingredient Sugar Low Stock", $envelope->subject);
    }

    public function test_mail_content_is_correct()
    {
        $ingredient = new Ingredient(['name' => 'Sugar']);
        $mail = new IngredientLowStockMail($ingredient);

        $content = $mail->content();

        $this->assertEquals('emails.ingredient-low-stock', $content->markdown);
    }
}
