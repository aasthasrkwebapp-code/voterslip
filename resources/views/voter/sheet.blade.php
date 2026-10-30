<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-size: 8.5pt; color: #111; }
        table.grid { width: 100%; border-collapse: collapse; }
        table.grid td { width: 50%; vertical-align: top; padding: 0 3mm 4mm 3mm; }
        .slip { border: 0.3mm solid #999; padding: 2mm; height: 60mm; }
        .head { text-align: center; font-size: 7.5pt; color: #333; }
        .meta { font-size: 8pt; margin-top: 1mm; }
        .epic { border: 0.2mm solid #333; padding: 0.5mm 1.5mm; font-weight: bold; }
        .name { font-size: 10pt; font-weight: bold; margin-top: 1.5mm; }
        .photo { width: 16mm; height: 20mm; }
        .break { page-break-after: always; }
    </style>
</head>
<body>
<table class="grid">
        @foreach($voters->chunk(2) as $row)
            <tr>
                @foreach($row as $voter)
                    <td>
                        <div class="slip">
                            <div class="head">पंचायत समिति चुनाव - मतदाता पर्ची</div>
                            <div class="head">ग्राम {{ $voter->ward->village_name }} पंचायत समिति {{ $voter->election->panchayat_samiti }} जिला {{ $voter->election->district }}</div>
                            <div class="head">मतदान केंद्र बूथ संख्या: {{ $voter->pollingStation->booth_number }} कमरा न {{ $voter->pollingStation->room_number }}</div>
                            <table class="meta" width="100%">
                                <tr>
                                    <td>मतदाता क्रमांक: {{ $voter->serial_number }}</td>
                                    <td align="right">वार्ड न {{ $voter->ward->ward_number }}</td>
                                </tr>
                            </table>
                            <table width="100%" style="margin-top:2mm">
                                <tr>
                                    <td>
                                        <span class="epic">{{ $voter->epic_number }}</span>
                                        <div class="name">नाम: {{ $voter->name }}</div>
                                        <div>{{ $voter->relation_type }} का नाम: {{ $voter->relative_name }}</div>
                                        <div>मकान संख्या: {{ $voter->house_number }}</div>
                                        <div>आयु: {{ $voter->age }} &nbsp;&nbsp; लिंग: {{ $voter->gender }}</div>
                                    </td>
                                    <td align="right" style="width:18mm">
                                        @if($voter->photo)
                                            <img class="photo" src="{{ public_path($voter->photo->photo_path) }}">
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</body>
</html>
