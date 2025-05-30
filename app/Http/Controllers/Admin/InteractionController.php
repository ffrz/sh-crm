<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Interaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InteractionController extends Controller
{
    public function index()
    {
        return inertia('admin/interaction/Index');
    }

    public function detail($id = 0)
    {
        return inertia('admin/interaction/Detail', [
            'data' => Interaction::with(['user', 'customer', 'service'])->findOrFail($id),
        ]);
    }

    public function data(Request $request)
    {
        $orderBy = $request->get('order_by', 'id');
        $orderType = $request->get('order_type', 'asc');
        $filter = $request->get('filter', []);

        $q = Interaction::with(['user', 'customer', 'service']);

        if (!empty($filter['search'])) {
            $q->where(function ($q) use ($filter) {
                $q->where('subject', 'like', '%' . $filter['search'] . '%');
                $q->where('summary', 'like', '%' . $filter['search'] . '%');
                $q->where('notes', 'like', '%' . $filter['search'] . '%');
            });
        }

        if (!empty($filter['status']) && ($filter['status'] != 'all')) {
            $q->where('status', '=', $filter['status']);
        }

        if (!empty($filter['type']) && ($filter['type'] != 'all')) {
            $q->where('type', '=', $filter['type']);
        }

        $q->orderBy($orderBy, $orderType);

        $items = $q->paginate($request->get('per_page', 10))->withQueryString();

        return response()->json($items);
    }

    public function duplicate($id)
    {
        $item = Interaction::findOrFail($id);
        $item->id = null;
        $item->created_at = null;
        return inertia('admin/interaction/Editor', [
            'data' => $item,
        ]);
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
            'statuses' => Interaction::Statuses,
        ]);
    }

    public function save(Request $request)
    {
        $validated =  $request->validate([
            'user_id'        => 'required|exists:users,id',
            'customer_id'    => 'required|exists:customers,id',
            'interaction_date'     => 'required|date',
            'interaction_time'     => 'nullable|time',
            'purpose'        => 'required|string|max:500',
            'notes'          => 'nullable|string|max:500',
            'customer_status' => 'required|in:' . implode(',', array_keys(Customer::Statuses)),
            'status'         => 'required|in:' . implode(',', array_keys(Interaction::Statuses)),
            'next_followup_date' => 'nullable|date|max:100',
            'location'       => 'nullable|string|max:100',
        ]);

        $item = !$request->id ? new Interaction() : Interaction::findOrFail($request->post('id', 0));

        $customer_status = $validated['customer_status'];
        unset($validated['customer_status']); // Remove id from validated data if exists

        DB::beginTransaction();

        Customer::where('id', $validated['customer_id'])->update(['status' => $customer_status]);
        $item->fill($validated);
        $item->save();

        DB::commit();

        return redirect(route('admin.interaction.index'))->with('success', "Interaction $item->name telah disimpan.");
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
