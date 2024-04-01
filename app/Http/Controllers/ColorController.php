<?php

namespace App\Http\Controllers;

use App\Http\Requests\Color\ColorDeleteRequest;
use App\Http\Requests\Color\ColorIndexQueryRequest;
use App\Http\Requests\Color\ColorRestoreRequest;
use App\Http\Requests\Color\ColorStoreRequest;
use App\Http\Requests\Color\ColorUpdateRequest;
use App\Http\Resources\Color\ColorIndexQueryCollection;
use App\Models\Color;
use App\Models\File;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ColorController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.Colors.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(ColorIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $colors = Color::with('sample')
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
                new ColorIndexQueryCollection($colors),
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

    public function create()
    {
        try {
            return $this->successResponse(
                '',
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

    public function store(ColorStoreRequest $request)
    {
        try {
            $color = new Color();
            $color->name = $request->input('name');
            $color->code = $request->input('code');
            $color->save();

            if ($request->hasFile('sample')) {
                $file = new File();
                $file->model_type = Color::class;
                $file->model_id = $color->id;
                $file->name = $request->file('sample')->getClientOriginalName();
                $file->path = $request->file('sample')->store('Colors/' . $color->id, 'public');
                $file->mime = $request->file('sample')->getMimeType();
                $file->extension = $request->file('sample')->getClientOriginalExtension();
                $file->size = $request->file('sample')->getSize();
                $file->user_id = Auth::user()->id;
                $file->metadata = json_encode((array) stat($request->file('sample')));
                $file->save();
            }

            return $this->successResponse(
                $color,
                'EL color de producto fue registrado exitosamente.',
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

    public function edit($id)
    {
        try {
            $color = Color::with('sample')->withTrashed()->findOrFail($id);
            $color->path = is_null($color->sample) ? $color->sample : asset('storage/' . $color->sample->path);
            
            return $this->successResponse(
                $color,
                'El color de producto fue encontrado exitosamente.',
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

    public function update(ColorUpdateRequest $request, $id)
    {
        try {
            $color = Color::with('sample')->withTrashed()->findOrFail($id);
            $color->name = $request->input('name');
            $color->code = $request->input('code');
            $color->save();

            if($request->hasFile('sample')) {
                if(is_null($color->sample)) {
                    $file = new File();
                    $file->model_type = Color::class;
                    $file->model_id = $color->id;
                    $file->name = $request->file('sample')->getClientOriginalName();
                    $file->path = $request->file('sample')->store('Colors/' . $color->id, 'public');
                    $file->mime = $request->file('sample')->getMimeType();
                    $file->extension = $request->file('sample')->getClientOriginalExtension();
                    $file->size = $request->file('sample')->getSize();
                    $file->user_id = Auth::user()->id;
                    $file->metadata = json_encode((array) stat($request->file('sample')));
                    $file->save();
                } else {   
                    if (Storage::disk('public')->exists($color->sample->path)) {
                        Storage::disk('public')->delete($color->sample->path);
                    }
                    $color->sample->name = $request->file('sample')->getClientOriginalName();
                    $color->sample->path = $request->file('sample')->store('Colors/' . $color->id, 'public');
                    $color->sample->mime = $request->file('sample')->getMimeType();
                    $color->sample->extension = $request->file('sample')->getClientOriginalExtension();
                    $color->sample->size = $request->file('sample')->getSize();
                    $color->sample->user_id = Auth::user()->id;
                    $color->sample->metadata = json_encode((array) stat($request->file('sample')));
                    $color->sample->save();
                }
            }

            return $this->successResponse(
                $color,
                'El color de producto fue actualizado exitosamente.',
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

    public function delete(ColorDeleteRequest $request)
    {
        try {
            $color = Color::withTrashed()->findOrFail($request->input('id'))->delete();
            return $this->successResponse(
                $color,
                'El color de producto fue eliminado exitosamente.',
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

    public function restore(ColorRestoreRequest $request)
    {
        try {
            $color = Color::withTrashed()->findOrFail($request->input('id'))->restore();
            return $this->successResponse(
                $color,
                'El color de producto fue restaurado exitosamente.',
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
