<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderInvoice\OrderInvoiceIndexQueryRequest;
use App\Http\Requests\OrderInvoice\OrderInvoiceStoreRequest;
use App\Http\Resources\OrderInvoice\OrderInvoiceIndexQueryCollection;
use App\Models\File;
use App\Models\Invoice;
use App\Models\OrderDispatch;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use PhpParser\Node\Expr\Cast\Object_;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class OrderInvoiceController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function index()
    {
        try {
            return view('Dashboard.OrderInvoices.Index');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function indexQuery(OrderInvoiceIndexQueryRequest $request)
    {
        try {
            $start_date = Carbon::parse($request->input('start_date'))->startOfDay();
            $end_date = Carbon::parse($request->input('end_date'))->endOfDay();
            //Consulta por nombre
            $orderInvoices = OrderDispatch::with(['order', 'dispatch_user', 'invoices.files', 'invoice_user',
                    'order.client' => fn($query) => $query->withTrashed(),
                    'order.client.country', 'order.client.departament', 'order.client.city',
                    'order.client_branch' => fn($query) => $query->withTrashed(),
                    'order.client_branch.country', 'order.client_branch.departament', 'order.client_branch.city',
                    'order.seller_user' => fn($query) => $query->withTrashed(),
                    'order.wallet_user' => fn($query) => $query->withTrashed(),
                    'order.correria' => fn($query) => $query->withTrashed(),
                    'order_dispatch_details.order_dispatch_detail_quantities',
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
                ->whereHas('order_packing')
                ->whereIn('dispatch_status', ['Empacado', 'Despachado'])
                ->orderBy($request->input('column'), $request->input('dir'))
                ->paginate($request->input('perPage'));

            return $this->successResponse(
                new OrderInvoiceIndexQueryCollection($orderInvoices),
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

    public function store(OrderInvoiceStoreRequest $request)
    {
        try {
            $orderDispatch = OrderDispatch::findOrFail($request->input('order_dispatch_id'));

            foreach($request->input('invoices') as $index => $invoice){
                $invoiceNew = new Invoice();
                $invoiceNew->model_id = $request->input('order_dispatch_id');
                $invoiceNew->model_type = OrderDispatch::class;
                $invoiceNew->value = $invoice['value'];
                $invoiceNew->reference = $invoice['reference'];
                $invoiceNew->date = Carbon::parse($invoice['date'])->format('Y-m-d H:i:s');
                $invoiceNew->save();
    
                foreach($request->invoices[$index]['supports'] as $support) {
                    $file = new File();
                    $file->model_type = Invoice::class;
                    $file->model_id = $invoiceNew->id;
                    $file->name = $support->getClientOriginalName();
                    $file->path = $support->store('Invoices/' . $invoiceNew->id, 'public');
                    $file->mime = $support->getMimeType();
                    $file->extension = $support->getClientOriginalExtension();
                    $file->size = $support->getSize();
                    $file->user_id = Auth::user()->id;
                    $file->metadata = json_encode((array) stat($support));
                    $file->save();
                }
            }     
            
            $orderDispatch->invoice_user_id = Auth::user()->id;
            $orderDispatch->invoice_date = Carbon::now()->format('Y-m-d H:i:s');
            $orderDispatch->dispatch_status = 'Despachado';
            $orderDispatch->save();

            return $this->successResponse(
                [
                    'orderDispatch' => $orderDispatch,
                    'url' => URL::route('Dashboard.Orders.Invoice.Download', ['id' => $orderDispatch->id])
                ],
                'Las facturas fueron registradas exitosamente.',
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

    public function download($id)
    {
        try {
            $orderDispatch = OrderDispatch::with([ 'invoices',
                    'dispatch_user'  => fn($query) => $query->withTrashed(),
                    'order.seller_user'  => fn($query) => $query->withTrashed(),
                    'order.wallet_user'  => fn($query) => $query->withTrashed(),
                    'order.client.document_type' => fn($query) => $query->withTrashed(),
                    'order.client_branch' => fn($query) => $query->withTrashed(),
                    'order.client_branch.country', 'order.client_branch.departament', 'order.client_branch.city',
                    'order.seller_user' => fn($query) => $query->withTrashed(), 'files',
                    'order_packing.order_packages.package_type',
                    'order_packing.order_packages.order_package_details.order_package_detail_quantities.order_dispatch_detail_quantity.order_detail_quantity.size'
                ])
                ->findOrFail($id);

            $sizes = $orderDispatch->order_packing->order_packages->pluck('order_package_details')->flatten()->pluck('order_package_detail_quantities')->flatten()->pluck('order_dispatch_detail_quantity')->pluck('order_detail_quantity')->pluck('size')->unique()->sortBy('id')->values();

            foreach($orderDispatch->order_packing->order_packages as $orderPackage) {
                $url = URL::route('Packing.Package.Details', ['id' => $orderPackage->id]);
                $orderPackage->qrCode = QrCode::size(200)->generate($url);
            }
            
            $pdf = \PDF::loadView('Dashboard.OrderInvoices.PDF', compact('orderDispatch', 'sizes'))/* ->setPaper('a4', 'landscape') */->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);
            /* $pdf = \PDF::loadView('Browser_public.pdfdocument', compact('queryic'))->output();
            return $pdf->download('pdfdocument.pdf'); */
            return $pdf->stream("{$orderDispatch->consecutive}-ROTULO.pdf");
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pdf de la orden de despacho del pedidos: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }
}
