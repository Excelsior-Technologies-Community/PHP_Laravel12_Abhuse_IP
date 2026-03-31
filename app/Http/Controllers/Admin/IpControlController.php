<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use Illuminate\Http\Request;

class IpControlController extends Controller {
    public function index() {
        $ips = BlockedIp::latest()->get();
        return view('admin.ips', compact('ips'));
    }

    public function store(Request $request) {
        $request->validate(['ip' => 'required|ip', 'reason' => 'required']);
        BlockedIp::create(['ip_address' => $request->ip, 'reason' => $request->reason]);
        return back();
    }

    public function destroy($id) {
        BlockedIp::findOrFail($id)->delete();
        return back();
    }
}