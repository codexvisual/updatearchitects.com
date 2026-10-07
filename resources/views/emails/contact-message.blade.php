<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>New contact message</title>
</head>
<body style="margin:0;padding:0;background-color:#f5f5f4;font-family:Arial,Helvetica,sans-serif;color:#1c1917;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color:#f5f5f4;padding:24px 12px;">
    <tr>
        <td align="center">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background-color:#ffffff;border:1px solid #e7e5e4;border-radius:12px;overflow:hidden;">
                <tr>
                    <td style="background-color:#c7432c;padding:20px 24px;">
                        <p style="margin:0;font-size:17px;font-weight:bold;color:#ffffff;">New contact message</p>
                        <p style="margin:6px 0 0;font-size:13px;color:#fde0d8;">{{ config('app.name') }}</p>
                    </td>
                </tr>
                <tr>
                    <td style="padding:24px;">
                        <p style="margin:0 0 18px;font-size:15px;line-height:1.6;">Someone has sent a message through the website contact form. It is already stored in the CMS.</p>

                        <table role="presentation" width="100%" cellpadding="0" cellspacing="0" border="0" style="border:1px solid #e7e5e4;border-radius:8px;border-collapse:separate;">
                            <tr>
                                <td style="padding:10px 14px;font-size:13px;color:#78716c;background-color:#fafaf9;border-bottom:1px solid #e7e5e4;width:34%;">Name</td>
                                <td style="padding:10px 14px;font-size:14px;border-bottom:1px solid #e7e5e4;">{{ $contactMessage->name }}</td>
                            </tr>
                            <tr>
                                <td style="padding:10px 14px;font-size:13px;color:#78716c;background-color:#fafaf9;border-bottom:1px solid #e7e5e4;">Email</td>
                                <td style="padding:10px 14px;font-size:14px;border-bottom:1px solid #e7e5e4;"><a href="mailto:{{ $contactMessage->email }}" style="color:#c7432c;">{{ $contactMessage->email }}</a></td>
                            </tr>
                            @if($contactMessage->phone)
                                <tr>
                                    <td style="padding:10px 14px;font-size:13px;color:#78716c;background-color:#fafaf9;border-bottom:1px solid #e7e5e4;">Phone</td>
                                    <td style="padding:10px 14px;font-size:14px;border-bottom:1px solid #e7e5e4;">{{ $contactMessage->phone }}</td>
                                </tr>
                            @endif
                            @if($contactMessage->subject)
                                <tr>
                                    <td style="padding:10px 14px;font-size:13px;color:#78716c;background-color:#fafaf9;border-bottom:1px solid #e7e5e4;">Subject</td>
                                    <td style="padding:10px 14px;font-size:14px;border-bottom:1px solid #e7e5e4;">{{ $contactMessage->subject }}</td>
                                </tr>
                            @endif
                            <tr>
                                <td style="padding:10px 14px;font-size:13px;color:#78716c;background-color:#fafaf9;">Language</td>
                                <td style="padding:10px 14px;font-size:14px;">{{ strtoupper($contactMessage->locale) }}</td>
                            </tr>
                        </table>

                        <p style="margin:20px 0 6px;font-size:13px;color:#78716c;text-transform:uppercase;letter-spacing:.06em;">Message</p>
                        <p style="margin:0;font-size:15px;line-height:1.7;white-space:pre-line;border-left:3px solid #c7432c;padding-left:14px;">{{ $contactMessage->message }}</p>

                        <p style="margin:24px 0 0;">
                            <a href="{{ route('admin.contacts.index') }}" style="display:inline-block;background-color:#c7432c;color:#ffffff;text-decoration:none;padding:11px 20px;border-radius:8px;font-size:14px;font-weight:bold;">Open in the CMS</a>
                        </p>
                        <p style="margin:16px 0 0;font-size:12px;color:#a8a29e;">Received {{ $contactMessage->created_at?->format('d M Y, h:i A') }}</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
