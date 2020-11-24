<html>

<head>
   <link rel="stylesheet" type="text/css" href="{{ url('materialize/css/custom/print1-a4.css') }}">
   {{-- @include('layouts.materialize.components.print-style') --}}

</head>

<body style="font-family: courier New; font-size: 10pt;">

   <table width="100%" style="border-collapse: collapse;">
      <!-- <tr><td>&nbsp;</td></tr> -->
      <!-- <tr><td>&nbsp;</td></tr> -->
      <tr>
         <td style="text-align: center; font-size: 12pt;" colspan="7"><strong>Barang Cacat Dari Expedisi</strong></td>
      </tr>
      <tr>
         <td style="text-align: center; font-size: 12pt;" colspan="7"><strong>{{date('d M Y ',strtotime($beritaAcara->date_of_receipt))}}</strong></td>
      </tr>
      <tr>
         <td style="text-align: center; font-size: 12pt;" colspan="7"><strong>{{$beritaAcara->berita_acara_no}}</strong></td>
      </tr>
      <tr>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td style="border: 1pt solid #000000; text-align:center;">No.</td>
         <td style="border: 1pt solid #000000; text-align:center;">Tipe</td>
         <td style="border: 1pt solid #000000; text-align:center;">No. Seri</td>
         <td style="border: 1pt solid #000000; text-align:center;">Sopir / No Polisi</td>
         <td style="border: 1pt solid #000000; text-align:center;">Kerusakan</td>
         <td style="border: 1pt solid #000000; text-align:center;">Gambar Unit</td>
      </tr>
      {{-- Body --}}
      @php
      $no = 1;
      $qty = 0;
      @endphp
      @if(!empty($beritaAcaraDetail))
      @forelse($beritaAcaraDetail as $key => $value)
      <tr>
         <td style="border: 1pt solid #000000;">{{$no}}.</td>
         <td style="border: 1pt solid #000000;">{{$value['do_no']}}</td>
         <td style="border: 1pt solid #000000;">{{$value['model_name']}}</td>
         <td style="border: 1pt solid #000000;">{{$value['serial_number']}}</td>
         <td style="border: 1pt solid #000000;">{{$value['qty']}}</td>
         <td style="border: 1pt solid #000000;">{{$value['description']}}</td>
         <td style="border: 1pt solid #000000; width: 50mm;">{{$value['keterangan']}}</td>
      </tr>
      @php
      $no ++;
      $qty+=$value['qty'];
      @endphp
      @empty
      <tr>
         <td colspan="7" style="border: 1pt solid #000000;text-align:center;"><i>no data avalaible</i></td>
      </tr>
      @endforelse
      @endif
      <tr>
         <td>&nbsp;</td>
      </tr>
      <tr>
         <td colspan="4" style="border: 1pt solid #000000;"><strong>Total</strong></td>
         <td style="border: 1pt solid #000000;">{{$qty}}</td>
      </tr>
   </table>

   <footer>
   </footer>

</body>

</html>