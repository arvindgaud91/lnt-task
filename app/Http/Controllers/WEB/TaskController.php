<?php

namespace App\Http\Controllers\WEB;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\model\Task;

class TaskController extends Controller
{
    
    protected $taskObj;
    protected $img;
    protected $color;

    public function __construct(Task $task)
    {
        $this->taskObj = $task;
        $this->createImage();    
        header("Content-type: image/png");
    }
    /**
     * Display a listing of the task.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            $columns = [
                0 => 'sap_id',
                1 => 'host_name',
                2 => 'loop_back',
                3 => 'mac_address',
            ];
            $queryString = $this->taskObj->latest();
            $totalData = $queryString->count();
            $start = $request->input('start');
            $length = $request->input('length');

            if (! empty($request->input('search.value'))) {
                $search = $request->input('search.value');
                $queryString = $queryString->where('sap_id', 'LIKE', "%{$search}%")
                                            ->orWhere('host_name', 'LIKE', "%{$search}%")
                                            ->orWhere('loop_back', 'LIKE', "%{$search}%")
                                            ->orWhere('mac_address', 'LIKE', "%{$search}%");
            }
            foreach($request->input('columns') as $key => $val){
                if (! empty($val['search']['value']) && $val['data']!='action') {
                    $search = $val['search']['value'];
                    $queryString = $queryString->where($val['data'], 'LIKE', "%{$search}%");
                }
            }

            $totalFiltered = $queryString->count();
            if ($length != -1 && $length != 'all') {
                $queryString = $queryString->offset($start)->limit($length);
            }
            //$data = $queryString->get();
            if (! empty($request->input('order'))) {
                $sortCol = $columns[intval($request->input('order.0.column'))];
                $dir = $request->input('order.0.dir');
                $queryString = $queryString->orderBy($sortCol, $dir);
            }
            $data = $queryString->get()->toArray();
            foreach($data as $key => $val){
                $data[$key]['action'] = '<a class="btn btn-sm btn-primary mr-2" href="'.route('task.edit',$val['id']).'">Edit</a><button type="submit" class="btn btn-sm btn-danger delete-task" data-id="'.$val['id'].'">Delete</button></form>';
            }
            //dd($data);
            $jsonData = [
                'draw' => intval($request->input('draw')),
                'recordsTotal' => intval($totalData),
                'recordsFiltered' => intval($totalFiltered),
                'data' => $data,
            ];

            return $jsonData;
        }
        return view('index');
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('task');
    }
  
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        //dd($request);
        $request->validate([
            'sap_id' => 'required|unique:tasks,sap_id|max:18|min:18',
            'host_name' => ['required','regex:/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/','unique:tasks,host_name','max:14','min:14'],
            'loop_back' => 'required|ipv4|unique:tasks,loop_back',
            'mac_address' => 'required|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|unique:tasks,mac_address|max:17|min:17'
        ]);

        $task = $this->taskObj->create($request->all());

        return redirect()->route('task.index')->with('success','Task created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\model\Task  $task
     * @return \Illuminate\Http\Response
     */
    /* public function show(Task $task)
    {
        return view('products.show',compact('task'));
    } */
   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  Integer  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = $this->taskObj->findOrFail($id);
        return view('edit',compact('task'));
    }
  
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\model\Task  $task
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $task = $this->taskObj->findOrFail($id);
        $request->validate([
            'sap_id' => 'required|max:18|min:18|unique:tasks,sap_id,'.$id,
            'host_name' => ['required','regex:/^(([a-zA-Z0-9]|[a-zA-Z0-9][a-zA-Z0-9\-]*[a-zA-Z0-9])\.)*([A-Za-z0-9]|[A-Za-z0-9][A-Za-z0-9\-]*[A-Za-z0-9])$/','unique:tasks,host_name,'.$id,'max:14','min:14'],
            'loop_back' => 'required|ipv4|unique:tasks,loop_back,'.$id,
            'mac_address' => 'required|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|max:17|min:17|unique:tasks,mac_address,'.$id,
        ]);
  
        $task->update($request->all());
  
        return redirect()->route('task.index')
                        ->with('success','Task updated successfully');
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
            $task = $this->taskObj->findOrFail($id);
            $task->delete();
            return response()->json(['status' => true, 'message' => 'Task deleted successfully', 'data' => null]);
        } catch (\Exception $exception) {
            return response()->json(['status' => false, 'message' => 'Something went wrong.'], 500);
        }
    }

    //create View
    public function createView(){
        \DB::statement('CREATE VIEW TaskView AS
        SELECT host_name, loop_back, mac_address
        FROM tasks');
    }

    //create View
    public function ScriptsToCallServer(){
        echo "2.1.c"; 
        echo disk_free_space("C:");
        
        echo "<br>";
        echo "<br>";
        
        echo "2.1.d"; 
        
        echo fileinode("test.php");
        echo "<br>";
        
        echo "2.1.e"; 
        
        
        // specifying directory 
        $dir = './'; 
          
        //scanning files in a given diretory in ascending order 
        $files = scandir($dir); 
          
        print_r($files); 
        
        
        echo "<br>"; 
        
        //FTP URL: ftp.dlptest.com or ftp://ftp.dlptest.com/
        //FTP User: dlpuser@dlptest.com
        //Password: SzMf7rTE4pCrf9dV286GuNe4N
        
        $stream_options = array('ftp' => array('overwrite' => true));
        $stream_context = stream_context_create($stream_options);
        if(copy('test.txt', 'ftp://dlpuser@dlptest.com:SzMf7rTE4pCrf9dV286GuNe4N@ftp.dlptest.com/test.txt', $stream_context)) {
          echo " Successful!!!";
        }
    }

    /**
     * create Image.
     * @return Image
     */
    public function createImage(){
        // create a 300*300 image
        $this->img = imagecreatetruecolor(600, 400);

        // allocate colors
        $this->color = imagecolorallocate($this->img, 255, 255, 255);
    }

    /**
     * Draw hexagone using coordinates.
     * @param Integer : $x1 coordinate
     * @param Integer : $y1 coordinate
     * @param Integer : $x2 coordinate
     * @param Integer : $y2 coordinate
     * @param Integer : $x3 coordinate
     * @param Integer : $y3 coordinate
     * @param Integer : $x4 coordinate
     * @param Integer : $y4 coordinate
     * @param Integer : $x5 coordinate
     * @param Integer : $y5 coordinate
     * @param Integer : $x6 coordinate
     * @param Integer : $y6 coordinate
     * @return Image
     */
    public function createHexagon($x1, $y1, $x2, $y2, $x3, $y3, $x4, $y4, $x5, $y5, $x6, $y6){
        $this->createLine($x1, $y1, $x2, $y2); 
        $this->createLine($x2, $y2, $x3, $y3); 
        $this->createLine($x3, $y3, $x4, $y4); 
        $this->createLine($x4, $y4, $x5, $y5); 
        $this->createLine($x5, $y5, $x6, $y6); 
        $this->createLine($x6, $y6, $x1, $y1);	
    }

    /**
     * Draw circle using coordinates.
     * @param Integer : $cx coordinate
     * @param Integer : $cy coordinate
     * @param Integer : $width
     * @param Integer : $height
     * @param Integer : $start
     * @param Integer : $end 
     * @return Image
     */
    public function createCircle($cx, $cy, $width, $height, $start, $end){
        imagearc($this->img, $cx, $cy, $width, $height, $start, $end, $this->color);	
    }

    /**
     * Draw diamond using coordinates.
     * @param Integer : $x1 coordinate
     * @param Integer : $y1 coordinate
     * @param Integer : $x2 coordinate
     * @param Integer : $y2 coordinate 
     * @return Image
     */
    public function createLine($x1, $y1, $x2, $y2){
        imageline($this->img, $x1, $y1, $x2, $y2, $this->color);	
    }

    /**
     * Draw diamond using coordinates.
     * @param Integer : $x1 coordinate
     * @param Integer : $y1 coordinate
     * @param Integer : $x2 coordinate
     * @param Integer : $y2 coordinate
     * @param Integer : $x3 coordinate
     * @param Integer : $y3 coordinate
     * @return Image
     */
    public function triangleCreate($x1, $y1, $x2, $y2, $x3, $y3){

        $this->createLine($x1, $y1, $x2, $y2); 
        $this->createLine($x2, $y2, $x3, $y3); 
        $this->createLine($x1, $y1, $x3, $y3);
    }

    /**
     * Draw geometric shapes coordinates.
     * @return Image
     */
    public function createImage1(){
        $this->createHexagon(150,75, 50, 200, 150, 325, 275, 325, 350,200, 275, 75);
        $this->createCircle(200, 200, 200, 200,  0, 360);
        $this->createLine(200, 125, 125, 200);
        $this->createLine(200, 125, 275, 200);
        $this->createLine(125, 200, 200, 275);
        $this->createLine(275, 200, 200, 275);
        $this->generateImage();
    }

    /**
     * Draw geometric shapes coordinates.
     * @return Image
     */
    public function createImage2(){
        $this->createCircle(100, 100, 50, 50,  0, 360);
        $this->createCircle(400, 100, 50, 50,  0, 360);
        $this->createCircle(110, 200, 50, 50,  0, 360);
        $this->createCircle(250, 300, 50, 50,  0, 360);
        $this->createCircle(390, 200, 50, 50,  0, 360);
        $this->createLine(125, 100, 375, 100);
        $this->createLine(100, 125, 100, 175);
        $this->createLine(400, 125, 400, 175);
        $this->createLine(125, 220, 225, 300);
        $this->createLine(275, 300, 375, 220);
        $this->generateImage();
    }

    /**
     * Draw geometric shapes coordinates.
     * @return Image
     */
    public function createImage3(){
        $this->triangleCreate(240, 100, 290, 50, 340, 100);
        $this->triangleCreate(40, 300, 90, 250, 140, 300);
        $this->triangleCreate(440, 300, 490, 250, 540, 300);
        $this->createLine(265, 100, 105, 265);
        $this->createLine(315, 100, 475, 265);
        $this->createLine(125, 285, 455, 285);
        $this->generateImage();
    }


    /**
     * generate image.
     * @return Image
     */
    public function generateImage(){
        //output image
        imagepng($this->img);
        // free memory
        imagedestroy($this->img);
    }
}
