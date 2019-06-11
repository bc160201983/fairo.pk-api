<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Category;
use Validator;
use Auth;

class CategoriesController extends Controller
{
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = array();
        $categories = Category::paginate(10);
        if($categories->isEmpty()){
            return response()->json(['Error' => 'No data found',  'status' => 400 ]);
            //$data = ['error' => 'No data found', 'Status' => 400];  
        }
        
            foreach($categories as $category){
                $data[] = [
                    'id' => $category['id'],
                    'Name' => $category['CName'],
                    'DisplayOrder' => $category['DisplayOrder'],
                    'Picture' => url('/category_images/' . $category['Picture']),
                    'IsActive' => $category['IsActive'],
                ]; 
            }
          
            return response()->json(
                [
                    'Categories' => $data,
                    'Status' => 200,
                ]    
            );
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'CName' => 'required',
            //'DisplayOrder'  => 'required',
            'Picture' => 'required|image|mimes:jpg,jpeg,png,gif',

        ];
        $validate = Validator::make($request->all(), $rules);
        if($validate->fails()){
            return response()->json([$validate->errors(),  'status' => 400 ]);
        }

        $filename = null;
        if($request->hasFile('Picture')){
            $file = $request->file('Picture');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            //$filePath = public_path();
            $file->move('/category_images', $filename);
        }
        
        Category::create([
            'CName' => $request->get('CName'),
            'DisplayOrder' => $request->get('DisplayOrder'),
            'Picture' => $filename,
            'IsActive' => $request->get('IsActive'),
        ]);
        return response()->json(['success' => "Category Added Successfully", 'status' => 200]);
        //return response()->json($request->get('Picture'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = array();
        $category = Category::find($id);
        if(is_null($category)){
            return response()->json(['Error' => 'No data found',  'status' => 400 ]);
            //$data['error'] = 'No data found Status 400';  
        }
        //return response()->json($category);

        return $data = [
            'Categories' => $category,
            'Status' => 200,
            
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // $rules = [
        //     'CName' => 'required',
        //     //'DisplayOrder'  => 'required',
        //     'Picture' => 'required|image|mimes:jpg,jpeg,png,gif',

        // ];
        // $validate = Validator::make($request->all(), $rules);
        // if($validate->fails()){
        //     return response()->json([$validate->errors(),  'status' => 400 ]);
        // }
        //$data = array();
        // $category = Category::find($id);
        // if(is_null($category)){
        //     return response()->json(['Error' => 'No data found',  'status' => 400 ]);
        // }

        // $category->CName = $request->get('CName');
        // $category->DiplayOrder = $request->get('DiplayOrder');
        // $category->Picture = $request->get('Picture');
        // $category->IsActive = $request->get('IsActive');
        // $category->save();
        $category = Category::find($id);
        if(is_null($category)){
            return response()->json(['Error' => 'No data found',  'status' => 400 ]);
        }
        $category->update($request->all());
        
       
        return response()->json(['success' => $category, 'status' => 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if(is_null($category)){
            return response()->json(['Error' => 'No data found',  'status' => 400 ]);
        }
        $category->delete();
        return response()->json(['success' => 'Category Deleted Successfully', 'status' => 200]);
    }
}
