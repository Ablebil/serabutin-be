<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('auth.mail.verify_subject') }}</title>
</head>

<body style="margin:0; padding:0; background-color:#E4DDD5; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#E4DDD5; padding:20px 0;">
        <tr>
            <td align="center">

                <table width="100%" cellpadding="0" cellspacing="0"
                    style="max-width:600px; background:#ffffff; border-radius:10px; overflow:hidden;">

                    <tr>
                        <td style="background-color:#F97316; padding:20px; text-align:center; color:white;">
                            <h1 style="margin:0; font-size:22px;">
                                {{ __('auth.mail.verify_subject') }}
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px; color:#1f2937; font-size:14px; line-height:1.6;">

                            <p style="margin-top:0;">
                                {{ __('auth.mail.verify_greeting', ['name' => $fullName]) }}
                            </p>

                            <p>
                                {{ __('auth.mail.verify_intro') }}
                                <strong style="font-weight:bold; color:#F97316;">Serabutin</strong>.
                            </p>

                            <p>
                                {{ __('auth.mail.verify_action') }}
                            </p>

                            <div style="text-align:center; margin:30px 0;">
                                <a href="{{ $verificationUrl }}" target="_blank" style="
                                        background-color:#F97316;
                                        color:white;
                                        text-decoration:none;
                                        padding:12px 24px;
                                        border-radius:6px;
                                        display:inline-block;
                                        font-weight:bold;
                                        font-size:14px;
                                        line-height:20px;
                                    ">
                                    {{ __('auth.mail.verify_button') }}
                                </a>
                            </div>

                            <!-- Fallback link -->
                            <p style="font-size:12px; color:#6b7280; word-break:break-all;">
                                Jika tombol tidak bekerja, salin dan buka link berikut:
                                <br>
                                <a href="{{ $verificationUrl }}" style="color:#F97316;">
                                    {{ $verificationUrl }}
                                </a>
                            </p>

                            <p>
                                {{ __('auth.mail.verify_outro') }}
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f9fafb; padding:20px; text-align:center; font-size:12px; color:#6b7280;">
                            <p style="margin:0;">
                                &copy; {{ date('Y') }} Serabutin. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>

</body>

</html>