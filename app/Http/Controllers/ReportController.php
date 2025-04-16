<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Models\ReportBuilder;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PDO;
use Ramsey\Uuid\Uuid;
use Throwable;

use function Laravel\Prompts\table;

class ReportController extends Controller
{
    public $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $configurations = [];
        $uuid =$request->get('searchId',null);
        $fields = ReportBuilder::whereNull('parent_id')->get()->pluck('field_name')->toArray();
        if($uuid ){
            $configurations = session()->get("builder.{$uuid}", []);
            $searchId =$uuid;
        }else{
            $searchId = Str::uuid();
        }
        // dd($configurations);
        return view('welcome', compact('fields', 'searchId','configurations'));
    }


    public function field()
    {
        $fillableFields = (new User())->getFillable();
        return view('report.index');
    }

    // In your controller
    public function getFilteredFields(Request $request)
    {
        $configurations = [];
        $selectedFields = $request->input('selected_fields', []);
        $uuid = $request->get('searchId',null);

        if($uuid ){
            $configurations = session()->get('builder',[])[$uuid]??[];
        }
        // Fetch fields from the database
        $fields = ReportBuilder::whereIn('field_name', $selectedFields)
            ->with('children')
            ->get();

        // Generate the view or response
        $html = view('report.filtered_fields', compact('fields', 'selectedFields','configurations'))->render();

        return response()->json(['html' => $html]);
    }

    public function getrecord(Request $request)
    {

        $filter = $request->except(['selectedFields', 'searchId', '_token']);
        $configurations = explode(',', $request->get('selectedFields'));
        $builder = ["configurations" => $configurations, "filter" => $filter];

        session()->put("builder.{$request->searchId}", $builder);

        // dd(session()->get("builder.{$request->searchId}"));
        $data = $this->service->filterData($builder);
        $id = $request->searchId;
        return view('report.list', compact('data', 'id'));
    }

    public function export($id)
    {
        $builder = session()->get('builder');
        $builder = $builder[$id]??[];
        $data = $this->service->filterData($builder ?? [], $id);
        return Excel::download(new UserExport( $data), 'users.xlsx');
    }
}
