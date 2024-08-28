@component('mail::message')
# Hello

The ingredient {{ $ingredient->name }} is low in stock. The current stock is {{ $ingredient->stock }}.

This is an automated message. Please do not reply to this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
