<div style="max-width: 900px; margin: 50px auto; font-family: sans-serif; border: 1px solid #ddd; padding: 20px; border-radius: 8px;">
    <h2 style="color: #d32f2f;">IP Abuse Control Panel</h2>
    
    <form action="{{ route('admin.ips.store') }}" method="POST" style="display: flex; gap: 10px; margin-bottom: 20px;">
        @csrf
        <input type="text" name="ip" placeholder="127.0.0.1" required style="flex: 1; padding: 8px; border: 1px solid #ccc; background: #f0f4ff;">
        <input type="text" name="reason" placeholder="hakers" required style="flex: 1; padding: 8px; border: 1px solid #ccc; background: #f0f4ff;">
        <button type="submit" style="background: #d32f2f; color: white; border: none; padding: 8px 20px; cursor: pointer; border-radius: 4px;">Block IP</button>
    </form>

    <table style="width: 100%; border-collapse: collapse;">
        <thead style="background: #f8f9fa;">
            <tr>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">IP Address</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Reason</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Blocked At</th>
                <th style="border: 1px solid #ddd; padding: 10px; text-align: left;">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ips as $ip)
            <tr>
                <td style="border: 1px solid #ddd; padding: 10px;">{{ $ip->ip_address }}</td>
                <td style="border: 1px solid #ddd; padding: 10px;">{{ $ip->reason }}</td>
                <td style="border: 1px solid #ddd; padding: 10px;">{{ $ip->created_at->format('Y-m-d H:i') }}</td>
                <td style="border: 1px solid #ddd; padding: 10px;">
                    <form action="{{ route('admin.ips.destroy', $ip->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" style="color: red; border: none; background: none; cursor: pointer;">Unblock</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>