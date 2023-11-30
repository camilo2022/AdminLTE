<?php

namespace App\Http\Controllers;

use App\Exports\Product\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductDeleteRequest;
use App\Http\Requests\Product\ProductDestroyRequest;
use App\Http\Requests\Product\ProductEditRequest;
use App\Http\Requests\Product\ProductIndexQueryRequest;
use App\Http\Requests\Product\ProductMasiveRequest;
use App\Http\Requests\Product\ProductRestoreRequest;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Requests\Product\ProductUploadRequest;
use App\Http\Resources\Product\ProductIndexQueryCollection;
use App\Imports\Product\ProductImport;
use App\Models\Category;
use App\Models\ClothingLine;
use App\Models\Collection;
use App\Models\Color;
use App\Models\Model;
use App\Models\Product;
use App\Models\ProductHasColor;
use App\Models\ProductHasSize;
use App\Models\ProductPhoto;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\Trademark;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Products.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(ProductIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $products = Product::with([
                    'clothing_line' => function ($query) { $query->withTrashed(); },
                    'category' => function ($query) { $query->withTrashed(); },
                    'subcategory' => function ($query) { $query->withTrashed(); },
                    'model' => function ($query) { $query->withTrashed(); },
                    'trademark' => function ($query) { $query->withTrashed(); },
                    'collection' => function ($query) { $query->withTrashed(); },
                    'colors',
                    'sizes'
                ])
                ->when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->input('search'));
                    }
                )
                ->when($request->filled('start_date') && $request->filled('end_date'),
                    function ($query) use ($start_date, $end_date) {
                        $query->filterByDate($start_date, $end_date);
                    }
                )
                ->orderBy($request->input('column'), $request->input('dir'))
                ->withTrashed() //Trae los registros 'eliminados'
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new ProductIndexQueryCollection($products),
                $this->getMessage('Success'),
                200
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function create(ProductCreateRequest $request)
    {
        try {
            if($request->filled('clothing_line_id')) {
                return $this->successResponse(
                    Category::where('clothing_line_id', '=', $request->input('clothing_line_id'))->get(),
                    'Categorias encontrados con exito.',
                    200
                );
            }

            if($request->filled('category_id')) {
                return $this->successResponse(
                    Subcategory::where('category_id', '=', $request->input('category_id'))->get(),
                    'Subcategorias encontradas con exito.',
                    200
                );
            }

            return $this->successResponse(
                (object) [
                    'clothing_lines' => ClothingLine::all(),
                    'models' => Model::all(),
                    'trademarks' => Trademark::all(),
                    'sizes' => Size::all(),
                    'colors' => Color::all(),
                    'collections' => Collection::all()
                ],
                'Ingrese los datos para hacer la validacion y registro.',
                200
            );
        } catch (Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function store(ProductStoreRequest $request)
    {
        try {
            $product = new Product();
            $product->code = $request->input('code');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->clothing_line_id = $request->input('clothing_line_id');
            $product->category_id = $request->input('category_id');
            $product->subcategory_id = $request->input('subcategory_id');
            $product->model_id = $request->input('model_id');
            $product->trademark_id = $request->input('trademark_id');
            $product->collection_id = $request->input('collection_id');
            $product->save();

            collect($request->input('colors'))->map(function ($color) use ($product){
                $productHasColor = new ProductHasColor();
                $productHasColor->product_id = $product->id;
                $productHasColor->color_id = $color;
                $productHasColor->save();
            });

            collect($request->input('sizes'))->map(function ($size) use ($product){
                $productHasSize = new ProductHasSize();
                $productHasSize->product_id = $product->id;
                $productHasSize->size_id = $size;
                $productHasSize->save();
            });

            if ($request->hasFile('photos')) {
                $photos = $request->file('photos');
                foreach ($photos as $photo) {
                    $productPhoto = new ProductPhoto();
                    $productPhoto->product_id = $product->id;
                    $productPhoto->name = $photo->getClientOriginalName();
                    $productPhoto->path = $photo->store('Products/' . $product->id, 'public');
                    $productPhoto->save();
                }
            }

            return $this->successResponse(
                $product,
                'El producto fue registrado exitosamente.',
                201
            );
        } catch (ModelNotFoundException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function edit(ProductEditRequest $request, $id)
    {
        try {
            if($request->filled('clothing_line_id')) {
                return $this->successResponse(
                    Category::where('clothing_line_id', '=', $request->input('clothing_line_id'))->get(),
                    'Categorias encontrados con exito.',
                    200
                );
            }

            if($request->filled('category_id')) {
                return $this->successResponse(
                    Subcategory::where('category_id', '=', $request->input('category_id'))->get(),
                    'Subcategorias encontradas con exito.',
                    200
                );
            }

            return $this->successResponse(
                (object) [
                    'product' => Product::with([
                        'clothing_line' => function ($query) { $query->withTrashed(); },
                        'category' => function ($query) { $query->withTrashed(); },
                        'subcategory' => function ($query) { $query->withTrashed(); },
                        'model' => function ($query) { $query->withTrashed(); },
                        'trademark' => function ($query) { $query->withTrashed(); },
                        'collection' => function ($query) { $query->withTrashed(); },
                        'colors',
                        'sizes'
                    ])->findOrFail($id),
                    'clothing_lines' => ClothingLine::all(),
                    'models' => Model::all(),
                    'trademarks' => Trademark::all(),
                    'sizes' => Size::all(),
                    'colors' => Color::all(),
                    'collections' => Collection::all()
                ],
                'El producto fue encontrado exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function update(ProductUpdateRequest $request, $id)
    {
        try {
            $product = Product::withTrashed()->findOrFail($id);
            $product->code = $request->input('code');
            $product->description = $request->input('description');
            $product->price = $request->input('price');
            $product->clothing_line_id = $request->input('clothing_line_id');
            $product->category_id = $request->input('category_id');
            $product->subcategory_id = $request->input('subcategory_id');
            $product->model_id = $request->input('model_id');
            $product->trademark_id = $request->input('trademark_id');
            $product->collection_id = $request->input('collection_id');
            $product->save();

            $colors = collect($request->input('colors'))->map(function ($color) use ($product){
                $productHasColor = ProductHasColor::withTrashed()->where('color_id', '=', $color)->where('product_id', '=', $product->id)->first();
                $productHasColor = !is_null($productHasColor) ? $productHasColor : new ProductHasColor();
                $productHasColor->product_id = $product->id;
                $productHasColor->color_id = $color;
                $productHasColor->deleted_at = null;
                $productHasColor->save();
                return $productHasColor->id;
            });

            ProductHasColor::whereNotIn('id', $colors)->where('product_id', '=', $product->id)->delete();

            $sizes = collect($request->input('sizes'))->map(function ($size) use ($product){
                $productHasSize = ProductHasSize::withTrashed()->where('size_id', '=', $size)->where('product_id', '=', $product->id)->first();
                $productHasSize = !is_null($productHasSize) ? $productHasSize : new ProductHasSize();
                $productHasSize->product_id = $product->id;
                $productHasSize->size_id = $size;
                $productHasSize->deleted_at = null;
                $productHasSize->save();
                return $productHasSize->id;
            });

            ProductHasSize::whereNotIn('id', $sizes)->where('product_id', '=', $product->id)->delete();

            if ($request->hasFile('photos')) {
                $photos = $request->file('photos');
                foreach ($photos as $photo) {
                    $productPhoto = new ProductPhoto();
                    $productPhoto->product_id = $product->id;
                    $productPhoto->name = $photo->getClientOriginalName();
                    $productPhoto->path = $photo->store('Products/' . $product->id, 'public');
                    $productPhoto->save();
                }
            }

            return $this->successResponse(
                $product,
                'El producto fue actualizado exitosamente.',
                200
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function show($id)
    {
        try {
            $product = Product::with([
                'clothing_line' => function ($query) { $query->withTrashed(); },
                'category' => function ($query) { $query->withTrashed(); },
                'subcategory' => function ($query) { $query->withTrashed(); },
                'model' => function ($query) { $query->withTrashed(); },
                'trademark' => function ($query) { $query->withTrashed(); },
                'collection' => function ($query) { $query->withTrashed(); },
                'photos',
                'colors',
                'sizes'
            ])->findOrFail($id);

            foreach ($product->photos as $photo) {
                $photo->path = asset('storage/' . $photo->path);
            }

            return $this->successResponse(
                $product,
                'El producto fue encontrado exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function destroy(ProductDestroyRequest $request)
    {
        try {
            $productPhoto = ProductPhoto::findOrFail($request->input('id'));
            if (Storage::disk('public')->exists($productPhoto->path)) {
                Storage::disk('public')->delete($productPhoto->path);
            }
            $productPhoto->delete();
            return $this->successResponse(
                $productPhoto,
                'La imagen del producto fue eliminado exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function delete(ProductDeleteRequest $request)
    {
        try {
            $product = Product::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $product,
                'El producto fue eliminado exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function restore(ProductRestoreRequest $request)
    {
        try {
            $product = Product::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $product,
                'El producto fue restaurado exitosamente.',
                204
            );
        } catch (ModelNotFoundException $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ModelNotFoundException'),
                    'error' => $e->getMessage()
                ],
                404
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function upload(ProductUploadRequest $request)
    {
        try {
            $products = Excel::toCollection(new ProductImport, $request->file('products'))->first();

            $productsValidate = new ProductMasiveRequest();
            $productsValidate->merge([
                'products' => $products->toArray(),
            ]);

            $validator = Validator::make(
                $productsValidate->all(),
                $productsValidate->rules(),
                $productsValidate->messages()
            );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $groups = $products->groupBy('code');
            $products = $groups->map(function ($group, $index) {
                return [
                    'code' => $index,
                    'description' => $group->first()['description'],
                    'price' => $group->first()['price'],
                    'clothing_line_id' => $group->first()['clothing_line_id'],
                    'category_id' => $group->first()['category_id'],
                    'subcategory_id' => $group->first()['subcategory_id'],
                    'model_id' => $group->first()['model_id'],
                    'trademark_id' => $group->first()['trademark_id'],
                    'collection_id' => $group->first()['collection_id'],
                    'colors' => $group->pluck('color_id')->unique()->values()->toArray(),
                    'sizes' => $group->pluck('size_id')->unique()->values()->toArray(),
                ];
            })->values();

            foreach($products as $product) {
                $product = (object) $product;
                $productNew = new Product();
                $productNew->code = $product->code;
                $productNew->description = $product->description;
                $productNew->price = $product->price;
                $productNew->clothing_line_id = $product->clothing_line_id;
                $productNew->category_id = $product->category_id;
                $productNew->subcategory_id = $product->subcategory_id;
                $productNew->model_id = $product->model_id;
                $productNew->trademark_id = $product->trademark_id;
                $productNew->collection_id = $product->collection_id;
                $productNew->save();

                collect($product->colors)->map(function ($color) use ($productNew){
                    $productHasColor = new ProductHasColor();
                    $productHasColor->product_id = $productNew->id;
                    $productHasColor->color_id = $color;
                    $productHasColor->save();
                });

                collect($product->sizes)->map(function ($size) use ($productNew){
                    $productHasSize = new ProductHasSize();
                    $productHasSize->product_id = $productNew->id;
                    $productHasSize->size_id = $size;
                    $productHasSize->save();
                });
            }

            return $this->successResponse(
                '',
                'Los productos fueron registrados exitosamente.',
                201
            );
        } catch (ValidationException $e) {
            // Maneja los errores de validación del nuevo formulario y retorna una respuesta de error
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('ValidationException'),
                    'errors' => $e->errors(),
                ],
                422
            );
        } catch (Exception $e) {
            // Devuelve una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function download()
    {
        try {
            $products = Product::with([
                    'clothing_line' => function ($query) { $query->withTrashed(); },
                    'category' => function ($query) { $query->withTrashed(); },
                    'subcategory' => function ($query) { $query->withTrashed(); },
                    'model' => function ($query) { $query->withTrashed(); },
                    'trademark' => function ($query) { $query->withTrashed(); },
                    'collection' => function ($query) { $query->withTrashed(); },
                    'colors',
                    'sizes'
                ])
                ->get();

            return Excel::download(new ProductExport($products),"PRODUCTOS.xlsx");
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('QueryException'),
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
