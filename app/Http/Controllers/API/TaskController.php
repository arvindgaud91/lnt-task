<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Task;
use Illuminate\Validation\ValidationException;

class TaskController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try{
            $columns = [
                0 => 'sap_id',
                1 => 'host_name',
                2 => 'loop_back',
                3 => 'mac_address',
            ];
            $queryString = Task::latest();
            $totalData = $queryString->count();
            /* $start = $request->input('start');
            $length = $request->input('length'); */

            if (! empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $queryString = $queryString->where('sap_id', 'LIKE', "%{$search}%")
                                            ->orWhere('host_name', 'LIKE', "%{$search}%")
                                            ->orWhere('loop_back', 'LIKE', "%{$search}%")
                                            ->orWhere('mac_address', 'LIKE', "%{$search}%");
            }
            if($request->input('columns')){
                foreach($request->input('columns') as $key => $val){
                    if (! empty($val['search']['value']) && $val['data']!='action') {
                        $search = $val['search']['value'];
                        $queryString = $queryString->where($val['data'], 'LIKE', "%{$search}%");
                    }
                }
            }

            $totalFiltered = $queryString->count();
            /* if ($length != -1 && $length != 'all') {
                $queryString = $queryString->offset($start)->limit($length);
            } */
            //$data = $queryString->get();
            if (! empty($request->input('order'))) {
                $sortCol = $columns[intval($request->input('order.0.column'))];
                $dir = $request->input('order.0.dir');
                $queryString = $queryString->orderBy($sortCol, $dir);
            }
            $data = $queryString->get()->toArray();
            /* foreach($data as $key => $val){
                $data[$key]['action'] = '<a class="btn btn-sm btn-primary mr-2" href="'.route('task.edit',$val['id']).'">Edit</a><button type="submit" class="btn btn-sm btn-danger delete-task" data-id="'.$val['id'].'">Delete</button></form>';
            } */
            //dd($data);
            $jsonData = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return response()->json(['status' => true, 'message' => 'successful', 'data' => $jsonData]);
        }
        catch (\Exception $exception) {dd($exception);
            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
        }
    }
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        try{
            $request->validate([
                'sap_id' => 'required|unique:tasks,sap_id|max:18|min:18',
                'host_name' => ['required','regex:/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/','unique:tasks,host_name','max:14', 'min:14'],
                'loop_back' => 'required|ipv4|unique:tasks,loop_back',
                'mac_address' => 'required|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|unique:tasks,mac_address|max:17|min:17'
            ]);

            $task = Task::create($request->all());

            return response()->json(['status' => true, 'message' => 'Task created successfully', 'data' => $task]);
        }
        catch (\Exception $exception) {
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => false,
                    'message'    => $exception->errors(),
                ], 422);
            }
            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
        }
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\model\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $ip)
    {
        try{
            //$task = Task::findOrFail($id);
            $task = Task::where('loop_back', $ip)->first();
            $request->validate([
                'sap_id' => 'required|max:18|min:18|unique:tasks,sap_id,'.$task->id,
                'host_name' => ['required','regex:/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/','unique:tasks,host_name,'.$task->id,'max:14','min:14'],
                'mac_address' => 'required|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|max:17|min:17|unique:tasks,mac_address,'.$task->id,
            ]);
    
            $task->sap_id = $request->sap_id;
            $task->host_name = $request->host_name;
            $task->mac_address = $request->mac_address;
            $task->save();
            return response()->json(['status' => true, 'message' => 'Task updated successfully', 'data' => null]);
        }
        catch (\Exception $exception) {
            if ($exception instanceof ValidationException) {
                return response()->json([
                    'status' => false,
                    'message'    => $exception->errors(),
                ], 422);
            }
            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\model\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try{
            $task = Task::findOrFail($id);
            $task->delete();
            return response()->json(['status' => true, 'message' => 'Task deleted successfully', 'data' => null]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    /**
     * filter data using ip ranges.
     *
     * @param  String  $IPaddr
     * @return \Illuminate\Http\Response
     */
    public function filterIpRanges ($IPaddr)
    {
        try{
            // Converts a string containing an (IPv4) Internet Protocol dotted address into a long integer
            $ip_address = ip2long($IPaddr);
            $result = Task::whereRaw('INET_ATON(loop_back)<="'.$ip_address.'"')->latest()->get();
            return response()->json(['status' => true, 'message' => 'successful', 'data' => $result]);
        }
        catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
        }
    }
}
