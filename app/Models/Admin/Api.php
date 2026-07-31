<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;
use DB;

class Api extends Model
{
    protected $table = 'orders';

    // ── Order Create ─────────────────────────────────────────────────────────
    public static function ordercreate($request, $user_id)
    {
        try {
            $admin      = DB::table('admins')->where('id', $user_id)->first();
            $company_id = $admin ? $admin->company_id : 1;

            $order = new Order();
            $order->user_id        = $user_id;
            $order->order_id       = $request->order_id ?? null;
            $order->ship_fname     = $request->ship_fname     ?? null;
            $order->ship_lname     = $request->ship_lname     ?? null;
            $order->ship_email     = $request->ship_email     ?? null;
            $order->ship_company   = $request->ship_company   ?? null;
            $order->ship_phone     = $request->ship_phone     ?? null;
            $order->ship_address   = $request->ship_address   ?? null;
            $order->ship_address_2 = $request->ship_address_2 ?? null;
            $order->ship_country   = $request->ship_country   ?? null;
            $order->ship_pincode   = $request->ship_pincode   ?? null;
            $order->ship_city      = $request->ship_city      ?? null;
            $order->ship_state     = $request->ship_state     ?? null;
            $order->ship_latitude  = $request->ship_latitude  ?? null;
            $order->ship_longitude = $request->ship_longitude ?? null;
            $order->ship_gstin     = $request->ship_gstin     ?? null;
            $order->bill_fname     = $request->bill_fname     ?? null;
            $order->bill_lname     = $request->bill_lname     ?? null;
            $order->bill_company   = $request->bill_company   ?? null;
            $order->bill_phone     = $request->bill_phone     ?? null;
            $order->bill_address   = $request->bill_address   ?? null;
            $order->bill_address_2 = $request->bill_address_2 ?? null;
            $order->bill_country   = $request->bill_country   ?? null;
            $order->bill_pincode   = $request->bill_pincode   ?? null;
            $order->bill_city      = $request->bill_city      ?? null;
            $order->bill_state     = $request->bill_state     ?? null;
            $order->bill_latitude  = $request->bill_latitude  ?? null;
            $order->bill_longitude = $request->bill_longitude ?? null;
            $order->bill_gstin     = $request->bill_gstin     ?? null;
            $order->e_bill_no      = $request->e_bill_no      ?? null;
            $order->same_add       = $request->same_add;
            $order->discount       = $request->order_discount ?? 0;
            $order->shipping_cost  = $request->shipping_cost  ?? '0.00';
            $order->total          = $request->total          ?? 0;
            $order->custom_total   = $request->custom_total   ?? $request->total;
            $order->payment_mode   = $request->payment_mode;
            $order->vendor_order_id= $request->vendor_order_id;
            $order->channel        = $request->channel        ?? 'API';
            $order->weight         = $request->weight;
            $order->length         = $request->length;
            $order->breadth        = $request->breadth;
            $order->height         = $request->height;
            $order->note           = $request->note           ?? null;
            $order->company_id     = $company_id;
            $order->save();

            $order->order_id = $order->id;
            $order->save();

            if (!empty($request->name)) {
                foreach ($request->name as $key => $name) {
                    OrderDetail::create([
                        'user_id'       => $user_id,
                        'order_id'      => $order->id,
                        'name'          => $name,
                        'code'          => $request->code[$key]          ?? $name,
                        'price'         => $request->price[$key]         ?? 0,
                        'discount'      => $request->discount[$key]      ?? 0,
                        'qty'           => $request->qty[$key]           ?? 1,
                        'discount_type' => $request->discount_type[$key] ?? 0,
                        'tax_percent'   => $request->tax_percent[$key]   ?? 0,
                        'tax_amount'    => $request->tax_amount[$key]    ?? 0,
                        'total_price'   => $request->total_price[$key]   ?? 0,
                        'company_id'    => $company_id,
                    ]);
                }
            }

            return $order;
        } catch (\Exception $e) {
            return false;
        }
    }

    // ── Order Create AWB ─────────────────────────────────────────────────────
    public static function ordercreateawb($request, $user_id, $dg_goods = false)
    {
        $order = Order::where('id', $request->order_id)->where('user_id', $user_id)->first();
        if (!$order) {
            return ['error', 'Order not found or access denied'];
        }
        if ($order->tracking_info) {
            return ['error', 'AWB already generated for this order'];
        }

        $order->status             = '12';
        $order->warehouse_id       = $request->warehouse_id;
        $order->return_warehouse_id= $request->return_warehouse_id ?? $request->warehouse_id;
        $order->save();

        return ['success', 'Order manifested. AWB will be assigned by the system.'];
    }

    // ── Order Update ─────────────────────────────────────────────────────────
    public static function orderupdate($request, $user_id, $id)
    {
        $order = Order::where('id', $id)->where('user_id', $user_id)->first();
        if (!$order) {
            return response()->json(['error' => true, 'message' => 'Order not found'], 404);
        }

        $fields = [
            'ship_fname','ship_lname','ship_email','ship_company','ship_phone',
            'ship_address','ship_address_2','ship_country','ship_pincode','ship_city','ship_state',
            'ship_latitude','ship_longitude','ship_gstin','bill_fname','bill_lname','bill_company',
            'bill_phone','bill_address','bill_address_2','bill_country','bill_pincode','bill_city',
            'bill_state','bill_latitude','bill_longitude','bill_gstin','e_bill_no','same_add',
            'total','custom_total','payment_mode','vendor_order_id','weight','length','breadth','height','note',
        ];

        foreach ($fields as $field) {
            if ($request->has($field)) {
                $order->$field = $request->$field;
            }
        }
        if ($request->has('order_discount')) {
            $order->discount = $request->order_discount;
        }
        $order->save();

        if (!empty($request->name)) {
            OrderDetail::where('order_id', $id)->delete();
            foreach ($request->name as $key => $name) {
                OrderDetail::create([
                    'user_id'       => $user_id,
                    'order_id'      => $id,
                    'name'          => $name,
                    'code'          => $request->code[$key]          ?? $name,
                    'price'         => $request->price[$key]         ?? 0,
                    'discount'      => $request->discount[$key]      ?? 0,
                    'qty'           => $request->qty[$key]           ?? 1,
                    'discount_type' => $request->discount_type[$key] ?? 0,
                    'tax_percent'   => $request->tax_percent[$key]   ?? 0,
                    'tax_amount'    => $request->tax_amount[$key]    ?? 0,
                    'total_price'   => $request->total_price[$key]   ?? 0,
                    'company_id'    => $order->company_id,
                ]);
            }
        }

        return response()->json(['success' => true, 'message' => 'Order updated successfully'], 200);
    }

    // ── Order Delete ─────────────────────────────────────────────────────────
    public static function orderdelete($id, $user_id)
    {
        try {
            if (!is_array($id)) {
                $id = [$id];
            }
            $orders = Order::whereIn('id', $id)->where('user_id', $user_id)->get();
            if ($orders->isEmpty()) {
                return ['success' => false, 'message' => 'Order not found or access denied'];
            }
            foreach ($orders as $order) {
                if (!in_array($order->getRawOriginal('status'), ['1', '4'])) {
                    return ['success' => false, 'message' => 'Cannot delete order in current status'];
                }
                OrderDetail::where('order_id', $order->id)->delete();
                $order->delete();
            }
            return ['success' => true, 'message' => 'Order deleted successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    // ── Order Cancel ─────────────────────────────────────────────────────────
    public static function ordercancel($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return ['error', 'Order not found'];
        }
        $order->status = '4';
        $order->save();
        return ['success', 'Order cancelled successfully'];
    }

    // ── Order Manifest ───────────────────────────────────────────────────────
    public static function ordermanifest($order_id, $user_id)
    {
        $order = Order::where('id', $order_id)->where('user_id', $user_id)->first();
        if (!$order) {
            return ['error', 'Order not found or access denied'];
        }
        if (!$order->tracking_info) {
            return ['error', 'AWB not generated. Please assign courier first.'];
        }
        if ($order->getRawOriginal('status') == '12') {
            return ['error', 'Order already manifested'];
        }
        $order->status = '12';
        $order->manifest_date = now();
        $order->save();
        return ['success', 'Order manifested successfully'];
    }

    // ── Get Status Value ─────────────────────────────────────────────────────
    public static function getstatusvalue($history, $courier_id, $type = 'forward')
    {
        // Map courier-specific status text to internal status codes
        // 2=Shipped, 3=Delivered, 5=RTO, 6=RTO Delivered, 10=NDR, 13=RTO Transit, 14=In Transit, 15=OFD

        $delivered   = ['3', 'DLVD', 'DL', 'Delivered', 'delivered'];
        $ofd         = ['15', 'OOD', 'OFD', 'Out for Delivery', 'out for delivery'];
        $ndr         = ['10', 'UD', 'NDR', 'Undelivered', 'ndr'];
        $rto_transit = ['13', 'RTO', 'RTS', 'Return to Origin', 'rto'];

        // Common helper
        $chk = function ($val) use ($delivered, $ofd, $ndr, $rto_transit) {
            $v = strtolower((string)$val);
            if ($v === '' || $v === null) return '14';
            if (str_contains($v, 'deliver') && !str_contains($v, 'undeliver') && !str_contains($v, 'out')) return '3';
            if (str_contains($v, 'out for delivery')) return '15';
            if (str_contains($v, 'rto') || str_contains($v, 'return to origin')) return '13';
            if (str_contains($v, 'undeliver') || str_contains($v, 'ndr') || $v === 'ud') return '10';
            if (str_contains($v, 'transit') || str_contains($v, 'in transit')) return '14';
            return '14';
        };

        switch ((string)$courier_id) {
            case '1': return $chk($history['status'] ?? '');
            case '2': return $chk(($history['Scan'] ?? '') . ' ' . ($history['ScanType'] ?? ''));
            case '3': return $chk($history['status_title'] ?? $history['status'] ?? '');
            case '4': return $chk($history['Process'] ?? '');
            case '5': return $chk($history['strAction'] ?? '');
            case '6': return $chk($history['statusDescription'] ?? '');
            case '7': return $chk($history['public_description'] ?? $history['status'] ?? '');
            case '8':
                if ($type === 'backward') {
                    $s = strtolower($history['state'] ?? '');
                    if (str_contains($s, 'delivered')) return '6';
                    return '13';
                }
                return $chk($history['status'] ?? '');
            case '9': return $chk($history['eventCode'] ?? '');
            default:  return '14';
        }
    }

    // ── Calculate Rates ──────────────────────────────────────────────────────
    public static function calculateRates($request, $user_id)
    {
        $pickup  = $request->pickup_pin;
        $drop    = $request->drop_pin;
        $length  = $request->length;
        $breadth = $request->breadth;
        $height  = $request->height;
        $weight  = $request->weight; // grams
        $payment = $request->payment;

        $pin1 = DB::table('pincodes')->where('pincode', $pickup)->first();
        $pin2 = DB::table('pincodes')->where('pincode', $drop)->first();
        if (!$pin1 || !$pin2) {
            return [];
        }

        $zone_id = Order::getzone($pickup, $drop);
        if (!$zone_id || $zone_id === '0') {
            return [];
        }
        $zone = zone($zone_id);

        $admin      = DB::table('admins')->where('id', $user_id)->first();
        $company_id = $admin ? $admin->company_id : 1;
        $parent_id  = $admin ? ($admin->parent_id ?? $user_id) : $user_id;

        $couriers_json = json_decode(file_get_contents(resource_path('views/admin/courier.json')), true);

        $vol_weight    = ($length * $breadth * $height) / 5000;
        $weight_kg     = $weight / 1000;
        $use_weight    = max($weight_kg, $vol_weight);

        $weight_slabs  = ['0.5','1','1.5','2','3','3.5','5','10','20','30','50'];
        $slab = '0.5';
        foreach ($weight_slabs as $w) {
            $slab = $w;
            if ($w >= $use_weight) break;
        }

        $transports = ['NDD','SDD','Air','Surface'];
        $cour_ids   = [2,3,7,10,4,8,11,12,13];
        $result     = [];

        foreach ($cour_ids as $c_id) {
            foreach ($transports as $transport) {
                $rate = DB::table('ratecards')
                    ->where('courier_id', $c_id)
                    ->where('transport', $transport)
                    ->where('status', 1)
                    ->where('user_id', $user_id)
                    ->where('weight', $slab)
                    ->where('additional', 0)
                    ->first();

                if (!$rate) continue;

                $freight = $rate->$zone ?? 0;
                if (!$freight) continue;

                $gst   = ($freight * 18) / 100;
                $cod   = ($payment == 'cod') ? ($rate->cod ?? 0) : 0;
                $total = $freight + $gst + $cod;

                $mode_icon = match($transport) {
                    'Air'     => 'fa-plane',
                    'Surface' => 'fa-truck',
                    'NDD'     => 'fa-bicycle',
                    default   => 'fa-motorcycle',
                };

                $edd = '';
                if ($c_id == 2) {
                    if ($transport == 'Air') {
                        $mot = 'E';
                    } elseif ($transport == 'Surface') {
                        $mot = 'S';
                    } elseif ($transport == 'NDD' || $transport == 'SDD') {
                        $mot = 'N';
                    } else {
                        $mot = 'S';
                    }
                    $pickup_date = date('Y-m-d H:i');
                    $tat_response = Integration::get_expected_tat_delhivery($pickup, $drop, $mot, 'B2C', $pickup_date, $pickup_date);
                    $tat_data = json_decode($tat_response, true);
                    if (isset($tat_data['data']['expected_delivery_date'])) {
                        $edd = date('d M, Y', strtotime($tat_data['data']['expected_delivery_date']));
                    } elseif (isset($tat_data[0]['expected_delivery_date'])) {
                        $edd = date('d M, Y', strtotime($tat_data[0]['expected_delivery_date']));
                    }
                }

                $result[] = [
                    'courier_id'  => $c_id,
                    'name'        => $couriers_json[$c_id]['name'] ?? 'Unknown',
                    'img'         => asset('public/courier') . '/' . ($couriers_json[$c_id]['image'] ?? ''),
                    'mode'        => $mode_icon,
                    'weight_used' => $slab,
                    'weight'      => round($use_weight, 2) . ' kg',
                    'zone'        => $zone,
                    'price'       => 'Rs.' . number_format($total, 2),
                    'cod'         => round($cod, 2),
                    'gst'         => round($gst, 2),
                    'freight'     => round($freight, 2),
                    'edd'         => $edd,
                ];
                break; // one entry per transport per courier
            }
        }

        return $result;
    }

    // ── Warehouse Save ───────────────────────────────────────────────────────
    public static function warehouse($request, $user_id, $id = 0)
    {
        try {
            $admin      = DB::table('admins')->where('id', $user_id)->first();
            $company_id = $admin ? $admin->company_id : 1;

            $wh = ($id && $id != 0)
                ? DB::table('warehouses')->where('id', $id)->where('user_id', $user_id)->first()
                : null;

            $data = [
                'user_id'      => $user_id,
                'company_id'   => $company_id,
                'name'         => $request->name,
                'contact_name' => $request->contact_name,
                'company'      => $request->company,
                'email'        => $request->email        ?? null,
                'phone'        => $request->phone,
                'address'      => $request->address,
                'address_2'    => $request->address_2    ?? null,
                'city'         => $request->city,
                'state'        => $request->state,
                'country_id'   => $request->country_id,
                'pincode'      => $request->pincode,
                'gst_no'       => $request->gst_no       ?? null,
                'updated_at'   => now(),
            ];

            if ($wh) {
                DB::table('warehouses')->where('id', $wh->id)->update($data);
                return [$wh->id, 'Warehouse updated successfully'];
            }

            $data['created_at'] = now();
            $newId = DB::table('warehouses')->insertGetId($data);
            return [$newId, 'Warehouse saved successfully'];
        } catch (\Exception $e) {
            return false;
        }
    }

    // ── Warehouse Delete ─────────────────────────────────────────────────────
    public static function deleteware($id, $user_id)
    {
        $wh = DB::table('warehouses')->where('id', $id)->where('user_id', $user_id)->first();
        if (!$wh) {
            return 'Warehouse not found or access denied';
        }
        DB::table('warehouses')->where('id', $id)->delete();
        return 'Warehouse deleted successfully';
    }
}