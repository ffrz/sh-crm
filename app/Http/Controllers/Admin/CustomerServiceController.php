<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\CustomerService;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerServiceController extends Controller
{
    public function index()
    {
        return inertia('admin/customer-service/Index', [
            'customers' => Customer::query()->orderBy('name', 'asc')->get(),
            'services' => Service::query()->orderBy('name', 'asc')->get(),
        ]);
    }

    public function detail($id = 0)
    {
        return inertia('admin/customer-service/Detail', [
            'data' => CustomerService::with([
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

        $q = CustomerService::with(['customer', 'service']);

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

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function editor($id = 0)
    {
        $item = $id ? CustomerService::findOrFail($id) : new CustomerService([
            'status' => CustomerService::Status_Active,
        ]);
        return inertia('admin/customer-service/Editor', [
            'data' => $item,
            'customers' => Customer::orderBy('name', 'asc')->get(),
            'services' => Service::orderBy('name', 'asc')->get(),
        ]);
    }

    public function save(Request $request)
    {
        $validated =  $request->validate([
            'customer_id'      => 'required|exists:customers,id',
            'service_id'       => 'required|exists:services,id',
            'status'           => 'required|in:' . implode(',', array_keys(CustomerService::Statuses)),
            'description'      => 'nullable|string',
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date',
            'notes'            => 'nullable|string',
        ]);

        $item = !$request->id ? new CustomerService() : CustomerService::findOrFail($request->post('id', 0));
        $item->fill($validated);
        $item->save();

        return redirect(route('admin.customer-service.index'))->with('success', "Intearksi #$item->id telah disimpan.");
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = CustomerService::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => "CustomerService #$item->id telah dihapus."
        ]);
    }
}
