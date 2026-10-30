<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-size: 11pt; color: #111; }
        .title { background: #fbe9d7; color: #c2570c; text-align: center; font-weight: bold; padding: 6px; font-size: 13pt; }
        .boxes td { border: 1px solid #bbb; text-align: center; padding: 5px; font-size: 8pt; color: #555; }
        .boxes b { display: block; color: #d9650d; font-size: 13pt; }
        .name { font-size: 16pt; font-weight: bold; }
        .pill { border: 1px solid #ccc; padding: 2px 6px; font-size: 9pt; }
        .label { font-size: 8pt; color: #777; margin-top: 8px; }
        .foot { border-top: 1px solid #ddd; margin-top: 10px; padding-top: 6px; font-size: 9pt; }
    </style>
</head>
<body>
    <div class="title">{{ $voter->election->name }}</div>

    <table class="boxes" width="100%" cellspacing="4" cellpadding="0" style="margin-top:8px">
        <tr>
            <td>WARD NO.<b>{{ $voter->ward->ward_number }}</b></td>
            <td>BOOTH NO.<b>{{ $voter->pollingStation->booth_number }}</b></td>
            <td>SR. NO.<b>{{ $voter->serial_number }}</b></td>
            <td>HOUSE NO.<b>{{ $voter->house_number }}</b></td>
        </tr>
    </table>

    <table width="100%" style="margin-top:10px">
        <tr>
            <td style="width:70px">
                @if($voter->photo)
                    <img src="{{ public_path($voter->photo->photo_path) }}" width="62" height="62">
                @endif
            </td>
            <td>
                <div class="name">{{ $voter->name }}</div>
                <span class="pill">{{ $voter->epic_number }}</span>
                <span class="pill">{{ $voter->gender }}, {{ $voter->age }} yrs</span>
            </td>
        </tr>
    </table>

    <div class="label">ADDRESS</div>
    <div>{{ $voter->house_number }}, {{ $voter->locality }}</div>

    <div class="label">POLLING STATION</div>
    <div>{{ $voter->pollingStation->booth_number }} - {{ $voter->pollingStation->polling_station_name }}, कमरा नं. {{ $voter->pollingStation->room_number }}</div>

    <div class="foot">{{ $voter->relation_type }} का नाम: {{ $voter->relative_name }}</div>
</body>
</html>
