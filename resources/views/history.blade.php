<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IP Search History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body style="background:#f5f7fb;">

    <div class="container py-5">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold text-primary">
                    📜 IP Search History
                </h2>

                <p class="text-muted">
                    View all searched IP addresses.
                </p>

            </div>

            <div>

                <a href="{{ route('user') }}" class="btn btn-primary">
                    🔍 Search IP
                </a>

                <a href="{{ route('dashboard') }}" class="btn btn-success">
                    📊 Dashboard
                </a>

            </div>

        </div>

        @if(session('success'))

            <div class="alert alert-success">

                {{ session('success') }}

            </div>

        @endif

        <div class="card shadow">

            <div class="card-header bg-dark text-white">

                <div class="row">

                    <div class="col-md-6">

                        <h5 class="mb-0">
                            Search History
                        </h5>

                    </div>

                    <div class="col-md-6">

                        <form method="GET" action="{{ route('history') }}">

                            <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                                placeholder="Search IP, Country, City...">

                        </form>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <table class="table table-bordered table-hover align-middle">

                    <thead class="table-primary">

                        <tr>

                            <th>#</th>

                            <th>IP</th>

                            <th>Country</th>

                            <th>City</th>

                            <th>Region</th>

                            <th>Date</th>

                            <th width="120">Action</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($histories as $history)

                            <tr>

                                <td>

                                    {{ $histories->firstItem() + $loop->index }}

                                </td>

                                <td>

                                    {{ $history->ip }}

                                </td>

                                <td>

                                    {{ $history->country }}

                                </td>

                                <td>

                                    {{ $history->city }}

                                </td>

                                <td>

                                    {{ $history->region }}

                                </td>

                                <td>

                                    {{ $history->created_at->format('d M Y h:i A') }}

                                </td>

                                <td>

                                    <form action="{{ route('history.delete', $history->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this record?')">

                                        @csrf
                                        @method('DELETE')

                                        <button class="btn btn-danger btn-sm">

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center text-muted">

                                    No history found.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

                <div class="d-flex justify-content-center mt-4">
                    {{ $histories->withQueryString()->links() }}
                </div>

            </div>

        </div>

        <div class="text-center mt-5">
            <hr>
            <p class="text-muted mb-0">
                Laravel 12 IP Location Tracker
            </p>

            <small class="text-secondary">
                Search History • Pagination • Delete Record
            </small>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>