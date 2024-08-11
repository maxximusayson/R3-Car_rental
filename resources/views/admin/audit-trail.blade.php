@extends('layouts.myapp1')

@section('content')

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
                        <th class="pb-3 text-left">Date</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($audits as $audit)
                        <tr class="border-t">
                            <td class="py-2">{{ $audit->user->name }}</td>
                            <td class="py-2">{{ $audit->action }}</td>
                            <td class="py-2">{{ $audit->details }}</td>
                            <td class="py-2">{{ $audit->created_at->format('Y-m-d H:i:s') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $audits->links() }}
        @endif
    </div>
</div>
@endsection
