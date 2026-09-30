<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use App\Models\IpBlockingActivity;
use Illuminate\Http\Request;

class IpControlController extends Controller
{
    /**
     * Display IP security dashboard.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Search and Filtering
        |--------------------------------------------------------------------------
        */

        $query = BlockedIp::query();

        // Search by IP address or reason
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', '%' . $search . '%')
                    ->orWhere('reason', 'like', '%' . $search . '%');
            });
        }

        // Filter by reason
        if ($request->filled('reason')) {
            $query->where('reason', 'like', '%' . $request->reason . '%');
        }

        // Filter by start date
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        // Filter by end date
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        /*
        |--------------------------------------------------------------------------
        | Paginated Blocked IP Records
        |--------------------------------------------------------------------------
        */

        $ips = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Analytics
        |--------------------------------------------------------------------------
        */

        $totalBlockedIps = BlockedIp::count();

        $blockedToday = BlockedIp::whereDate(
            'created_at',
            today()
        )->count();

        $blockedThisMonth = BlockedIp::whereMonth(
            'created_at',
            now()->month
        )
            ->whereYear('created_at', now()->year)
            ->count();

        $totalActivities = IpBlockingActivity::count();

        $blockActivities = IpBlockingActivity::where(
            'action',
            'BLOCK'
        )->count();

        $unblockActivities = IpBlockingActivity::where(
            'action',
            'UNBLOCK'
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Top Blocking Reasons
        |--------------------------------------------------------------------------
        */

        $topReasons = BlockedIp::selectRaw(
            'reason, COUNT(*) as total'
        )
            ->whereNotNull('reason')
            ->groupBy('reason')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Activity
        |--------------------------------------------------------------------------
        */

        $recentActivities = IpBlockingActivity::latest()
            ->limit(8)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Recent Blocked IPs
        |--------------------------------------------------------------------------
        */

        $recentBlockedIps = BlockedIp::latest()
            ->limit(5)
            ->get();

        return view('admin.ips', compact(
            'ips',
            'totalBlockedIps',
            'blockedToday',
            'blockedThisMonth',
            'totalActivities',
            'blockActivities',
            'unblockActivities',
            'topReasons',
            'recentActivities',
            'recentBlockedIps'
        ));
    }

    /**
     * Store a blocked IP.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip' => [
                'required',
                'ip',
                'unique:blocked_ips,ip_address',
            ],
            'reason' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $blockedIp = BlockedIp::create([
            'ip_address' => $validated['ip'],
            'reason' => $validated['reason'],
        ]);

        /*
        |--------------------------------------------------------------------------
        | Record BLOCK Activity
        |--------------------------------------------------------------------------
        */

        IpBlockingActivity::create([
            'ip_address' => $blockedIp->ip_address,
            'action' => 'BLOCK',
            'reason' => $blockedIp->reason,
        ]);

        return back()->with(
            'success',
            'IP address has been blocked successfully.'
        );
    }

    /**
     * Unblock an IP.
     */
    public function destroy($id)
    {
        $blockedIp = BlockedIp::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Record UNBLOCK Activity Before Deleting
        |--------------------------------------------------------------------------
        */

        IpBlockingActivity::create([
            'ip_address' => $blockedIp->ip_address,
            'action' => 'UNBLOCK',
            'reason' => $blockedIp->reason,
        ]);

        $blockedIp->delete();

        return back()->with(
            'success',
            'IP address has been unblocked successfully.'
        );
    }
}