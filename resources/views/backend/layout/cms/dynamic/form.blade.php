@extends('backend.app')

@push('styles')
<style>
    .dropify-wrapper .dropify-preview .dropify-render img {
        max-height: 200px;
        object-fit: contain;
    }
</style>
@endpush

@section('content')
<div class="container-xxl">

<form id="cmsForm"
    action="{{ isset($cms) ? route('admin.dynamic.cms.update', $cms->id) : route('admin.dynamic.cms.store') }}"
    method="POST"
    enctype="multipart/form-data">

    @csrf
    @if(isset($cms))
        @method('PUT')
    @endif

    {{-- PAGE / SECTION --}}
    <div class="row g-4 mb-4">

        <div class="col-md-4">
            <label class="form-label">Page</label>
            <select name="page" class="form-select">
                <option value="home" {{ ($cms->page ?? '')=='home'?'selected':'' }}>Home</option>
                <option value="about" {{ ($cms->page ?? '')=='about'?'selected':'' }}>About</option>
                <option value="men" {{ ($cms->page ?? '')=='men'?'selected':'' }}>Men</option>
                <option value="women" {{ ($cms->page ?? '')=='women'?'selected':'' }}>Women</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Section</label>
            <select name="section" class="form-select">
                <option value="hero" {{ ($cms->section ?? '')=='hero'?'selected':'' }}>Hero</option>
                <option value="about" {{ ($cms->section ?? '')=='about'?'selected':'' }}>About</option>
                <option value="services" {{ ($cms->section ?? '')=='services'?'selected':'' }}>Services</option>
                <option value="gallery" {{ ($cms->section ?? '')=='gallery'?'selected':'' }}>Gallery</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Status</label>
            <select name="status" class="form-select">
                <option value="active" {{ ($cms->status ?? '')=='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ ($cms->status ?? '')=='inactive'?'selected':'' }}>Inactive</option>
            </select>
        </div>

    </div>

    {{-- BASIC --}}
    <div class="card mb-4">
        <div class="card-header"><h5>Basic Content</h5></div>
        <div class="card-body">

            <input type="text" name="title" class="form-control mb-3"
                value="{{ $cms->title ?? '' }}" placeholder="Title">

            <input type="text" name="sub_title" class="form-control mb-3"
                value="{{ $cms->sub_title ?? '' }}" placeholder="Sub Title">

            <textarea name="description" class="form-control mb-3" rows="4"
                placeholder="Description">{{ $cms->description ?? '' }}</textarea>

            <input type="file" name="image" class="form-control dropify"
                @if(!empty($cms->image)) data-default-file="{{ asset($cms->image) }}" @endif>

        </div>
    </div>

    {{-- TODO --}}
    <div class="card mb-4" id="todo-section" style="display:none;">
        <div class="card-header"><h5>Todo List</h5></div>
        <div class="card-body" id="todo-container"></div>
    </div>

    <button type="button" id="add-todo-btn" class="btn btn-primary mb-3">
        + Add Todo
    </button>

    <div class="text-end">
        <button class="btn btn-success">Save</button>
    </div>

</form>

</div>
@endsection


@push('scripts')
<script>
    const APP_URL = "{{ asset('') }}";
</script>

<script>

let existingTodos = @json($cms->v1 ?? []);

existingTodos = Array.isArray(existingTodos) ? existingTodos : [];

existingTodos = existingTodos.map(todo => ({
    title: todo?.title ?? '',
    sub_title: todo?.sub_title ?? '',
    button_text: todo?.button_text ?? '',
    link_url: todo?.link_url ?? '',
    image: todo?.image ?? ''
}));
function renderTodos() {

let container = $('#todo-container');
container.html('');

if (!existingTodos.length) {
    $('#todo-section').hide();
    return;
}

$('#todo-section').show();

existingTodos.forEach((todo, i) => {

    let html = `
    <div class="card mb-3 todo-item">
        <div class="card-body">

            <div class="row g-3">

                <div class="col-md-6">
                    <input type="text" name="v1[${i}][title]" class="form-control todo-title"
                        value="${todo.title ?? ''}" placeholder="Title">
                </div>

                <div class="col-md-6">
                    <input type="text" name="v1[${i}][sub_title]" class="form-control todo-sub-title"
                        value="${todo.sub_title ?? ''}" placeholder="Sub Title">
                </div>

                <div class="col-md-6">
                    <input type="text" name="v1[${i}][button_text]" class="form-control todo-button-text"
                        value="${todo.button_text ?? ''}" placeholder="Button Text">
                </div>

                <div class="col-md-6">
                    <input type="url" name="v1[${i}][link_url]" class="form-control todo-link-url"
                        value="${todo.link_url ?? ''}" placeholder="Link URL">
                </div>

                <div class="col-md-8">
                    <input type="file" name="v1[${i}][image]" class="form-control dropify"
                        ${todo.image ? `data-default-file="${APP_URL}${todo.image}"` : ''}

                </div>

                <div class="col-md-4 text-end d-flex align-items-end">
                    <button type="button" class="btn btn-danger remove-todo">
                        Remove
                    </button>
                </div>

            </div>

        </div>
    </div>`;

    container.append(html);
});

$('.dropify').each(function () {
    if (!$(this).data('dropify')) {
        $(this).dropify();
    }
});
}


/* ADD */
$(document).on('click','#add-todo-btn',function(){
    existingTodos.push({
        title:'',
        sub_title:'',
        button_text:'',
        link_url:'',
        image:''
    });
    renderTodos();
});


/* REMOVE */
$(document).on('click','.remove-todo',function(){
    let index = $(this).closest('.todo-item').data('index');
    existingTodos.splice(index,1);
    renderTodos();
});


/* INPUT SYNC */
$(document).on('input','#todo-container input',function(){

    let item = $(this).closest('.todo-item');
    let index = item.data('index');

    if(existingTodos[index]){
        existingTodos[index].title = item.find('.todo-title').val();
        existingTodos[index].sub_title = item.find('.todo-sub-title').val();
        existingTodos[index].button_text = item.find('.todo-button-text').val();
        existingTodos[index].link_url = item.find('.todo-link-url').val();
    }
});


/* INIT */
$(document).ready(function(){
    renderTodos();
});

</script>
@endpush
