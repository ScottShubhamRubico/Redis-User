<h1> Computer</h1>
<form action="{{ route('computer') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".csv">
    <button type="submit">Import CSV</button>
</form>

<h1> Furniture</h1>
<form action="{{ route('furniture') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <input type="file" name="file" accept=".csv">
    <button type="submit">Import CSV</button>
</form>
