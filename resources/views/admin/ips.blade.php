<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IP Security Dashboard</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f4f6f9;
            font-family: Arial, Helvetica, sans-serif;
            color: #1f2937;
        }

        .container {
            max-width: 1250px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .header {
            margin-bottom: 25px;
        }

        .header h1 {
            margin: 0 0 8px;
            color: #b91c1c;
        }

        .header p {
            margin: 0;
            color: #6b7280;
        }

        .success {
            background: #dcfce7;
            border: 1px solid #86efac;
            color: #166534;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error {
            background: #fee2e2;
            border: 1px solid #fca5a5;
            color: #991b1b;
            padding: 12px 15px;
            border-radius: 6px;
            margin-bottom: 20px;
        }

        .error ul {
            margin: 5px 0 0;
            padding-left: 20px;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 22px;
            border: 1px solid #e5e7eb;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .stat-card h3 {
            margin: 0 0 10px;
            font-size: 14px;
            color: #6b7280;
        }

        .stat-card .number {
            font-size: 28px;
            font-weight: bold;
            color: #111827;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 20px;
            margin-bottom: 25px;
        }

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.04);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 18px;
            font-size: 19px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        input {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
        }

        button {
            border: none;
            border-radius: 6px;
            padding: 10px 16px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-danger {
            background: #dc2626;
            color: white;
        }

        .btn-danger:hover {
            background: #b91c1c;
        }

        .btn-primary {
            background: #2563eb;
            color: white;
        }

        .btn-secondary {
            background: #6b7280;
            color: white;
        }

        .btn-success {
            background: #16a34a;
            color: white;
        }

        .search-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr auto;
            gap: 10px;
            align-items: end;
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 6px;
            color: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #f8fafc;
            color: #374151;
            font-size: 13px;
        }

        th,
        td {
            border: 1px solid #e5e7eb;
            padding: 11px;
            text-align: left;
            font-size: 14px;
        }

        tr:hover {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .badge-block {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-unblock {
            background: #dcfce7;
            color: #166534;
        }

        .pagination {
            margin-top: 18px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
        }

        .pagination a,
        .pagination span {
            border: 1px solid #d1d5db;
            padding: 7px 11px;
            border-radius: 5px;
            text-decoration: none;
            color: #374151;
            background: white;
            font-size: 13px;
        }

        .pagination .active {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        .empty {
            text-align: center;
            color: #6b7280;
            padding: 25px;
        }

        @media (max-width: 900px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .search-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 600px) {
            .stats {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .search-grid {
                grid-template-columns: 1fr;
            }

            table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>

<div class="container">

    {{-- Header --}}
    <div class="header">
        <h1>🛡️ IP Abuse Security Dashboard</h1>
        <p>Monitor, block, search and audit abusive IP addresses.</p>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Validation Errors --}}
    @if($errors->any())
        <div class="error">
            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- ========================================================= --}}
    {{-- ANALYTICS DASHBOARD --}}
    {{-- ========================================================= --}}

    <div class="stats">

        <div class="stat-card">
            <h3>Total Blocked IPs</h3>
            <div class="number">
                {{ $totalBlockedIps }}
            </div>
        </div>

        <div class="stat-card">
            <h3>Blocked Today</h3>
            <div class="number">
                {{ $blockedToday }}
            </div>
        </div>

        <div class="stat-card">
            <h3>Blocked This Month</h3>
            <div class="number">
                {{ $blockedThisMonth }}
            </div>
        </div>

        <div class="stat-card">
            <h3>Total Activities</h3>
            <div class="number">
                {{ $totalActivities }}
            </div>
        </div>

    </div>


    <div class="stats">

        <div class="stat-card">
            <h3>Total Block Actions</h3>
            <div class="number">
                {{ $blockActivities }}
            </div>
        </div>

        <div class="stat-card">
            <h3>Total Unblock Actions</h3>
            <div class="number">
                {{ $unblockActivities }}
            </div>
        </div>

        <div class="stat-card">
            <h3>Active Protection</h3>
            <div class="number">
                {{ $totalBlockedIps > 0 ? 'ON' : 'READY' }}
            </div>
        </div>

        <div class="stat-card">
            <h3>Audit Tracking</h3>
            <div class="number">
                ACTIVE
            </div>
        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BLOCK IP FORM --}}
    {{-- ========================================================= --}}

    <div class="card">

        <h2>🚫 Block New IP Address</h2>

        <form action="{{ route('admin.ips.store') }}" method="POST">

            @csrf

            <div class="form-row">

                <div>
                    <input
                        type="text"
                        name="ip"
                        value="{{ old('ip') }}"
                        placeholder="Example: 192.168.1.10"
                        required
                    >
                </div>

                <div>
                    <input
                        type="text"
                        name="reason"
                        value="{{ old('reason') }}"
                        placeholder="Example: Malicious bot activity"
                        required
                    >
                </div>

            </div>

            <br>

            <button type="submit" class="btn-danger">
                Block IP
            </button>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- SEARCH & FILTER --}}
    {{-- ========================================================= --}}

    <div class="card">

        <h2>🔎 Search & Filter Blocked IPs</h2>

        <form
            action="{{ route('admin.ips.index') }}"
            method="GET"
        >

            <div class="search-grid">

                <div class="field">
                    <label>Search</label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search IP or reason"
                    >
                </div>

                <div class="field">
                    <label>Reason</label>

                    <input
                        type="text"
                        name="reason"
                        value="{{ request('reason') }}"
                        placeholder="Example: Bot"
                    >
                </div>

                <div class="field">
                    <label>From Date</label>

                    <input
                        type="date"
                        name="from_date"
                        value="{{ request('from_date') }}"
                    >
                </div>

                <div class="field">
                    <label>To Date</label>

                    <input
                        type="date"
                        name="to_date"
                        value="{{ request('to_date') }}"
                    >
                </div>

                <div>
                    <button type="submit" class="btn-primary">
                        Search
                    </button>
                </div>

            </div>

        </form>

        <br>

        <a
            href="{{ route('admin.ips.index') }}"
            style="text-decoration: none;"
        >
            <button type="button" class="btn-secondary">
                Clear Filters
            </button>
        </a>

    </div>


    {{-- ========================================================= --}}
    {{-- BLOCKED IP TABLE --}}
    {{-- ========================================================= --}}

    <div class="card">

        <h2>🚨 Currently Blocked IP Addresses</h2>

        <table>

            <thead>

                <tr>
                    <th>#</th>
                    <th>IP Address</th>
                    <th>Reason</th>
                    <th>Blocked At</th>
                    <th>Action</th>
                </tr>

            </thead>

            <tbody>

                @forelse($ips as $ip)

                    <tr>

                        <td>
                            {{ $ips->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <strong>
                                {{ $ip->ip_address }}
                            </strong>
                        </td>

                        <td>
                            {{ $ip->reason }}
                        </td>

                        <td>
                            {{ $ip->created_at->format('Y-m-d H:i') }}
                        </td>

                        <td>

                            <form
                                action="{{ route('admin.ips.destroy', $ip->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure you want to unblock this IP address?');"
                            >

                                @csrf
                                @method('DELETE')

                                <button
                                    type="submit"
                                    class="btn-success"
                                >
                                    Unblock
                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="5" class="empty">
                            No blocked IP addresses found.
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>


        {{-- Pagination --}}

        @if($ips->hasPages())

            <div class="pagination">

                @if($ips->onFirstPage())

                    <span>Previous</span>

                @else

                    <a href="{{ $ips->previousPageUrl() }}">
                        Previous
                    </a>

                @endif


                @foreach($ips->getUrlRange(
                    max(1, $ips->currentPage() - 2),
                    min($ips->lastPage(), $ips->currentPage() + 2)
                ) as $page => $url)

                    @if($page == $ips->currentPage())

                        <span class="active">
                            {{ $page }}
                        </span>

                    @else

                        <a href="{{ $url }}">
                            {{ $page }}
                        </a>

                    @endif

                @endforeach


                @if($ips->hasMorePages())

                    <a href="{{ $ips->nextPageUrl() }}">
                        Next
                    </a>

                @else

                    <span>Next</span>

                @endif

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- TOP BLOCKING REASONS --}}
    {{-- ========================================================= --}}

    <div class="dashboard-grid">

        <div class="card">

            <h2>📊 Top Blocking Reasons</h2>

            @if($topReasons->count())

                <table>

                    <thead>
                        <tr>
                            <th>Reason</th>
                            <th>Total</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($topReasons as $reason)

                            <tr>
                                <td>
                                    {{ $reason->reason }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $reason->total }}
                                    </strong>
                                </td>
                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty">
                    No blocking reason data available.
                </div>

            @endif

        </div>


        {{-- Recent Blocked IPs --}}

        <div class="card">

            <h2>🚨 Recent Blocks</h2>

            @if($recentBlockedIps->count())

                <table>

                    <thead>
                        <tr>
                            <th>IP</th>
                            <th>Reason</th>
                        </tr>
                    </thead>

                    <tbody>

                        @foreach($recentBlockedIps as $ip)

                            <tr>

                                <td>
                                    {{ $ip->ip_address }}
                                </td>

                                <td>
                                    {{ $ip->reason }}
                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            @else

                <div class="empty">
                    No recent blocked IPs.
                </div>

            @endif

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- AUDIT HISTORY --}}
    {{-- ========================================================= --}}

    <div class="card">

        <h2>📝 IP Blocking Activity & Audit History</h2>

        <table>

            <thead>

                <tr>
                    <th>#</th>
                    <th>IP Address</th>
                    <th>Action</th>
                    <th>Reason</th>
                    <th>Date & Time</th>
                </tr>

            </thead>

            <tbody>

                @forelse($recentActivities as $activity)

                    <tr>

                        <td>
                            {{ $loop->iteration }}
                        </td>

                        <td>
                            <strong>
                                {{ $activity->ip_address }}
                            </strong>
                        </td>

                        <td>

                            @if($activity->action === 'BLOCK')

                                <span class="badge badge-block">
                                    BLOCK
                                </span>

                            @else

                                <span class="badge badge-unblock">
                                    UNBLOCK
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $activity->reason ?? 'N/A' }}
                        </td>

                        <td>
                            {{ $activity->created_at->format('Y-m-d H:i:s') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="empty">
                            No IP blocking activity has been recorded yet.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</body>
</html>