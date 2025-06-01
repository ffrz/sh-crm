@extends('export.layout', [
    'title' => $title,
])

@section('content')
  <table>
    <thead>
      <tr>
        <th>No</th>
        <th>ID</th>
        <th>Username</th>
        <th>Nama Lengkap</th>
        <th>Hak Akses</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($items as $index => $item)
        <tr>
          <td align="right">{{ $index + 1 }}</td>
          <td>{{ $item->id }}</td>
          <td>{{ $item->username }}</td>
          <td>{{ $item->name }}</td>
          <td>{{ \App\Models\User::Roles[$item->role] }}</td>
          <td>{{ $item->active ? 'Aktif' : 'Non Aktif' }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
@endsection
