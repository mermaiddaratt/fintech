@php
    function rupiah($angka){
        $hasil_rupiah = "Rp" . number_format($angka,0,',','.');
        return $hasil_rupiah;
    }
@endphp


@extends('layouts.app')

@section('content')
<div class="container">
    <p class="fw-bold fs-5">
        Hi, {{ Auth::user()->name }}
    </p>

    {{-- Bank --}}
    @if (Auth::user()->role  == 'bank')
        <div class="container">
            <div class="row justify-content-center mb-4">
                <div class="col-md-4">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header fw-bold" style="">
                                    Saldo
                                </div>
                                <div class="card-body">
                                    {{ rupiah($saldo) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col">
                            <div aclass="card">
                                <div class="card-header fw-bold" style="">
                                    Transaksi
                                </div>
                                <div class="card-body">
                                    {{ $transactions }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col">
                            <div class="card">
                                <div class="card-header fw-bold" style="">
                                    Nasabah
                                </div>
                                <div class="card-body">
                                    {{ $nasabah }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header fw-bold">
                    Request TopUp
                </div>
                <div class="card-body">
                    <div class="row ">
                        @foreach ( $request_topup as $request )
                             <div class="col-3 mb-3">
                                <form method="POST" action="{{ route('acceptRequest') }}">
                                    @csrf
                                    <input type="hidden" value="{{ $request->id }}" name="id">
                                    <div class="card">
                                        <div class="card-header">
                                            {{ $request->user->name }}
                                        </div>
                                        <div class="card-body">
                                           Nominal: {{ rupiah($request->credit) }}
                                        </div>
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-primary">Accept Request</button>
                                        </div>
                                    </div>
                                </form>
                             </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Siswa --}}
    @if (Auth::user()->role == 'siswa')
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header fs-5">Product Catalog</div>
                    <div class="card-body">
                        <div class="row">
                            @foreach ($products as $key => $product )
                                <div class="col col-4 col-md-4 col-sm-6 mb-3">
                                    <form method="POST" action="{{ route('addToCart') }}" >
                                        @csrf
                                        <input type="hidden" value="{{ Auth::user()->id }}" name="user_id">
                                        <input type="hidden" value="{{ $product->id }}" name="product_id">
                                        <input type="hidden" value="{{ $product->price }}" name="price">
                                        <div class="card">
                                            <div class="card-header">
                                                {{ $product->name }}
                                            </div>
                                            <div class="card-body">
                                                <div class=" d-flex justify-content-center align-items-center mb-3" style="height: 150px">
                                                    <img src="{{ $product->photo }}" style="width: 125px">
                                                </div>
                                                <div>{{ $product->description }}</div>
                                                <div>Harga:  {{ rupiah($product->price) }} </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="mb-3 ">
                                                    <input class="form-control" type="number" name="quantity" value="0" min="0">
                                                </div>
                                                <div class="d-grid gap-2">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="bi bi-cart3"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header fs-5 ">
                        Keranjang
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($carts as $key => $cart)
                                <li>{{ $cart->product->name }} | {{ $cart->quantity }} * {{ rupiah($cart->price) }} </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-end fw-bold">
                            Total Biaya: {{ rupiah($total_biaya) }}
                        </div>
                        <form action="{{ route('payNow') }}" method="POST">
                            <div class="d-grid gap-2">
                                @csrf
                                <button type="submit" class="btn btn-success">
                                    Bayar Sekarang
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header fs-5 ">
                        Riwayat Transaksi
                    </div>
                    <div class="card-body">
                        <ul>
                            @foreach ($transactions as $key => $transaction)
                                <div class="row mb-3">
                                    <div class="col">
                                        <div class="row">
                                            <div class="col fw-bold">
                                                {{ $transaction[0]->order_id }}
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col text-secondary" style="font-size: 12px">
                                                {{ $transaction[0]->created_at }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col d-flex justify-content-end align-items-center">
                                        <a href="{{ route('download', ['order_id' => $transaction[0]->order_id] ) }}" class="btn btn-success" target="_blank">
                                        Download
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-header fs-5">Mutasi Wallet</div>
                    <div class="card-body">
                        <ul>
                            @foreach ($mutasi as $data)
                                <li>
                                    {{ rupiah($data->credit) ? rupiah($data->credit) : 'Debit' }} | {{ rupiah($data->debit) ? rupiah($data->debit) : 'Kredit' }} | {{ $data->description }}
                                    @if ($data->status == 'proses')
                                        <span class="badge text-bg-warning">PROSES</span>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
   @endif
</div>
@endsection
