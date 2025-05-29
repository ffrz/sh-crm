<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServiceController extends Controller
{
    public function index()
    {
        return inertia('admin/service/Index');
    }

    public function detail($id = 0)
    {
        return inertia('admin/service/Detail', [
            'data' => Service::findOrFail($id),
            // TODO: Implement active customer count when the relationship is defined
            // 'active_customer_count' => Service::findOrFail($id)->customers()->where('active', true)->count(),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'name');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        $q = Service::query();

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('name', 'like', '%' . $filter['search'] . '%');
                $q->orWhere('notes', 'like', '%' . $filter['search'] . '%');
            });
        }

        if (!empty($filter['status']) && ($filter['status'] != 'all')) {
            $q->where('active', '=', $filter['status']);
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function duplicate($id)
    {
        $item = Service::findOrFail($id);
        $item->id = null;
        $item->created_at = null;
        return inertia('admin/service/Editor', [
            'data' => $item,
        ]);
    }

    public function editor($id = 0)
    {
        $item = $id ? Service::findOrFail($id) : new Service([
            'active' => true,
        ]);
        return inertia('admin/service/Editor', [
            'data' => $item,
            'users' => User::where('active', true)->orderBy('username', 'asc')->get(),
        ]);
    }

    public function save(Request $request)
    {
        $validated =  $request->validate([
            'name' => [
                'required',
                'max:255',
                Rule::unique('services', 'name')->ignore($request->id),
            ],
            'active' => 'required|boolean',
            'notes'  => 'nullable|string|max:255',
        ]);

        $item = !$request->id ? new Service() : Service::findOrFail($request->post('id', 0));
        $item->fill($validated);
        $item->save();

        return redirect(route('admin.service.index'))->with('success', "Layanan $item->name telah disimpan.");
    }

    public function delete($id)
    {
        allowed_roles([User::Role_Admin]);

        $item = Service::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => "Layanan $item->name telah dihapus."
        ]);
    }
}
