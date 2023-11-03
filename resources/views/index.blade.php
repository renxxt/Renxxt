@extends('layouts.app')
@section('content')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.26/sweetalert2.css" integrity="sha512-aNZkM/JhVXzcEdwmFm6Zg0tPZNZb+e/2xmYduDayY/NJ2JgiC6XcCCk4u941r8/ZsmoIZPgwO8WY7YVwrLihLA==" crossorigin="anonymous" referrerpolicy="no-referrer">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
<script src="https://cdn.amcharts.com/lib/5/index.js"></script>
<script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
<script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
<style>
    .state {
		width: 20px;
		height: 20px;
	}

</style>

<h2 style="margin-left: 20px;">預借看板</h2>

<div class="container row">
    <div class="col order-first row mt-auto mb-auto" style="margin-left: 3.5%;">
        <div class="state" style="background: #FF8A00"></div>
        <h6 class="mx-2">待審核</h6>
        <div class="state" style="background: #048A21"></div>
        <h6 class="mx-2">已審核</h6>
        <div class="state" style="background: #409AED"></div>
        <h6 class="mx-2">使用中</h6>
        <div class="state" style="background: #A6A6A6"></div>
        <h6 class="mx-2">已歸還</h6>
    </div>
    <form class="col mt-3">
        <div class="row">
            <select class="form-control col-md-4 mr-2" id="attributeID">
                <option value="0" selected>全部屬性</option>
                @if ($attributes !== false)
                    @foreach ($attributes as $row)
                        <option value="{{ $row['attributeID'] }}">{{ $row['name'] }}</option>
                    @endforeach
                @endif
            </select>

            <input type="datetime" class="form-control col-md-5 mr-2" id="date" value="<?= $date; ?>" placeholder="搜尋日期" required>
            <input type="button" class="btn" id="search" value="搜尋" style="background-color: #3E517A; color: white;">
        </div>
    </form>
</div>

<div id="chart" style="margin-top: 20px;">
    <div id="chartdiv"></div>
</div>

<div class="modal" id="detailModal" role="dialog">
    <div class="modal-dialog modal-lg" style="background-color: #FF0000;">
        <div class="modal-content">
            <div id="detailContent" class="mt-3 mb-3 ml-5">
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#date').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            minDate: new Date(),
            locale: {
                cancelLabel: '取消',
                applyLabel: '確認',
                format: 'YYYY-MM-DD',
            }
        });

        $('#date').on('cancel.daterangepicker', function(ev, picker) {
			$(this).val('');
		});

        $('#date').on('apply.daterangepicker', function(ev, picker) {
			$(this).val(picker.startDate.format('YYYY-MM-DD'));
		});

        $(document).on('click', '#search', function() {
            chart();
        })
    })
    var data = [];
    function chart() {
        $("#chartdiv").remove();
        $('#chart').append("<div id='chartdiv'></div>");
        var attributeID = $('#attributeID').val();
        var date = $('#date').val();
        $.ajax({
            type: 'POST',
            data: {
                attributeID: attributeID,
                date: date
            },
            url: "{{ route('api.list') }}",
            datatype: 'json',
            success: function(result) {
                data = [];
                var devices = result.devices;
                $.each(result.result, function(index, result) {
                    if (((result.estimated_pickup_time).substr(0, 10)) != ((result.estimated_return_time).substr(0, 10)) && ((result.estimated_pickup_time).substr(0, 10)) == date) {
                        data.push({
                            displayValue: result.applicationID,
                            category: result.device,
                            time: result.time,
                            start: new Date(result.startYear, result.startMonth, result.startDay, result.startHour, result.startMinute).getTime(),
                            end: new Date(result.endYear, result.endMonth, result.endDay, 23, 59).getTime(),
                            columnSettings: {
                                fill: result.color,
                            }
                        });
                    } else if (((result.estimated_pickup_time).substr(0, 10)) != ((result.estimated_return_time).substr(0, 10)) && ((result.estimated_return_time).substr(0, 10)) == date) {
                        data.push({
                            displayValue: result.applicationID,
                            category: result.device,
                            time: result.time,
                            start: new Date(result.endYear, result.endMonth, result.endDay, 00, 0).getTime(),
                            end: new Date(result.endYear, result.endMonth, result.endDay, result.endHour, result.endMinute).getTime(),
                            columnSettings: {
                                fill: result.color,
                            }
                        });
                    } else if (((result.estimated_pickup_time).substr(0, 10)) != ((result.estimated_return_time).substr(0, 10))) {
                        data.push({
                            displayValue: result.applicationID,
                            category: result.device,
                            time: result.time,
                            start: new Date(result.startYear, result.startMonth, result.startDay, 00, 0).getTime(),
                            end: new Date(result.endYear, result.endMonth, result.endDay, 23, 59).getTime(),
                            columnSettings: {
                                fill: result.color,
                            }
                        });
                    } else {
                        data.push({
                            displayValue: result.applicationID,
                            category: result.device,
                            time: result.time,
                            start: new Date(result.startYear, result.startMonth, result.startDay, result.startHour, result.startMinute).getTime(),
                            end: new Date(result.endYear, result.endMonth, result.endDay, result.endHour, result.endMinute).getTime(),
                            columnSettings: {
                                fill: result.color,
                            }
                        });
                    }
                })

                var year = date.substr(0, 4);
				var month = date.substr(5, 2);
				var day = date.substr(8, 2);

                am5.ready(function() {
                    var root = am5.Root.new("chartdiv");
                    root.dateFormatter.setAll({
                        dateFormat: "yyyy-MM-dd",
                        dateFields: ["valueX", "openValueX"],
                    });

                    root.setThemes([
                        am5themes_Animated.new(root)
                    ]);

                    var chart = root.container.children.push(am5xy.XYChart.new(root, {
                        panX: "none",
                        panY: "none",
                        wheelX: "none",
                        wheelY: "none",
                        pinchZoom: false,
                        layout: root.verticalLayout
                    }));

                    var legend = chart.children.push(am5.Legend.new(root, {
                        centerX: am5.p50,
                        x: am5.p50
                    }))

                    var yAxis = chart.yAxes.push(
                        am5xy.CategoryAxis.new(root, {
                            categoryField: "category",
                            renderer: am5xy.AxisRendererY.new(root, {
                                inversed: true
                            }),
                            tooltip: am5.Tooltip.new(root, {})
                        })
                    );

                    var colors = chart.get("colors");

                    yAxis.data.setAll(devices);

                    var xAxis = chart.xAxes.push(
                        am5xy.DateAxis.new(root, {
                            baseInterval: {
                                timeUnit: "minute",
                                count: 1
                            },
                            renderer: am5xy.AxisRendererX.new(root, {}),
                            min: (new Date(year, month, day, 00, 0)).getTime(),
                            max: (new Date(year, month, day, 23, 59)).getTime(),
                        })
                    );

                    var series = chart.series.push(am5xy.ColumnSeries.new(root, {
                        xAxis: xAxis,
                        yAxis: yAxis,
                        openValueXField: "start",
                        valueXField: "end",
                        categoryYField: "category",
                        sequencedInterpolation: true
                    }));

                    series.columns.template.setAll({
                        templateField: "columnSettings",
                        strokeOpacity: 0,
                        tooltipText: "{category}: {time}",
                    });

                    series.columns.template.events.on("click", function(ev) {
						var item = ev.target.dataItem.dataContext;
                        $.ajax({
                            type: 'POST',
                            data: {
                                applicationID: item.displayValue,
                            },
                            url: "{{ route('api.detail') }}",
                            datatype: 'json',
                            success: function(result) {
                                $('#detailModal').modal('show');
                                $('#detailContent h5').remove();
                                var detail = `
                                    <h5>${result.uuid}</h5>
                                    <h5>申請人：${result.name}</h5>
                                    <h5>單位：${result.department}</h5>
                                    <h5>屬性名稱：${result.attribute}</h5>
                                    <h5>設備名稱：${result.device}</h5>
                                    <h5>預計使用時間：${result.estimated_pickup_time} ~ ${result.estimated_return_time}</h5>
                                `;
                                $('#detailContent').append(detail);
                            }
                        })
					});

                    xAxis.data.setAll(data);
                    series.data.setAll(data);
                    series.appear();
                    chart.appear(1000, 100);
                });
            }
        })
    }
    chart();
</script>
@endsection
