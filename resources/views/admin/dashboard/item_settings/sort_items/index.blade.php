@extends('admin.layouts.main')

@section('additionalCSS')
    <link href="{{ asset('plugins/toastr/toastr.min.css') }}" rel="stylesheet">
@stop

@section('content')
    <div class="ly-row">
            <div class="ly-12">
                <div class="ly-row">
                    <div class="ly-2">
                        <label>Order By</label>
                    </div>

                    <div class="ly-1">
                        <label>Type</label>
                    </div>

                    <div class="ly-2">
                        <label>Category</label>
                    </div>

                    <div class="ly-2">
                    </div>

                    <div class="ly-2">
                    </div>

                    <div class="ly-1">
                        <label>Show Per Page</label>
                    </div>
                </div>
            </div>
            <div class="ly-12 m15">
                <div class="ly-row mb5">
                    <div class="ly-2">
                        <div class="form_row">
                            <div class="form_inline">
                                <div class="select">
                                    <select class="form_global form-control-rounded form-control-sm" id="sort">
                                        <option value="1" {{ request()->get('sort') == '1' ? 'selected' : '' }}>Sort No</option>
                                        <option value="2" {{ request()->get('sort') == '2' ? 'selected' : '' }}>Activation Date</option>
                                        <option value="3" {{ request()->get('sort') == '3' ? 'selected' : '' }}>Modification Date</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ly-1">
                        <div class="form_row">
                            <div class="form_inline">
                                <div class="select">
                                    <select class="form_global form-control-rounded form-control-sm" id="active">
                                        <option value="1" {{ request()->get('a') == '1' ? 'selected' : '' }}>All</option>
                                        <option value="2" {{ request()->get('a') == null ? 'selected' : (request()->get('a') == '2' ? 'selected' : '') }}>Active</option>
                                        <option value="3" {{ request()->get('a') == '3' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ly-2">
                        <div class="form_row">
                            <div class="form_inline">
                                <div class="select">
                                    <select class="form_global form-control-rounded form-control-sm" id="d_parent_category">
                                        <option value="0">All Category</option>
                                        @foreach($defaultCategories as $item)
                                            <option value="{{ $item['id'] }}" data-index="{{ $loop->index }}" {{ request()->get('c1') == $item['id'] ? 'selected' : '' }}>{{ $item['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ly-2">
                        <div class="form_row">
                            <div class="form_inline">
                                <div class="select">
                                    <select class="form_global form-control-rounded form-control-sm" id="d_second_parent_category">
                                        <option value="0">All Category</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ly-2">
                        <div class="form_row">
                            <div class="form_inline">
                                <div class="select">
                                    <select class="form_global form-control-rounded form-control-sm" id="d_third_parent_category">
                                        <option value="0">All Category</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ly-1">
                        <div class="form_row">
                            <div class="form_inline">
                                <div class="select">
                                    <select class="form_global form-control-rounded form-control-sm" id="showPerPage">
                                        <option value="1" {{ request()->get('p') == '1' ? 'selected' : '' }}>50</option>
                                        <option value="2" {{ request()->get('p') == '2' ? 'selected' : '' }}>100</option>
                                        <option value="3" {{ request()->get('p') == '3' ? 'selected' : '' }}>150</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ly-2 ">
                        <button class="ly_btn  btn_blue" id="btnFilter">Filter</button>
                        <button class="ly_btn  btn_blue" id="btnSave">Save</button>
                    </div>
                </div>
            </div>

            <div class="ly-12 m15">
                <div class="ly-row">
                <form action="{{ route('admin_sort_items_save') }}" method="POST" id="form-sort">
                    @csrf
                    <ul id="SortItems">
                    @foreach($items as $item)
                        <li>
                            <div class="short_product_inner">
                                <a href="{{ route('admin_edit_item', ['item' => $item->id]) }}">
                                    @if (sizeof($item->images) > 0)
                                        <img src="{{ Storage::url($item->images[0]->thumbs_image_path) }}" alt="{{ $item->style_no }}" class="img-fluid">
                                    @else
                                        <img src="{{ asset('images/no-image.png') }}" alt="{{ $item->style_no }}" class="img-fluid">
                                    @endif
                                </a>
                                <p><a href="{{ route('admin_edit_item', ['item' => $item->id]) }}">{{ $item->style_no }}</a></p>
                                <input type="text" name="sort[]" class="form-control input_sort" value="{{ $item->sorting }}" data-item-id="{{ $item->id }}">
                                <input type="hidden" name="ids[]" value="{{ $item->id }}">
                            </div>
                        </li>
                    @endforeach
                    </ul>
                </form>
                </div>
            </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="pagination">
                {{ $items->appends(request()->query())->links() }}
            </div>
        </div>
    </div>

@stop

@section('additionalJS')
    <script type="text/javascript" src="{{ asset('plugins/toastr/toastr.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('plugins/sortable/js/Sortable.min.js') }}"></script>
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });


            var defaultCategories = <?php echo json_encode($defaultCategories); ?>;
            var message = '{{ session('message') }}';

            if (message != '')
                toastr.success(message);

            var el = document.getElementById('SortItems');
            Sortable.create(el, {
                animation: 150,
                dataIdAttr: 'data-id',
                onEnd: function (th) {
                    updateSort(th);
                },
            });

            $('#btnFilter').click(function () {
                filter();
            });

            $('#btnSave').click(function () {
                $('#form-sort').submit();
            });

            function filter() {
                var url = '{{ route('admin_sort_items_view') }}' + '?sort=';
                url += $('#sort').val();
                url += '&a=' + $('#active').val();
                url += '&c1=' + $('#d_parent_category').val();
                url += '&c2=' + $('#d_second_parent_category').val();
                url += '&c3=' + $('#d_third_parent_category').val();
                url += '&p=' + $('#showPerPage').val();

                window.location.replace(url);
            }

            function updateSort(th) {
                var oldIndex = th.oldIndex;
                var newIndex = th.newIndex;
                var startIndex = 0;
                var endIndex = 0;
                if (newIndex === undefined) newIndex = $('#SortItems li').length - 1;
                if ($('#SortItems li').length > 1 && newIndex < $('#SortItems li').length) {
                    var leftInput = $('#SortItems li :input[name="sort[]"]')[newIndex - 1]
                    var newInput = $('#SortItems li :input[name="sort[]"]')[newIndex]
                    var rightInput = $('#SortItems li :input[name="sort[]"]')[newIndex + 1]

                    var newValue = parseInt($(newInput).val());

                    if (oldIndex == newIndex) {
                        newValue = parseInt($(newInput).val());
                    } else if (oldIndex < newIndex) {
                        newValue = parseInt($(leftInput).val());
                        startIndex = oldIndex;
                        endIndex = newIndex - 1;
                        for (let indexIterator = startIndex; indexIterator <= endIndex; indexIterator++) {
                            var currentInput = $('#SortItems li :input[name="sort[]"]')[indexIterator];
                            let currentValue = parseInt($(currentInput).val()) + 1;
                            $(currentInput).val(currentValue).trigger("change")
                        }
                        // hit api
                        let formData = {
                            item_id : $(newInput).data('item-id'),
                            sorting : newValue,
                        }
                        $.ajax({
                            type: "POST",
                            url: "{{route('admin_sort_single_item')}}",
                            data: formData,
                            dataType: 'json'
                        })
                        .done(function() {
                            toastr.success('Updated!');
                        });
                    } else {
                        newValue = parseInt($(rightInput).val());
                        startIndex = newIndex + 1;
                        endIndex = oldIndex;
                        for (let indexIterator = startIndex; indexIterator <= endIndex; indexIterator++) {
                            var currentInput = $('#SortItems li :input[name="sort[]"]')[indexIterator];
                            let currentValue = parseInt($(currentInput).val()) - 1;
                            $(currentInput).val(currentValue).trigger("change")
                        }
                        // hit api
                        let formData = {
                            item_id : $(newInput).data('item-id'),
                            sorting : newValue
                        }
                        $.ajax({
                            type: "POST",
                            url: "{{route('admin_sort_single_item')}}",
                            data: formData,
                            dataType: 'json'
                        })
                        .done(function() {
                            toastr.success('Updated!');
                        })
                        ;
                    }
                    $(newInput).val(newValue).trigger("change")
                }
            }

            // Category
            var d_parent_index;
            var d_second_id = '{{ request()->get('c2') }}';
            var d_third_id = '{{ request()->get('c3') }}';

            $('#d_parent_category').change(function () {
                $('#d_second_parent_category').html('<option value="0">All Category</option>');
                $('#d_third_parent_category').html('<option value="0">All Category</option>');
                var parent_id = $(this).val();

                if ($(this).val() != '0') {
                    var index = $(this).find(':selected').data('index');
                    d_parent_index = index;

                    var childrens = defaultCategories[index].subCategories;

                    $.each(childrens, function (index, value) {
                        if (value.id == d_second_id)
                            $('#d_second_parent_category').append('<option data-index="' + index + '" value="' + value.id + '" selected>' + value.name + '</option>');
                        else
                            $('#d_second_parent_category').append('<option data-index="' + index + '" value="' + value.id + '">' + value.name + '</option>');
                    });
                }

                $('#d_second_parent_category').trigger('change');
            });

            $('#d_parent_category').trigger('change');

            $('#d_second_parent_category').change(function () {
                $('#d_third_parent_category').html('<option value="0">All Category</option>');

                if ($(this).val() != '0') {
                    var index = $(this).find(':selected').attr('data-index');

                    var childrens = defaultCategories[d_parent_index].subCategories[index].subCategories;

                    $.each(childrens, function (index, value) {
                        if (value.id == d_third_id)
                            $('#d_third_parent_category').append('<option data-index="' + index + '" value="' + value.id + '" selected>' + value.name + '</option>');
                        else
                            $('#d_third_parent_category').append('<option data-index="' + index + '" value="' + value.id + '">' + value.name + '</option>');
                    });
                }
            });

            $('#d_second_parent_category').trigger('change');
        });
    </script>
@stop
