<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Interaction;
use App\Models\User;
use App\Rules\UserIdOrAll;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        return inertia('admin/report/Index');
    }

    public function interaction(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validated = $request->validate([
                'period' => 'required',
                'user_id' => ['required', new UserIdOrAll()],
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            [$start_date, $end_date] = resolve_period($validated['period'], $validated['start_date'], $validated['end_date']);

            return response()->json([
                'url' => route('admin.report.interaction'),
                'params' => [
                    'download' => 1,
                    'user_id' => $validated['user_id'],
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ]
            ]);
        }

        $filter = $request->only(['user_id', 'start_date', 'end_date']);
        extract($filter);

        if (isset($start_date, $end_date, $user_id)) {
            $q = Interaction::with([
                'user:id,username,name',
                'customer:id,name,company,address,business_type',
                'service:id,name'
            ]);

            if ($user_id !== 'all') {
                $q->where('user_id', $user_id);
            }

            $items = $q->whereBetween('date', [$start_date, $end_date])
                ->where('status', Interaction::Status_Done)
                ->orderBy('date')
                ->select('id', 'date', 'type', 'engagement_level', 'subject', 'summary', 'user_id', 'customer_id', 'service_id')
                ->get();

            $title = 'Laporan Interaksi';
            $subtitles = ['Periode ' . format_date($start_date) . ' s/d ' . format_date($end_date)];
            $user = null;

            if ($user_id !== 'all') {
                $user = User::find($user_id);
                $title .= " - $user->name ($user->username)";
            } else {
                $title .= ' - All Sales';
            }

            $filename = env('APP_NAME') . ' - ' . $title;

            $data = [
                'title' => $title,
                'subtitles' => $subtitles,
                'items' => $items,
                'user' => $user,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ];

            return Pdf::loadView('report.interaction', $data)
                ->setPaper('a4', 'landscape')
                ->download($filename . '.pdf');
        }

        return inertia('admin/report/interaction/Interaction', [
            'users' => $this->getUsers(),
        ]);
    }

    public function salesActivity(Request $request)
    {
        if ($request->isMethod('POST')) {
            $validated = $request->validate([
                'period' => 'required',
                'user_id' => ['required', new UserIdOrAll()],
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            [$start_date, $end_date] = resolve_period($validated['period'], $validated['start_date'], $validated['end_date']);

            return response()->json([
                'url' => route('admin.report.sales-activity'),
                'params' => [
                    'download' => 1,
                    'user_id' => $validated['user_id'],
                    'start_date' => $start_date,
                    'end_date' => $end_date,
                ]
            ]);
        }

        $filter = $request->only(['user_id', 'start_date', 'end_date']);
        extract($filter);

        if (isset($start_date, $end_date, $user_id)) {

            // Interactions
            $interactions = DB::table('interactions')
                ->select(
                    DB::raw('DATE(date) as date'),
                    'user_id',
                    DB::raw('COUNT(*) as total_interactions')
                )
                ->where('status', 'done')
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date', 'user_id');

            // Closings
            $closings = DB::table('closings')
                ->select(
                    DB::raw('DATE(date) as date'),
                    'user_id',
                    DB::raw('COUNT(*) as total_closings')
                )
                ->whereBetween('date', [$start_date, $end_date])
                ->groupBy('date', 'user_id');

            // New Customers
            $new_customers = DB::table('customers')
                ->select(
                    DB::raw('DATE(created_datetime) as date'),
                    'created_by_uid',
                    DB::raw('COUNT(*) as total_new_customers')
                )
                ->whereBetween('created_datetime', [$start_date, $end_date])
                ->groupBy('date', 'created_by_uid');

            // Gabungkan semua dengan LEFT JOIN
            $items = DB::query()
                ->fromSub($interactions, 'i')
                ->leftJoinSub($closings, 'c', function ($join) {
                    $join->on('i.date', '=', 'c.date')
                        ->on('i.user_id', '=', 'c.user_id');
                })
                ->leftJoinSub($new_customers, 'n', function ($join) {
                    $join->on('i.date', '=', 'n.date')
                        ->on('i.user_id', '=', 'n.created_by_uid');
                })
                ->join('users', 'i.user_id', '=', 'users.id')
                ->select(
                    'i.date',
                    'users.name as sales_name',
                    'i.total_interactions',
                    DB::raw('COALESCE(c.total_closings, 0) as total_closings'),
                    DB::raw('COALESCE(n.total_new_customers, 0) as total_new_customers')
                )
                ->orderBy('i.date')
                ->orderBy('sales_name')
                ->get();

            $title = 'Laporan Interaksi';
            $subtitles = ['Periode ' . format_date($start_date) . ' s/d ' . format_date($end_date)];
            $user = null;

            if ($user_id !== 'all') {
                $user = User::find($user_id);
                $title .= " - $user->name ($user->username)";
            } else {
                $title .= ' - All Sales';
            }

            $filename = env('APP_NAME') . ' - ' . $title;

            $data = [
                'title' => $title,
                'subtitles' => $subtitles,
                'items' => $items,
                'user' => $user,
                'start_date' => $start_date,
                'end_date' => $end_date,
            ];

            return Pdf::loadView('report.sales-activity', $data)
                ->setPaper('a4', 'landscape')
                ->download($filename . '.pdf');
        }

        return inertia('admin/report/interaction/SalesActivity', [
            'users' => $this->getUsers(),
        ]);
    }

    protected function getUsers()
    {
        return User::where('active', true)
            ->orderBy('username', 'asc')
            ->select('id', 'username', 'name')
            ->get();
    }
}
