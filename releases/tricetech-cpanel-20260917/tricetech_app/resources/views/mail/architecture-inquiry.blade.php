{{-- Plain-text email, never rendered as HTML: Blade's escaped {{ }} output would
     turn "Cloud & Software" into "Cloud &amp; Software" in the inbox, so the
     visitor's own words are printed literally instead. --}}
New website inquiry
Received: {{ now()->format('F j, Y \a\t g:i A T') }}

Email: {!! $inquiry['email'] !!}
Service interest: {!! ($inquiry['interest'] ?? null) ?: 'General inquiry' !!}

Technical description:
{!! $inquiry['message'] !!}

--
Reply to this message to answer {!! $inquiry['email'] !!} directly.
Submitted through the Tricetech Solutions website.
