

<style>
    .output-body {
        white-space: pre-wrap;
        background: #000000;
        color: #00fa4a;
        padding: 10px;
        border-radius: 0;
    }

</style>

<div class="card">
    <!-- /.box-header -->
    <div class="card-body no-padding">
        <table class="table table-striped table-hover">
            <tbody>
            <tr>
                <th style="width: 10px">#</th>
                <th>{{ trans('lavero-admin-scheduling::scheduling.task') }}</th>
                <th>{{ trans('lavero-admin-scheduling::scheduling.run_at') }}</th>
                <th>{{ trans('lavero-admin-scheduling::scheduling.next_run_time') }}</th>
                <th>{{ trans('lavero-admin-scheduling::scheduling.description') }}</th>
                <th>{{ trans('lavero-admin-scheduling::scheduling.run') }}</th>
            </tr>
            @foreach($events as $index => $event)
            <tr>
                <td>{{ $index+1 }}.</td>
                <td><code>{{ $event['task']['name'] }}</code></td>
                <td><span class="label label-success">{{ $event['expression'] }}</span>&nbsp;{{ $event['readable'] }}</td>
                <td>{{ $event['nextRunDate'] }}</td>
                <td>{{ $event['description'] }}</td>
                <td><a class="btn btn-xs btn-primary run-task" data-id="{{ $index+1 }}">{{ trans('lavero-admin-scheduling::scheduling.run') }}</a></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <!-- /.box-body -->
</div>

<div class="card box-default output-box d-none mt-4">
    <div class="card-header with-border">


        <h3 class="card-title"><i class="icon-terminal"></i> &nbsp; {{ trans('lavero-admin-scheduling::scheduling.output') }}</h3>
    </div>
    <!-- /.box-header -->
    <div class="card-body">
        <pre class="output-body"></pre>
    </div>
    <!-- /.box-body -->
</div>
<script data-exec-on-popstate>

        document.querySelectorAll('.run-task').forEach(el=>{

            el.addEventListener("click",function (e) {
                var id = e.target.dataset.id;
                var url = '{{ route('scheduling-run') }}';
                var data = {id:id};

                admin.ajax.post(url,data,function (result) {
                    console.log(data);
                    if (typeof data === 'object') {
                        document.querySelector('.output-box').classList.remove('d-none');
                        document.querySelector('.output-box .output-body').innerHTML = result.data.data;
                    }
                });
            });
        });

</script>