<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Closing;
use App\Models\Service;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClosingController extends Controller
{
    public function index()
    {
        return inertia('admin/closing/Index', [
            'users' => User::where('active', true)->orderBy('username', 'asc')->get(),
            'services' => Service::orderBy('name', 'asc')->get(),
        ]);
    }

    public function detail($id = 0)
    {
        return inertia('admin/closing/Detail', [
            'data' => Closing::with([
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

        $q = Closing::with(['user', 'customer', 'service']);

        if (!empty($filter['search'])) {
            $q->where('description', 'like', '%' . $filter['search'] . '%')
                ->orWhere('notes', 'like', '%' . $filter['search'] . '%')
                ->orWhereHas('customer', function ($q) use ($filter) {
                    $q->where('name', 'like', '%' . $filter['search'] . '%')
                        ->orWhere('company', 'like', '%' . $filter['search'] . '%');
                })
                ->orWhereHas('service', function ($q) use ($filter) {
                    $q->where('name', 'like', '%' . $filter['search'] . '%');
                });
        }

        if (!empty($filter['user_id']) && ($filter['user_id'] != 'all')) {
            $q->where('user_id', '=', $filter['user_id']);
        }

        if (!empty($filter['service_id']) && ($filter['service_id'] != 'all')) {
            $q->where('service_id', '=', $filter['service_id']);
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
        $item = $id ? Closing::findOrFail($id) : new Closing([
            'user_id' => Auth::user()->id,
            'date' => Carbon::today(),
        ]);
        return inertia('admin/closing/Editor', [
            'data' => $item,
            'users' => User::where('active', true)->orderBy('username', 'asc')->get(),
            'customers' => Customer::orderBy('name', 'asc')->get(),
            'services' => Service::orderBy('name', 'asc')->get(),
        ]);
    }

    public function save(Request $request)
    {
        $validated =  $request->validate([
            'user_id'          => 'required|exists:users,id',
            'customer_id'      => 'required|exists:customers,id',
            'service_id'       => 'required|exists:services,id',
            'date'             => 'required|date',
            'description'      => 'required|string|max:255',
            'amount'           => 'required|numeric|gt:0',
            'notes'            => 'nullable|string|max:500',
        ]);

        $item = !$request->id ? new Closing() : Closing::findOrFail($request->post('id', 0));
        $item->fill($validated);
        $item->save();

        return redirect(route('admin.closing.index'))->with('success', "Intearksi #$item->id telah disimpan.");
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = Closing::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => "Closing $item->name telah dihapus."
        ]);
    }
}
