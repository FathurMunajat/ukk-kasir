@extends('layouts.app')
@section('title', 'Purchase - W Mart')

@section('content')
  <div class="mb-4">
    <div class="breadcrumb">
        <span class="breadcrumb-item">
            <i class="bi bi-house-door"></i>
            <i class="bi bi-arrow-right-short"></i> Purchase
        </span>
    </div>
    <h2 class="page-title">Purchase</h2>
  </div>

  {{-- Card with Table --}}
 
  <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <div class="card-body">
      <div class="table-responsive">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
          <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
            <div class="d-flex justify-content-end mb-3">
                @if (in_array(Auth::user()->role, ['user']))
              <a href="{{ route('Purchase.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle"></i> Add Purchase
              </a>
                @endif
            </div>
            <tr>
              <th scope="col" class="px-6 py-3">No</th>
              <th scope="col" class="px-6 py-3">Customer Name</th>
              <th scope="col" class="px-6 py-3">Date</th>
              <th scope="col" class="px-6 py-3">Total Amount</th>
              <th scope="col" class="px-6 py-3">Made By</th>
              <th scope="col" class="px-6 py-3">Action</th>
            </tr>
          </thead>
          <tbody>
            @forelse ($purchases as $purchase)
              <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td>{{ $purchase->member_id }}</td>
                <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                <td>Rp {{ number_format($purchase->payment_amount, 0, ',', '.') }}</td>
                <td>{{ $purchase->user->name ?? '-' }}</td>
                <td>
                    <i class="bi bi-eye"></i> Show
                  </a>  
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="6" class="text-center">No Purchase Data</td>
              </tr>
            @endforelse
          </tbody>
        </table>
        <div class="d-flex justify-content-end">
         
        </div>
      </div>
    </div>
  </div>
@endsection
