

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
                <th>Last Run</th>
                <th>{{ trans('lavero-admin-scheduling::scheduling.description') }}</th>
                <th>Status</th>
                <th>{{ trans('lavero-admin-scheduling::scheduling.run') }}</th>
            </tr>
            @foreach($events as $index => $event)
            @php
                $taskId = $index + 1;
                $taskName = $event['task']['name'] ?? '';
                $runInfo = $history[$taskId] ?? null;
                $isDisabled = in_array($taskName, $disabled ?? [], true);
            @endphp
            <tr>
                <td>{{ $taskId }}.</td>
                <td><code>{{ $taskName }}</code></td>
                <td><span class="label label-success">{{ $event['expression'] }}</span>&nbsp;{{ $event['readable'] }}</td>
                <td>{{ $event['nextRunDate'] }}</td>
                <td class="small">
                    @if($runInfo)
                        <span class="badge bg-{{ ($runInfo['status'] ?? '') === 'success' ? 'success' : 'danger' }}">{{ $runInfo['status'] }}</span>
                        <span class="text-muted">{{ $runInfo['last_run_at'] }} ({{ $runInfo['duration_ms'] }}ms)</span>
                    @else
                        <span class="text-muted">—</span>
                    @endif
                </td>
                <td>{{ $event['description'] }}</td>
                <td>
                    <button type="button" class="btn btn-xs {{ $isDisabled ? 'btn-outline-secondary' : 'btn-outline-success' }} toggle-task" data-task="{{ $taskName }}">
                        {{ $isDisabled ? 'Disabled' : 'Active' }}
                    </button>
                </td>
                <td><a class="btn btn-xs btn-primary run-task" data-id="{{ $taskId }}">{{ trans('lavero-admin-scheduling::scheduling.run') }}</a></td>
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
                    if (result && result.data) {
                        document.querySelector('.output-box').classList.remove('d-none');
                        document.querySelector('.output-box .output-body').innerHTML = result.data.data || '';
                    }
                });
            });
        });

        document.querySelectorAll('.toggle-task').forEach(el => {
            el.addEventListener('click', function () {
                var task = this.dataset.task;
                admin.ajax.post('{{ route("scheduling-toggle") }}', { task: task }, function (res) {
                    if (res && res.data && res.data.status) {
                        admin.ajax.reload();
                    }
                });
            });
        });

</script>