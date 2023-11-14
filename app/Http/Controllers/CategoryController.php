<?php

namespace App\Http\Controllers;

use App\Exports\CategoryExport;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class CategoryController
 * @package App\Http\Controllers
 */
class CategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            #$data = Order::select('*');
            $categories = Category::select('categories.*')
                ->where('categories.status_id', '=', '1')
                ->get();

            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('image_preview', function ($row) {
                    $imagePath = Storage::url($row->cover_image);
                    $preview = '<a href="' . $imagePath . '" target="_blank" class="preview  btn btn-light rounded-pill btn-sm"><i class="las la-image pe-none"></i></a>';
                    return $preview;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<a href="javascript:void(0)" data-id="' . $row->id . '" class="view  btn btn-light rounded-pill btn-sm"><i class="las la-pen pe-none"></i></a> 
                                          <a href="javascript:void(0)" data-id="' . $row->id . '" class="destroy  btn btn-light rounded-pill btn-sm"><i class="lar la-trash-alt pe-none"></i></a>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'image_preview'])
                ->make(true);
        }

        return view('admin.category.index');
    }

    public function store(Request $request)
    {
        //request()->validate(Category::$rules);

        $response = ['code' => 400, 'status' => 'error'];

        # Store in Storage Folder
        $imagen = $request->file('cover');

        $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();
        $ruta = Storage::putFileAs('public/images/categories', $imagen, $coverName);

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => str_replace('public', '', $ruta),
        ];

        $category = Category::create($data);

        if ($category) {
            $response['code'] = 200;
            $response['status'] = 'success';
        }

        return response()->json($response);
    }

    public function update(Request $request, Category $category)
    {
        //request()->validate(Category::$rules);

        $response = ['code' => 400, 'status' => 'error'];

        $category = Category::find($request->id);

        $coverName = "";
        if ($request->hasFile('cover')) {
            $imagen = $request->file('cover');
            $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();            
            $ruta = Storage::putFileAs('public/images/categories', $imagen, $coverName);
            //$data['cover_image'] = $ruta;
            $category->cover_image =str_replace("public/", "", $ruta);
        }

        # Store in Storage Folder
        $category->name = $request->name;
        $category->description = $request->description;

        $result = $category->save();

        if ($result) {
            $response['code'] = 200;
            $response['status'] = 'success';

        } else {
            $errors = $category->getErrors();
            $response['errors'] = $errors->all();
        }

        return response()->json($response);
    }


    public function show(Request $request)
    {
        $category = Category::find($request->id);

        if ($category) {
            $response = [
                'code' => 200,
                'status' => 'success',
                'data' => $category
            ];
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        $category = Category::find($id);

        $category->status_id = 0;
        $category->save();

        $response = ['code' => 200, 'status' => 'success'];
        return response()->json($response);
    }

    public function export(Request $request)
    {
        return Excel::download(new CategoryExport($request->all()), 'archivo_excel.xlsx');
    }
}
