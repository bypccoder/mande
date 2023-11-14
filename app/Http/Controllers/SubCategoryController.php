<?php

namespace App\Http\Controllers;

use App\Exports\SubCategoryExport;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

/**
 * Class SubCategoryController
 * @package App\Http\Controllers
 */
class SubCategoryController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            #$data = Order::select('*');
            $subCategories = SubCategory::leftJoin('categories', 'sub_categories.category_id', '=', 'categories.id')
                ->select('sub_categories.*', 'categories.name as subcategory_name')
                ->where('sub_categories.status_id', '=', '1')
                ->get();

            return DataTables::of($subCategories)
                ->addIndexColumn()
                ->addColumn('image_preview', function ($row) {
                    $imagePath = Storage::url($row->cover_image);
                    $preview = '<a href="' . $imagePath . '" target="_blank" class="preview  btn btn-light rounded-pill btn-sm"><i class="las la-image pe-none"></i></a>';
                    return $preview;
                })
                ->addColumn('iced', function ($row) {

                    $tag = ($row->is_iced) ? "<span class='badge text-bg-info text-white'>S/. $row->iced_price</span>" : '-';
                    $html = $tag;
                    return $html;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '<div class="d-flex flex-row"><a href="javascript:void(0)" data-id="' . $row->id . '" class="view  btn btn-light rounded-pill btn-sm"><i class="las la-pen pe-none"></i></a> 
                                      <a href="javascript:void(0)" data-id="' . $row->id . '" class="destroy  btn btn-light rounded-pill btn-sm"><i class="lar la-trash-alt pe-none"></i></a>';
                    $actionBtn .= '<div class="dropdown">
                                        <button class="btn btn-light rounded-pill btn-sm" type="button" data-coreui-toggle="dropdown" aria-expanded="false">
                                        <i class="las la-ellipsis-v"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                        <li><a class="dropdown-item enable-iced" data-id="' . $row->id . '" href="#">Habilitar Bebida Fria</a></li>
                                        </ul>
                                    </div></div>';
                    return $actionBtn;
                })
                ->rawColumns(['action', 'image_preview', 'iced'])
                ->make(true);
        }

        $categories = Category::where('status_id', 1)->get();

        $data = [
            'categorias' => $categories
        ];

        return view('subcategory.index', $data);
    }

    public function store(Request $request)
    {
        //request()->validate(SubCategory::$rules);

        $response = ['code' => 400, 'status' => 'error'];

        # Store in Storage Folder
        $imagen = $request->file('cover');

        $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();
        $ruta = Storage::putFileAs('public/images/sub-categories', $imagen, $coverName);

        $data = [
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
            'cover_image' => $ruta,
        ];

        if ($request->iced_price) {
            $data['is_iced'] = 1;
            $data['iced_price'] = $request->iced_price;
        }

        $subCategory = SubCategory::create($data);

        if ($subCategory) {
            $response['code'] = 200;
            $response['status'] = 'success';
        }

        return response()->json($response);
    }

    public function show(Request $request)
    {
        $subCategory = SubCategory::find($request->id);

        if ($subCategory) {
            $response = [
                'code' => 200,
                'status' => 'success',
                'data' => $subCategory
            ];
        }

        return response()->json($response);
    }

    public function update(Request $request)
    {
        //request()->validate(SubCategory::$rules);


        $response = ['code' => 400, 'status' => 'error'];

        $subCategory = SubCategory::find($request->id);

        $coverName = "";
        if ($request->hasFile('cover')) {
            $imagen = $request->file('cover');
            $coverName = $request->name . '_' . time() . '.' . $imagen->getClientOriginalExtension();
            $ruta = Storage::putFileAs('public/images/categories', $imagen, $coverName);
            //$data['cover_image'] = $ruta;
            $subCategory->cover_image = str_replace("public/", "", $ruta);
        }

        $subCategory->name = $request->name;
        $subCategory->description = $request->description;
        $subCategory->iced_price = $request->iced_price;

        if (!$request->iced_price) {            
            $subCategory->is_iced = 0; 
        }

        $result = $subCategory->save();

        if ($result) {
            $response['code'] = 200;
            $response['status'] = 'success';
        } else {
            $errors = $subCategory->getErrors();
            $response['errors'] = $errors->all();
        }

        return response()->json($response);
    }

    public function destroy($id)
    {
        $subCategory = SubCategory::find($id);

        $subCategory->status_id = 0;
        $subCategory->save();

        $response = ['code' => 200, 'status' => 'success'];
        return response()->json($response);
    }

    public function enableIced(Request $request)
    {
        $response = ['code' => 400, 'status' => 'error'];

        $subcategory = $request->id;
        $iced_price = $request->price;

        if (!$subcategory) {
            return response()->json($response);
        }

        $subCategory = SubCategory::find($request->id);

        $subCategory->is_iced = 1;
        $subCategory->iced_price = $iced_price;
        $subCategory->save();

        $response = ['code' => 200, 'status' => 'success'];
        return response()->json($response);
    }

    public function export(Request $request)
    {
        return Excel::download(new SubCategoryExport($request->all()), 'archivo_excel.xlsx');
    }
}
