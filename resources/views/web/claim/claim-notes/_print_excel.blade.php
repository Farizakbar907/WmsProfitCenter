<link rel="stylesheet" type="text/css" href="{{ url('materialize/css/custom/print1-a4.css') }}">
{{-- @include('layouts.materialize.components.print-style') --}}

<body style="font-family: courier New; font-size: 10pt;">
   <!--mpdf
<htmlpagefooter name="myheader">
  <div style="position:absolute;top:5mm;right:10mm;" v="Page {PAGENO} of {nb}">
  
  </div>
  </htmlpagefooter>
<sethtmlpagefooter name="myheader" value="on" />
mpdf-->

   <table width="100%">
      <tr>
         <td>
            <table width="100%" style="font-family: Arial Narrow;border-collapse: collapse; font-size: 9pt;">
               {{-- Logo --}}
               <tr>
                  <td colspan="15" style="border-left: 2pt solid #000000; border-top: 2pt solid #000000; border-right: 2pt solid #000000;">&nbsp; </td>
               </tr>
               {{-- Title --}}
               <tr>
                  <td colspan="15" style="border-left: 2pt solid #000000; border-right: 2pt solid #000000; text-align: center; font-size: 18pt;"><strong>CLAIM LETTER</strong></td>
               </tr>
               <tr>
                  <td colspan="15" style="border-left: 2pt solid #000000; border-right: 2pt solid #000000; text-align: center; font-size: 12pt;">(Transporter/ Outsourcing Logistics)</td>
               </tr>
               <tr>
                  <td colspan="15" style="border-left: 2pt solid #000000; border-right: 2pt solid #000000;">&nbsp;</td>
               </tr>
               {{-- No --}}
               <tr>
                  <td style="border-left: 2pt solid #000000; text-align: left; width: 7mm;"><strong>No :</strong></td>
                  <td colspan="5" style="text-align: center; border-bottom: 1px solid #000000;">{{$claimNote->claim_note_no}}</td>
                  <td colspan="9" style="border-right: 2pt solid #000000">&nbsp;</td>
               </tr>
               <tr>
                  <td colspan="4" style="border-left: 2pt solid #000000; text-align: left;"><strong>Issued by :</strong></td>
                  <td colspan="2" style="text-align: center; border-bottom: 1px solid #000000; width: 50mm;"><strong>LOGISTICS</strong></td>
                  <td style="width: 7mm;">&nbsp;</td>
                  <td colspan="2" style="text-align: left;"><strong>Division :</strong></td>
                  <td colspan="2" style="text-align: center; border-bottom: 1px solid #000000;"><strong>LOGISTICS</strong></td>
                  <td colspan="">&nbsp;</td>
                  <td style="text-align: center;"><strong>Date:</strong></td>
                  <td style="text-align: center; border-bottom: 1px solid #000000;"><strong>{{date('d-M-Y',strtotime($claimNote->created_at))}}</strong></td>
                  <td style="border-right: 2pt solid #000000; width: 5mm;">&nbsp;</td>
               </tr>
               <tr>
                  <td colspan="15" style="height: 10px; border-left: 2pt solid #000000; border-right: 2pt solid #000000;"></td>
               </tr>
               {{-- Table --}}
               <tr>
                  <td colspan="7" style="border-top: 2pt solid #000000; border-bottom: 2pt solid #000000; border-left: 2pt solid #000000;"><strong>Plan No :</strong></td>
                  <td colspan="3" style="border-top: 2pt solid #000000; border-bottom: 2pt solid #000000; border-left: 1pt solid #000000;"><strong>Part Code :</strong></td>
                  <td colspan="2" style="border-top: 2pt solid #000000; border-bottom: 2pt solid #000000; border-left: 1pt solid #000000;"><strong>Part Name :</strong></td>
                  <td colspan="2" style="border-top: 2pt solid #000000; border-bottom: 2pt solid #000000; border-left: 1pt solid #000000;"><strong>Mould Name :</strong></td>
                  <td style="border-top: 2pt solid #000000; border-bottom: 2pt solid #000000; border-right: 2pt solid #000000; width: 5mm;">&nbsp;</td>
               </tr>
               {{-- Body --}}
               <tr>
                  <td colspan="7" style="border-left: 2pt solid #000000; border-top: 2pt solid #000000; border-bottom: 2pt solid #000000;">
                     <table style="font-family: Arial Narrow;border-collapse: collapse; font-size: 9pt;">
                        <tr>
                           <td colspan="">Reason :</td>
                           <td colspan="">{{$claimNote->claim=='carton-box'?'Claim Carton Box':'Claim Note Unit'}}</td>
                        </tr>
                        <tr>
                           <td style="height: 10px;" colspan="8"></td>
                        </tr>
                        <tr>
                           <td colspan="" style=" text-align: left;"><strong>Claim Unit : &nbsp; {{$claimNote->sum_qty}} Unit</strong></td>
                           <td colspan="" style="width: 7mm;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="height: 10px;" colspan="8"></td>
                        </tr>
                        @foreach($claimNoteDetail as $key => $value)
                        <tr>
                           <td>
                              <div style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; border-right: 1pt solid #000000; border-top: 1pt solid #000000; width:15px; height:15px; float: right;"></div>
                           </td>
                           <td colspan="">{{$value['description']}}</td>
                        </tr>
                        <tr>
                           <td style="height: 10px;" colspan="8"></td>
                        </tr>
                        @endforeach
                     </table>
                  </td>
                  <td colspan="8" style="border-top: 2pt solid #000000; border-right: 2pt solid #000000; border-left: 1pt solid #000000; border-bottom: 2pt solid #000000;">
                     <table style="font-family: Arial Narrow;border-collapse: collapse; font-size: 9pt;">
                        <tr>
                           <td colspan="3">Claim Amount :</td>
                           <td colspan="5"></td>
                        </tr>
                        <tr>
                           <td style="height: 10px;" colspan="8"></td>
                        </tr>
                        <tr>
                           <td width="100px">&#9312;</td>
                           <td width="500px" colspan="2" style="">Material Cost Amount</td>
                           <td colspan="2">&nbsp;</td>
                           <td>=</td>
                           <td width="300px">&nbsp;</td>
                           <td>&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="height: 10px;" colspan="8"></td>
                        </tr>
                        <tr>
                           <td>&#9313;</td>
                           <td colspan="2" style="">F/G Sales Price Amount</td>
                           <td colspan="2">&nbsp;</td>
                           <td>=</td>
                           <td>&nbsp;</td>
                           <td>&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="height: 10px;" colspan="8"></td>
                        </tr>
                        <tr>
                           <td>&#9314;</td>
                           <td colspan="2" style="">Man Power Cost</td>
                           <td colspan="2">&nbsp;</td>
                           <td>=</td>
                           <td>&nbsp;</td>
                           <td>&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="height: 10px;" colspan="8"></td>
                        </tr>
                        <tr>
                           <td>&#9315;</td>
                           <td colspan="2" style="">Other Cost</td>
                           <td colspan="2">&nbsp;</td>
                           <td>=</td>
                           <td style="border-bottom: 1pt solid #000000;">&nbsp;</td>
                           <td>+</td>
                        </tr>
                        <tr>
                           <td style="height: 10px;" colspan="8"></td>
                        </tr>
                        <tr>
                           <td colspan="3">Claim Cost (&#9312;+&#9313;+&#9314;+&#9315;)</td>
                           <td colspan="2">&nbsp;</td>
                           <td>=</td>
                           <td>&nbsp;</td>
                           <td>&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="height: 50px;" colspan="8"></td>
                        </tr>
                        <tr>
                           <td colspan="8" style="font-size: 7pt;">Note: Prices are subject to change without prior notice</td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <!-- Total Claim -->
               <tr>
                  <td colspan="5" style="border-bottom: 2pt solid #000000; border-left: 2pt solid #000000; width: 130mm;"><strong>Total Claim Amount</strong></td>
                  <td>
                     <table width="100%" style="font-size: 8pt;">
                        <tr>
                           <td>
                              <div style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; border-right: 1pt solid #000000; border-top: 1pt solid #000000; width:15px; height:15px; float: right;"></div>
                           </td>
                           <td style="width: 5mm; text-align: left;"><strong>IDR</strong></td>
                           <td>
                              <div style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; border-right: 1pt solid #000000; border-top: 1pt solid #000000; width:15px; height:15px; float: right;"></div>
                           </td>
                           <td style="width: 5mm; text-align: left;"><strong>USD</strong></td>
                           <td>
                              <div style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; border-right: 1pt solid #000000; border-top: 1pt solid #000000; width:15px; height:15px; float: right;"></div>
                           </td>
                           <td style="width: 5mm; text-align: left;"><strong>JYP</strong></td>
                        </tr>
                     </table>
                  </td>
                  <td colspan="2" style="border-bottom: 2pt solid #000000;"><strong>OTHERS:</strong></td>
                  <td colspan="7" style="border-bottom: 2pt solid #000000; border-right: 2pt solid #000000;">&nbsp;</td>
               </tr>
               <tr>
                  <td colspan="5" style="border-left: 2pt solid #000000;"><strong>Logistic Dept. Opinion :</strong></td>
                  <td colspan="2" style="border-top: 2pt solid #000000;">&nbsp;</td>
                  <td colspan="8" style="border-top: 2pt solid #000000; border-right: 2pt solid #000000;">&nbsp;</td>
               </tr>
               <tr>
                  <td colspan="15" style="border-left: 2pt solid #000000; border-right: 2pt solid #000000;">&nbsp;</td>
               </tr>
               <tr>
                  <td colspan="7" style="border-left: 2pt solid #000000;">&nbsp;</td>
                  <td colspan="2">&nbsp;</td>
                  <td colspan="6" style="border-right: 2pt solid #000000;">
                     <table width="100%" style="font-size: 8pt; border-collapse: collapse;">
                        <tr>
                           <td style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;"><strong>Div. Head</strong></td>
                           <td style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;"><strong>Div. Head</strong></td>
                           <td rowspan="2" style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; border-bottom: 1pt solid #000000; text-align: center;"><strong>Dept Head</strong></td>
                           <td colspan="2" rowspan="2" style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; border-bottom: 1pt solid #000000; text-align: center;"><strong>PIC</strong></td>
                        </tr>
                        <tr>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;"><strong>(Japanese)</strong></td>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;"><strong>(Local)</strong></td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;">Denny A</td>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;">Firman</td>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;">Tomi S</td>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;">Hardian</td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="15" style="height: 1px; border-bottom: 2pt solid #000000; border-left: 2pt solid #000000; border-right: 2pt solid #000000;"></td>
               </tr>
               <tr>
                  <td colspan="15" style="height: 1px; border-left: 2pt solid #000000; border-right: 2pt solid #000000;"></td>
               </tr>
               <!-- Company Name -->
               <tr>
                  <td colspan="5" style="border-top: 2pt solid #000000; border-left: 2pt solid #000000;"><strong>Company Name :</strong></td>
                  <td colspan="3" style="border-top: 2pt solid #000000; font-size: 12pt;"><strong>SEJAHTERA BERSAMA TRANSINDO, PT.</strong></td>
                  <td colspan="7" style="border-top: 2pt solid #000000; border-right: 2pt solid #000000;"><strong>Supplier Code :</strong></td>
               </tr>
               <tr>
                  <td colspan="5" style="border-left: 2pt solid #000000;"><strong>Opinion :</strong></td>
                  <td colspan="10" style="border-right: 2pt solid #000000;"></td>
               </tr>
               <tr>
                  <td colspan="6" style="border-left: 2pt solid #000000;">Payment Method( &radic; ):</td>
                  <td>
                     <div style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; border-right: 1pt solid #000000; border-top: 1pt solid #000000; width:15px; height:15px; float: right;"></div>
                  </td>
                  <td>&nbsp;Transfer</td>
                  <td colspan="2">&nbsp;</td>
                  <td>
                     <div style="border-bottom: 1pt solid #000000; border-left: 1pt solid #000000; border-right: 1pt solid #000000; border-top: 1pt solid #000000; width:15px; height:15px; float: right;"></div>
                  </td>
                  <td>&nbsp;Deduct Payment</td>
                  <td colspan="">Claim Amount(IDR, JYP, USD)</td>
                  <td style="border-bottom: 1pt solid #000000;">: &nbsp; </td>
                  <td colspan="" style="border-right: 2pt solid #000000;">
                     <table width="100%" style="font-size: 8pt; border-collapse: collapse;">
                        <tr>
                           <td style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center; width: 15mm;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="border-right: 1pt solid #000000; border-left: 1pt solid #000000;text-align: center; width: 15mm;"><strong>PIC</strong></td>
                        </tr>
                        <tr>
                           <td style="border-right: 1pt solid #000000; border-left: 1pt solid #000000; border-bottom: 1pt solid #000000; text-align: center; width: 15mm;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="15" style="height: 1px; border-left: 2pt solid #000000; border-right: 2pt solid #000000"></td>
               </tr>
               <tr>
                  <td colspan="15" style="height: 1px; border-top: 2pt solid #000000; border-left: 2pt solid #000000; border-right: 2pt solid #000000"></td>
               </tr>
               <tr>
                  <td colspan="6" style="border-top: 2pt solid #000000; border-left: 2pt solid #000000;"><strong>Accounting Dept. Opinion :</strong></td>
                  <td colspan="9" style="border-top: 2pt solid #000000; border-right: 2pt solid #000000;">&nbsp;</td>
               </tr>
               <tr>
                  <td colspan="8" style="border-left: 2pt solid #000000;">&nbsp;</td>
                  <td colspan="3">&nbsp;</td>
                  <td colspan="4" style="border-right: 2pt solid #000000;">
                     <table width="100%" style="font-size: 8pt; border-collapse: collapse;">
                        <tr>
                           <td style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;"><strong>Div. Head</strong></td>
                           <td rowspan="2" style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; border-bottom: 1pt solid #000000; text-align: center;"><strong>Dept. Head</strong></td>
                           <td rowspan="2" style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; border-bottom: 1pt solid #000000; text-align: center; width: 15mm;"><strong>PIC</strong></td>
                        </tr>
                        <tr>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;"><strong>(Japanese)</strong></td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;">K. Tani</td>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;">Syaalom</td>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="15" style="height: 1px; border-bottom: 2pt solid #000000; border-left: 2pt solid #000000; border-right: 2pt solid #000000;"></td>
               </tr>
               <tr>
                  <td colspan="6" style="border-top: 2pt solid #000000; border-left: 2pt solid #000000;"><strong>Management Opinion :</strong></td>
                  <td style="border-top: 2pt solid #000000;"></td>
                  <td colspan="8" style="border-top: 2pt solid #000000; border-right: 2pt solid #000000;">&nbsp;</td>
               </tr>
               <tr>
                  <td colspan="8" style="border-left: 2pt solid #000000;">&nbsp;</td>
                  <td colspan="3">&nbsp;</td>
                  <td colspan="4" style="border-right: 2pt solid #000000;">
                     <table width="100%" style="font-size: 8pt; border-collapse: collapse;">
                        <tr>
                           <td style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; border-bottom: 1pt solid #000000; text-align: center;"><strong>President<br>Director</strong></td>
                           <td style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; border-bottom: 1pt solid #000000; text-align: center;"><strong>Vice<br>President</strong></td>
                           <td style="border-top: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; border-bottom: 1pt solid #000000; text-align: center;"><strong>Finance<br>Director</strong></td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style=" border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                        </tr>
                        <tr>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;">Mr. Teraoka</td>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000;">&nbsp;</td>
                           <td style="border-bottom: 1pt solid #000000; border-right: 1pt solid #000000; border-left: 1pt solid #000000; text-align: center;">Yagura</td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td colspan="15" style="height: 1px; border-bottom: 2pt solid #000000; border-left: 2pt solid #000000; border-right: 2pt solid #000000;"></td>
               </tr>
               <tr>
                  <td colspan="4" style="border-top: 2pt solid #000000; border-left: 2pt solid #000000; height: 40px;"><strong>Debit Note No :</strong></td>
                  <td colspan="2" style="border-top: 2pt solid #000000;">&nbsp;</td>
                  <td colspan="9" style="border-top: 2pt solid #000000; border-right: 2pt solid #000000;">&nbsp;</td>
               </tr>
               <tr>
                  <td colspan="6" style="border-top: 2pt solid #000000; font-size: 7pt"> Note: Harga diatas dapat berubah sewaktu-waktu tanpa pemberitahuan sebelumnya</td>
                  <td colspan="7" style="border-top: 2pt solid #000000;"></td>
                  <td colspan="2" style="text-align: center; font-size: 9pt; border-bottom: 2pt solid #000000; border-left: 2pt solid #000000; border-right: 2pt solid #000000; border-top: 2pt solid #000000;">QUALITY-FORM-003</td>
               </tr>
            </table>
         </td>
      </tr>
   </table>

   <!-- new page -->
   <div style="break-after:page"></div>

   <table width="100%" style="font-family: Arial Narrow;border-collapse: collapse; font-size: 7pt;">
      <tr>
         <td>
            <table width="100%" style="font-family: Arial Narrow;border-collapse: collapse; font-size: 7pt;">
               <tr>
                  <td>
                     <table width="100%">
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
                        <tr>
                           <td colspan="1" style="font-size: 8pt; text-align: left; width: 50mm;"><strong>01/Claim U-Log/Des/2019</strong></td>
                           <td style="font-size: 8pt; text-align: left;"><strong>{{money_reformat($subTotal,'IDR')}}</strong></td>
                        </tr>
                        <tr>
                           <td>&nbsp;</td>
                        </tr>
                     </table>
                  </td>
               </tr>
               <tr>
                  <td>
                     {{-- Main Table --}}
                     <table width="100%" style="font-family: Arial Narrow;border-collapse: collapse; font-size: 7pt;">
                        {{-- Table Head --}}
                        <tr>
                           <td style="border: 1pt solid #000000; width: 15mm;">Date</td>
                           <td style="border: 1pt solid #000000; width: 50mm;">Ekspedisi</td>
                           <td style="border: 1pt solid #000000; width: 15mm;">Driver</td>
                           <td style="border: 1pt solid #000000; width: 20mm;">Plate Number</td>
                           <td style="border: 1pt solid #000000; width: 15mm;">Destination</td>
                           <td style="border: 1pt solid #000000; width: 15mm;">Delivery Order</td>
                           <td style="border: 1pt solid #000000; width: 20mm;">Model</td>
                           <td style="border: 1pt solid #000000; width: 15mm;">Serial Number</td>
                           <td style="border: 1pt solid #000000; width: 5mm;">Qty</td>
                           <td style="border: 1pt solid #000000;width: 10mm;">Warehouse</td>
                           <td style="border: 1pt solid #000000;width: 15mm; ">Description</td>
                           <td style="border: 1pt solid #000000;width: 7mm;">Claim</td>
                           <td style="border: 1pt solid #000000;width: 15mm;">Price</td>
                           <td style="border: 1pt solid #000000;width: 15mm;">Total Price</td>
                        </tr>
                        {{-- Body Table --}}
                        @if (!empty($claimNoteDetail))
                        @foreach ($claimNoteDetail as $k => $v)
                        <tr>
                           <td style="border: 1pt solid #000000;">{{!empty($v->date_of_receipt)?date('d-M-Y',strtotime($v->date_of_receipt)):'-'}}</td>
                           <td style="border: 1pt solid #000000;"><strong>{{!empty($v->expedition_name)?$v->expedition_name:'-'}}</strong></td>
                           <td style="border: 1pt solid #000000;">{{!empty($v->driver_name)?$v->driver_name:'-'}}</td>
                           <td style="border: 1pt solid #000000;">{{!empty($v->vehicle_number)?$v->vehicle_number:'-'}}</td>
                           <td style="border: 1pt solid #000000;">{{!empty($v->destination)?$v->destination:'-'}}</td>
                           <td style="border: 1pt solid #000000;">{{!empty($v->do_no)?$v->do_no:'-'}}</td>
                           <td style="border: 1pt solid #000000;">{{!empty($v->model_name)?$v->model_name:'-'}}</td>
                           <td style="border: 1pt solid #000000;">{{!empty($v->serial_number)?$v->serial_number:'-'}}</td>
                           <td style="border: 1pt solid #000000;text-align:center">{{!empty($v->qty)?$v->qty:0}}</td>
                           <td style="border: 1pt solid #000000;">{{!empty($v->location)?$v->location:'-'}}</td>
                           <td style="border: 1pt solid #000000;">{{!empty($v->description)?$v->description:'-'}}</td>
                           <td style="border: 1pt solid #000000;">{{!empty($claimNote->claim)?$claimNote->claim:'-'}}</td>
                           <td style="border: 1pt solid #000000; text-align:right;width:100px;">{{!empty($v->price)?money_reformat($v->price,'IDR'):0}}</td>
                           <td style="border: 1pt solid #000000; text-align:right;width:100px;">{{money_reformat($v->qty*$v->price,'IDR')}}</td>
                        </tr>
                        @endforeach
                        @else
                        <td colspan="14" style="border: 1pt solid #000000;">empty</td>
                        @endif
                     </table>
                  </td>
               </tr>
            </table>
         </td>
      </tr>
   </table>