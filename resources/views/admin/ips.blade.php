<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

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
            max-width: 1400px;
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
            box-shadow: 0 2px 5px rgba(0, 0, 0, .05);
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

        .card {
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 25px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, .04);
        }

        .card h2 {
            margin-top: 0;
            margin-bottom: 18px;
            font-size: 19px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
        }

        .search-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr 1fr 1fr auto;
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

        input,
        select {
            width: 100%;
            padding: 11px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background: white;
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

        .btn-warning {
            background: #d97706;
            color: white;
        }

        .btn-dark {
            background: #111827;
            color: white;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 1100px;
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

        .badge-active {
            background: #fee2e2;
            color: #991b1b;
        }

        .badge-expired {
            background: #e5e7eb;
            color: #374151;
        }

        .badge-low {
            background: #dcfce7;
            color: #166534;
        }

        .badge-medium {
            background: #fef3c7;
            color: #92400e;
        }

        .badge-high {
            background: #fed7aa;
            color: #9a3412;
        }

        .badge-critical {
            background: #fecaca;
            color: #7f1d1d;
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

        .dashboard-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .score {
            font-weight: bold;
        }

        .score-high {
            color: #dc2626;
        }

        .score-medium {
            color: #d97706;
        }

        .score-low {
            color: #16a34a;
        }

        .export-link {
            text-decoration: none;
        }

        @media (max-width: 1100px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .form-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .search-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .form-grid,
            .search-grid {
                grid-template-columns: 1fr;
            }
        }

    </style>
</head>

<body>

<div class="container">

    <div class="header">

        <h1>🛡️ IP Abuse Security Dashboard</h1>

        <p>
            Monitor, block, search, filter and audit abusive IP addresses.
        </p>

    </div>


    @if(session('success'))

        <div class="success">
            {{ session('success') }}
        </div>

    @endif


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
    {{-- ANALYTICS --}}
    {{-- ========================================================= --}}

    <div class="stats">

        <div class="stat-card">

            <h3>Total Blocked IPs</h3>

            <div class="number">
                {{ $totalBlockedIps }}
            </div>

        </div>


        <div class="stat-card">

            <h3>Active Blocks</h3>

            <div class="number">
                {{ $activeBlockedIps }}
            </div>

        </div>


        <div class="stat-card">

            <h3>Expired Blocks</h3>

            <div class="number">
                {{ $expiredBlockedIps }}
            </div>

        </div>


        <div class="stat-card">

            <h3>High/Critical Risk</h3>

            <div class="number">
                {{ $highRiskIps }}
            </div>

        </div>

    </div>


    <div class="stats">

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


        <div class="stat-card">

            <h3>Delete Actions</h3>

            <div class="number">
                {{ $deleteActivities }}
            </div>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- BLOCK NEW IP --}}
    {{-- ========================================================= --}}

    <div class="card">

        <h2>🚫 Block New IP Address</h2>

        <form
            action="{{ route('admin.ips.store') }}"
            method="POST"
        >

            @csrf

            <div class="form-grid">

                <div class="field">

                    <label>IP Address</label>

                    <input
                        type="text"
                        name="ip"
                        value="{{ old('ip') }}"
                        placeholder="192.168.1.10"
                        required
                    >

                </div>


                <div class="field">

                    <label>Reason</label>

                    <input
                        type="text"
                        name="reason"
                        value="{{ old('reason') }}"
                        placeholder="Malicious bot activity"
                        required
                    >

                </div>


                <div class="field">

                    <label>Severity</label>

                    <select name="severity" required>

                        <option value="low">
                            Low
                        </option>

                        <option
                            value="medium"
                            selected
                        >
                            Medium
                        </option>

                        <option value="high">
                            High
                        </option>

                        <option value="critical">
                            Critical
                        </option>

                    </select>

                </div>


                <div class="field">

                    <label>Abuse Score</label>

                    <input
                        type="number"
                        name="abuse_confidence_score"
                        min="0"
                        max="100"
                        value="{{ old('abuse_confidence_score', 0) }}"
                    >

                </div>


                <div class="field">

                    <label>Country</label>

                    <input
                        type="text"
                        name="country"
                        value="{{ old('country') }}"
                        placeholder="India"
                    >

                </div>


                <div class="field">

                    <label>ISP</label>

                    <input
                        type="text"
                        name="isp"
                        value="{{ old('isp') }}"
                        placeholder="Example ISP"
                    >

                </div>


                <div class="field">

                    <label>Expires At</label>

                    <input
                        type="datetime-local"
                        name="expires_at"
                        value="{{ old('expires_at') }}"
                    >

                </div>

            </div>

            <br>

            <button
                type="submit"
                class="btn-danger"
            >
                🚫 Block IP
            </button>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- SEARCH AND FILTER --}}
    {{-- ========================================================= --}}

    <div class="card">

        <h2>🔎 Advanced Search & Filters</h2>

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
                        placeholder="IP, reason, country, ISP"
                    >

                </div>


                <div class="field">

                    <label>Reason</label>

                    <input
                        type="text"
                        name="reason"
                        value="{{ request('reason') }}"
                        placeholder="Bot"
                    >

                </div>


                <div class="field">

                    <label>Status</label>

                    <select name="status">

                        <option value="">
                            All
                        </option>

                        <option
                            value="active"
                            @selected(request('status') === 'active')
                        >
                            Active
                        </option>

                        <option
                            value="expired"
                            @selected(request('status') === 'expired')
                        >
                            Expired
                        </option>

                    </select>

                </div>


                <div class="field">

                    <label>Severity</label>

                    <select name="severity">

                        <option value="">
                            All
                        </option>

                        @foreach(['low', 'medium', 'high', 'critical'] as $severity)

                            <option
                                value="{{ $severity }}"
                                @selected(request('severity') === $severity)
                            >
                                {{ ucfirst($severity) }}
                            </option>

                        @endforeach

                    </select>

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


                <div class="field">

                    <label>Sort</label>

                    <select name="sort">

                        <option
                            value="created_at"
                            @selected(request('sort', 'created_at') === 'created_at')
                        >
                            Date
                        </option>

                        <option
                            value="ip_address"
                            @selected(request('sort') === 'ip_address')
                        >
                            IP
                        </option>

                        <option
                            value="severity"
                            @selected(request('sort') === 'severity')
                        >
                            Severity
                        </option>

                        <option
                            value="abuse_confidence_score"
                            @selected(request('sort') === 'abuse_confidence_score')
                        >
                            Abuse Score
                        </option>

                        <option
                            value="expires_at"
                            @selected(request('sort') === 'expires_at')
                        >
                            Expiry
                        </option>

                    </select>

                </div>

            </div>

            <br>

            <div class="actions">

                <button
                    type="submit"
                    class="btn-primary"
                >
                    🔎 Apply Filters
                </button>

                <a
                    href="{{ route('admin.ips.index') }}"
                    class="export-link"
                >

                    <button
                        type="button"
                        class="btn-secondary"
                    >
                        Clear
                    </button>

                </a>

            </div>

        </form>

    </div>


    {{-- ========================================================= --}}
    {{-- BULK ACTIONS --}}
    {{-- ========================================================= --}}

    <div class="card">

        <h2>⚡ Bulk Security Actions</h2>

        <div class="actions">

            <button
                type="button"
                class="btn-success"
                onclick="submitBulk('unblock')"
            >
                ✓ Bulk Unblock
            </button>

            <button
                type="button"
                class="btn-danger"
                onclick="submitBulk('delete')"
            >
                🗑 Bulk Delete
            </button>

            <a
                href="{{ route('admin.ips.export', request()->query()) }}"
                class="export-link"
            >

                <button
                    type="button"
                    class="btn-dark"
                >
                    📥 Export CSV
                </button>

            </a>

        </div>

    </div>


    {{-- ========================================================= --}}
    {{-- IP TABLE --}}
    {{-- ========================================================= --}}

    <div class="card">

        <h2>🚨 Blocked IP Addresses</h2>

        <form
            id="bulk-form"
            method="POST"
        >

            @csrf

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                <input
                                    type="checkbox"
                                    id="select-all"
                                >
                            </th>

                            <th>#</th>

                            <th>IP Address</th>

                            <th>Reason</th>

                            <th>Status</th>

                            <th>Severity</th>

                            <th>Abuse Score</th>

                            <th>Country</th>

                            <th>ISP</th>

                            <th>Expires</th>

                            <th>Created</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($ips as $ip)

                            <tr>

                                <td>

                                    <input
                                        type="checkbox"
                                        name="ids[]"
                                        value="{{ $ip->id }}"
                                        class="ip-checkbox"
                                    >

                                </td>


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

                                    @if($ip->effective_status === 'expired')

                                        <span class="badge badge-expired">
                                            EXPIRED
                                        </span>

                                    @else

                                        <span class="badge badge-active">
                                            ACTIVE
                                        </span>

                                    @endif

                                </td>


                                <td>

                                    <span
                                        class="badge badge-{{ $ip->severity }}"
                                    >
                                        {{ strtoupper($ip->severity) }}
                                    </span>

                                </td>


                                <td>

                                    @php

                                        $scoreClass =
                                            $ip->abuse_confidence_score >= 75
                                                ? 'score-high'
                                                : (
                                                    $ip->abuse_confidence_score >= 40
                                                        ? 'score-medium'
                                                        : 'score-low'
                                                );

                                    @endphp

                                    <span class="score {{ $scoreClass }}">

                                        {{ $ip->abuse_confidence_score }}%

                                    </span>

                                </td>


                                <td>
                                    {{ $ip->country ?? 'Unknown' }}
                                </td>


                                <td>
                                    {{ $ip->isp ?? 'Unknown' }}
                                </td>


                                <td>

                                    @if($ip->expires_at)

                                        {{ $ip->expires_at->format('Y-m-d H:i') }}

                                    @else

                                        Never

                                    @endif

                                </td>


                                <td>

                                    {{ $ip->created_at->format('Y-m-d H:i') }}

                                </td>


                                <td>

                                    <form
                                        action="{{ route('admin.ips.destroy', $ip->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Are you sure you want to unblock this IP?');"
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

                                <td
                                    colspan="12"
                                    class="empty"
                                >
                                    No blocked IP addresses found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </form>


        {{-- Pagination --}}

        @if($ips->hasPages())

            <div class="pagination">

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

            </div>

        @endif

    </div>


    {{-- ========================================================= --}}
    {{-- TOP REASONS --}}
    {{-- ========================================================= --}}

    <div class="dashboard-grid">

        <div class="card">

            <h2>📊 Top Blocking Reasons</h2>

            @if($topReasons->count())

                <table>

                    <thead>

                        <tr>

                            <th>
                                Reason
                            </th>

                            <th>
                                Total
                            </th>

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


        <div class="card">

            <h2>🚨 Recent Blocks</h2>

            @if($recentBlockedIps->count())

                <table>

                    <thead>

                        <tr>

                            <th>
                                IP
                            </th>

                            <th>
                                Severity
                            </th>

                            <th>
                                Score
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @foreach($recentBlockedIps as $ip)

                            <tr>

                                <td>
                                    {{ $ip->ip_address }}
                                </td>

                                <td>
                                    {{ ucfirst($ip->severity) }}
                                </td>

                                <td>
                                    {{ $ip->abuse_confidence_score }}%
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
    {{-- ACTIVITY FILTER --}}
    {{-- ========================================================= --}}

    <div class="card">

        <h2>📝 Security Activity Audit</h2>

        <form
            action="{{ route('admin.ips.index') }}"
            method="GET"
        >

            <div class="search-grid">

                <div class="field">

                    <label>Activity Search</label>

                    <input
                        type="text"
                        name="activity_search"
                        value="{{ request('activity_search') }}"
                        placeholder="Search IP or reason"
                    >

                </div>


                <div class="field">

                    <label>Action</label>

                    <select name="activity_action">

                        <option value="">
                            All Actions
                        </option>

                        <option
                            value="BLOCK"
                            @selected(request('activity_action') === 'BLOCK')
                        >
                            BLOCK
                        </option>

                        <option
                            value="UNBLOCK"
                            @selected(request('activity_action') === 'UNBLOCK')
                        >
                            UNBLOCK
                        </option>

                        <option
                            value="DELETE"
                            @selected(request('activity_action') === 'DELETE')
                        >
                            DELETE
                        </option>

                    </select>

                </div>


                <div class="field">

                    <label>From</label>

                    <input
                        type="date"
                        name="activity_from"
                        value="{{ request('activity_from') }}"
                    >

                </div>


                <div class="field">

                    <label>To</label>

                    <input
                        type="date"
                        name="activity_to"
                        value="{{ request('activity_to') }}"
                    >

                </div>


                <div>

                    <button
                        type="submit"
                        class="btn-primary"
                    >
                        Filter Activity
                    </button>

                </div>

            </div>

        </form>

        <br>


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

                                <span class="badge badge-active">
                                    BLOCK
                                </span>

                            @elseif($activity->action === 'UNBLOCK')

                                <span class="badge badge-low">
                                    UNBLOCK
                                </span>

                            @else

                                <span class="badge badge-critical">
                                    DELETE
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

                        <td
                            colspan="5"
                            class="empty"
                        >
                            No IP blocking activity has been recorded yet.
                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>


<script>

    const selectAll = document.getElementById('select-all');

    if (selectAll) {

        selectAll.addEventListener('change', function () {

            document
                .querySelectorAll('.ip-checkbox')
                .forEach(function (checkbox) {

                    checkbox.checked = selectAll.checked;

                });

        });

    }


    function submitBulk(action) {

        const selected = document.querySelectorAll(
            '.ip-checkbox:checked'
        );

        if (selected.length === 0) {

            alert('Please select at least one IP address.');

            return;

        }

        if (action === 'delete') {

            if (!confirm(
                'Are you sure you want to permanently delete the selected IP addresses?'
            )) {

                return;

            }

        }

        if (action === 'unblock') {

            if (!confirm(
                'Are you sure you want to unblock the selected IP addresses?'
            )) {

                return;

            }

        }

        const form = document.getElementById('bulk-form');

        if (action === 'delete') {

            form.action =
                "{{ route('admin.ips.bulk-delete') }}";

        } else {

            form.action =
                "{{ route('admin.ips.bulk-unblock') }}";

        }

        form.submit();

    }

</script>

</body>

</html>