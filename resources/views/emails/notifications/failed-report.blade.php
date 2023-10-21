@component('mail::message')
# Order reporting failed - Amazon

## Order ID: {{ $order->id }}

```php
{{ $exception->getMessage() }}
```

Thanks,<br>
{{ config('app.name') }}
@endcomponent
