<!DOCTYPE html>

<html>
<head>
    @include('components.header')
</head>
<style>
body {
    background-color: #f5f5f5;
    padding: 10px;
}
</style>
<body>
    <div class="page-container" style="min-height: 1000px;">
        <div class="mb-2" style="display: inline-block;">
            <a href="{{ route('index') }}" class="btn btn-outline-primary btn-sm" data-group="main" style="width: 50px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
            </a>
            <b><span class="text-gradient-primary">PRIM Database Analytics</span></b>
        </div>
        <div class="subgroup-container" data-group="range">
            <!-- <div class="mt-2 mb-2">
                <div id="weeklyInputs" class="d-flex flex-wrap">
                    <div class="form-floating mb-3 me-2" style="width: 200px;">
                        <input type="date" class="form-control" id="startDate" value="{{ now()->subDays(7)->toDateString() }}">
                        <label for="startDate">From</label>
                    </div>
                    <div class="form-floating mb-3 me-2" style="width: 200px;">
                        <input type="date" class="form-control" id="endDate" value="{{ now()->toDateString() }}">
                        <label for="endDate">To</label>
                    </div>
                    <div class="form-floating mb-3" style="width: 350px;">
                        <select class="form-control" id="leaderboardMonthDate">
                            @for ($i = 0; $i < 12; $i++)
                                <option value="{{ now()->startOfYear()->addMonths($i)->format('Y-m') }}" {{ now()->format('Y-m') == now()->startOfYear()->addMonths($i)->format('Y-m') ? 'selected' : '' }}>
                                    {{ now()->startOfYear()->addMonths($i)->format('F Y') }}
                                </option>
                            @endfor
                        </select>
                        <label for="leaderboardMonthDate">Leaderboard Month</label>
                    </div>
                </div>
            </div> -->
            <div class="mt-2 mb-4">
                <div id="weeklyButtons" class="d-flex flex-wrap">
                    <div class="me-2" style="width: 200px;">
                        <button type="button" class="generate-report-btn btn btn-outline-primary w-100" data-report="chart"><i class="bi bi-file-earmark-arrow-down-fill"></i> Users Count</button>
                    </div>
                    <div class="me-2" style="width: 200px;">
                        <button type="button" class="generate-report-btn btn btn-outline-primary w-100" data-report="overview"><i class="bi bi-file-earmark-arrow-down-fill"></i> Overview</button>
                    </div>
                </div>
            </div>
            <hr class="my-2">
            <div class="loader" style="margin: 0 auto; margin-top: 50px;">
                <section class="item"></section>
            </div>
            <div id="range-leaderboard-table-container" class="loaded-content printable-container row mt-4" data-report="leaderboard"></div>
            <div class="loaded-content text-center">
                <div class="btn-group d-flex mt-3" role="group" style="width: 500px; margin: 0 auto;">
                    <button type="button" class="chart-range-btn btn btn-outline-primary w-100" data-group="hourly">Hourly</button>
                    <button type="button" class="chart-range-btn btn btn-primary w-100" data-group="daily">Daily</button>
                    <button type="button" class="chart-range-btn btn btn-outline-primary w-100" data-group="weekly">Weekly</button>
                    <button type="button" class="chart-range-btn btn btn-outline-primary w-100" data-group="monthly">Monthly</button>
                </div>
                <div class="printable-container" data-report="chart">
                    <h5 class="text-gradient-primary mt-3 mb-1">Active Users</h5>
                    <div id="chart"></div>
                </div>
                <hr class="my-2">
                <div class="printable-container" data-report="overview">
                    <div class="row">
                        <div class="col-sm-2">
                            <div class="card text-center" style="background-color: #259efa59">
                                <div class="card-body">
                                    <h5 class="card-title">Users</h5>
                                    <p class="card-text" id="users-count" style="font-size: 32px;">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="card text-center" style="background-color:rgba(69, 250, 37, 0.35)">
                                <div class="card-body">
                                    <h5 class="card-title">Rooms</h5>
                                    <p class="card-text" id="rooms-count" style="font-size: 32px;">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="card text-center" style="background-color:rgba(69, 250, 37, 0.35)">
                                <div class="card-body">
                                    <h5 class="card-title">Chats</h5>
                                    <p class="card-text" id="room-chats-count" style="font-size: 32px;">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="card text-center" style="background-color:rgba(250, 37, 115, 0.35)">
                                <div class="card-body">
                                    <h5 class="card-title">Files Generated</h5>
                                    <p class="card-text" id="ai-files-count" style="font-size: 32px;">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="card text-center" style="background-color:rgba(250, 37, 115, 0.35)">
                                <div class="card-body">
                                    <h5 class="card-title">Claude <i class="bi bi-arrow-down"></i></h5>
                                    <p class="card-text" id="ai-responses-count" style="font-size: 32px;">0</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="card text-center" style="background-color:rgba(250, 37, 115, 0.35)">
                                <div class="card-body">
                                    <h5 class="card-title">Claude <i class="bi bi-arrow-up"></i></h5>
                                    <p class="card-text" id="ai-usages-count" style="font-size: 32px;">0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src="{{ asset('js/apexcharts.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/luxon/3.4.3/luxon.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

let chartInstance = null;

function loadChart(type) {
    $.get(`https://prim-api.o513.dev/api/v1/telemetries/users/${type}`, function(response) {
        const labels = response.map(r => r.label);
        const counts = response.map(r => r.count);

        const options = {
            chart: {
                type: 'line',
                height: 225
            },
            series: [{
                name: 'Visitors',
                data: counts
            }],
            xaxis: {
                categories: labels
            }
        };

        if (chartInstance) {
            chartInstance.updateOptions(options, true, true);
        } else {
            chartInstance = new ApexCharts(document.querySelector("#chart"), options);
            chartInstance.render();
        }

        $('.chart-range-btn').prop('disabled', false);
    });
}

function loadOverviews() {
    $.get('https://prim-api.o513.dev/api/v1/telemetries/overview/counts', function(data) {
        $('#users-count').text(data.users);
        $('#rooms-count').text(data.rooms);
        $('#room-chats-count').text(data.room_chats);
        $('#ai-files-count').text(data.ai_files);
        $('#ai-responses-count').text(data.ai_responses);
        $('#ai-usages-count').text(data.ai_usages);
    });
}

$(document).ready(function() {
    loadChart('daily');
    loadOverviews();

    $('.chart-range-btn').on('click', function() {
        const group = $(this).data('group');
        $('.chart-range-btn').prop('disabled', true);
        $('.chart-range-btn').removeClass('btn-primary').addClass('btn-outline-primary');
        $(this).removeClass('btn-outline-primary').addClass('btn-primary');

        loadChart(group);
    });
});
</script>
</html>