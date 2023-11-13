<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoriesAndSubcategories\CategoriesAndSubcategoriesDeleteRequest;
use App\Http\Requests\CategoriesAndSubcategories\CategoriesAndSubcategoriesIndexQueryRequest;
use App\Http\Requests\CategoriesAndSubcategories\CategoriesAndSubcategoriesRestoreRequest;
use App\Http\Requests\CategoriesAndSubcategories\CategoriesAndSubcategoriesStoreRequest;
use App\Http\Requests\CategoriesAndSubcategories\CategoriesAndSubcategoriesUpdateRequest;
use App\Http\Resources\CategoriesAndSubcategories\CategoriesAndSubcategoriesIndexQueryCollection;
use App\Models\Category;
use App\Models\ClothingLine;
use App\Models\Subcategory;
use App\Traits\ApiResponser;
use App\Traits\ApiMessage;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;

class CategoriesAndSubcategoriesController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.CategoriesAndSubcategories.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(CategoriesAndSubcategoriesIndexQueryRequest $request)
    {
        try{
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            // Consultar roles con relaciones y aplicar filtros
            $categoriesAndSubcategories = Category::with([
                    'clothing_line' => function ($query) {
                        $query->withTrashed(); // Incluye líneas de ropa eliminadas
                    },
                    'subcategories' => function ($query) {
                        $query->withTrashed(); // Incluye subcategorías eliminadas
                    }
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
            // Devolver una respuesta exitosa con los roles y permisos paginados
            return $this->successResponse(
                new CategoriesAndSubcategoriesIndexQueryCollection($categoriesAndSubcategories),
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

    public function create()
    {
        try {
            return $this->successResponse(
                ClothingLine::all(),
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

    public function store(CategoriesAndSubcategoriesStoreRequest $request)
    {
        try {
            $category = new Category();
            $category->clothing_line_id = $request->input('clothing_line_id');
            $category->name = $request->input('name');
            $category->code = $request->input('code');
            $category->description = $request->input('description');
            $category->save();

            collect($request->input('subcategories'))->map(function ($subcategory) use ($category){
                $subcategory = (object) $subcategory;
                $subcategoryNew = new Subcategory();
                $subcategoryNew->category_id = $category->id;
                $subcategoryNew->name = $subcategory->subcategory;
                $subcategoryNew->code = $subcategory->code;
                $subcategoryNew->description = $subcategory->description;
                $subcategoryNew->save();
            });
            
            return $this->successResponse(
                $category,
                'Categoria y subcategorias creadas exitosamente.',
                201
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
            // Deshacer la transacción en caso de excepción y devolver una respuesta de error
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function edit($id)
    {
        try {
            return $this->successResponse(
                (object) [
                    'category' => Category::with('clothing_line', 'subcategories')->findOrFail($id),
                    'clothingLines' => ClothingLine::all()
                ],
                'La categoria y subcategorias fueron encontrados exitosamente.',
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

    public function update(CategoriesAndSubcategoriesUpdateRequest $request, $id)
    {
        try {
            $category = Category::findOrFail($id);
            $category->clothing_line_id = $request->input('clothing_line_id');
            $category->name = $request->input('name');
            $category->code = $request->input('code');
            $category->description = $request->input('description');
            $category->save();
            
            collect($request->input('subcategories'))->map(function ($subcategory) use ($category){
                $subcategory = (object) $subcategory;
                $subcategoryNew = isset($subcategory->id) ? Subcategory::findOrFail($subcategory->id) : new Subcategory();
                $subcategoryNew->category_id = $category->id;
                $subcategoryNew->name = $subcategory->subcategory;
                $subcategoryNew->code = $subcategory->code;
                $subcategoryNew->description = $subcategory->description;
                $subcategoryNew->deleted_at = $subcategory->status ? null : Carbon::now()->format('Y-m-d H:i:s');
                $subcategoryNew->save();
                return $subcategoryNew->id;
            });

            return $this->successResponse(
                $category,
                'Categoria y subcategorias actualizadas exitosamente.',
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
            // Deshacer la transacción en caso de excepción y devolver una respuesta de error
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function delete(CategoriesAndSubcategoriesDeleteRequest $request)
    {
        try {
            // Eliminar categoria y subcategorias
            $category = Category::findOrFail($request->input('id'))->delete();
            // Devolver una respuesta exitosa
            return $this->successResponse(
                $category,
                'Categoria y subcategorias eliminadas exitosamente.',
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
            // Deshacer la transacción en caso de excepción y devolver una respuesta de error
            return $this->errorResponse(
                [
                    'message' => $this->getMessage('Exception'),
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }

    public function restore(CategoriesAndSubcategoriesRestoreRequest $request)
    {
        try {
            $category = Category::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $category,
                'Categoria y subcategorias fueron restauradas exitosamente.',
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
}
