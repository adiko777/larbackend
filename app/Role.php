<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{

    public function hasPermissionTo(...$permissions){
        // role->hasPermissionTo('edit-user', 'edit-issue');
        return $this->permissions()->whereIn('slug', $permissions)->count();
    }

    public function permissions(){
        return $this->belongsToMany(Permission::class,'roles_permissions');
    }

    public function scopeDeveloper($query){
        return $query->where('slug', 'developer');
    }
    public function scopeAdmin($query){
        return $query->where('slug', 'admin');
    }

//    public function hasPermissionTo(...$permissions){
//        // user->hasPermissionTo('edit-user', 'edit-issue');
//        return $this->permissions()->whereIn('slug', $permissions)->count() ||
//            $this->roles()->whereHas('permissions', function ($q) use ($permissions){
//                $q->whereIn->whereIn('slug', $permissions);
//            })->count();
//    }
}
