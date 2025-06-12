<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PratihariNotice;

class PratihariNoticeController extends Controller
{
    public function showNoticeForm()
    {
        return view('admin.add-notice');
    }
}
