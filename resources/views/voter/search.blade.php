<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Voter E-Slip Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #f5f5f4; }
        .slip-card { max-width: 380px; border-radius: 16px; background: #fff; border: 1px solid #e7e5e4; }
        .election-banner { background: #fdf1e4; color: #c2570c; border-radius: 10px; font-weight: 600; }
        .info-box { border: 1px solid #d6d3d1; border-radius: 10px; }
        .info-box .label { font-size: .7rem; color: #78716c; letter-spacing: .04em; }
        .info-box .value { color: #ea580c; font-weight: 700; font-size: 1.15rem; }
        .avatar { width: 84px; height: 84px; border-radius: 50%; object-fit: cover; }
        .avatar-fallback { width: 84px; height: 84px; border-radius: 50%; background: #15803d; color: #fff; font-size: 2rem; font-weight: 600; display: flex; align-items: center; justify-content: center; }
        .pill { background: #f5f5f4; border-radius: 999px; padding: 2px 10px; font-size: .85rem; }
        .btn-slip-view { color: #ea580c; border-color: #ea580c; }
        .btn-slip-view:hover { background: #ea580c; color: #fff; }
        .btn-slip-print { background: #ea580c; border-color: #ea580c; color: #fff; }
        .btn-slip-print:hover { background: #c2410c; border-color: #c2410c; color: #fff; }
    </style>
</head>
<body>

<div class="container py-4" style="max-width: 420px">
    <div class="card p-3 mb-4">
        <label class="form-label fw-semibold">Ward 1 – 8 slips per page (A4)</label>
        <form method="GET" action="{{ route('voter.sheet') }}" class="input-group">
            <input type="hidden" name="ward" value="1">
            <select name="page" class="form-select">
                @for($page = 1; $page <= $pageCount; $page++)
                    <option value="{{ $page }}">Page {{ $page }}</option>
                @endfor
            </select>
            <button class="btn btn-outline-warning" type="submit">Open page PDF</button>
        </form>
    </div>

    <form method="GET" action="{{ route('voter.search') }}" class="mb-4">
        <label class="form-label fw-semibold">Voter ID (EPIC)</label>
        <div class="input-group">
            <input type="text" name="epic" value="{{ $epic }}" class="form-control text-uppercase"
                   placeholder="e.g. IED1540749" required>
            <button class="btn btn-warning" type="submit">Search</button>
        </div>
    </form>

    @if($notFound)
        <div class="alert alert-danger">No voter found with EPIC "{{ $epic }}".</div>
    @endif

    @if($voter)
        <div class="slip-card p-3">
            <div class="election-banner text-center py-2 mb-3">{{ $voter->election->name }}</div>

            <div class="row g-2 mb-3 text-center">
                <div class="col-3"><div class="info-box py-2"><div class="label">WARD NO.</div><div class="value">{{ $voter->ward->ward_number }}</div></div></div>
                <div class="col-3"><div class="info-box py-2"><div class="label">BOOTH NO.</div><div class="value">{{ $voter->pollingStation->booth_number }}</div></div></div>
                <div class="col-3"><div class="info-box py-2"><div class="label">SR. NO.</div><div class="value">{{ $voter->serial_number }}</div></div></div>
                <div class="col-3"><div class="info-box py-2"><div class="label">HOUSE NO.</div><div class="value">{{ $voter->house_number }}</div></div></div>
            </div>

            <div class="d-flex align-items-center gap-3 mb-3">
                @if($voter->photo)
                    <img class="avatar" src="{{ asset($voter->photo->photo_path) }}" alt="">
                @else
                    <div class="avatar-fallback">{{ mb_substr($voter->name, 0, 1) }}</div>
                @endif
                <div>
                    <div class="fs-5 fw-bold">{{ $voter->name }}</div>
                    <span class="pill">{{ $voter->epic_number }}</span>
                    <span class="pill">{{ $voter->gender }}, {{ $voter->age }} yrs</span>
                </div>
            </div>

            <div class="small text-secondary">ADDRESS</div>
            <div class="mb-2">{{ $voter->house_number }}, {{ $voter->locality }}</div>

            <div class="small text-secondary">POLLING STATION</div>
            <div class="mb-3">{{ $voter->pollingStation->booth_number }} - {{ $voter->pollingStation->polling_station_name }}, कमरा नं. {{ $voter->pollingStation->room_number }}</div>

            <hr>

            <div class="row g-2">
                <div class="col-6">
                    <a class="btn btn-slip-view w-100" target="_blank" href="{{ route('voter.slip', $voter) }}">View E-Slip</a>
                </div>
                <div class="col-6">
                    <a class="btn btn-slip-print w-100" href="{{ route('voter.slip', ['voter' => $voter, 'download' => 1]) }}">Print Slip</a>
                </div>
            </div>
        </div>
    @endif
</div>

</body>
</html>
