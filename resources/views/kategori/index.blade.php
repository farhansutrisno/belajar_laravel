<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CRUD Category</title>
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css"
        href="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.3/af-2.3.7/b-2.1.1/cr-1.5.5/date-1.1.1/fc-4.0.1/fh-3.2.1/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.4/sr-1.0.1/datatables.min.css" />
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"
        integrity="sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p" crossorigin="anonymous" />

</head>

<body>
    <div class="container-fluid">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Data Kategori</h1>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="box">
                        <div class="box-header with-border">
                            <button onclick="addForm()" class="btn btn-success btn-xs btn-flat"><i
                                    class="fa fa-plus-circle"></i>
                                Tambah</button>
                            <a href="{{ url('/category/cetak') }}" class="btn btn-primary">Export PDF</a>
                            <a href="{{ url('/category/export_excel') }}" class="btn btn-success my-3" target="_blank">EXPORT EXCEL</a>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-stiped table-bordered">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th width="10%"><i class="fas fa-cog"></i></th>
                                    </tr>
                                </thead>

                                @foreach ($dataKategori as $item)
                                    <tr>
                                        <td width="5%">1</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->description }}</td>
                                        <td width="10%">
                                            <a href="javascript:void(0)" data-id="{{ $item->id }}" class="edit btn btn-primary btn-sm editKategori"><i class="fa fa-edit"></i>Edit</a>
                                            <a href="javascript:void(0)" data-id="{{ $item->id }}" class="btn btn-danger btn-sm deleteKategori"><i class="fa fa-trash"></i>Delete</a>
                                        </td>
                                    </tr>
                                @endforeach

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    @includeIf('kategori.form')
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.0.1/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript"
        src="https://cdn.datatables.net/v/bs5/jq-3.6.0/dt-1.11.3/af-2.3.7/b-2.1.1/cr-1.5.5/date-1.1.1/fc-4.0.1/fh-3.2.1/kt-2.6.4/r-2.2.9/rg-1.1.4/rr-1.2.8/sc-2.0.5/sb-1.3.0/sp-1.4.0/sl-1.3.4/sr-1.0.1/datatables.min.js">
    </script>
    <script>

    $(document).ready(function(){
		$('.table').DataTable();
	});

        //seting header csrf token laravel untuk semua request ajax
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });

        //close modal
        $('.close-btn').click(function(e) {
            $('.modal').modal('hide')
        });

        //save data untuk edit atau create
        $('#saveBtn').click(function(e) {
            var formdata = $("#modal-form form").serializeArray();
            var data = {};
            $(formdata).each(function(index, obj) {
                data[obj.name] = obj.value;
            });
            if (validation(data)) {
                $.ajax({
                    data: $('#modal-form form').serialize(),
                    url: "{{ route('category.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function(data) {
                        $('#modal-form').modal('hide');
                        $('.table').DataTable().draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            }

        });


        //memunculkan form edit
        $('body').on('click', '.editKategori', function() {
            var id = $(this).data('id');
            console.log(id);
            $.get("{{ route('category.index') }}" + '/edit/' + id, function(data) {
                $('.modal-title').text('Edit Category');
                $('#modal-form').modal('show');
                $('#id').val(data.id);
                $('#name').val(data.name);
                $('#description').val(data.description);
            })
        });

        //delete data
        $('body').on('click', '.deleteKategori', function() {
            var id = $(this).data("id");
            if (confirm("Are You sure want to delete !") == true) {
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('category.index') }}" + '/destroy/' + id,
                    success: function(data) {
                        $('.table').DataTable().draw();
                    },
                    error: function(data) {
                        console.log('Error:', data);
                    }
                });
            }
        });

        //memunculkan form add
        function addForm() {
            $("#modal-form").modal('show');
            $('#id').val('');
            $('.modal-title').text('Add Category');
            $('#modal-form form')[0].reset();
            $('#modal-form [name=name]').focus();
        }

        //validasi name harus di isi
        function validation(data) {
            let formIsValid = true;
            $('span[id^="error"]').text('');
            if (!data.name) {
                formIsValid = false;
                $("#error-name").text('The name field is required.')
            }
            return formIsValid;
        }

        function submitHandler() {
            $('#saveBtn').click();
        }
    </script>
</body>

</html>
