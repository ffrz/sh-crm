<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return inertia('admin/customer/Index');
    }

    public function detail($id = 0)
    {
        return inertia('admin/customer/Detail', [
            'data' => Customer::with(['assigned_user'])->findOrFail($id),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        $q = Customer::query();

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('phone', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('address', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('email', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('company', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('source', 'like', '%' . $filter['search'] . '%');
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
        $item = Customer::findOrFail($id);
        $item->id = null;
        $item->created_at = null;
        return inertia('admin/customer/Editor', [
            'data' => $item,
        ]);
    }

    public function editor($id = 0)
    {
        $item = $id ? Customer::findOrFail($id) : new Customer(['status' => Customer::Status_New]);
        return inertia('admin/customer/Editor', [
            'data' => $item,
            'users' => User::where('active', true)->orderBy('username', 'asc')->get(),
            'statuses' => Customer::Statuses,
        ]);
    }

    public function save(Request $request)
    {
        $validated =  $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:500',
            'company'        => 'nullable|string|max:255',
            'status'         => 'required|in:' . implode(',', array_keys(Customer::Statuses)),
            'source'         => 'nullable|string|max:100',
            'notes'          => 'nullable|string',
            'assigned_user_id' => 'nullable|exists:users,id',
        ]);

        $item = !$request->id ? new Customer() : Customer::findOrFail($request->post('id', 0));
        $item->fill($validated);
        $item->save();

        return redirect(route('admin.customer.index'))->with('success', "Pelanggan $item->name telah disimpan.");
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = Customer::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => "Pelanggan $item->name telah dihapus."
        ]);
    }
}
