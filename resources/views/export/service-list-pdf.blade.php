@extends('export.layout', [
    'title' => $title,
])

@section('content')

  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID</th>
        <th>Nama Layanan</th>
        <th>Status</th>
        <th>Catatan</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($items as $index => $item)
        <tr>
          <td align="right">{{ $index + 1 }}</td>
          <td>{{ $item->id }}</td>
          <td>{{ $item->name }}</td>
          <td>{{ $item->active ? 'Aktif' : 'Non Aktif' }}</td>
          <td>{{ $item->notes }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  @endsection
