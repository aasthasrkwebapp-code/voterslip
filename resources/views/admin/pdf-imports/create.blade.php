
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Upload Voter PDF - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-3">
                    <h4 class="mb-0">Complete Ward Voter PDF Upload</h4>
                </div>

                <div class="card-body p-4">

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <strong>Please correct the following errors:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="alert alert-info">
                        Upload the complete voter list PDF for one ward.
                        The PDF may contain voters from multiple polling booths.
                    </div>

                    <form method="POST"
                          action="{{ route('admin.pdf-imports.store') }}"
                          enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Election</label>
                            <select name="election_id"
                                    class="form-select"
                                    required>
                                <option value="">Select Election</option>
                                @foreach($elections as $election)
                                    <option value="{{ $election->id }}"
                                        @selected(old('election_id') == $election->id)>
                                        {{ $election->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Ward</label>
                            <select name="ward_id"
                                    class="form-select"
                                    required>
                                <option value="">Select Ward</option>
                                @foreach($wards as $ward)
                                    <option value="{{ $ward->id }}"
                                        @selected(old('ward_id') == $ward->id)>
                                        {{ $ward->ward_number }}
                                        @if($ward->ward_name)
                                            - {{ $ward->ward_name }}
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text">
                                Select a ward belonging to the chosen election.
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Complete Voter List PDF</label>
                            <input type="file"
                                   name="pdf_file"
                                   class="form-control"
                                   accept=".pdf,application/pdf"
                                   required>
                            <div class="form-text">
                                PDF only. Maximum file size: 50 MB.
                                One PDF can contain multiple polling booths.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Upload Complete Ward PDF
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

</body>
</html>
