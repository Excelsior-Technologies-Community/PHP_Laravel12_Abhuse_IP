<div style="text-align: center; margin-top: 50px;">
    <h2>IP Abuse Checker</h2>
    <form action="{{ route('check.ip') }}" method="POST">
        @csrf
        <input type="text" name="ip" placeholder="Enter IP Address" required>
        <button type="submit">Check IP</button>
    </form>

    @if(isset($data))
        <div style="margin-top: 20px; border: 1px solid #ccc; padding: 20px; display: inline-block;">
            <p><strong>IP:</strong> {{ $data['ipAddress'] }}</p>
            <p><strong>Abuse Confidence Score:</strong> {{ $data['abuseConfidenceScore'] }}%</p>
            <p><strong>Country:</strong> {{ $data['countryName'] }}</p>
            <p><strong>Usage Type:</strong> {{ $data['usageType'] }}</p>
        </div>
    @endif
</div>