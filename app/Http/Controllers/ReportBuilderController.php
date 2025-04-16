<?php

namespace App\Http\Controllers;

use App\Exports\UserExport;
use App\Services\UserService;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class ReportBuilderController extends Controller
{

    private $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }


    public function listFilteredData($id)
    {

        try{
            $builder = [
                "1" => [
                    "configurations" => [
                        "name" => true,
                        "gender" => true,
                        "email" => true,
                        "languages" => true,
                        "education" => true,
                        "date_of_birth" => true,
                        "address" => true,
                        "file" => true
                    ],
                    "filters" => [
                        "name" => "Paritosh",
                        "gender" => "1",
                        "country_id" => "356",
                        "email" => "paritosh@gmail.com",
                        "languages" => [
                            "1",
                            "2",
                        ],
                        "education" => [
                            [
                                "year" => "2022-2024",
                                "degree_id" => "1",
                                "university" => "marwadi",
                            ],
                            [
                                "year" => "2020-2021",
                                "degree_id" => "1",
                                "university" => "VVP",
                            ],
                        ],
                        "date_of_birth" => [
                            "from" => "2023-12-16",
                            "to" => "2024-12-10"
                        ],
                        "address" => "",
                        "file" => "Download Report"
                    ]
                ]
            ];
    
            $data = $this->service->filterData($builder,$id);

            return view('report.list',compact('data','id'));

        }catch(Throwable $e)
        {
            dd($e);
        }
        
    }
    
    public function export($id)
    {
        $builder = [
            "1" => [
                "configurations" => [
                    "name" => true,
                    "gender" => true,
                    "email" => true,
                    "languages" => true,
                    "education" => true,
                    "date_of_birth" => true,
                    "address" => true,
                    "file" => true
                ],
                "filters" => [
                    "name" => "Paritosh",
                    "gender" => "1",
                    "country_id" => "356",
                    "email" => "paritosh@gmail.com",
                    "languages" => [
                        "1",
                        "4",
                    ],
                    "education" => [
                        [
                            "year" => "2022-2024",
                            "degree_id" => "1",
                            "university" => "marwadi",
                        ],
                        [
                            "year" => "2022-2024",
                            "degree_id" => "2",
                            "university" => "marwadi",
                        ],
                    ],
                    "date_of_birth" => [
                        "from" => "2023-12-16",
                        "to" => "2024-12-10"
                    ],
                    "address" => "",
                   "file" => "Download Report"
                ]
            ]
        ];
        $data = $this->service->filterData($builder,$id);
       
        return Excel::download(new UserExport($data['data'], $data['conf']), 'users.xlsx');
    }
}
