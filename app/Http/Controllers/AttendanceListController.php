<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AttendanceListController extends Controller
{
    public function index(){
        return view('manager.attendance.index');
    }
}