<?php

namespace App\Http\Controllers;

use App\Exports\Product\ProductExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductAssignColorToneRequest;
use App\Http\Requests\Product\ProductAssignSizeRequest;
use App\Http\Requests\Product\ProductChargeRequest;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Http\Requests\Product\ProductDeleteRequest;
use App\Http\Requests\Product\ProductDestroyRequest;
use App\Http\Requests\Product\ProductEditRequest;
use App\Http\Requests\Product\ProductIndexQueryRequest;
use App\Http\Requests\Product\ProductMasiveRequest;
use App\Http\Requests\Product\ProductRemoveColorToneRequest;
use App\Http\Requests\Product\ProductRemoveSizeRequest;
use App\Http\Requests\Product\ProductRestoreRequest;
use App\Http\Requests\Product\ProductStoreRequest;
use App\Http\Requests\Product\ProductUpdateRequest;
use App\Http\Requests\Product\ProductUploadRequest;
use App\Http\Resources\Product\ProductIndexQueryCollection;
use App\Imports\Product\ProductImportSheets;
use App\Models\Category;
use App\Models\ClothingLine;
use App\Models\Color;
use App\Models\Correria;
use App\Models\Model;
use App\Models\Product;
use App\Models\ProductColorTone;
use App\Models\ProductSize;
use App\Models\File;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\Tone;
use App\Models\Trademark;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
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
                    'clothing_line' => fn($query) => $query->withTrashed(),
                    'category' => fn($query) => $query->withTrashed(),
                    'subcategory' => fn($query) => $query->withTrashed(),
                    'model' => fn($query) => $query->withTrashed(),
                    'trademark' => fn($query) => $query->withTrashed(),
                    'correria' => fn($query) => $query->withTrashed(),
                    'colors_tones.color' => fn($query) => $query->withTrashed(),
                    'colors_tones.tone' => fn($query) => $query->withTrashed(),
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
                ->withTrashed() //Trae los registros 'eliminados'
                ->orderBy($request->input('column'), $request->input('dir'))
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
                [
                    'clothing_lines' => ClothingLine::all(),
                    'models' => Model::all(),
                    'trademarks' => Trademark::all(),
                    'correrias' => Correria::all(),
                ],
                'Ingrese los datos para hacer la validacion y registro.',
                204
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
            $product->price = $request->input('price');
            $product->cost = $request->input('cost');
            $product->clothing_line_id = $request->input('clothing_line_id');
            $product->category_id = $request->input('category_id');
            $product->subcategory_id = $request->input('subcategory_id');
            $product->model_id = $request->input('model_id');
            $product->trademark_id = $request->input('trademark_id');
            $product->correria_id = $request->input('correria_id');
            $product->save();

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
                [
                    'product' => Product::findOrFail($id),
                    'clothing_lines' => ClothingLine::all(),
                    'models' => Model::all(),
                    'trademarks' => Trademark::withTrashed()->get(),
                    'correrias' => Correria::withTrashed()->get(),
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
            $product->price = $request->input('price');
            $product->cost = $request->input('cost');
            $product->clothing_line_id = $request->input('clothing_line_id');
            $product->category_id = $request->input('category_id');
            $product->subcategory_id = $request->input('subcategory_id');
            $product->model_id = $request->input('model_id');
            $product->trademark_id = $request->input('trademark_id');
            $product->correria_id = $request->input('correria_id');
            $product->save();

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
            $colors = Color::all();
            $tones = Tone::all();
            $sizes = Size::with('products')->get();
            $product = Product::with(['clothing_line', 'category', 'subcategory', 'model', 'trademark', 'colors_tones.files', 'sizes'])->findOrFail($id);
            $colors_tones = collect([]);

            foreach ($product->colors_tones as $color_tone) {
                foreach ($color_tone->files as $file) {
                    $file->path = asset('storage/' . $file->path);
                }
            }

            foreach ($sizes as $size) {
                $productsId = $size->products->pluck('id')->all();
                $size->admin = in_array($id, $productsId);
            }

            foreach($colors as $color) {
                foreach($tones as $tone) {
                    $admin = false;

                    foreach ($product->colors_tones as $color_tone) {
                        if($color_tone->color->id == $color->id && $color_tone->tone->id == $tone->id) {
                            $admin = true;
                            break;
                        }
                    }

                    $colors_tones->push([
                        'color_id' => $color->id,
                        'color_name' => $color->name,
                        'color_code' => $color->code,
                        'tone_id' => $tone->id,
                        'tone_name' => $tone->name,
                        'tone_code' => $tone->code,
                        'admin' => $admin
                    ]);
                }
            }
            
            return $this->successResponse(
                [
                    'product' => $product,
                    'sizes' => $sizes,
                    'colors_tones' => $colors_tones,
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

    public function assignSize(ProductAssignSizeRequest $request)
    {
        try {
            $product_sizes = new ProductSize();
            $product_sizes->product_id = $request->input('product_id');
            $product_sizes->size_id = $request->input('size_id');
            $product_sizes->save();

            return $this->successResponse(
                $product_sizes,
                'Talla de producto asignado exitosamente.',
                200
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

    public function removeSize(ProductRemoveSizeRequest $request)
    {
        try {
            $product_sizes = ProductSize::where('product_id', '=', $request->input('product_id'))
            ->where('size_id', '=', $request->input('size_id'))->delete();

            return $this->successResponse(
                $product_sizes,
                'Talla de producto removido exitosamente.',
                200
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

    public function assignColorTone(ProductAssignColorToneRequest $request)
    {
        try {
            $product_color_tone = new ProductColorTone();
            $product_color_tone->product_id = $request->input('product_id');
            $product_color_tone->color_id = $request->input('color_id');
            $product_color_tone->tone_id = $request->input('tone_id');
            $product_color_tone->save();

            return $this->successResponse(
                $product_color_tone,
                'Color y tono de producto asignado exitosamente.',
                200
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

    public function removeColorTone(ProductRemoveColorToneRequest $request)
    {
        try {
            $product_sizes = ProductColorTone::where('product_id', '=', $request->input('product_id'))
            ->where('color_id', '=', $request->input('color_id'))
            ->where('tone_id', '=', $request->input('tone_id'))
            ->delete();

            return $this->successResponse(
                $product_sizes,
                'Color y tono de producto removido exitosamente.',
                200
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

    public function charge(ProductChargeRequest $request)
    {
        try {
            $productColorTone = ProductColorTone::with('product')->findOrFail($request->input('product_color_tone_id'));
            foreach ($request->file('files') as $archive) {
                $file = new File();
                $file->model_type = ProductColorTone::class;
                $file->model_id = $request->input('product_color_tone_id');
                $file->name = $archive->getClientOriginalName();
                $file->path = $archive->store('Products/' . $productColorTone->product->id . '/' . $productColorTone->id, 'public');
                $file->mime = $archive->getMimeType();
                $file->extension = $archive->getClientOriginalExtension();
                $file->size = $archive->getSize();
                $file->user_id = Auth::user()->id;
                $file->metadata = json_encode((array) stat($archive));
                $file->save();
            }
            return $this->successResponse(
                '',
                'Los archivos del producto fueron cargados exitosamente.',
                200
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

    public function destroy(ProductDestroyRequest $request)
    {
        try {
            $file = File::findOrFail($request->input('id'));
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }
            $file->delete();

            return $this->successResponse(
                $file,
                'La imagen del producto fue eliminado exitosamente.',
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
            $products = Excel::toCollection(new ProductImportSheets, $request->file('products'));

            foreach($products['Products'] as $product) {
                $product['clothingLine_category'] = $product['category_id'];
                $product['category_subcategory'] = $product['subcategory_id'];
            }

            $productsValidate = new ProductMasiveRequest();
            $productsValidate->merge([
                'Products' => $products['Products']->toArray(),
                'ProductsSizes' => $products['ProductsSizes']->toArray(),
                'ProductsColorsTones' => $products['ProductsColorsTones']->toArray(),
            ]);

            $validator = Validator::make(
                $productsValidate->all(),
                $productsValidate->rules(),
                $productsValidate->messages()
            );

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }

            $products = [];
            foreach ($productsValidate->Products as $product) {
                $products[$product['code']] = $product;
            }

            // Combinar información de tallas y colores_tones en los productos
            foreach ($productsValidate->ProductsSizes as $size) {
                $products[$size['code']]['sizes'][] = ['size_id' => $size['size_id']];
            }

            foreach ($productsValidate->ProductsColorsTones as $colorTone) {
                $products[$colorTone['code']]['colors_tones'][] = ['color_id' => $colorTone['color_id'], 'tone_id' => $colorTone['tone_id']];
            }

            // Extraer los productos del array asociativo
            $products = array_values($products);

            $products = collect($products)->map(function ($product) {
                $product = (object) $product;

                $productNew = new Product();
                $productNew->code = $product->code;
                $productNew->price = $product->price;
                $productNew->cost = $product->cost;
                $productNew->clothing_line_id = $product->clothing_line_id;
                $productNew->category_id = $product->category_id;
                $productNew->subcategory_id = $product->subcategory_id;
                $productNew->model_id = $product->model_id;
                $productNew->trademark_id = $product->trademark_id;
                $productNew->correria_id = $product->correria_id;
                $productNew->save();

                // Map para sizes
                $product->sizes = collect($product->sizes)->map(function ($productSizes) use ($productNew) {
                    $productSizes = (object) $productSizes;
                    $productSizesNew = new ProductSize();
                    $productSizesNew->product_id = $productNew->id;
                    $productSizesNew->size_id = $productSizes->size_id;
                    $productSizesNew->save();
                    return $productSizes;
                });

                // Map para colors_tones
                $product->colors_tones = collect($product->colors_tones)->map(function ($productColorsTones) use ($productNew) {
                    $productColorsTones = (object) $productColorsTones;
                    $productColorsTonesNew = new ProductColorTone();
                    $productColorsTonesNew->product_id = $productNew->id;
                    $productColorsTonesNew->color_id = $productColorsTones->color_id;
                    $productColorsTonesNew->tone_id = $productColorsTones->tone_id;
                    $productColorsTonesNew->save();
                    return $productColorsTones;
                });

                return $product;
            });

            return $this->successResponse(
                $products,
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
                    'correria' => fn($query) => $query->withTrashed(),
                    'clothing_line' => fn($query) => $query->withTrashed(),
                    'category' => fn($query) => $query->withTrashed(),
                    'subcategory' => fn($query) => $query->withTrashed(),
                    'model' => fn($query) => $query->withTrashed(),
                    'trademark' => fn($query) => $query->withTrashed(),
                    'colors_tones.color' => fn($query) => $query->withTrashed(),
                    'colors_tones.tone' => fn($query) => $query->withTrashed(),
                    'sizes' => fn($query) => $query->withTrashed(),
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
