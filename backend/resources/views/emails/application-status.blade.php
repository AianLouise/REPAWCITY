<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $data['title'] }}</title>
</head>
<body style="margin:0;padding:0;background:#f5e6d3;font-family:Arial,Helvetica,sans-serif;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f5e6d3;padding:32px 16px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" max-width="560" cellspacing="0" cellpadding="0" style="max-width:560px;background:#ffffff;border-radius:16px;overflow:hidden;">
                    <tr>
                        <td style="background:#4a2c17;padding:24px;text-align:center;">
                            <h1 style="margin:0;color:#f5e6d3;font-size:22px;">rePaw City</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:32px;">
                            <h2 style="margin:0 0 16px;color:#6c4421;font-size:20px;">{{ $data['title'] }}</h2>
                            <p style="margin:0 0 16px;color:#555555;line-height:1.6;">
                                @if (isset($data['pet_name']))
                                    Pet: <strong>{{ $data['pet_name'] }}</strong><br>
                                @endif
                                @if (isset($data['appointment_type']))
                                    Service: <strong>{{ $data['appointment_type'] }}</strong><br>
                                @endif
                                @if (isset($data['appointment_date']))
                                    Date: <strong>{{ $data['appointment_date'] }}</strong><br>
                                @endif
                                @if (isset($data['time_slot']))
                                    Time: <strong>{{ $data['time_slot'] }}</strong><br>
                                @endif
                                Status: <strong>{{ $data['status'] ?? 'Updated' }}</strong>
                            </p>
                            <p style="margin:0 0 24px;color:#555555;line-height:1.6;white-space:pre-line;">{{ $data['message'] }}</p>
                            <p style="margin:0;color:#888888;font-size:13px;">If you have questions, reply to this email or contact us at repawcity@gmail.com.</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
