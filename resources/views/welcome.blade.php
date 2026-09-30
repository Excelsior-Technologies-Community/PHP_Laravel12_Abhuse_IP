<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>IP Abuse Checker</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 800px;
            margin: auto;
        }

        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            margin-bottom: 25px;
        }

        h1 {
            margin-top: 0;
            text-align: center;
        }

        form {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }

        input {
            flex: 1;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        button {
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            background: #2563eb;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }

        button:hover {
            background: #1d4ed8;
        }

        .result {
            margin-top: 25px;
        }

        .result-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .result-row:last-child {
            border-bottom: none;
        }

        .label {
            font-weight: bold;
        }

        .error {
            margin-top: 20px;
            padding: 12px;
            background: #fee2e2;
            color: #991b1b;
            border-radius: 6px;
        }

        .score {
            font-size: 28px;
            font-weight: bold;
        }

        .admin-link {
            text-align: center;
            margin-top: 20px;
        }

        .admin-link a {
            color: #2563eb;
            text-decoration: none;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="card">

        <h1>🛡️ IP Abuse Checker</h1>

        <p style="text-align: center;">
            Check an IP address against the AbuseIPDB database.
        </p>

        <form action="{{ route('check.ip') }}" method="POST">

            @csrf

            <input
                type="text"
                name="ip"
                placeholder="Enter IP address"
                value="{{ old('ip') }}"
                required
            >

            <button type="submit">
                Check IP
            </button>

        </form>

        @if ($errors->has('ip'))
            <div class="error">
                {{ $errors->first('ip') }}
            </div>
        @endif

    </div>


    @if(isset($data))

        <div class="card">

            <h2>IP Abuse Check Result</h2>

            <div class="result">

                <div class="result-row">
                    <span class="label">IP Address</span>
                    <span>{{ $data['ipAddress'] }}</span>
                </div>

                <div class="result-row">
                    <span class="label">Abuse Confidence Score</span>
                    <span class="score">
                        {{ $data['abuseConfidenceScore'] }}%
                    </span>
                </div>

                <div class="result-row">
                    <span class="label">Country</span>
                    <span>{{ $data['countryName'] }}</span>
                </div>

                <div class="result-row">
                    <span class="label">Usage Type</span>
                    <span>{{ $data['usageType'] }}</span>
                </div>

                <div class="result-row">
                    <span class="label">ISP</span>
                    <span>{{ $data['isp'] }}</span>
                </div>

                <div class="result-row">
                    <span class="label">Domain</span>
                    <span>{{ $data['domain'] }}</span>
                </div>

                <div class="result-row">
                    <span class="label">Total Reports</span>
                    <span>{{ $data['totalReports'] }}</span>
                </div>

                <div class="result-row">
                    <span class="label">Last Reported</span>
                    <span>
                        {{ $data['lastReportedAt'] ?? 'Never' }}
                    </span>
                </div>

            </div>

        </div>

    @endif


    <div class="admin-link">
        <a href="{{ route('admin.ips.index') }}">
            → Open IP Security Admin Dashboard
        </a>
    </div>

</div>

</body>
</html>