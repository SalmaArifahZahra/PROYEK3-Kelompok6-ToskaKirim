@extends('layouts.layout_customer')

@section('title', 'Dashboard Customer')

@section('content')

    @include('component.customer.carousel_card')
    @include('component.customer.kategori_card')
    @include('component.customer.card_produk')
@endsection
