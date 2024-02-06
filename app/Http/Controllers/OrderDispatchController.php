<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\OrderDetail;
use App\Models\Size;
use App\Traits\ApiMessage;
use App\Traits\ApiResponser;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderDispatchController extends Controller
{
    use ApiResponser;
    use ApiMessage;

    public function filter()
    {
        try {
            return view('Dashboard.OrderDispatches.Filter');
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function filterQueryReferences()
    {
        try {
            $references = OrderDetail::with('order', 'product', 'color', 'tone')
                ->whereHas('order', function ($query) {
                    $query->where('seller_status', 'Aprobado')
                        ->where(function ($query) {
                            $query->where('wallet_status', 'Parcialmente Aprobado')
                                ->orWhere('wallet_status', 'Aprobado');
                        });
                })
                ->where('status', 'Aprobado')
                ->get()
                ->map(function ($orderDetail) {
                    $reference = "{$orderDetail->product->code}-{$orderDetail->color->code}-{$orderDetail->tone->code}";
                    return [
                        'reference' => $reference,
                        'product_id' => $orderDetail->product->id,
                        'color_id' => $orderDetail->color->id,
                        'tone_id' => $orderDetail->tone->id,
                    ];
                })
                ->unique('reference')
                ->values(); 

            return $this->successResponse(
                $references,
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

    public function filterQueryOrders(Request $request)
    {
        try {
            $warehouseDiscount = Inventory::with('warehouse', 'product', 'color', 'tone')
                ->whereHas('warehouse', fn($query) => $query->where('to_discount', true))
                ->where('product_id', $request->input('product_id'))
                ->where('color_id', $request->input('color_id'))
                ->where('tone_id', $request->input('tone_id'))
                ->get();

            $warehouseNoDiscount = Inventory::with('warehouse', 'product', 'color', 'tone')
                ->whereHas('warehouse', fn($query) => $query->where('to_discount', false))
                ->where('product_id', $request->input('product_id'))
                ->where('color_id', $request->input('color_id'))
                ->where('tone_id', $request->input('tone_id'))
                ->groupBy('size_id')
                ->select('size_id', DB::raw('sum(quantity) as quantity'))
                ->get();

            $ordersDetails = OrderDetail::with('order', 'product', 'color', 'tone', 'quantities')
                ->whereHas('order', function ($query) {
                    $query->where('seller_status', 'Aprobado')
                        ->where(function ($query) {
                            $query->where('wallet_status', 'Parcialmente Aprobado')
                                ->orWhere('wallet_status', 'Aprobado');
                        });
                })
                ->where('status', 'Aprobado')
                ->where('product_id', $request->input('product_id'))
                ->where('color_id', $request->input('color_id'))
                ->where('tone_id', $request->input('tone_id'))
                ->get();

            $sizes = Size::whereIn('id', $warehouseDiscount->pluck('size_id')->merge($warehouseNoDiscount->pluck('size_id'))->unique()->values())->get();

            $ordersDetails = $ordersDetails->map(function ($orderDetail) use ($sizes) {

                $orderDetailSizes = $orderDetail->quantities->pluck('size_id')->unique()->values();
                $missingSizes = $sizes->pluck('id')->diff($orderDetailSizes)->values();

                $quantities = collect($orderDetail->quantities)->mapWithKeys(function ($quantity) {
                    return [$quantity['size']->id => [
                        'order_detail_id' => $quantity['order_detail_id'],
                        'quantity' => $quantity['quantity'],
                    ]];
                });

                $missingSizes->each(function ($missingSize) use ($quantities, $orderDetail) {
                    $quantities[$missingSize] = [
                        'order_detail_id' => $orderDetail->id,
                        'quantity' => 0,
                    ];
                });

                return [
                    'id' => $orderDetail->id,
                    'order' => $orderDetail->order,
                    'product' => $orderDetail->product,
                    'color' => $orderDetail->color,
                    'tone' => $orderDetail->tone,
                    'price' => $orderDetail->price,
                    'seller_date' => $orderDetail->seller_date,
                    'seller_observation' => $orderDetail->seller_observation,
                    'status' => $orderDetail->status,
                    'quantities' => $quantities,
                ];
            });

            $warehouseDiscountNew = (object) [
                'name' => $warehouseDiscount->isNotEmpty() ? $warehouseDiscount->first()->warehouse->name : 'N/A',
                'code' => $warehouseDiscount->isNotEmpty() ? $warehouseDiscount->first()->warehouse->code : 'N/A',
                'quantites' => []
            ];

            $warehouseNoDiscountNew = (object) [
                'name' => 'OTRAS BODEGAS',
                'code' => 'OB',
                'quantites' => []
            ];

            foreach ($sizes as $size) {
                $discounted = $warehouseDiscount->where('size_id', $size->id)->first();
                $noDiscount = $warehouseNoDiscount->where('size_id', $size->id)->first();

                $warehouseDiscountNew->quantites[$size->id] = $discounted ? $discounted->quantity : 0 ;
                $warehouseNoDiscountNew->quantites[$size->id] = $noDiscount ? $noDiscount->quantity : 0 ;
            }

            return $this->successResponse(
                [
                    'ordersDetails' => $ordersDetails,
                    'warehouseDiscount' => $warehouseDiscountNew,
                    'warehouseNoDiscount' => $warehouseNoDiscountNew,
                    'sizes' => $sizes
                ],
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
}
