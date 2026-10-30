
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Dashboard - Voter E-Slip</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand">Voter E-Slip Admin</span>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="btn btn-light btn-sm">
                Logout
            </button>
        </form>
    </div>
</nav>

<div class="container py-5">
    <h3 class="mb-2">Welcome, {{ auth('admin')->user()->name }}</h3>
    <p class="text-muted">Administrator Dashboard</p>

    <div class="row g-3 mt-3">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5>PDF Import Management</h5>
                    <p class="text-muted">
                        Upload and manage voter-list PDF files.
                    </p>
                    <a href="{{ route('admin.pdf-imports.create') }}"
                       class="btn btn-primary">
                        Upload Voter PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
