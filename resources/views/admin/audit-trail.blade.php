@extends('layouts.myapp1')

@section('content')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCDg7pLs7iesp74vQ-KSEjnFJW3BKhVq7k"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <!-- Clock -->
  <div id="clock" class="text-gray-900 dark:text-gray-300 text-lg font-semibold absolute top-4 right-4">
      <span id="time"></span>
  </div>

  <script>
      function updateTime() {
          const now = new Date();
          let hours = now.getHours();
          const minutes = String(now.getMinutes()).padStart(2, '0');
          const seconds = String(now.getSeconds()).padStart(2, '0');
          const ampm = hours >= 12 ? 'PM' : 'AM';
          hours = hours % 12;
          hours = hours ? hours : 12; // the hour '0' should be '12'

          const month = now.toLocaleString('default', { month: 'long' });
          const day = now.getDate();
          const year = now.getFullYear();

          const dateString = `${month} ${day}, ${year}`;
          const timeString = `${hours}:${minutes}:${seconds} ${ampm}`;
          document.getElementById('time').textContent = `${dateString} ${timeString}`;
      }
      setInterval(updateTime, 1000);
      updateTime();  // Initial call to display the time immediately
  </script>

<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-6">Audit Trail</h1>
    <div class="bg-white rounded-lg shadow-md p-4">
        @if($audits->isEmpty())
            <p class="text-gray-500">No audit logs available.</p>
        @else
            <table class="w-full text-left border-separate border-spacing-2">
                <thead>
                    <tr>
                        <th class="pb-3 text-left">User</th>
                        <th class="pb-3 text-left">Action</th>
                        <th class="pb-3 text-left">Details</th>
                        <th class="pb-3 text-left">Reservation</th>
                        <th class="pb-3 text-left">Payment</th>
                        <th class="pb-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audits as $audit)
                        <tr class="border-t">
                            <td class="py-2">{{ $audit->user->name }}</td>
                            <td class="py-2">{{ $audit->action }}</td>
                            <td class="py-2">{{ $audit->details }}</td>
                            <td class="py-2">
                                @if($audit->reservation)
                                    <div><strong>Car:</strong> {{ $audit->reservation->car->model }}</div>
                                    <div><strong>Start Date:</strong> {{ $audit->reservation->start_date->format('Y-m-d') }}</div>
                                    <div><strong>End Date:</strong> {{ $audit->reservation->end_date->format('Y-m-d') }}</div>
                                @else
                                    <span class="text-gray-500">No reservation data</span>
                                @endif
                            </td>
                            <td class="py-2">
                                @if($audit->payment)
                                    <div><strong>Amount:</strong> ${{ number_format($audit->payment->amount, 2) }}</div>
                                    <div><strong>Method:</strong> {{ $audit->payment->method }}</div>
                                    <div><strong>Status:</strong> {{ $audit->payment->status }}</div>
                                @else
                                    <span class="text-gray-500">No payment data</span>
                                @endif
                            </td>
                            <td class="py-2">{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
           <!-- Pagination links -->
<div class="pagination">
    @if ($audits->onFirstPage())
        <span class="disabled">« Previous</span>
    @else
        <a href="{{ $audits->previousPageUrl() }}">« Previous</a>
    @endif

    @foreach ($audits->getUrlRange(1, $audits->lastPage()) as $page => $url)
        @if ($page == $audits->currentPage())
            <span class="active">{{ $page }}</span>
        @else
            <a href="{{ $url }}">{{ $page }}</a>
        @endif
    @endforeach

    @if ($audits->hasMorePages())
        <a href="{{ $audits->nextPageUrl() }}">Next »</a>
    @else
        <span class="disabled">Next »</span>
    @endif
</div>
<style>
    /* Pagination Container */
.pagination {
    display: flex;
    justify-content: center;
    margin-top: 20px;
}

.pagination a, .pagination span {
    display: inline-block;
    padding: 8px 16px;
    margin: 0 4px;
    border-radius: 4px;
    text-align: center;
    text-decoration: none;
    color: #333;
    background-color: #f5f5f5;
    border: 1px solid #ddd;
}

.pagination a:hover, .pagination a:focus {
    background-color: #e0e0e0;
    border-color: #ccc;
}

.pagination .active {
    background-color: #007bff;
    color: #fff;
    border-color: #007bff;
}

.pagination .disabled {
    color: #ccc;
    border-color: #ddd;
}

</style>
        @endif
    </div>
</div>
@endsection
