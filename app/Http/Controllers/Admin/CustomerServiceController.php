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
            'customers' => Customer::where('active', '=', 1)->orderBy('name', 'asc')->get(),
            'services' => Service::where('active', '=', 1)->orderBy('name', 'asc')->get(),
        ]);
    }

    public function detail($id = 0)
    {
        return inertia('admin/customer-service/Detail', [
            'data' => CustomerService::with(['customer', 'service'])->findOrFail($id),
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
            'start_date'       => 'nullable|date',
            'end_date'         => 'nullable|date',
            'notes'            => 'nullable|string',
        ]);

        // validasi pengguna dengan layanan yang sama
        $exists = CustomerService::where('customer_id', $request->customer_id)
            ->where('service_id', $request->service_id)
            ->when($request->id, fn($q) => $q->where('id', '!=', $request->id))
            ->exists();

        if ($exists) {
            return back()->withErrors(['customer_id' => 'Data client dengan layanan tersebut sudah ada.'])->withInput();
        }

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
