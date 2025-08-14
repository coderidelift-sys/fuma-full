<?php

namespace App\DataTables\Scopes;

use Yajra\DataTables\Contracts\DataTableScope;

class UserRoleScope implements DataTableScope
{
    protected $roleId;

    public function __construct($roleId = null)
    {
        $this->roleId = $roleId;
    }

    public function apply($query)
    {
        return $query->when($this->roleId, function ($query) {
            $query->whereHas('roles', function ($query) {
                $query->where('id', $this->roleId);
            });
        });
    }
}
