@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('packets') }}"> Back</a>
            </div>
            <div class="pull-left">
                <h2>Add New Packet</h2>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('packet.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Name:</strong>
                    <input type="text" name="name" class="form-control" placeholder="Name">
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="form-group">
                    <strong>Description:</strong>
                    <textarea class="form-control" style="height:150px" name="description"
                              placeholder="Description"></textarea>
                </div>
            </div>

            <div class="dropdown show">
                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                   data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Select product
                </a>

                <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                    @foreach($products as $product)
                        <a class="dropdown-item" href="#"
                           onclick="addPacket('<?php echo $product->id ?>', '<?php echo $product->name ?>')">{{ $product -> name }}</a>
                    @endforeach
                </div>

                <div class="products">
                    <table class="table" id="tablePackets">
                        <thead>
                        <tr>
                            <th scope="col">Name Product</th>
                            <th scope="col">Product Amount</th>

                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <div class="clear_list">
                        <br>
                        <p onclick="clearList()" style="cursor: pointer">Remove last product</p>
                    </div>
                </div>
            </div>

            <div class="col-xs-12 col-sm-12 col-md-12 text-center">
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>

    <script>
        function addPacket(id, name) {
            var table = document.getElementById("tablePackets");
            var index = table.rows.length;
            var row = table.insertRow(index);
            var cell1 = row.insertCell(0);
            var cell2 = row.insertCell(1);
            var cell3 = row.insertCell(2);
            var cell4 = row.insertCell(3);
            cell1.innerHTML = name;
            cell2.innerHTML = '<div class="col-xs-2">\n' +
                '                                    <input class="form-control" id="ex1" type="text" placeholder="amount" value="1" name="packet' + id + '"> \n' +
                '                                </div>';
        }

        function clearList() {
            var table = document.getElementById("tablePackets");
            var index = table.rows.length;
            if (index > 1) {
                document.getElementById("tablePackets").deleteRow(index - 1);
            }
        }
    </script>
@endsection
