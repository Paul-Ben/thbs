<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class DepartmentController extends Controller
{
    public function index(): View
    {
        $departments = Department::orderByDesc('created_at')->paginate(15);
        return view('superadmin.departments.index', compact('departments'));
    }

    public function create(): View
    {
        return view('superadmin.departments.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:departments,code',
            'name' => 'required|string|max:255|unique:departments,name',
        ]);

        Department::create($validated);

        ToastMagic::success('Department created successfully.');

        return redirect()->route('superadmin.departments.index');
    }

    public function show(Department $department): View
    {
        return view('superadmin.departments.show', compact('department'));
    }

    public function edit(Department $department): View
    {
        return view('superadmin.departments.edit', compact('department'));
    }

    public function update(Request $request, Department $department): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:departments,code,' . $department->id,
            'name' => 'required|string|max:255|unique:departments,name,' . $department->id,
        ]);

        $department->update($validated);

        ToastMagic::success('Department updated successfully.');

        return redirect()->route('superadmin.departments.index');
    }

    public function destroy(Department $department): RedirectResponse
    {
        $department->delete();

        ToastMagic::success('Department deleted successfully.');

        return redirect()->route('superadmin.departments.index');
    }
}
