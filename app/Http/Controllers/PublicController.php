<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OrderPackage;
use App\Models\ProductColorTone;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PublicController extends Controller
{
    public function packingPackage($package, $number)
    {
        try {
            $orderPackage = OrderPackage::with([
                    'order_packing.packing_user', 'package_type', 'order_packing.order_packages',
                    'order_package_details.order_dispatch_detail.order_detail.product',
                    'order_package_details.order_dispatch_detail.order_detail.color',
                    'order_package_details.order_dispatch_detail.order_detail.tone',
                    'order_package_details.order_package_detail_quantities.order_dispatch_detail_quantity.order_detail_quantity.size',
                    'order_packing.order_dispatch.dispatch_user'  => fn($query) => $query->withTrashed(),
                    'order_packing.order_dispatch.order.seller_user'  => fn($query) => $query->withTrashed(),
                    'order_packing.order_dispatch.order.wallet_user'  => fn($query) => $query->withTrashed(),
                    'order_packing.order_dispatch.order.client.document_type' => fn($query) => $query->withTrashed(),
                    'order_packing.order_dispatch.order.client_branch' => fn($query) => $query->withTrashed(),
                    'order_packing.order_dispatch.order.client_branch.country',
                    'order_packing.order_dispatch.order.client_branch.departament',
                    'order_packing.order_dispatch.order.client_branch.city',
                    'order_packing.order_dispatch.order.seller_user' => fn($query) => $query->withTrashed(),
                ])
                ->findOrFail($package);

            $sizes = $orderPackage->order_package_details->pluck('order_package_detail_quantities')->flatten()->pluck('order_dispatch_detail_quantity')->pluck('order_detail_quantity')->pluck('size')->unique()->sortBy('id')->values();

            $pdf = \PDF::loadView('Public.Packing.Package', compact('orderPackage', 'number', 'sizes'))->setOptions(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

            return $pdf->stream("{$orderPackage->order_packing->order_dispatch->consecutive}-{$orderPackage->package_type->name}-#{$number}.pdf");
        } catch (ModelNotFoundException $e) {
            return back()->with('danger', 'Ocurrió un error al cargar el pdf de la orden de despacho del pedidos: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }

    public function productView($product, $color, $tone)
    {
        try {
            $productColorTone = ProductColorTone::with('product.trademark', 'color', 'tone', 'files')->where('product_id', $product)->where('color_id', $color)->where('tone_id', $tone)->firstOrFail();

            $imageFiles = [];
            $videoFiles = [];

            foreach ($productColorTone->files as $file) {
                $file->path = asset('storage/' . $file->path);
                if (in_array($file->extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $imageFiles[] = $file;
                } elseif (in_array($file->extension, ['mp4', 'avi', 'wmv', 'mov', 'mkv', 'flv', 'webm', 'mpeg'])) {
                    $videoFiles[] = $file;
                }
            }
            
            return view('Public.Product.View', compact('productColorTone', 'imageFiles', 'videoFiles'));
        } catch (ModelNotFoundException $e) {
            return $e->getMessage();
            return back()->with('danger', 'Ocurrió un error al cargar el pdf de la orden de despacho del pedidos: ' . $this->getMessage('ModelNotFoundException'));
        } catch (Exception $e) {
            return $e->getMessage();
            return back()->with('danger', 'Ocurrió un error al cargar la vista: ' . $e->getMessage());
        }
    }
}
