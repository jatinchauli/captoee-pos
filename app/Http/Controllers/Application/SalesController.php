<?php

namespace App\Http\Controllers\Application;

use App\Http\Controllers\Controller;
use App\Libraries\AllSettingFormat;
use App\Models\Order;
use App\Models\OrderItems;
use App\Models\Payments;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductVariant;
use App\Models\Setting;
use App\Models\Supplier;
use Carbon\Carbon;
use Faker\Provider\ar_SA\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class SalesController extends Controller
{
    public function index()
    {
        return view('application.views.sales.add_sales');
    }
    
    public function getProductDetails(Request $request) {
        $sku = $request->sku;
        // $qty = $request->qty;
        $query = DB::select('select product_brands.name as brand_name, product_variants.id as variant_id, products.id as product_id, product_variants.variant_title, product_variants.attribute_values, product_variants.imageURL as variantImgURL, products.imageURL as productImgURL, products.title as product_name
                            from product_brands , products, product_variants
                            where product_variants.product_id = products.id
                            and product_brands.id = products.brand_id
                            and sku = ?',[$sku]);

        $prod_attr = ProductAttribute::select("name")->get();

        
        if(count($query)) {
            $total_stock = OrderItems::where('product_id',$query[0]->product_id)
                                                ->where('variant_id',$query[0]->variant_id)
                                                ->sum('quantity');
            
            

            $data = new stdClass();
            $data->brand_name = $query[0]->brand_name;
            $data->product_name = $query[0]->product_name;
            $data->total_stocks = $total_stock;
            if($query[0]->variant_title != 'default_variant') {
                $variants = $query[0]->variant_title;
                $arr = explode(",",$variants);
                foreach($prod_attr as $key => $value) {
                    $value->value = $arr[$key];
                }
                $data->attributes = $prod_attr;
            }

            if($query[0]->variantImgURL == 'no_image.png') {
                $data->product_img = $query[0]->productImgURL;
            } else {
                $data->product_img = $query[0]->variantImgURL;
            }
            


            return $data;
        }
        
    }


    public function store(Request $request) {
        
        // dd($request->all());
        $lastInvoiceNumber = Setting::getSettingValue('last_invoice_number')->setting_value; // Last row invoice number
        $allSettings = new AllSettingFormat;
        $invoiceFixes = $allSettings->getInvoiceFixes();
        echo $lastInvoiceNumber;
        // exit;
        $order = new Order;
        $order->date = Carbon::now()->toDateString();
        $order->order_type = 'sales';
        $order->market_place = $request->market_place;
        $order->awb_code = $request->awb_code;
        $order->sub_total = (floatval($request->qty) * floatval($request->mrp));
        $order->total_tax = 0.00;
        $order->due_amount = 0.00;
        $order->total = (floatval($request->qty) * floatval($request->mrp));
        $order->type = 'Market Place';
        $order->profit = 0.00;
        $order->status = 'done';
        $order->all_discount = 0.00;
        $order->supplier_id = $request->seller;
        $order->branch_id = 1;
        $order->created_by = Auth::id();
        $order->invoice_id = $invoiceFixes['prefix'] . $lastInvoiceNumber . $invoiceFixes['suffix'];
        $order->created_at = date('Y-m-d H:i:s');
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        $lastInvoiceNumber += 1;
        $lastUpdatedInvoice = Setting::where('setting_name', 'last_invoice_number')->first()->setting_value;
        if ($lastInvoiceNumber > $lastUpdatedInvoice) {
            Setting::updateSetting('last_invoice_number', $lastInvoiceNumber);
        }

        $productVariant = ProductVariant::select('id', 'product_id')
                                            ->where('sku',$request->product_code)->first();

        $orderItem = new OrderItems;
        $orderItem->product_id = $productVariant->product_id;
        $orderItem->variant_id = $productVariant->id;
        $orderItem->type = 'sales';
        $orderItem->quantity = -(int)$request->qty;
        $orderItem->price = $request->mrp;
        $orderItem->discount = 0.00;
        $orderItem->sub_total = $order->total;
        $orderItem->order_id = $order->id;
        $orderItem->created_at = date('Y-m-d H:i:s');
        $orderItem->updated_at = date('Y-m-d H:i:s');
        $orderItem->save();


        $payment = new Payments;
        $payment->date = Carbon::now()->toDateString();
        $payment->paid = $order->total;
        $payment->payment_method = 1;
        $payment->order_id = $order->id;
        $payment->cash_register_id = 1;
        $payment->created_at = date('Y-m-d H:i:s');
        $payment->updated_at = date('Y-m-d H:i:s');
        $payment->save();
        
        return redirect('/new-sales');
    }
    
    public function getSellerList(Request $request) {
        $query = DB::select('SELECT orders.supplier_id as id,suppliers.first_name,suppliers.last_name 
                                FROM `order_items`,`orders`,`suppliers`,`product_variants` 
                                WHERE orders.id = order_items.order_id 
                                and suppliers.id = orders.supplier_id 
                                and product_variants.id = order_items.variant_id 
                                and order_items.type = ? 
                                and product_variants.sku = ? 
                                GROUP BY(orders.supplier_id)', ['receiving',$request->sku]);
        return $query;
    }

    public function fetchStockOnSeller(Request $request) {
        // dd($request->all());
        $total_stock = DB::select('select sum(order_items.quantity) as stock from product_variants,order_items,orders
                                    where product_variants.id = order_items.variant_id
                                    and order_items.order_id = orders.id
                                    and orders.supplier_id = ? 
                                    and product_variants.sku = ?
                                ', [$request->sellerId,$request->sku]);
        return $total_stock[0];
    }
    
}
