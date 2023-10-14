<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModulesAndSubmodules\ModulesAndSubmodulesIndexQueryRequest;
use App\Http\Resources\ModulesAndSubmodules\ModulesAndSubmodulesIndexQueryCollection;
use App\Models\Module;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class ModulesAndSubmodulesController extends Controller
{
    use ApiResponser;

    private $success = 'Consulta Exitosa.';
    private $error = 'Algo salió mal.';
    private $errorQueryException = 'Error del servidor de la base de datos.';
    private $errorModelNotFoundException = 'El modulo y los submodulos no pudo ser encontrado.';

    public function index()
    {
        try {
            return view('Dashboard.ModuleAndSubmodules.Index');
        } catch (Exception $e) {
            return back()->with(
                'danger',
                'Ocurrió un error al cargar la vista: ' . $e->getMessage()
            );
        }
    }

    public function indexQuery(ModulesAndSubmodulesIndexQueryRequest $request)
    {
        try{
            $start_date = Carbon::parse($request->start_date)->startOfDay();
            $end_date = Carbon::parse($request->end_date)->endOfDay();
            // Consultar roles con relaciones y aplicar filtros
            $modulesAndSubmodules = Module::with('roles', 'submodules.permission')
                ->when($request->filled('search'),
                    function ($query) use ($request) {
                        $query->search($request->search);
                    }
                )
                ->when($request->filled('start_date') && $request->filled('end_date'),
                    function ($query) use ($start_date, $end_date) {
                        $query->filterByDate($start_date, $end_date);
                    }
                )
                ->orderBy($request->column, $request->dir)
                ->paginate($request->perPage);
            // Devolver una respuesta exitosa con los roles y permisos paginados
            return $this->successResponse(
                new ModulesAndSubmodulesIndexQueryCollection($modulesAndSubmodules),
                $this->success,
                200
            );
        } catch (QueryException $e) {
            // Manejar la excepción de la base de datos
            return $this->errorResponse(
                [
                    'message' => $this->errorQueryException,
                    'error' => $e->getMessage()
                ],
                500
            );
        } catch (Exception $e) {
            // Devolver una respuesta de error en caso de excepción
            return $this->errorResponse(
                [
                    'message' => $this->error,
                    'error' => $e->getMessage()
                ],
                500
            );
        }
    }
}
