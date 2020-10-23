<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class InvoiceReceiptHeader extends Model
{
  protected $table     = "log_invoice_receipt_header";
  public $incrementing = false;

  public function details()
  {
    return $this->hasMany('App\Models\InvoiceReceiptDetail', 'id_header');
  }

  public function getAmountInvoice()
  {
    return InvoiceReceiptDetail::select(
      DB::raw('SUM(cbm_amount + ritase_amount + ritase2_amount + multidro_amount + unloading_amount + overstay_amount) AS amount_before_tax')
    )
      ->where('id_header', $this->id)
      ->first()
      ->amount_before_tax;
  }

  public function getPrintReceiptData()
  {
    $data = [];

    $total_ritase    = 0;
    $total_cbm       = 0;
    $total_ritase2   = 0;
    $total_multidrop = 0;
    $total_unloading = 0;
    $total_overstay  = 0;

    foreach ($this->details as $key => $value) {
      $data['list'][$value->do_manifest_no]['total_model'] = empty($data['list'][$value->do_manifest_no]['total_model']) ? 1 : ($data['list'][$value->do_manifest_no]['total_model'] + 1);

      $data['list'][$value->do_manifest_no]['do_manifest_no']      = $value->do_manifest_no;
      $data['list'][$value->do_manifest_no]['do_manifest_date']    = $value->do_manifest_date;
      $data['list'][$value->do_manifest_no]['city_name']           = $value->city_name;
      $data['list'][$value->do_manifest_no]['vehicle_description'] = $value->vehicle_description;
      $data['list'][$value->do_manifest_no]['vehicle_number']      = $value->vehicle_number;

      $total_ritase += $value->ritase_amount;
      $total_cbm += $value->cbm_amount;
      $total_ritase2 += $value->ritase2_amount;
      $total_multidrop += $value->multidro_amount;
      $total_unloading += $value->unloading_amount;
      $total_overstay += $value->overstay_amount;

      $data['list'][$value->do_manifest_no]['ritase']    = empty($data['list'][$value->do_manifest_no]['ritase']) ? $value->ritase_amount : ($data['list'][$value->do_manifest_no]['ritase'] + $value->ritase_amount);
      $data['list'][$value->do_manifest_no]['cbm']       = empty($data['list'][$value->do_manifest_no]['cbm']) ? $value->cbm_amount : ($data['list'][$value->do_manifest_no]['cbm'] + $value->cbm_amount);
      $data['list'][$value->do_manifest_no]['ritase2']   = empty($data['list'][$value->do_manifest_no]['ritase2']) ? $value->ritase2_amount : ($data['list'][$value->do_manifest_no]['ritase2'] + $value->ritase2_amount);
      $data['list'][$value->do_manifest_no]['multidrop'] = empty($data['list'][$value->do_manifest_no]['multidrop']) ? $value->multidro_amount : ($data['list'][$value->do_manifest_no]['multidrop'] + $value->multidro_amount);
      $data['list'][$value->do_manifest_no]['unloading'] = empty($data['list'][$value->do_manifest_no]['unloading']) ? $value->unloading_amount : ($data['list'][$value->do_manifest_no]['unloading'] + $value->unloading_amount);
      $data['list'][$value->do_manifest_no]['overstay']  = empty($data['list'][$value->do_manifest_no]['overstay']) ? $value->overstay_amount : ($data['list'][$value->do_manifest_no]['overstay'] + $value->overstay_amount);

      $data['list'][$value->do_manifest_no]['do'][$value->delivery_no]['no_do_sap']    = $value->delivery_no;
      $data['list'][$value->do_manifest_no]['do'][$value->delivery_no]['tgl_do_sap']   = $value->do_date;
      $data['list'][$value->do_manifest_no]['do'][$value->delivery_no]['ship_to_code'] = $value->ship_to_code;
      $data['list'][$value->do_manifest_no]['do'][$value->delivery_no]['ship_to']      = $value->ship_to;
      $data['list'][$value->do_manifest_no]['do'][$value->delivery_no]['acc_code']     = $value->acc_code;

      $model['model']    = $value->model;
      $model['quantity'] = $value->quantity;
      $model['cbm_do']   = $value->cbm_do;

      $data['list'][$value->do_manifest_no]['do'][$value->delivery_no]['models'][] = $model;
    }

    $data['total_ritase']    = $total_ritase;
    $data['total_cbm']       = $total_cbm;
    $data['total_ritase2']   = $total_ritase2;
    $data['total_multidrop'] = $total_multidrop;
    $data['total_unloading'] = $total_unloading;
    $data['total_overstay']  = $total_overstay;

    $data['total_freight'] = $total_ritase + $total_cbm + $total_ritase2 + $total_multidrop + $total_unloading + $total_overstay;
    $data['tax']           = $data['total_freight'] * 10 / 100;
    $data['grand_total']   = $data['total_freight'] + $data['tax'];

    return $data;
  }
}
