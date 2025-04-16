<?php

namespace App\Services;

use App\Helpers\QueryHelper;
use App\Models\ReportBuilder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class UserService
{
    public $query = null;
    public $configurations = null;
    /**
     * Create a new class instance.
     */

    public function filterData($input)
    {
        $filterData = $input['filter'] ?? [];
        $this->configurations = $input['configurations']??[];
        $data['header'] = array_map(function ($key) {
            return null;
        }, array_flip($this->configurations));

        $allOptionsData = [];
        //if (!empty($filterData)) {
       
            $reportBuilders = ReportBuilder::whereIn('field_name', $this->configurations)->get();
            $this->query = User::query();
            foreach ($reportBuilders as $builder) {
                $data['body'][$builder->field_name] = null;
                if ($builder->field_type == 'complex') {
                    $data['header'][$builder->field_name] = $builder->master_table_info['pivot_select_fields'] ?? [];
                    $data['body'][$builder->field_name] = [];
                }
                $fieldName = $builder->field_name??null;

                $searchValue = $filterData[$fieldName] ?? null;

                $this->query = $this->queryBuilder($this->query, $builder, $fieldName, $searchValue);
            }
            $results = $this->query->get();
            $userIds =  $results->pluck('id')->toArray();
            $dynamicReportBuilders = $reportBuilders->where('dynamic', true);
            $optionReportBuilders = $reportBuilders->where('dynamic', false)->whereNotNull('option_info');
            foreach( $optionReportBuilders as $options ){
                $allOptionsData[$options->field_name]["data"] =$options->option_info??[];
                $allOptionsData[$options->field_name]["option_type"] =true;
            }
            foreach($dynamicReportBuilders as $dynamicField){
                $table = $dynamicField->master_table_info['table_name'] ?? null;
                $valueField = $dynamicField->master_table_info['value_field'] ?? null;
                $keyField = $dynamicField->master_table_info['key_field'] ?? null;
                $pivot_table = $dynamicField->master_table_info['pivot_table'] ?? null;
                if ($pivot_table) {
                    $pivot_main_key_field = $dynamicField->master_table_info['pivot_main_key_field'] ?? null;
                    $pivot_relation_key_field = $dynamicField->master_table_info['pivot_relation_key_field'] ?? null;
                    $query = DB::table($table);
                    $query = $this->getJoin($query, $table, $pivot_table, $keyField, $pivot_main_key_field, $pivot_relation_key_field);
                    $additionalFields = [];
                    if ($dynamicField->field_type == 'complex') {
                        $fields = $dynamicField->children->where('dynamic', true);
                        foreach ($fields as $child) {
                            if ($child->master_table_info['pivot_table'] == $child->master_table_info['table_name']) {
                                continue;
                            }
                            // dump($child->master_table_info['table_name'], $child->master_table_info['pivot_table']);
                            $query = $this->getComplexFieldJoin($query, $child->master_table_info['table_name'], $child->master_table_info['pivot_table'], $child->master_table_info['key_field'], $child->master_table_info['pivot_main_key_field'], $child->field_name);
                            // dump($query->toSql(), $query->getBindings(), $child->master_table_info);
                            $additionalFields[] = $child->master_table_info['table_name'].".".$child->master_table_info['value_field']." as ".$child->field_name;
                        }
                    }
                    // dump("====>", array_merge(["{$table}.*", $pivot_main_key_field], $additionalFields));
                    $queryData = $query->select(array_merge(["{$table}.*", $pivot_main_key_field], $additionalFields))->whereIn($pivot_main_key_field, $userIds)->get();
                } else {
                    $ids = $results->pluck($dynamicField->field_name);
                    $queryData = DB::table($table)->select([$valueField, $keyField])->whereIn($keyField, $ids)->groupBy($keyField)->get();
                }
                $allOptionsData[$dynamicField->field_name]["key_field"] = $keyField;
                $allOptionsData[$dynamicField->field_name]["value_field"] =$valueField;
                $allOptionsData[$dynamicField->field_name]["pivot_main_key_field"] =$pivot_main_key_field??null;
                $allOptionsData[$dynamicField->field_name]["data"] = $queryData->map(function ($item) {
                    return (array) $item; // Cast stdClass object to array
                })->toArray();
            }
            // dd($allOptionsData);
            $data['body'] =  $results->transform(function($item) use($data,$allOptionsData) {
                $result = [];
                if(isset($item->file_name)){
                    $file_name = $item->file_name;
                    $item->file_name = ['file_name'=> $file_name,"file_path"=> $item->file_path];
                }
                foreach ($data['header'] as $key => $value) {
                    // Default mapping of item attributes to the header structure
                    $result[$key] = $item->$key ?? null;
                    $pivotKeyField = $options['pivot_main_key_field'] ?? 'user_id';
                    if (isset($allOptionsData[$key])) {
                        $options = $allOptionsData[$key]??[];
                        $optionsData = $options['data'] ?? [];
                        // Case 1: If type is 'option' and item has a value for the key
                        if ($item->$key !== null && isset($options['option_type']) && $options['option_type'] === true) {
                            $result[$key] = $optionsData[$item->$key] ?? null;
                        }
                        // Case 2: Map based on key_field and value_field if item has a value for the key
                        elseif ($item->$key !== null) {
                            $keyField = $options['key_field'] ?? null;
                            $valueField = $options['value_field'] ?? null;
                            $result[$key] = collect($optionsData)
                                ->where($keyField, $item->$key)
                                ->first()[$valueField] ?? null;
                        }
                        // case 3
                        elseif(is_array($value)){
                            $options = collect($optionsData)->where($pivotKeyField, $item->id)->values();
                            $record = $options->transform(function($option,$key) use ($value){
                                $records = [];
                                foreach ($value as $optionValue) {
                                    $records[$optionValue] = $option[$optionValue] ?? null;
                                }
                                return $records;
                            })->toArray();
                            $result[$key] = $record;

                        }
                        // Case 4: Map based on pivot_main_key_field
                        else {
                            $valueField = $options['value_field'] ?? null;
                            $result[$key] = collect($optionsData)
                                ->where($pivotKeyField, $item->id)
                                ->pluck($valueField)
                                ->toArray();
                        }
                    }
                }
                return $result;
                // return array_intersect_key($item->toArray(), $data['body']) + array_diff_key($data['body'], $item->toArray());
            })->toArray();
            return $data;
       // }
    }

    private function getJoin($query, $table, $pivot_table, $keyField, $pivot_main_key_field, $pivot_relation_key_field = null)
    {
        // dump($pivot_table, $table);
        if ($pivot_table!=$table){
            $relationalField = $pivot_relation_key_field?:$pivot_main_key_field;
            $query->join($pivot_table, "{$table}.{$keyField}", '=', "{$pivot_table}.{$relationalField}");
            // dump("=--->", $query->toSql());
        }
        return $query;
    }

    private function getComplexFieldJoin($query, $table, $pivot_table, $keyField, $pivot_main_key_field, $pivot_relation_key_field = null)
    {
        // dump($pivot_table, $table);
        $relationalField = $pivot_relation_key_field?:$pivot_main_key_field;
        $query->join($table, "{$table}.{$keyField}", '=', "{$pivot_table}.{$relationalField}");
            // dump("=--->", $query->toSql());
        return $query;
    }

    private function queryBuilder($query, $builder, $fieldName, $searchValue, $parent = null)
    {
        $masterTableName = "users";
        $relationalSelect = [];
        try{
            DB::enableQueryLog();
            $pivotTableInfo = $builder->dynamic == true && isset($builder->master_table_info['pivot_table']) ? $builder->master_table_info : [];
            $display = $parent ? in_array($parent, $this->configurations) : in_array($fieldName, $this->configurations);
            // $masterTableName = $parent ? ($builder->master_table_info['table_name']??null) : $masterTableName;
            switch ($builder->field_type) {
                case 'text':
                case 'email':
                case 'textarea':
                    if(isset($fieldName) && !empty($searchValue)  && $display){
                        //$query->where($masterTableName.'.'.$fieldName, 'like', '%' . $searchValue . '%');
                        $query->where(DB::raw("REPLACE(REPLACE($masterTableName.$fieldName, '\n', ' '), '\r', '')"), 'like', '%' . $searchValue . '%');
                     }
                    break;
                case 'radio':
                case 'checkbox':
                case 'select':
                    if(isset($fieldName) && $searchValue!==null && $display) {
                        $searchValueAr = is_array($searchValue) ? $searchValue : [$searchValue];
                        if (!empty($pivotTableInfo)) {
                            // pivot table: language
                            // need to set join and has to add field into query->select in case if client says it should show uniqiue row.
                            $query->whereExists(function ($query) use($pivotTableInfo, $searchValueAr, $fieldName, $masterTableName) {
                                $query->select(DB::raw(1))
                                    ->from($pivotTableInfo['pivot_table'])
                                    ->whereRaw($pivotTableInfo['pivot_table'].'.'.$pivotTableInfo['pivot_main_key_field'].' = '.$masterTableName.'.id');

                                $query->where(function($query) use($pivotTableInfo, $fieldName, $searchValueAr) {
                                    foreach ($searchValueAr as $value) {
                                        $query->orWhere($pivotTableInfo['pivot_table'].'.'.$fieldName, $value);
                                    }
                                });
                            });
                        } else {
                            $query->where(function($query) use($masterTableName, $fieldName, $searchValueAr) {
                                foreach ($searchValueAr as $value) {
                                    $query->orWhere($masterTableName.'.'.$fieldName, $value);
                                }
                            });
                        }
                    }
                    break;
                case 'daterange':
                case 'datepicker':
                case 'date':
                    if(isset($fieldName) && $display && $builder->dynamic == false ){
                        // $query->whereBetween($masterTableName.'.'.$fieldName, [$searchValue['from'], $searchValue['to']]);
                        //note: need to format the $searchValue into Y-m-d
                        $from = isset($searchValue['from']) ? Carbon::parse($searchValue['from'])->format('Y-m-d'): null;
                        $to =  isset($searchValue['to']) ? Carbon::parse($searchValue['to'])->format('Y-m-d'): null;

                        if ($from && $to) {
                            // If both 'from' and 'to' are available, use whereBetween
                            $query->whereBetween($masterTableName . '.' . $fieldName, [$from, $to]);
                        } elseif ($from) {
                            // If only 'from' is available, use greater than or equal to
                            $query->where($masterTableName . '.' . $fieldName, '>=', $from);
                        } elseif ($to) {
                            // If only 'to' is available, use less than or equal to
                            $query->where($masterTableName . '.' . $fieldName, '<=', $to);
                        }
                    }
                    break;
                case 'complex':
                    $children = $builder->children;
                    $parentField = $builder->field_name;
                    $pivotTableInfo = $builder->master_table_info;
                    $complexValues = $searchValue;
                    if(!empty($complexValues) && $display){

                            $query->whereExists(function ($query) use($complexValues, $parentField, $children, $masterTableName, $pivotTableInfo) {
                                $query->select(DB::raw(1))
                                        ->from($pivotTableInfo['pivot_table'])
                                        ->whereRaw($pivotTableInfo['pivot_table'].'.'.$pivotTableInfo['pivot_main_key_field'].' = '.$masterTableName.'.id');
                                $query->where(function($query) use($complexValues, $parentField, $children, $masterTableName, $pivotTableInfo) {
                                    foreach ($complexValues as $values) {
                                        $query->orWhere(function($query) use($values, $parentField, $children, $masterTableName) {
                                            $fields = array_keys($values);

                                            foreach ($fields as $field) {
                                                $child = $children->where('field_name', $field)->first();
                                                $fieldName = $child->field_name??null;
                                                $pivotTableInfo = $child->master_table_info;
                                                $fieldType = $child->field_type;
                                                $searchValue = $values[$field]??null;
                                                if ($searchValue == null) {
                                                    continue;
                                                }
                                                if (in_array($fieldType, ['date', 'datepicker', 'daterange'])) {
                                                    $from = isset($searchValue['from']) ? Carbon::parse($searchValue['from'])->format('Y-m-d'): null;
                                                    $to =  isset($searchValue['to']) ? Carbon::parse($searchValue['to'])->format('Y-m-d'): null;

                                                    if ($from && $to) {
                                                        // If both 'from' and 'to' are available, use whereBetween
                                                        $query->whereBetween($pivotTableInfo['pivot_table'] . '.' . $fieldName, [$from, $to]);
                                                    } elseif ($from) {
                                                        // If only 'from' is available, use greater than or equal to
                                                        $query->where($pivotTableInfo['pivot_table'] . '.' . $fieldName, '>=', $from);
                                                    } elseif ($to) {
                                                        // If only 'to' is available, use less than or equal to
                                                        $query->where($pivotTableInfo['pivot_table'] . '.' . $fieldName, '<=', $to);
                                                    }
                                                } else if (in_array($fieldType, ['select', 'radio', 'checkbox'])) {
                                                    // dd($fieldName, $searchValue, $pivotTableInfo);
                                                    $searchValueAr = is_array($searchValue) ? $searchValue : [$searchValue];

                                                    $query->where(function($query) use($pivotTableInfo, $fieldName, $searchValueAr) {
                                                        foreach ($searchValueAr as $value) {
                                                            $query->orWhere($pivotTableInfo['pivot_table'].'.'.$fieldName, $value);
                                                        }
                                                    });
                                                } else {
                                                    //$query->where($pivotTableInfo['pivot_table'].'.'.$fieldName, 'like', "%{$searchValue}%");
                                                    $query->where(DB::raw("REPLACE(REPLACE({$pivotTableInfo['pivot_table']}.{$fieldName}, '\n', ' '), '\r', '')"), 'like', "%{$searchValue}%");
                                                }

                                                // $this->queryBuilder($query, $child, $fieldName, $values[$field], $parentField);
                                            }
                                        });
                                    }
                                });
                            });
                        // });
                        // dd($query->toSql(), $query->getBindings());
                    }
                    break;
                default:
                    # code...
                    break;
            }

        } catch(Throwable $th) {
            // dd($fieldName, $searchValue, $th->getMessage(), $th->getFile(), $th->getLine(), $th->getTrace(), $this->configurations, $builder);
            throw $th;
        }
        return $query;
    }
}
