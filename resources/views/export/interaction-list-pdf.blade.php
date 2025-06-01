@extends('export.layout', [
    'title' => $title,
])

@section('content')
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID</th>
        <th>Tanggal</th>
        <th>Jenis</th>
        <th>Status</th>
        <th>Sales</th>
        <th>Client</th>
        <th>Layanan</th>
        <th>Engagement</th>
        <th>Subjek</th>
        <th>Summary</th>
        <th>Catatan</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($items as $index => $item)
        <tr>
          <td align="right">{{ $index + 1 }}</td>
          <td>{{ $item->id }}</td>
          <td>{{ $item->date }}</td>
          <td>{{ \App\Models\Interaction::Types[$item->type] }}</td>
          <td>{{ \App\Models\Interaction::Statuses[$item->status] }}</td>
          <td>{{ $item->user->name }} ({{ $item->user->username }})</td>
          <td>
            {{ $item->customer->name }} - {{ $item->customer->company }} - {{ $item->customer->business_type }} - {{ $item->customer->address }}
        </td>
          <td>{{ $item->service->name }}</td>
          <td>{{ \App\Models\Interaction::EngagementLevels[$item->engagement_level] }}</td>
          <td>{{ $item->subject }}</td>
          <td>{{ $item->summary }}</td>
          <td>{{ $item->notes }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
