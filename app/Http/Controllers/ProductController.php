<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;

class ProductController extends Controller
{
    protected $filePath;
    
    public function __construct(){
        $this->filePath = public_path('database/information.json');
    }

    // Date = 2024-11-27
    // Author = Dhruvit S
    // Purpose = This function is used to fetch and display data
    public function index(){
        $results = $this->renderResult();
        return view('welcome',compact('results'));
    }

    // Date = 2024-11-27
    // Author = Dhruvit S
    // Purpose = This function is used to store/update submitted data into json format
    public function store(Request $request){
        try{
            $productName = $request->productName;
            $qty         = $request->qty;
            $price       = $request->price;
    
            $result = [
                'product_name' => $productName,
                'qty'          => $qty,
                'price'        => $price,
                'created_at'   => date('Y-m-d H:i:s')
            ];
            
            // Create directory if not exists 
            $directoryPath = public_path('database/');
            if(!File::isDirectory($directoryPath)){
                File::makeDirectory($directoryPath, 0777, true, true);
            } 
            
            // Store data in json format
            $resultData = $tempArray = array();
            if(($input = file_get_contents($this->filePath)) != false){
                $tempArray = json_decode($input, true);
            }

            if(!isset($request->productId)){
                array_push($tempArray, $result);
                $resultData[] = $tempArray;
            }else{
                $tempArray[$request->productId]['product_name'] = $productName;
                $tempArray[$request->productId]['qty']          = $qty;
                $tempArray[$request->productId]['price']        = $price;
            }
            
            $jsonData = json_encode($tempArray);
            file_put_contents($this->filePath, $jsonData);

            // Fetch inserted data
            $results   = $this->renderResult();
            $tableData = view('table',compact('results'))->render();
            
            return response()->json([
                'success' => true,
                'data'    => $tableData, 
                'message' => 'Product information saved successfully!'
            ]);
        }catch(Exception $e){
            return response()->json(['success' => false,'message' => 'Opps!Something went wrong,Please try again...']);
        }
    }

    // Date = 2024-11-27
    // Author = Dhruvit S
    // Purpose = This function is used to fetch selected record to update
    public function edit(Request $request){
        $result = $this->renderResult();

        return response()->json([
            'success' => true,
            'data'    => $result[$request->id], 
            'message' => 'Product information saved successfully!'
        ]);
    }

    // Date = 2024-11-27
    // Author = Dhruvit S
    // Purpose = This function return data stored in json fileis
    public function renderResult(){
        $results   = [];

        if(file_exists($this->filePath)){
            if(($input = file_get_contents($this->filePath)) != false){
                $results = json_decode($input, true);
            }
        }

        krsort($results);
        return $results;
    }
}
