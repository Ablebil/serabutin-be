<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.mail.verify_subject') }}</title>
</head>

<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #1f2937;">
    <p>{{ __('auth.mail.verify_greeting', ['name' => $fullName]) }}</p>
    <p>{{ __('auth.mail.verify_intro') }}</p>
    <p>{{ __('auth.mail.verify_action') }}</p>
    <p>
        <a href="{{ $verificationUrl }}" target="_blank"
            rel="noopener noreferrer">{{ __('auth.mail.verify_button') }}</a>
    </p>
    <p>{{ __('auth.mail.verify_outro') }}</p>
</body>

</html>