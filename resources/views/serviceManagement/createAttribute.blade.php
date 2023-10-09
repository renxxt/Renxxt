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

<h2 style="margin-left: 20px;">新增屬性</h2>

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

<form method="POST" action="{{ route('serviceManagement.attribute.store') }}" class="mt-4 text-center form">
    @csrf
    <div id="originalForm">
        <div class="row">
            <label class="col-sm-2 col-form-label">屬性名字</label>
            <div class="col-sm-10">
                <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">批准層級</label>
            <div class="col-sm-10">
                <select id="department" name="approved_level" class="form-control">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">批准層數</label>
            <div class="col-sm-10">
                <select id="department" name="approved_layers" class="form-control">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                </select>
            </div>
        </div>
        <div class="row mt-3">
            <label class="col-sm-2 col-form-label">同行人數</label>
            <div class="col-sm-10">
                <select name="companion_number" class="form-control">
                    <option value="0">0</option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                </select>
            </div>
        </div>
        <div class="row">
            <label class="col-sm-2 mt-4">可見/隱藏</label>
            <div class="col-sm-1 mt-3">
                <input type="checkbox" name="display" data-toggle="toggle">
            </div>
        </div>
        <div class="row">
            <label class="col-sm-2 mt-4">取用表單</label>
            <div class="col-sm-1 mt-3 pickup">
                <input type="checkbox" name="pickup_form" id="pickupFormCheckbox" data-toggle="toggle">
            </div>
            <a class="col-sm-2 mt-4" id="pickupPreview" style="display: none;">預覽</a>
        </div>
        <div class="row">
            <label class="col-sm-2 mt-4">歸還表單</label>
            <div class="col-sm-1 mt-3">
                <input type="checkbox" name="return_form" id="returnFormCheckbox" data-toggle="toggle">
            </div>
            <a class="col-sm-2 mt-4" id="returnPreview" style="display: none;">預覽</a>
        </div>
        <div class="mt-4">
            <input type="submit" name="submit" class="btn" value="新增" style="background-color: #3E517A; color: white;">
        </div>
    </div>
    <div class="card col-sm-6" id="pickupForm" style="display: none;">
        <div style="margin: 25px;">
            <h2>取用表單</h2>
            <ul class="pickupList">
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
            </ul>
            <div class="mt-4">
                <input type="button" id="submitReturnForm" class="btn" value="完成" style="background-color: #3E517A; color: white;">
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $(".pickupList").sortable();
        $(".pickupList").disableSelection();

        var submitPickupForm = false;
        var submitReturnForm = false;
        $('#pickupFormCheckbox').change(function() {
            var display = $(this).prop('checked');
            if (display == true) {
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
                if (!$('#returnForm').is(':visible')) {
                    $('.form').css('max-width', '650');
                    $('#originalForm').removeClass('col-sm-6');
                }
                $('#pickupPreview').hide();
                $('#pickupForm').hide();
                $('.pickupList').empty();
            }
        });

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

        $('#pickupPreview').click(function() {
            $('#returnForm').hide();
            if (submitReturnForm !== true) {
                $('#returnFormCheckbox').bootstrapToggle('off');
            }
            $('#pickupForm').show();
            $('.form').css('max-width', '1200px');
            $('#originalForm').addClass('col-sm-6');
        });

        $(".returnList").sortable();
        $(".returnList").disableSelection();
        $('#returnFormCheckbox').change(function() {
            var display = $(this).prop('checked');
            if (display == true) {
                $('#pickupForm').hide();
                if (!submitPickupForm) {
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
            } else if (display==false) {
                submitReturnForm = false;
                if (!$('#pickupForm').is(':visible')) {
                    $('.form').css('max-width', '650');
                    $('#originalForm').removeClass('col-sm-6');
                }
                $('#returnPreview').hide();
                $('#returnForm').hide();
                $('.returnList').empty();
            }
        });

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
