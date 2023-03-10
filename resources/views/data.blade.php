<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaduan Masyarakat</title>
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
    <h2 class="title-table">Laporan Keluhan</h2>
<div style="display: flex; justify-content: center; margin-bottom: 30px">
    <a href="{{route('logout')}}" class="button-17" style="text-align: center; margin-top:-px">Logout</a> 
    <div style="margin-right:10px; margin-left:10px; margin-top:5px"> <b>|</b></div>
    <a href="{{route('home')}}" class="button-17" style="text-align: center">Home</a>
</div>



<div style="display: flex; justify-content:flex-end; align-items:center">
    <form action="" method="GET">
        @csrf
        <input type="text" name="search" placeholder="cari berdasarkan nama ...">
        <button class="button-17" role="button" style="margin-left:5px;margin-top: -0.1px">Seacrh</button>
    </form>

    <div>
        <form action="{{route('data')}}" method="GET" style="margin-top:-30px; margin-left:5px ; margin-right: 33px">
            @csrf
            <button class="button-17" role="button">Refresh</button>
        </form>
    </div>
    
    </div>
   
    <div class="sec-center" style="margin-top: 5px; margin-right: 33px"> 	
        <input class="dropdown" type="checkbox" style="" id="dropdown" name="dropdown"/>
            <label class="for-dropdown" style="" for="dropdown">
                Print All <i class="uil uil-arrow-down"></i>
            </label>

            <div class="section-dropdown"> 
                <a class="tes" href="/export/excel">Print Excel<i class="uil uil-arrow-right"></i></a>
                <input class="dropdown-sub" type="checkbox" id="dropdown-sub" name="dropdown-sub"/>
                <a class="tes" href="/export/pdf">Print PDF <i class="uil uil-arrow-right"></i></a>
            </div>
    </div>
</div>

<div style="padding: 0 30px; margin-top:10px">
    <table>
        <thead>
        <tr>
            <th width="5%">No</th>
            <th>NIK</th>
            <th>Nama</th>
            <th>Telp</th>
            <th>Pengaduan</th>
            <th>Gambar</th>
            <th>Status Response</th>
            <th>Pesan Response</th>
            <th>Aksi</th>
        </tr>
        </thead>
        <tbody>
            @php
            $no = 1;
            @endphp
            @foreach ($reports as $report)
            <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $report['nik']}}</td>
                    <td>{{ $report['nama']}}</td>
                @php
                    $telp = substr_replace($report->no_telp, "62", 0, 1)         
                @endphp

                @php
                    if ($report->response){
                        $pesanWA = 'Hallo%20' . $report->nama . '! pengaduan anda di' . 
                        $report->response['status'] . '%20Berikut pesan untuk anda : ' . $report->response['pesan'];
                    }
                    else {
                        $pesanWA = 'Belum ada data response';
                    }
                @endphp
                    <td><a href="https://wa.me/{{$telp}}?text={{$pesanWA}}" target="_blank">{{$telp}}</a></td>
                    <td>{{ $report['pengaduan']}}</td>
                    <td>
                        {{-- menampilkan gambar full layar pada tab baru --}}
                        <a href="../assets/image/{{$report->foto}}"
                        target="_balnk">
                        <img src="{{asset('assets/image/'. $report->foto)}}" width="120">
                        </a>
                    </td>

                    <td>
                        {{-- cek apakah ada data report ini sudah memiliki relasi dengan data dari with('response') --}}
                        @if ($report->response)
                            {{-- kalau ada hasil relasinya, tampilkan bagian status --}}
                            {{ $report->response['status']}}
                        @else
                        {{-- kalau ga ada tampilkan tanda ini - --}}
                            -
                        @endif
                    </td>

                    <td>
                    {{-- cek apakah ada data report ini sudah memiliki relasi dengan data dari with('response') --}}
                        @if ($report->response)
                              {{-- kalau ada hasil relasinya, tampilkan bagian pesan --}}
                            {{ $report->response['pesan']}}
                        @else
                        {{-- kalau ga ada tampilkan tanda ini - --}}
                            -
                        @endif
                    </td>

                    <td>
                        
                        <div>
                            <form action="{{route('created.pdf', $report->id)}}" method="GET" style="margin-top:-20px; margin-right:5px">
                                @csrf
                                <button class="button-17" role="button">Print</button>
                            </form>
                        </div>

                        <form action="{{ route('delete', $report->id) }}" method="post" style="">
                            @csrf
                            @method('delete')
                            <button class="button-17" role="button" style="background-color: #ff0033ca; color:aliceblue">Delete</button>
                        </form>

                    </td>
            </tr>
            @endforeach
        </tbody>
    </table>    
    
</div>
</body>
</html>