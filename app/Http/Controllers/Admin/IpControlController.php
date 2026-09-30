<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlockedIp;
use App\Models\IpBlockingActivity;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class IpControlController extends Controller
{
    /**
     * Display IP security dashboard.
     */
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Automatically expire temporary blocks
        |--------------------------------------------------------------------------
        */

        BlockedIp::where('status', 'active')
            ->whereNotNull('expires_at')
            ->where('expires_at', '<', now())
            ->update([
                'status' => 'expired',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Blocked IP Query
        |--------------------------------------------------------------------------
        */

        $query = BlockedIp::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('ip_address', 'like', '%' . $search . '%')
                    ->orWhere('reason', 'like', '%' . $search . '%')
                    ->orWhere('country', 'like', '%' . $search . '%')
                    ->orWhere('isp', 'like', '%' . $search . '%');
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Reason Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('reason')) {
            $query->where(
                'reason',
                'like',
                '%' . $request->reason . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Severity Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        /*
        |--------------------------------------------------------------------------
        | Country Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('country')) {
            $query->where(
                'country',
                'like',
                '%' . $request->country . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filters
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'created_at',
            'ip_address',
            'severity',
            'abuse_confidence_score',
            'expires_at',
        ];

        $sort = in_array($request->sort, $allowedSorts)
            ? $request->sort
            : 'created_at';

        $direction = $request->direction === 'asc'
            ? 'asc'
            : 'desc';

        /*
        |--------------------------------------------------------------------------
        | Paginated IP Records
        |--------------------------------------------------------------------------
        */

        $ips = $query
            ->orderBy($sort, $direction)
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Analytics
        |--------------------------------------------------------------------------
        */

        $totalBlockedIps = BlockedIp::count();

        $activeBlockedIps = BlockedIp::where(
            'status',
            'active'
        )->count();

        $expiredBlockedIps = BlockedIp::where(
            'status',
            'expired'
        )->count();

        $blockedToday = BlockedIp::whereDate(
            'created_at',
            today()
        )->count();

        $blockedThisMonth = BlockedIp::whereMonth(
            'created_at',
            now()->month
        )
            ->whereYear(
                'created_at',
                now()->year
            )
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

        $deleteActivities = IpBlockingActivity::where(
            'action',
            'DELETE'
        )->count();

        $highRiskIps = BlockedIp::whereIn(
            'severity',
            ['high', 'critical']
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

        $activityQuery = IpBlockingActivity::query();

        if ($request->filled('activity_search')) {
            $activitySearch = trim(
                $request->activity_search
            );

            $activityQuery->where(function ($q) use ($activitySearch) {
                $q->where(
                    'ip_address',
                    'like',
                    '%' . $activitySearch . '%'
                )
                    ->orWhere(
                        'reason',
                        'like',
                        '%' . $activitySearch . '%'
                    );
            });
        }

        if ($request->filled('activity_action')) {
            $activityQuery->where(
                'action',
                $request->activity_action
            );
        }

        if ($request->filled('activity_from')) {
            $activityQuery->whereDate(
                'created_at',
                '>=',
                $request->activity_from
            );
        }

        if ($request->filled('activity_to')) {
            $activityQuery->whereDate(
                'created_at',
                '<=',
                $request->activity_to
            );
        }

        $recentActivities = $activityQuery
            ->latest()
            ->limit(15)
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
            'activeBlockedIps',
            'expiredBlockedIps',
            'blockedToday',
            'blockedThisMonth',
            'totalActivities',
            'blockActivities',
            'unblockActivities',
            'deleteActivities',
            'highRiskIps',
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

            'severity' => [
                'required',
                'in:low,medium,high,critical',
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after:now',
            ],

            'abuse_confidence_score' => [
                'nullable',
                'integer',
                'min:0',
                'max:100',
            ],

            'country' => [
                'nullable',
                'string',
                'max:100',
            ],

            'isp' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $blockedIp = BlockedIp::create([
            'ip_address' => $validated['ip'],
            'reason' => $validated['reason'],
            'status' => 'active',
            'severity' => $validated['severity'],
            'expires_at' => $validated['expires_at'] ?? null,
            'abuse_confidence_score' =>
                $validated['abuse_confidence_score'] ?? 0,
            'country' => $validated['country'] ?? null,
            'isp' => $validated['isp'] ?? null,
        ]);

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
     * Unblock one IP.
     */
    public function destroy($id)
    {
        $blockedIp = BlockedIp::findOrFail($id);

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

    /**
     * Bulk unblock.
     */
    public function bulkUnblock(Request $request)
    {
        $validated = $request->validate([
            'ids' => [
                'required',
                'array',
                'min:1',
            ],

            'ids.*' => [
                'integer',
                'exists:blocked_ips,id',
            ],
        ]);

        $ips = BlockedIp::whereIn(
            'id',
            $validated['ids']
        )->get();

        foreach ($ips as $ip) {
            IpBlockingActivity::create([
                'ip_address' => $ip->ip_address,
                'action' => 'UNBLOCK',
                'reason' => 'Bulk unblock',
            ]);
        }

        BlockedIp::whereIn(
            'id',
            $validated['ids']
        )->delete();

        return back()->with(
            'success',
            $ips->count() . ' IP address(es) unblocked successfully.'
        );
    }

    /**
     * Bulk delete.
     */
    public function bulkDelete(Request $request)
    {
        $validated = $request->validate([
            'ids' => [
                'required',
                'array',
                'min:1',
            ],

            'ids.*' => [
                'integer',
                'exists:blocked_ips,id',
            ],
        ]);

        $ips = BlockedIp::whereIn(
            'id',
            $validated['ids']
        )->get();

        foreach ($ips as $ip) {
            IpBlockingActivity::create([
                'ip_address' => $ip->ip_address,
                'action' => 'DELETE',
                'reason' => 'Bulk delete',
            ]);
        }

        BlockedIp::whereIn(
            'id',
            $validated['ids']
        )->delete();

        return back()->with(
            'success',
            $ips->count() . ' IP address(es) deleted successfully.'
        );
    }

    /**
     * Export filtered IPs to CSV.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = BlockedIp::query();

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where(
                    'ip_address',
                    'like',
                    '%' . $search . '%'
                )
                    ->orWhere(
                        'reason',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'country',
                        'like',
                        '%' . $search . '%'
                    )
                    ->orWhere(
                        'isp',
                        'like',
                        '%' . $search . '%'
                    );
            });
        }

        if ($request->filled('reason')) {
            $query->where(
                'reason',
                'like',
                '%' . $request->reason . '%'
            );
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        if ($request->filled('severity')) {
            $query->where(
                'severity',
                $request->severity
            );
        }

        if ($request->filled('country')) {
            $query->where(
                'country',
                'like',
                '%' . $request->country . '%'
            );
        }

        if ($request->filled('from_date')) {
            $query->whereDate(
                'created_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'created_at',
                '<=',
                $request->to_date
            );
        }

        $allowedSorts = [
            'created_at',
            'ip_address',
            'severity',
            'abuse_confidence_score',
            'expires_at',
        ];

        $sort = in_array(
            $request->sort,
            $allowedSorts
        )
            ? $request->sort
            : 'created_at';

        $direction = $request->direction === 'asc'
            ? 'asc'
            : 'desc';

        $ips = $query
            ->orderBy($sort, $direction)
            ->get();

        return response()->streamDownload(
            function () use ($ips) {
                $handle = fopen('php://output', 'w');

                fputcsv($handle, [
                    'ID',
                    'IP Address',
                    'Reason',
                    'Status',
                    'Severity',
                    'Abuse Score',
                    'Country',
                    'ISP',
                    'Expires At',
                    'Created At',
                ]);

                foreach ($ips as $ip) {
                    fputcsv($handle, [
                        $ip->id,
                        $ip->ip_address,
                        $ip->reason,
                        $ip->effective_status,
                        $ip->severity,
                        $ip->abuse_confidence_score,
                        $ip->country,
                        $ip->isp,
                        optional($ip->expires_at)
                            ->format('Y-m-d H:i:s'),
                        optional($ip->created_at)
                            ->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            },
            'blocked-ips-' . now()->format('Y-m-d-His') . '.csv',
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }
}