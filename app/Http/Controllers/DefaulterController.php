<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Issue;
use Illuminate\Support\Carbon;

class DefaulterController extends Controller
{
    public function index()
    {
        // overdue = due_date < today and not returned
        $overdues = class_exists(Issue::class)
            ? Issue::with(['book','student'])
                   ->whereNull('returned_at')
                   ->whereNotNull('due_date')
                   ->where('due_date','<', Carbon::now())
                   ->orderBy('due_date','asc')
                   ->get()
            : collect();

        return view('defaulters.index', ['overdues' => $overdues]);
    }
}
