<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PusherBatchUploader</title>
    <link rel="stylesheet" href="{{ asset('build/assets/app-00ef6d16.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css">
</head>

<body>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('4ba467a4d146b608c291', {
            cluster: 'ap3'
        });
        var channel = pusher.subscribe('batch-progress');
        channel.bind('pusher:subscription_succeeded', function (data) {
            console.log(data);
        });
        channel.bind('batch-progress-updated', function (data) {
            $('#uploadStatus').html('處理中');

            if (data.progress === 100) {
                location.reload();
            }
            $('.progressBarValue').css('width', data.progress + "%");

            console.log(data);
        });
    </script>

    <div id="form">
        <form id="uploadForm" action="/uploadCsv" method="post" enctype="multipart/form-data">
            @csrf
            <input type="file" name="mycsv" id="mycsv">
            <button type="button" onclick="uploadFile()">
                Upload
            </button>
        </form>
    </div>

    <div class="progressBar">
        <div id="uploadStatus"></div>
        <div class="progressBarcontainer">
            <div class="progressBarValue"></div>
        </div>
    </div>

    <div class="table-reponsive box">
        <table id="example" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Batch Id</th>
                    <th>Pending Jobs</th>
                    <th>Total Jobs</th>
                    <th>Created At</th>
                    <th>Finished At</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allBatches as $key => $data)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $data->id }}</td>
                    <td>{{ $data->pending_jobs }}</td>
                    <td>{{ $data->total_jobs }}</td>
                    <td>{{ date('Y-m-d H:i',$data->created_at) }}</td>
                    <td>{{ date('Y-m-d H:i',$data->finished_at) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    </div>

    <script>
        $(document).ready(function () {
            $('#example').DataTable();
        });

        var allBatches = {!! json_encode($allBatches)!!};
        console.log(allBatches);

        function uploadFile() {
            var formData = new FormData(document.getElementById('uploadForm'));

            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                type: 'POST',
                url: '/uploadCsv',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#uploadStatus').html('上傳中');
                },
                error: function (error) {
                    $('#uploadStatus').html('Error uploading');
                }
            });
        }
    </script>
</body>

</html>