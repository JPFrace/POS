<?php

namespace App\Supports\Utils;

use App\Models\Member;
use App\Models\Vendor;
use \App\Models\User as Admin;
use Auth;

class User
{
    /**
     *  Get user current login member id
     * @return mixed
     */
    public static function memberId(bool $self = false): ?int
    {
        if (!Auth::check())
            return null;

        $id = $self ? null : Auth::user()->id;
        if (Auth::user() instanceof Member) {
            $id = Auth::user()->id;
        } elseif (Auth::user() instanceof Admin) {
            $id = Member::admin()->first()->id;
        }

        return $id;
    }

    /**
     *  Get user current login member id
     * @return mixed
     */
    public static function vendorId(bool $self = false): ?int
    {
        if (!Auth::check())
            return null;

        $id = $self ? null : Auth::user()->id;
        if (Auth::user() instanceof Vendor) {
            $id = Auth::user()->id;
        } else if (Auth::user() instanceof Admin) {
            $id = Vendor::admin()->first()->id;
        }

        return $id;
    }
}
