<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Delete Verification</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,600,700,800,900" rel="stylesheet">

</head>

<body style="background:#f1f1f1;padding-top:20px;padding-bottom:20px;">
    <center>
        <table class="" border="0" cellspacing="0" cellpadding="0" width="600"
            style="width:6.25in;background:#ffffff; border-collapse:collapse">
            <tbody>
                <tr>
                    <td height="30"></td>
                </tr>
                <tr>
                    <td style="padding-left:20px;">
                        <p style="margin:5px 0px 5px 0px;font-size:20px;color:#222;font-family: Montserrat;font-weight:500;">
                            {{__('page.delete_verification_greeting')}}
                        </p>
                        <h3>{{$data['verification_code']}}</h3>
                    </td>
                </tr>
                <tr>
                    <td style="padding-left:20px;">
                        <p style="margin:5px 0px 5px 0px;font-size:18px;color:#222;font-family: Montserrat;font-weight:500;">
                            {{__('page.your_requested_data')}}
                        </p>
                        <table border="0" cellspacing="0" cellpadding="0" width="500"
                        style="width:6.25in;background:#ffffff; border-collapse:collapse">
                            <tr>
                                <td>{{__('page.date')}}</td>
                                <td>{{$data['period']}}</td>
                            </tr>
                            <tr>
                                <td>{{__('page.user')}}</td>
                                @php
                                    $user_id_array = $data['user'] != '' ? explode(',', $data['user']) : [];
                                    $user_name_array = [];
                                    foreach($user_id_array as $id) {
                                        $user = \App\User::find($id);
                                        array_push($user_name_array, $user->name);
                                    }
                                @endphp
                                <td>
                                    @if (count($user_name_array))
                                        {{implode(", ", $user_name_array)}}
                                    @else
                                        {{__('page.all_users')}}
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td height="30"></td>
                </tr>
            </tbody>
        </table>
    </center>
</body>

</html>
