@extends('export.layout', [
    'title' => $title,
])

@section('content')
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID</th>
        <th>Nama</th>
        <th>Telepon</th>
        <th>Email</th>
        <th>Alamat</th>
        <th>Nama Perusahaan</th>
        <th>Jenis Perusahaan</th>
        <th>Sumber</th>
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
          <td>{{ $item->phone }}</td>
          <td>{{ $item->email }}</td>
          <td>{{ $item->address }}</td>
          <td>{{ $item->company }}</td>
          <td>{{ $item->business_type }}</td>
          <td>{{ $item->source }}</td>
          <td>{{ $item->active ? 'Aktif' : 'Non Aktif' }}</td>
          <td>{{ $item->notes }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
