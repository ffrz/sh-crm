<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Interaction;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InteractionController extends Controller
{
    public function index()
    {
        return inertia('admin/interaction/Index');
    }

    public function detail($id = 0)
    {
        return inertia('admin/interaction/Detail', [
            'data' => Interaction::with([
                'user',
                'customer',
                'service',
                'created_by_user:id,username',
                'updated_by_user:id,username',
            ])->findOrFail($id),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'id');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        $q = Interaction::with([
            'user:id,username,name',
            'customer:id,name,company,address,business_type',
            'service:id,name'
        ]);

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('subject', 'like', '%' . $filter['search'] . '%')
                    ->orWhere('summary', 'like', '%' . $filter['search'] . '%')
                    ->orWhere('notes', 'like', '%' . $filter['search'] . '%')
                    ->orWhereHas('customer', function ($q) use ($filter) {
                        $q->where('name', 'like', '%' . $filter['search'] . '%')
                            ->orWhere('company', 'like', '%' . $filter['search'] . '%');
                    })
                    ->orWhereHas('service', function ($q) use ($filter) {
                        $q->where('name', 'like', '%' . $filter['search'] . '%');
                    });
            });
        }

        if (!empty($filter['status']) && ($filter['status'] != 'all')) {
            $q->where('status', '=', $filter['status']);
        }

        if (!empty($filter['type']) && ($filter['type'] != 'all')) {
            $q->where('type', '=', $filter['type']);
        }

        if (!empty($filter['engagement_level']) && ($filter['engagement_level'] != 'all')) {
            $q->where('engagement_level', '=', $filter['engagement_level']);
        }

        if (!empty($filter['date']) && ($filter['date'] != 'all')) {
            if ($filter['date'] == 'this_month') {
                $start = Carbon::now()->startOfMonth();
                $end = Carbon::now()->endOfMonth();
                $q->whereBetween('date', [$start, $end]);
            } elseif ($filter['date'] == 'last_month') {
                $start = Carbon::now()->subMonthNoOverflow()->startOfMonth();
                $end = Carbon::now()->subMonthNoOverflow()->endOfMonth();
                $q->whereBetween('date', [$start, $end]);
            } elseif ($filter['date'] == 'this_year') {
                $start = Carbon::now()->startOfYear();
                $end = Carbon::now()->endOfYear();
                $q->whereBetween('date', [$start, $end]);
            } elseif ($filter['date'] == 'last_year') {
                $start = Carbon::now()->subYear()->startOfYear();
                $end = Carbon::now()->subYear()->endOfYear();
                $q->whereBetween('date', [$start, $end]);
            } else {
                // Asumsikan filter['date'] dalam format YYYY-MM-DD
                try {
                    $date = Carbon::parse($filter['date']);
                    $q->whereDate('date', $date);
                } catch (\Exception $e) {
                    // Handle kesalahan parsing tanggal jika perlu
                }
            }
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function editor($id = 0)
    {
        $item = $id ? Interaction::findOrFail($id) : new Interaction([
            'status' => Interaction::Status_Planned,
            'user_id' => Auth::user()->id,
            'interaction_date' => Carbon::now(),
        ]);
        return inertia('admin/interaction/Editor', [
            'data' => $item,
            'users' => User::where('active', true)->orderBy('username', 'asc')->get(),
            'customers' => Customer::orderBy('name', 'asc')->get(),
            'services' => Service::orderBy('name', 'asc')->get(),
            'statuses' => Interaction::Statuses,
        ]);
    }

    public function save(Request $request)
    {
        $validated =  $request->validate([
            'user_id'          => 'required|exists:users,id',
            'customer_id'      => 'required|exists:customers,id',
            'service_id'       => 'required|exists:services,id',
            'date'             => 'required|date',
            'type'             => 'required|in:' . implode(',', array_keys(Interaction::Types)),
            'engagement_level' => 'required|in:' . implode(',', array_keys(Interaction::EngagementLevels)),
            'status'           => 'required|in:' . implode(',', array_keys(Interaction::Statuses)),
            'subject'          => 'required|string|max:255',
            'summary'          => 'nullable|string|max:500',
            'notes'            => 'nullable|string|max:500',
        ]);

        $item = !$request->id ? new Interaction() : Interaction::findOrFail($request->post('id', 0));
        $item->fill($validated);
        $item->save();

        return redirect(route('admin.interaction.index'))->with('success', "Intearksi #$item->id telah disimpan.");
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = Interaction::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => "Interaction $item->name telah dihapus."
        ]);
    }
}
