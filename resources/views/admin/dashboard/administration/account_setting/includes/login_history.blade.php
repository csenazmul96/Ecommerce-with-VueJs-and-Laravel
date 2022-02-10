<div class="login_history_content">
    <div class="login_history_table">
        <table class="table table_border_top">
            <thead>
                <tr>
                    <th>Login Date</th>
                    <th>User Name</th>
                    <th>User ID</th>
                    <th>IP Address</th>
                </tr>
            </thead>

            <tbody>
                @foreach($loginHistory as $item)
                    <tr>
                        <td>{{ date('m/d/Y g:i A', strtotime($item->created_at)) }}</td>
                        <td>{{ $item->user->first_name.' '.$item->user->last_name }}</td>
                        <td>{{ $item->user->user_id }}</td>
                        <td>{{ $item->ip }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="pagination_wrapper">
        <div class=" width_200p">
        </div>
        <ul class="pagination">
            <li><button class="ly_btn page_first{{ $loginHistory->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $loginHistory->currentPage() == 1 ?  ' disabled' : ''}}>| <i class="fas fa-chevron-left"></i></button></li>
            <li>
                <button class="ly_btn page_prev{{ $loginHistory->currentPage() > 1 ?  ' btn_paginate' : ''}}"{{ $loginHistory->currentPage() == 1 ?  ' disabled' : ''}}> <i class="fas fa-chevron-left"></i> PREV</button>
            </li>
            <li>
                <div class="pagination_input">
                    <input type="number" min="1" max="{{ $loginHistory->lastPage() }}" class="form_global page" value="{{ $loginHistory->currentPage() }}"> of {{ $loginHistory->lastPage() }}
                </div>
                <div class="pagination_btn">
                    <button class="ly_btn switch_page">GO</button>
                </div>
            </li>
            <li><button class="ly_btn page_next{{ $loginHistory->currentPage() < $loginHistory->lastPage() ?  ' btn_paginate' : ''}}"{{ $loginHistory->currentPage() == $loginHistory->lastPage() ?  ' disabled' : ''}}>  NEXT <i class="fas fa-chevron-right"></i></button></li>
            <li>
                <button class="ly_btn page_last{{ $loginHistory->currentPage() < $loginHistory->lastPage() ?  ' btn_paginate' : ''}}"{{ $loginHistory->currentPage() == $loginHistory->lastPage() ?  ' disabled' : ''}}> <i class="fas fa-chevron-right"></i> |</button>
            </li>
        </ul>
    </div>
</div>
