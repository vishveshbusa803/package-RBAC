@extends('layouts.master')

@section('title')
    Permissions
@endsection

@section('content')
    @component('common-components.breadcrumb')
        @slot('pagetitle')
            Masters
        @endslot
        @slot('title')
            Permissions
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">

                <div class="d-flex justify-content-between align-items-center mb-3 p-3">
                    <h4 class="card-title mb-0">Permissions List</h4>

                    @can('permission-create')
                        <a href="{{ route('permissions.create') }}" class="btn btn-primary">
                            <i class="uil uil-plus"></i> Create Permission
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    @php
                        // Group permissions by module name
                        $groupedPermissions = [];
                        foreach ($permissions as $permission) {
                            $parts = explode('-', $permission->name);
                            $operation = array_pop($parts);
                            $moduleName = implode('-', $parts);

                            if (!isset($groupedPermissions[$moduleName])) {
                                $groupedPermissions[$moduleName] = [
                                    'operations' => [],
                                    'firstPermissionId' => $permission->id,
                                ];
                            }
                            $groupedPermissions[$moduleName]['operations'][] = [
                                'name' => $operation,
                                'fullName' => $permission->name,
                            ];
                        }
                    @endphp

                    <table id="datatable" class="table table-bordered dt-responsive nowrap"
                        style="border-collapse: collapse; width: 100%;">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Module Name</th>
                                <th>Permissions</th>
                                <th width="15%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($groupedPermissions as $moduleName => $moduleData)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="fw-bold">{{ ucfirst($moduleName) }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach ($moduleData['operations'] as $op)
                                                <span class="badge bg-info">{{ $op['name'] }}</span>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        {{-- EDIT --}}
                                        @can('permission-edit')
                                            <a href="{{ route('permissions.edit', Crypt::encrypt($moduleData['firstPermissionId'])) }}"
                                                class="btn btn-sm btn-warning me-1" data-bs-toggle="tooltip"
                                                title="Edit Module">
                                                <i class="uil uil-edit"></i>
                                            </a>
                                        @endcan

                                        {{-- DELETE --}}
                                        {{-- @can('permission-delete')
                                            <form action="{{ route('permissions.destroy', $moduleData['firstPermissionId']) }}" method="POST"
                                                style="display:inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip"
                                                    title="Delete Module" onclick="return confirm('Delete all permissions for this module?')">
                                                    <i class="uil uil-trash-alt"></i>
                                                </button>
                                            </form>
                                        @endcan --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
@endsection
