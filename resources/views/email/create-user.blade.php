<!doctype html>
<html xmlns="http://www.w3.org/1999/xhtml">
@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo = App\Models\Utility::getValByName('company_logo');
@endphp
<head>
    <title>User Account Created</title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        body { background-color:#f8f8f8; margin: 0; padding: 0; font-family: 'Open Sans', Helvetica, Arial, sans-serif; }
        table { border-collapse: collapse; width: 100%; }
        img { border: none; display: block; height: auto; }
    </style>
</head>
<body>
<div style="background-color:#f8f8f8;">
    <table align="center" style="width:600px;">
        <tr>
            <td style="padding: 20px; text-align: center;">
                <img src="{{ $logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') }}" alt="Company Logo" width="110" />
            </td>
        </tr>
        <tr>
            <td style="background-color:#ffffff; padding: 40px;">
                <h2 style="color: #333333;">{{ __('Welcome to') }} {{ env('APP_NAME') }}</h2>
                <p>{{ __('Dear') }} {{ $user['name'] }},</p>
                <p>{{ __('We are excited to inform you that your account has been successfully created. Below are your login details:') }}</p>
                <ul>
                    <li><strong>{{ __('Email') }}:</strong> {{ $user['email'] }}</li>
                    <li><strong>{{ __('Password') }}:</strong> {{ $user['password'] }}</li>
                </ul>
                <p>{{ __('Please keep your login details secure and change your password after logging in') }}.</p>
                <p>{{ __('Feel free to contact us if you have any questions or need further assistance') }}.</p>
                <p><strong>{{ __('Regards') }},</strong><br>{{ env('APP_NAME') }}</p>
            </td>
        </tr>
    </table>
</div>
</body>
</html>