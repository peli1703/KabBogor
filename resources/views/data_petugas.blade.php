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
    <h2 class="title-table">Laporan Keluhan Petugas</h2>
<div style="display: flex; justify-content: center; margin-bottom: 30px">
    <a href="{{route('logout')}}" class="button-17" style="text-align: center; margin-top:-px">Logout</a> 
    <div style="margin-right:10px; margin-left:10px; margin-top:5px"> <b>|</b></div>
    <a href="{{route('home')}}" class="button-17" style="text-align: center">Home</a>
</div>

<div style="display: flex; justify-content:flex-end; align-items:center">
    <form action="" method="GET">
        @csrf
        <input type="text" name="search" placeholder="cari berdasarkan nama ...">
        <button class="button-17" role="button" style="margin-left:5px;margin-top: -0.1px">Cari</button>
        
    </form>
    <div>
        <form action="{{route('data.petugas')}}" method="GET" style="margin-top:-33px; margin-right:35px; margin-left:5px">
            @csrf
            <button class="button-17" role="button">Refresh</button>
        </form>
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
            <th>Status</th>
            <th>Pesan</th>
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
                    <td>{{ $report['no_telp']}}</td>
                    <td>{{ $report['pengaduan']}}</td>
                    <td>
                        <a href="../assets/image/{{$report->foto}}"
                            target="_balnk">
                        <img src="{{asset('assets/image/'. $report->foto)}}" width="120">
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
                    <td style="display: flex; justify-content:center;">
                        <a href="{{route('response.edit', $report->id)}}" class="back-btn" > Send response</a>
                    </td>
            </tr>
            @endforeach
        </tbody>
    </table>    
    
</div>
</body>
</html>