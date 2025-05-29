<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Visit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PharIo\Manifest\Author;

class VisitController extends Controller
{
    public function index()
    {
        return inertia('admin/visit/Index');
    }

    public function detail($id = 0)
    {
        return inertia('admin/visit/Detail', [
            'data' => Visit::with(['user', 'customer'])->findOrFail($id),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'id');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        $q = Visit::with(['user', 'customer']);

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('purpose', 'like', '%' . $filter['purpose'] . '%');
            });
        }

        if (!empty($filter['status']) && ($filter['status'] != 'all')) {
            $q->where('status', '=', $filter['status']);
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function duplicate($id)
    {
        $item = Visit::findOrFail($id);
        $item->id = null;
        $item->created_at = null;
        return inertia('admin/visit/Editor', [
            'data' => $item,
        ]);
    }

    public function editor($id = 0)
    {
        $item = $id ? Visit::findOrFail($id) : new Visit([
            'status' => Visit::Status_Planned,
            'user_id' => Auth::user()->id,
            'visit_date' => Carbon::now(),
        ]);
        return inertia('admin/visit/Editor', [
            'data' => $item,
            'users' => User::where('active', true)->orderBy('username', 'asc')->get(),
            'customers' => Customer::orderBy('name', 'asc')->get(),
            'statuses' => Visit::Statuses,
        ]);
    }

    public function save(Request $request)
    {
        $validated =  $request->validate([
            'user_id'        => 'required|exists:users,id',
            'customer_id'    => 'required|exists:customers,id',
            'visit_date'     => 'required|date',
            'visit_time'     => 'nullable|time',
            'purpose'        => 'required|string|max:500',
            'notes'          => 'nullable|string|max:500',
            'customer_status' => 'required|in:' . implode(',', array_keys(Customer::Statuses)),
            'status'         => 'required|in:' . implode(',', array_keys(Visit::Statuses)),
            'next_followup_date' => 'nullable|date|max:100',
            'location'       => 'nullable|string|max:100',
        ]);

        $item = !$request->id ? new Visit() : Visit::findOrFail($request->post('id', 0));

        $customer_status = $validated['customer_status'];
        unset($validated['customer_status']); // Remove id from validated data if exists

        DB::beginTransaction();

        Customer::where('id', $validated['customer_id'])->update(['status' => $customer_status]);
        $item->fill($validated);
        $item->save();

        DB::commit();

        return redirect(route('admin.visit.index'))->with('success', "Visit $item->name telah disimpan.");
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = Visit::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => "Visit $item->name telah dihapus."
        ]);
    }
}
