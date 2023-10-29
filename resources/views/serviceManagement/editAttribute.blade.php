@extends('layouts.app')
@section('content')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<style>
    .toggle {
        border-radius: 20rem;
    }

    .col-sm-6 {
        width: 50%;
        float: left;
    }

    .card {
        background-color: #ECECEA;
        padding-right: 0px;
        padding-left: 0px;
        margin-right: auto;
        margin-left: auto;
        max-width: 650px;
        max-height: 850px;
    }
</style>

<h2 style="margin-left: 20px;">修改屬性</h2>

@if($errors->any())
    <div class="alert alert-dismissible alert-danger" role="alert">
        @foreach($errors->all() as $error)
            <button type="button" class="close" data-dismiss="alert">
                <span aria-hidden="true">&times;</span>
                <span class="sr-only">Close</span>
            </button>
            <ul>{{ $error }}</ul>
        @endforeach
    </div>
@endif

<form method="POST" action="{{ route('serviceManagement.attribute.update') }}" class="mt-4 text-center form">
    @csrf
    <input type="hidden" name="_method" value="PUT">
    @if ($result !== false)
        <div id="originalForm">
            <div class="row">
                <label class="col-sm-2 col-form-label">屬性名字</label>
                <div class="col-sm-10">
                    <input type="text" name="name" class="form-control" value="{{ $result['name'] }}" required>
                </div>
            </div>
            <div class="row mt-3">
                <label class="col-sm-2 col-form-label">批准層級</label>
                <div class="col-sm-10">
                    <select id="department" name="approved_level" class="form-control">
                        <option value="0" {{ $result['approved_level'] == 0 ? 'selected' : '' }}>0</option>
                        <option value="1" {{ $result['approved_level'] == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ $result['approved_level'] == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ $result['approved_level'] == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ $result['approved_level'] == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ $result['approved_level'] == 5 ? 'selected' : '' }}>5</option>
                        <option value="6" {{ $result['approved_level'] == 6 ? 'selected' : '' }}>6</option>
                        <option value="7" {{ $result['approved_level'] == 7 ? 'selected' : '' }}>7</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <label class="col-sm-2 col-form-label">批准層數</label>
                <div class="col-sm-10">
                    <select id="department" name="approved_layers" class="form-control">
                        <option value="0" {{ $result['approved_layers'] == 0 ? 'selected' : '' }}>0</option>
                        <option value="1" {{ $result['approved_layers'] == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ $result['approved_layers'] == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ $result['approved_layers'] == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ $result['approved_layers'] == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ $result['approved_layers'] == 5 ? 'selected' : '' }}>5</option>
                        <option value="6" {{ $result['approved_layers'] == 6 ? 'selected' : '' }}>6</option>
                        <option value="7" {{ $result['approved_layers'] == 7 ? 'selected' : '' }}>7</option>
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <label class="col-sm-2 col-form-label">同行人數</label>
                <div class="col-sm-10">
                    <select name="companion_number" class="form-control">
                        <option value="0" {{ $result['companion_number'] == 0 ? 'selected' : '' }}>0</option>
                        <option value="1" {{ $result['companion_number'] == 1 ? 'selected' : '' }}>1</option>
                        <option value="2" {{ $result['companion_number'] == 2 ? 'selected' : '' }}>2</option>
                        <option value="3" {{ $result['companion_number'] == 3 ? 'selected' : '' }}>3</option>
                        <option value="4" {{ $result['companion_number'] == 4 ? 'selected' : '' }}>4</option>
                        <option value="5" {{ $result['companion_number'] == 5 ? 'selected' : '' }}>5</option>
                        <option value="6" {{ $result['companion_number'] == 6 ? 'selected' : '' }}>6</option>
                        <option value="7" {{ $result['companion_number'] == 7 ? 'selected' : '' }}>7</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2 mt-4">可見/隱藏</label>
                <div class="col-sm-1 mt-3">
                    <input type="checkbox" name="display" data-toggle="toggle" {{ $result['display'] == 1 ? 'checked' : '' }}>
                </div>
            </div>
            <div class="row">
                <label class="col-sm-2 mt-4">取用表單</label>
                <div class="col-sm-1 mt-3">
                    <input type="checkbox" name="pickup_form" id="pickupFormCheckbox" data-toggle="toggle" {{ $result['pickup_form'] == 1 ? 'checked' : '' }}>
                </div>
                @if ($result['pickup_form'] == 1)
                    <a class="col-sm-2 mt-4" id="pickupPreview">預覽</a>
                @endif
            </div>
            <div class="row">
                <label class="col-sm-2 mt-4">歸還表單</label>
                <div class="col-sm-1 mt-3">
                    <input type="checkbox" name="return_form" id="returnFormCheckbox" data-toggle="toggle" {{ $result['return_form'] == 1 ? 'checked' : '' }}>
                </div>
                @if ($result['return_form'] == 1)
                    <a class="col-sm-2 mt-4" id="returnPreview">預覽</a>
                @endif
            </div>
            <div class="mt-4">
                <input name="attributeID" value="{{ $result['attributeID'] }}" hidden>
                <input type="submit" name="submit" class="btn" value="修改" style="background-color: #3E517A; color: white;">
            </div>
        </div>
        <div class="card col-sm-6" id="pickupForm" style="display: none;">
            <div style="margin: 25px;">
                <h2>取用表單</h2>
                <ul class="pickupList">
                    @if ($result['pickup_form'] == 1)
                        @foreach ($result['pickup_forms'] as $row)
                            <li class="list-group-item">
                                <input class="form-check-input" name="pickupQuestion[]" type="checkbox" value="{{ $row['questionID'] }}" {{ $row['required'] === null ? '' : 'checked' }}>
                                <label class="form-check-label mr-3">{{ $row['question'] }}</label>
                                @if ($row['required'] !== null)
                                    <input type="checkbox" class="toggle" data-toggle="toggle" data-size="xs" {{ $row['required'] == 0 ? 'checked' : '' }} data-width="40.925" data-height="18.2">
                                    <input type="hidden" name="pickupRequired[]" value="{{ $row['required'] != 0 ? 'true' : 'false' }}">
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
                <div class="mt-4">
                    <input type="button" id="submitPickupForm" class="btn" value="完成" style="background-color: #3E517A; color: white;">
                </div>
            </div>
        </div>
        <div class="card col-sm-6" id="returnForm" style="display: none;">
            <div style="margin: 25px;">
                <h2>歸還表單</h2>
                <ul class="returnList">
                    @if ($result['return_form'] == 1)
                        @foreach ($result['return_forms'] as $row)
                            <li class="list-group-item">
                                <input class="form-check-input" name="returnQuestion[]" type="checkbox" value="{{ $row['questionID'] }}" {{ $row['required'] === null ? '' : 'checked' }}>
                                <label class="form-check-label mr-3">{{ $row['question'] }}</label>
                                @if ($row['required'] !== null)
                                    <input type="checkbox" class="toggle" data-toggle="toggle" data-size="xs" {{ $row['required'] == 0 ? 'checked' : '' }}>
                                    <input type="hidden" name="returnRequired[]" value="{{ $row['required'] != 0 ? 'true' : 'false' }}">
                                @endif
                            </li>
                        @endforeach
                    @endif
                </ul>
                <div class="mt-4">
                    <input type="button" id="submitReturnForm" class="btn" value="完成" style="background-color: #3E517A; color: white;">
                </div>
            </div>
        </div>
    @endif
</form>

<script>
    $(document).ready(function() {
        $(".pickupList").sortable();
        $(".pickupList").disableSelection();

        var submitPickupForm = $('#pickupFormCheckbox').prop('checked') ? 'true' : 'false';
        var submitReturnForm = $('#returnFormCheckbox').prop('checked') ? 'true' : 'false';
        $('#pickupFormCheckbox').change(function() {
            var display = $(this).prop('checked');
            if (display == true) {
                $('.pickupList').empty();
                $('#returnForm').hide();
                if (!submitReturnForm) {
                    $('#returnFormCheckbox').bootstrapToggle('off');
                }
                $.ajax({
                    type: 'GET',
                    url: "{{ route('api.getQuestion') }}",
                    dataType: 'json',
                    success: function(data) {
                        if (data !== false) {
                            $.each(data, function(index, list) {
                                var listItem = $('<li class="list-group-item"></li>');
                                var checkbox = $('<input class="form-check-input" name="pickupQuestion[]" type="checkbox" value="' + list.questionID + '">');
                                var label = $('<label class="form-check-label mr-3">' + list.question + '</label>');

                                listItem.append(checkbox);
                                listItem.append(label);
                                $('.pickupList').append(listItem);
                            });
                        }
                    }
                });
                $('#pickupForm').show();
                $('.form').css('max-width', '1200px');
                $('#originalForm').addClass('col-sm-6');
            } else if (display == false) {
                submitPickupForm = false;
                $('#pickupPreview').hide();
                if (!$('#returnForm').is(':visible')) {
                    $('.form').css('max-width', '650');
                    $('#originalForm').removeClass('col-sm-6');
                }
                $('#pickupForm').hide();
                $('.pickupList').empty();
            }
        })

        $('#submitPickupForm').click(function() {
            submitPickupForm = true;
            $('#pickupPreview').show();
            $('#pickupForm').hide();
            $('.form').css('max-width', '650');
            $('#originalForm').removeClass('col-sm-6');
        });

        $('.pickupList').on('change', 'input[name="pickupQuestion[]"]', function() {
            var select = $(this).prop('checked');
            var listItem = $(this).closest('li');

            if (select) {
                var required = $('<input type="checkbox" class="toggle" data-toggle="toggle" data-size="xs">');
                var hidden = $('<input type="hidden" name="pickupRequired[]" value="false">');

                listItem.append(required);
                listItem.append(hidden);
                required.bootstrapToggle();

                required.on('change', function() {
                    hidden.val($(this).prop('checked') ? 'true' : 'false');
                });
            } else {
                var required = listItem.find('.toggle');
                required.bootstrapToggle('destroy');
                required.removeAttr('data-toggle').hide();
            }
        });

        $(document).on('click', '#pickupPreview', function() {
            if (submitReturnForm !== true) {
                $('#returnFormCheckbox').bootstrapToggle('off');
            }
            $('#returnForm').hide();
            $('#pickupForm').show();
            $('.form').css('max-width', '1200px');
            $('#originalForm').addClass('col-sm-6');
        })

        $(".returnList").sortable();
        $(".returnList").disableSelection();
        $('#returnFormCheckbox').change(function() {
            var display = $(this).prop('checked');
            if (display == true) {
                $('.returnList').empty();
                $('#pickupForm').hide();
                if (!submitReturnForm) {
                    $('#pickupFormCheckbox').bootstrapToggle('off');
                }
                $.ajax({
                    type: 'GET',
                    url: "{{ route('api.getQuestion') }}",
                    dataType: 'json',
                    success: function(data) {
                        if (data !== false) {
                            $.each(data, function(index, list) {
                                var listItem = $('<li class="list-group-item"></li>');
                                var checkbox = $('<input class="form-check-input" name="returnQuestion[]" type="checkbox" value="' + list.questionID + '">');
                                var label = $('<label class="form-check-label mr-3">' + list.question + '</label>');

                                listItem.append(checkbox);
                                listItem.append(label);
                                $('.returnList').append(listItem);
                            });
                        }
                    }
                });
                $('#returnForm').show();
                $('.form').css('max-width', '1200px');
                $('#originalForm').addClass('col-sm-6');
            } else if (display == false) {
                submitReturnForm = false;
                $('#returnPreview').hide();
                if (!$('#pickupForm').is(':visible')) {
                    $('.form').css('max-width', '650');
                    $('#originalForm').removeClass('col-sm-6');
                }
                $('#returnForm').hide();
                $('.returnList').empty();
            }
        })

        $('#submitReturnForm').click(function() {
            submitReturnForm = true;
            $('#returnPreview').show();
            $('#returnForm').hide();
            $('.form').css('max-width', '650');
            $('#originalForm').removeClass('col-sm-6');
        });

        $('.returnList').on('change', 'input[name="returnQuestion[]"]', function() {
            var select = $(this).prop('checked');
            var listItem = $(this).closest('li');

            if (select) {
                var required = $('<input type="checkbox" class="toggle" data-toggle="toggle" data-size="xs">');
                var hidden = $('<input type="hidden" name="returnRequired[]" value="false">');

                listItem.append(required);
                listItem.append(hidden);
                required.bootstrapToggle();

                required.on('change', function() {
                    hidden.val($(this).prop('checked') ? 'true' : 'false');
                });
            } else {
                var required = listItem.find('.toggle');
                required.bootstrapToggle('destroy');
                required.removeAttr('data-toggle').hide();
            }
        });

        $('#returnPreview').click(function() {
            $('#pickupForm').hide();
            if (submitPickupForm !== true) {
                $('#pickupFormCheckbox').bootstrapToggle('off');
            }
            $('#returnForm').show();
            $('.form').css('max-width', '1200px');
            $('#originalForm').addClass('col-sm-6');
        });
    })
</script>
@endsection
