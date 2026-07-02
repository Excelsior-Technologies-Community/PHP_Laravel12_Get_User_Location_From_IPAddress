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

                            <div class="input-group">

                                <input
                                    type="text"
                                    name="search"
                                    class="form-control"
                                    placeholder="Search IP, Country, City..."
                                    value="{{ request('search') }}">

                                <button class="btn btn-primary">

                                    Search

                                </button>

                                @if(request('search'))

                                <a
                                    href="{{ route('history') }}"
                                    class="btn btn-secondary">

                                    Clear

                                </a>

                                @endif

                            </div>

                        </form>

                    </div>

                </div>

            </div>

            <div class="card-body">

                <div class="d-flex justify-content-between mb-3">

                    <div>

                        <a
                            href="{{ route('history.export.csv',['search'=>request('search')]) }}"
                            class="btn btn-success">

                            📥 Export CSV

                        </a>

                    </div>

                    <div>

                        <span class="badge bg-primary fs-6">

                            Total Records :
                            {{ $histories->total() }}

                        </span>

                    </div>

                </div>

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

                                <div class="d-flex align-items-center">

                                    <span id="ip{{ $history->id }}">

                                        {{ $history->ip }}

                                    </span>

                                    <button
                                        class="btn btn-sm btn-outline-primary ms-2"
                                        onclick="copyIp('ip{{ $history->id }}')">

                                        📋 Copy

                                    </button>

                                </div>

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

    <!-- Copy Success Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">

        <div
            id="copyToast"
            class="toast text-bg-success border-0"
            role="alert"
            aria-live="assertive"
            aria-atomic="true">

            <div class="d-flex">

                <div class="toast-body">

                    ✅ IP Address copied successfully.

                </div>

                <button
                    type="button"
                    class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast">
                </button>

            </div>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function copyIp(id) {
            const text = document.getElementById(id).innerText;

            navigator.clipboard.writeText(text)
                .then(() => {

                    const toast = new bootstrap.Toast(
                        document.getElementById('copyToast'), {
                            delay: 2000
                        }
                    );

                    toast.show();

                })
                .catch(() => {

                    alert('Unable to copy IP Address.');

                });
        }
    </script>

</body>

</html>