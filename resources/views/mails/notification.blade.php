<!DOCTYPE html>
<html lang="en" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="x-apple-disable-message-reformatting">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="format-detection" content="telephone=no, date=no, address=no, email=no">
    
    <title>{{ config('app.name') }}</title>
    {{-- <link href="https://fonts.googleapis.com/css?family=Montserrat:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700" rel="stylesheet" media="screen"> --}}
    
    <style>
        .hover-underline:hover {
            text-decoration: underline !important;
        }
        @media (max-width: 600px) {
            .sm-w-full {
                width: 100% !important;
            }
            .sm-px-24 {
                padding-left: 24px !important;
                padding-right: 24px !important;
            }
            .sm-py-32 {
                padding-top: 32px !important;
                padding-bottom: 32px !important;
            }
            .sm-leading-32 {
                line-height: 32px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; width: 100%; padding: 0; word-break: break-word; -webkit-font-smoothing: antialiased; background-color: #eceff1;">
    
    <div role="article" aria-roledescription="email" aria-label="{{ config('app.name') }}" lang="en" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
        <table style="width: 100%; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;" cellpadding="0" cellspacing="0" role="presentation">
            <tr>
                <td align="center" style="mso-line-height-rule: exactly; background-color: #eceff1; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                    <table class="sm-w-full" style="width: 600px;" cellpadding="0" cellspacing="0" role="presentation">
                        <tr>
                            <td class="sm-py-32 sm-px-24" style="mso-line-height-rule: exactly; padding: 48px; text-align: center; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                                
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="sm-px-24" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                    <tr>
                                        <td class="sm-px-24" style="mso-line-height-rule: exactly; border-radius: 4px; background-color: #ffffff; padding: 48px; text-align: left; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 16px; line-height: 24px; color: #626262;">
                                            <p style="mso-line-height-rule: exactly; margin-top: 0; margin-bottom: 50px; text-align: center; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif;">
                                                <a href="{{ config('app.url') }}" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly;">
                                                    <img src="{{ config('base.mail.logo') }}" width="155" alt="Logo" style="max-width: 100%; vertical-align: middle; line-height: 100%; border: 0; height: 60px; width: auto;">
                                                </a>
                                            </p>
                                            {{-- <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-bottom: 0; font-size: 20px; font-weight: 600;">Hey</p>
                                            <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin-top: 0; font-size: 24px; font-weight: 700; color: #ff5850;">John Doe!</p> --}}
                                            <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0; margin-bottom: 24px;">
                                                Thanks for using <b>{{ config('app.name') }}</b>. 
                                                <br>This is the latest notification for you.
                                            </p>

                                            <table style="width: 100%; margin-bottom: 50px;" cellpadding="0" cellspacing="0" role="presentation">
                                                <tbody>
                                                    <tr>
                                                        <td align="left" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; min-width: 100px; vertical-align: top;">
                                                            Module
                                                        </td>
                                                        <td align="center" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; width: 20px; vertical-align: top;">
                                                            :
                                                        </td>
                                                        <td align="left" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; vertical-align: top;">
                                                            <div style="font-weight: 600">
                                                                {!! $record->show_module !!}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; min-width: 100px; vertical-align: top;">
                                                            Description
                                                        </td>
                                                        <td align="center" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; width: 20px; vertical-align: top;">
                                                            :
                                                        </td>
                                                        <td align="left" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; vertical-align: top;">
                                                            <div style="font-weight: 600">
                                                                {!! $record->show_message !!}
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @if ($creator = $record->creator)
                                                        <tr>
                                                            <td align="left" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; min-width: 100px; vertical-align: top;">
                                                                Created By
                                                            </td>
                                                            <td align="center" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; width: 20px; vertical-align: top;">
                                                                :
                                                            </td>
                                                            <td align="left" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; vertical-align: top;">
                                                                {!! $record->creator->name !!}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                    @if ($created_at = $record->created_at)
                                                        <tr>
                                                            <td align="left" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; min-width: 100px; vertical-align: top;">
                                                                Created At
                                                            </td>
                                                            <td align="center" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; width: 20px; vertical-align: top;">
                                                                :
                                                            </td>
                                                            <td align="left" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-bottom: 8px; vertical-align: top;">
                                                                {!! $record->created_at->translatedFormat('d F Y, H:i') !!}
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>

                                            <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td style="mso-line-height-rule: exactly; mso-padding-alt: 16px 24px; border-radius: 4px; text-align: center; background-color: #7367f0; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; margin: auto;">
                                                        <a href="{{ url($record->full_url ?? config('app.url')) }}" target="_blank" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; display: block; padding-left: 24px; padding-right: 24px; padding-top: 16px; padding-bottom: 16px; font-size: 16px; font-weight: 600; line-height: 100%; color: #ffffff; text-decoration: none;">Open in App</a>
                                                    </td>
                                                </tr>
                                            </table>
                                            <table style="width: 100%;" cellpadding="0" cellspacing="0" role="presentation">
                                                <tr>
                                                    <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; padding-top: 32px; padding-bottom: 32px;">
                                                        <div style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 1px; background-color: #eceff1; line-height: 1px;">&zwnj;</div>
                                                    </td>
                                                </tr>
                                            </table>
                                            <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; margin: 0;">Thanks, <br>{{ config('app.name') }} Team</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 20px;"></td>
                                    </tr>
                                    <tr>
                                        <td style="mso-line-height-rule: exactly; padding-left: 48px; padding-right: 48px; font-family: Montserrat, -apple-system, 'Segoe UI', sans-serif; font-size: 14px; color: #eceff1;">
                                            <p style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; color: #263238;">
                                                You're receiving this email because of your account on
                                                <a href="{{ config('app.url') }}" class="hover-underline" style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; color: #7367f0; text-decoration: none;">{{ config('app.url') }}</a>
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="font-family: 'Montserrat', sans-serif; mso-line-height-rule: exactly; height: 16px;"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>

